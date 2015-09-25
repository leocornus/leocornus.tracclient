<?php
/**
 * @namespace
 */
namespace Wptc\Context;

use Wptc\Helper\ProjectHelper;

/**
 * this clss will provide a easy way to manage the request context
 * for the wp-trac-client.
 */
class RequestContext {

    /**
     * the constructor
     */
    public function __construct() {

        // get the tab name
        $tab_name = $this->getRequestParam('tab');
        if(!empty($tab_name)) {
            $this->setState('tab', $tab_name);
        }

        $this->init();
    }

    // set up the prefix for cookies.
    protected $before = "wptc_";

    /**
     * states for the request context.
     * it will have the following data structure.
     * $states = array(
     *     'state_name' => 'state value',
     *     'state_name_one' => 'state value one'
     * );
     */
    protected $states = array(
        'tab' => "",
        // general states for pager
        'page_number' => 0,
        'per_page' => 20,
        // project metadata.
        'project' => "",
        'milestone' => "",
        'version' => "",
        // git repos metadata, this should associate with project
        'repo_path' => "",
        // general filters for ticket
        'order' => "",
        'priority' => "",
        'status' => ""
    );

    /**
     * default value for states.
     */
    protected $defaults = array(
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

        // project name.
        $project = $this->getRequestParam('project');
        $this->setState('project', $project);

        // set the repo path.
        $repo_path = $this->getRequestParam('repo_path');
        if (empty($repo_path)) {
            // what's the better way to fin the repository path
            // for a project?
            $helper = new ProjectHelper($this->getState('project'));
            $pathes = $helper->getRepoPathes();
            // one get the first one for now.
            $repo_path = $pathes[0];
        }
        $this->setState('repo_path', $repo_path);
    }

    /**
     * load filter states, which will include:
     *  - status
     *  - owner
     *  - type
     *  - priority
     */
    public function loadFilters() {

        $this->loadTicketFilters();

        $search_term = $this->getRequestParam('search_term');
        if(empty($search_term)) {
            // default will be empty string.
            $search_term = "";
        }
        $this->setState('search_term', $search_term);
    }

