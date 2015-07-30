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
     * options for pager, we will provide default value here.
     */
    public $pagerOptions = array(
        'per_page' => 10,
        // page number starts from 0,
        'page_number' => 0
    );

    /**
     * project metadata, project name, version, milestone, etc.
     */
    public $metadata = array();

    /**
     * filters for a project, by different fields: status
     * type, owner,
     */
    public $filters = array();

    /**
     * the constructor
     *
     * @param $include_cookie
     */
    public function __construct($include_cookie=true) {

        $this->load($include_cookie);
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

        // clean all cookie state.
        // TODO: only get from POST and GET ignore COOKIE
        //$this->cleanCookieState($this->pagerOptions);

        // load context by ignore cookie. this is the initializing phase.
        $this->load(false);
    }

    /**
     * load context from HTTP request, including POST, GET and COOKIE.
     */
    public function load($include_cookie=True) {

        // === collect user information.
        // this will include user roles.
        if(is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $this->metadata['tracuser'] = $current_user;
        }

        // === collect ticket and project metadata.
        // the page slug will be the project name.
        $version = $this->getRequestParam('version', $include_cookie);
        $milestone = $this->getRequestParam('milestone', $include_cookie);
        // project name.
        $project = $this->getRequestParam('project', $include_cookie);
        if (!empty($version)) {
            // get the project name
            $project = wptc_get_project_name($version);
        }
        $this->metadata['version'] = $version;
        $this->metadata['milestone'] = $milestone;
        $this->metadata['project'] = $project;

        // === load total items based on metadata.
        //$total_items = $this->getTotalItems();

        // === collect pagination information.
        $per_page = $this->getRequestParam('per_page', $include_cookie);
        // items per page, default is 20
        if(empty($per_page)) {
            // set to default per_page to 20.
            $per_page = 10;
        }
        // page number, starts from 0.
        $page_number = $this->getRequestParam('page_number', $include_cookie);
        if (empty($page_number)) {
            // set to 0 as the default page number.
            $page_number = 0;
        }
        $this->pagerOptions['per_page'] = $per_page;
        $this->pagerOptions['page_number'] = $page_number;
        $this->pagerOptions['total_items'] = $total_items;

        // TODO: update cookie! in one hour expire time
        $this->setCookieState($this->pagerOptions, 3600);
        $this->setCookieState($this->metadata, 3600);
    }

    /**
     * get a HTTP request parameter's value.
     * by default this method will not check cookie.
     */
    public function getRequestParam($param, $include_cookie=true) {

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

    public function setCookieState($states, $expire=60) {
    
        foreach($states as $name => $value) {
            setcookie($name, $value, time() + $expire);
        }
    
        return;
    }

    public function cleanCookieState($states) {

        foreach($states as $name => $value) {
            // clean cookie by set the expire time to one hour 
            // before.
            setcookie($name, $value, time() - 3600);
        }
    }
}
