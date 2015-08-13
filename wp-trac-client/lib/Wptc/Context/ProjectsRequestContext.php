<?php
/**
 * @namespace
 */
namespace Wptc\Context;

use Wptc\Context\RequestContext;

/**
 * request context for all projects list page.
 */
class ProjectsRequestContext extends RequestContext {

    /**
     * the constructor
     */
    public function __construct() {

        $this->init();
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

        // load pager states: per_page, page_number, total_items.
        // this should be the last one to load, as it depends on 
        // metadata and filters.
        $this->loadPagerStates();
    }

    /**
     * load filter states, which will include:
     *  - status
     *  - owner
     *  - type
     *  - priority
     */
    public function loadFilters() {

        // do nothing for now.
    }

    /**
     * load pager states: per_page, page_number, and total_items.
     */
    public function loadPagerStates() {

        // === collect pagination information.
        $per_page = $this->getRequestParam('per_page');
        // items per page, default is 9
        // as the projects list page is 3-column rows.
        if(empty($per_page)) {
            // set to default per_page to 9,
            $per_page = 9;
        }
        // page number, starts from 0.
        $page_number = $this->getRequestParam('page_number');
        if (empty($page_number)) {
            // set to 0 as the default page number.
            $page_number = 0;
        }
        $this->setState('per_page', $per_page);
        $this->setState('page_number', $page_number);

        if($page_number == 0) {
            // this is all projects list page.
            $projects = wptc_get_projects();
            $this->setState('total_items', count($projects));
        }
    }

    /**
     * build query based on the metadata and filters. on context..
     */
    public function buildQuery() {

        // return empty string for now.
        return "";
    }
}