    /**
     * load ticket filter states, which will include:
     *  - status
     *  - owner
     *  - type
     *  - priority
     *  - order
     */
    public function loadTicketFilters() {

        // order.
        $order = $this->getRequestParam('order');
        if (empty($order)) {
            // set up the default sort order, by priority.
            $order = "priority";
        }
        $this->setState('order', $order);

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
     * load commit filters
     */
    public function loadCommitFilters() {

    }

    /**
     * load pager states: per_page, page_number, and total_items.
     */
    public function loadPagerStates() {

        // === collect pagination information.
        $per_page = $this->getRequestParam('per_page');
        // items per page, default is 9
        // as the projects list page is 3-column rows.
        // the value 0 is considerd as empty!
        // we need set per_page to 0 to get all result.
        if($per_page < 0) {
            $per_page = 0;
        } else if(empty($per_page)) {
            // set to defaultper_page to 9,
            $per_page = $this->defaults['per_page'];
        }
        // page number, starts from 0.
        $page_number = $this->getRequestParam('page_number');
        if (empty($page_number)) {
            // set to 0 as the default page number.
            $page_number = 0;
        }
        $this->setState('per_page', $per_page);
        $this->setState('page_number', $page_number);

        $current_query = $this->getState('current_query');
        $new_query = $this->buildQuery();
        if(!empty($current_query) && 
           ($new_query == $current_query)) {
            // do nothing here as all summary are the same.
            // the total number should already in cookie.
        } else {
            // set current query.
            $this->setState('current_query', $new_query);
            // calculate the total items for new query.
            $total = $this->calculateTotal($new_query);
            $this->setState('total_items', $total);

            // reset page number to 0
            $this->setState('page_number', 0);
        }
    }

    /**
     * calculate total items for differtne states.
     */
    public function calculateTotal($query) {

        return strlen($query);
    }

    /**
     * calculate tickets total.
     */
    public function calcTicketsTotal($query) {

        // execute query to get brief summary, such as
        // total items, items by status, etc.
        // set max=0 to return all items.
        $ids = wptc_ticket_query($query, 0);
        return count($ids);
    }

    /**
     * calculate commits total.
     */
    public function calcCommitsTotal($query) {

        // return everything for now.
        $repo_path = $this->getState('repo_path');

        return wpg_get_log_count($repo_path);
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
            $cookie_name = $this->before . $param;
            $value = $_COOKIE[$cookie_name];
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
    
        $this->cleanCookies();
        foreach($this->states as $name => $value) {
            $cookie_name = $this->before . $name;
            setcookie($cookie_name, $value, time() + $expire);
        }
    }

    /**
     * clean cookies with wptc_ prefix.
     */
    public function cleanCookies() {

        foreach($_COOKIE as $name=>$value) {
            $pos = strpos($name, $this->before);
            if ($pos == false) {
                // not find the prefix.
                // skip...
            } else {
                if ($pos == 0) {
                    // at the beginning of the name.
                    // set to expire now.
                    setcookie($name, "", time() - 3600);
                }
            }

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

        foreach($states as $name) {
            // clean cookie by set the expire time to one hour 
            // before.
            setcookie($name, "", time() - 3600);
        }
    }

    /**
     * build query based on the metadata and filters. on context..
     */
    public function buildQuery() {

        return $this->buildTicketQuery();
    }

    /**
     * build query based on the metadata and filters. on context..
     */
    public function buildTicketQuery() {

        // analyze the metadata
        // we will analyze the following fields: project, status
        $project_name = $this->getState('project');
        $version = $this->getState('version');
        // the value for status will be in pattern: 
        // "new,accepted,reopened"
        $status = explode(",", $this->getState('status'));
        // priority
        $priority = explode(",", $this->getState('priority'));
        // search term.
        $search_term = $this->getState('search_term');

        // starts the query from an empty string.
        $query = array(); 
        if(!empty($project_name)) {
            $query[] = "project={$project_name}";
        }
        // handle the version query.
        if(!empty($version)) {
            if($version == 'BACKLOG') {
                $v_none = array_merge($query, array('version='));
                $v_backlog = array_merge($query, 
                                         array('version=~backlog'));
                $v_none = implode("&", $v_none);
                $v_backlog = implode("&", $v_backlog);
                //$query[] = "version=";
                $the_query = "{$v_none}&or&{$v_backlog}";
                //$the_query = implode("&", $query);
            } else {
                $query[] = "version={$version}";
                $the_query = implode("&", $query);
            }

            return $the_query;
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

        // sort order
        $order = $this->getState('order');
        // query string for sort order
        switch($order) {
            case 'priority':
                // default sort order
                $query[] = 'order=priority';
                break;
            case 'changetime':
                // sort by last modified time.
                $query[] = 'order=changetime';
                // last modified shows at first.
                $query[] = 'desc=1';
                break;
            case 'id':
                // sort by last modified time.
                $query[] = 'order=id';
                // last modified shows at first.
                $query[] = 'desc=1';
                break;
            default:
                $query[] = 'order=priority';
                break;
        }

        // search term.
        if(!empty($search_term)) {
            // we will search 2 fields: description and summary
            // =~ is for contains
            $desc = array_merge($query, array("description=~{$search_term}"));
            $sum = array_merge($query, array("summary=~{$search_term}"));
            $owner = array_merge($query, array("owner=~{$search_term}"));
            $desc = implode("&", $desc);
            $sum = implode("&", $sum);
            $owner = implode("&", $owner);
            $the_query = "{$desc}&or&{$sum}&or&{$owner}";
        } else {
            $the_query = implode("&", $query);
        }

        return $the_query;
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

    /**
     * build the query for search commits.
     */
    public function buildCommitQuery() {

        // nothing for now...
        return "ALL-COMMITS";
    }
}
