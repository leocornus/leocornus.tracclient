<?php
/**
 * @namespace
 */
namespace Wptc;

/**
 * this clss will provide a easy way to manage the request context
 * for the wp-trac-client.
 */
class RequestContext {

    /**
     * the constructor
     */
    public function __construct() {

        $this->init();
    }

    /**
     * states for the request context.
     * it will have the following data structure.
     * $states = array(
     *     'state_name' => 'state value',
     *     'state_name_one' => 'state value one'
     * );
     */
    protected $states = array(
        // default value for number of items for a page.
        'per_page' => 10,
        // page number starts from 0.
        'page_number' => 0
    );

    /**
     * return all states.
     */
    public function getStates() {

        return $this->states;
    }

    /**
     * return the state value for a given state name.
     * if the the state name is not exist, it will return null.
     */
    public function getState($state_name) {

        if(array_key_exists($state_name, $this->states)) {
            return $this->states[$state_name];
        } else {
            return null;
        }
    }

    /**
     * set value for the given state name.
     */
    public function setState($state_name, $value) {

        $this->states[$state_name] = $value;
    }

    /**
     * reset the request context, 
     * it will be called when user reload page.
     *
     * this method will serve normal page load or reload.
     * it will consider as start point for a page.
     * 
     */
    public function init() {

        // load trac user information.
        $this->loadTracUser();

        // load filters.
        $this->loadFilters();

        // load metadata
        $this->loadMetadata();

        // load pager states: per_page, page_number, total_items.
        // this should be the last one to load, as it depends on 
        // metadata and filters.
        $this->loadPagerStates();

        // clean all cookie state.
        // TODO: only get from POST and GET ignore COOKIE
        //$this->cleanCookieState($this->pagerOptions);

        // load context by ignore cookie. this is the initializing phase.
        //$this->load(false);
    }

    /**
     * load user information.
     */
    public function loadTracUser() {

        // === collect user information.
        // this will include user roles.
        if(is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $this->states['tracuser'] = $current_user;
        }
    }

    /**
     * load metadata states, including:
     *  - project
     *  - milestone
     *  - version / sprint
     */
    public function loadMetadata() {

        // === collect ticket and project metadata.
        // the page slug will be the project name.
        $version = $this->getRequestParam('version');
        $milestone = $this->getRequestParam('milestone');
        // project name.
        $project = $this->getRequestParam('project');
        if (!empty($version)) {
            // get the project name
            $project = wptc_get_project_name($version);
        }
        // current query.
        $current_query = $this->getRequestParam('current_query');

        $this->setState('version', $version);
        $this->setState('milestone', $milestone);
        $this->setState('project', $project);
        $this->setState('current_query', $current_query);
    }

    /**
     * load filter states, which will include:
     *  - status
     *  - owner
     *  - type
     *  - priority
     */
    public function loadFilters() {

        // status.
        $status = $this->getRequestParam('status');
        if (empty($status)) {
            // set up the default status, none closed.
            $status = "accepted,assigned,new,reopened";
        }
        $this->setState('status', $status);

        // Priority
        $priority = $this->getRequestParam('priority');
        if (empty($priority)) {
            // default will include all priorities.
            $priority = "blocker,critical,major,minor,trivial,none";
        }
        $this->setState('priority', $priority);
    }

    /**
     * load pager states: per_page, page_number, and total_items.
     */
    public function loadPagerStates() {

        // === collect pagination information.
        $per_page = $this->getRequestParam('per_page');
        // items per page, default is 20
        if(empty($per_page)) {
            // set to default per_page to 20.
            $per_page = 10;
        }
        // page number, starts from 0.
        $page_number = $this->getRequestParam('page_number');
        if (empty($page_number)) {
            // set to 0 as the default page number.
            $page_number = 0;
        }
        $this->setState('per_page', $per_page);
        $this->setState('page_number', $page_number);

        // build the query from metedata.
        $current_query = $this->getState('current_query');
        $new_query = $this->buildQuery();
        if(!empty($current_query) && 
           ($new_query == $current_query)) {
            // do nothing here as all summary are the same.
            // the total number should already in cookie.
        } else {
            // set current query to new query.
            $this->setState('current_query', $new_query);
            // execute query to get brief summary, such as
            // total items, items by status, etc.
            // set max=0 to return all items.
            $ids = wptc_ticket_query($new_query, 0);
            // === load total items based on metadata.
            $this->setState('total_items', count($ids));
            // reset pager number to 0.
            $this->setState('page_number', 0);
        }
    }

    /**
     * get a HTTP request parameter's value.
     * by default this method will not check COOKIE.
     */
    public function getRequestParam($param, $include_cookie=false) {

        // try to find the selected theme name
        if (array_key_exists($param, $_POST)) {
            $value = $_POST[$param];
        } elseif (array_key_exists($param, $_GET)) {
            $value = $_GET[$param];
        } elseif ($include_cookie && array_key_exists($param, $_COOKIE)) {
            // cookie is one of the request in PHP.
            // check manuel $_REQUEST for details.
            $value = $_COOKIE[$param];
        } else {
            $value = '';
        }

        if(is_string($value)) {
            $value = str_replace("\r\n", "\n", stripslashes($value));
        }

        return $value;
    }

    /**
     * set whole states to cookie.
     */
    public function setCookieStates($expire=60) {
    
        foreach($this->states as $name => $value) {
            setcookie($name, $value, time() + $expire);
        }
    }

    /**
     * set cookies state.
     */
    public function setCookieState($states, $expire=60) {
    
        foreach($states as $name => $value) {
            setcookie($name, $value, time() + $expire);
        }
    
        return;
    }

    /**
     * clean the cookies 
     */
    public function cleanCookieState($states) {

        foreach($states as $name => $value) {
            // clean cookie by set the expire time to one hour 
            // before.
            setcookie($name, $value, time() - 3600);
        }
    }

    /**
     * build query based on the metadata and filters. on context..
     */
    public function buildQuery() {

        // analyze the metadata
        // we will analyze the following fields: project, status
        $project_name = $this->getState('project');
        // the value for status will be in pattern: 
        // "new,accepted,reopened"
        $status = explode(",", $this->getState('status'));
        // priority
        $priority = explode(",", $this->getState('priority'));

        // starts the query from an empty string.
        $query = array(); 
        if (!empty($project_name)) {
            $query[] = "project={$project_name}";
        }
        // all status.
        foreach ($status as $one) {
            $query[] = "status={$one}";
        }
        // all priority.
        foreach ($priority as $p) {
            if($p == 'none') {
                $query[] = "priority=";
            } else {
                $query[] = "priority={$p}";
            }
        }

        return implode("&", $query);
    }

    /**
     * build the main query, which is only based on the metadata
     * states.
     */
    public function buildMainQuery() {

        // we only have project for now.
        $project_name = $this->getState('project');
        return "project={$project_name}";
    }
}
