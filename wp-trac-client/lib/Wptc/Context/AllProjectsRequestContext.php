<?php
/**
 * @namespace
 */
namespace Wptc\Context;

use Wptc\Context\RequestContext;

/**
 * request context for all projects list page.
 */
class AllProjectsRequestContext extends RequestContext {

    /**
     * the constructor
     */
    public function __construct() {

        // update the default states.
        $this->defaults['per_page'] = 9;

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
        if(!empty($tab_name)) {
            $this->setState('tab', $tab_name);
            $this->defaults['per_page'] = 10;
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

        // load filter based on the tab.
        $tab_name = $this->getState('tab');
        if(!empty($tab_name)) {
            switch($tab_name) {
                case "tickets":
                    $this->loadTicketFilters();
                    break;
                case "commits":
                    $this->loadCommitFilters();
                    break;
            }
        }

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
     * build query based on the metadata and filters. on context..
     */
    public function buildQuery() {

        // build query based on the tab.
        $tab_name = $this->getState('tab');
        if(!empty($tab_name)) {
            switch($tab_name) {
                case "tickets":
                    $query = $this->buildTicketQuery();
                    break;
                case "commits":
                    $query = $this->buildCommitQuery();
                    break;
            }
        } else {
            $query = $this->getState('search_term'); 
            if(empty($query)) {
                $query = 'ALL_PROJECTS';
            }
        }
        return $query;
    }

    /**
     * function to calculate the total items for different state.
     */
    public function calculateTotal($query) {

        //calculate total based on the tab.
        $tab_name = $this->getState('tab');
        if(!empty($tab_name)) {
            switch($tab_name) {
                case "tickets":
                    $total = $this->calcTicketsTotal($query);
                    break;
                case "commits":
                    $total = $this->calcCommitsTotal($query);
                    break;
            }
        } else {
            $total = $this->calcProjectsTotal();
        }

        return $total;
    }

    /**
     * calculate projects total.
     */
    public function calcProjectsTotal() {

        $projects = wptc_get_projects($this->getState('search_term'));
        return count($projects);
    }
}
