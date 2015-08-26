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

        // get the tab name
        $tab_name = $this->getRequestParam('tab');
        if(empty($tab_name)) {
            $this->cleanCookieState(array('tab'));
        } else {
            $this->setState('tab', $tab_name);
        }

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
     * - peroject search term.
     */
    public function loadFilters() {

        $search_term = $this->getRequestParam('search_term');
        if(empty($search_term)) {
            // default will be empty string.
            $search_term = "";
        }
        $this->setState('search_term', $search_term);

        $current_query = $this->getRequestParam('current_query');
        if(empty($current_query)) {
            $current_query = "";
        }
        $this->setState('current_query', $current_query);
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
            $total = $this->calculateTotal();
            $this->setState('total_items', $total);

            // reset page number to 0
            $this->setState('page_number', 0);
        }
    }

    /**
     * build query based on the metadata and filters. on context..
     */
    public function buildQuery() {

        // return empty string for now.
        $query = $this->getState('search_term'); 
        if(empty($query)) {
            $query = 'ALL_PROJECTS';
        }
        return $query;
    }

    /**
     * function to calculate the total items for different state.
     */
    public function calculateTotal() {

        $projects = wptc_get_projects($this->getState('search_term'));
        return count($projects);
    }
}
