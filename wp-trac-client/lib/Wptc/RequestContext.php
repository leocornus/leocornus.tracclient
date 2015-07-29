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
     * reset the request context, 
     * it will be called when user reload page.
     */
    public function reset() {
        // clean all cookie state.
        // TODO: only get from POST and GET ignore COOKIE
        $this->cleanCookieState($this->pagerOptions);
    }

    /**
     *
     */
    public function init() {

        if(is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $this->metadata['tracuser'] = $current_user;
        }

        // collect ticket and project metadata.
        // the page slug will be the project name.
        $version = wptc_get_request_param('version');
        $milestone = wptc_get_request_param('milestone');
        // project name.
        $project = wptc_get_request_param('project');
        if (!empty($version)) {
            // get the project name
            $project = wptc_get_project_name($version);
        }
        $this->metadata['version'] = $version;
        $this->metadata['milestone'] = $milestone;
        $this->metadata['project'] = $project;

        // collect pagination information.
        $per_page = wptc_get_request_param('per_page');
        // items per page, default is 20
        if(empty($per_page)) {
            // set to default per_page to 20.
            $per_page = 10;
        }
        // page number, starts from 0.
        $page_number = wptc_get_request_param('page_number');
        if (empty($page_number)) {
            // set to 0 as the default page number.
            $page_number = 0;
        }
        $this->pagerOptions['per_page'] = $per_page;
        $this->pagerOptions['page_number'] = $page_number;

        // TODO: update cookie! in one hour expire time
        $this->setCookieState($this->pagerOptions, 3600);
        $this->setCookieState($this->metadata, 3600);
    }

    /**
     * get a HTTP request parameter's value.
     */
    public function getRequestParam($param) {

        // try to find the selected theme name
        if (array_key_exists($param, $_POST)) {
            $value = $_POST[$param];
        } elseif (array_key_exists($param, $_GET)) {
            $value = $_GET[$param];
        } elseif (array_key_exists($param, $_COOKIE)) {
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
