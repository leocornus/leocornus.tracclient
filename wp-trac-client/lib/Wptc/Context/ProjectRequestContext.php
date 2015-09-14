<?php
/**
 * @namespace
 */
namespace Wptc\Context;

use Wptc\Context\RequestContext;

/**
 * this clss will provide a easy way to manage the request context
 * for the wp-trac-client.
 */
class ProjectRequestContext extends RequestContext {

    /**
     * the constructor
     */
    public function __construct() {

        $this->defaults['per_page'] = 10;
        // get the tab name
        $tab_name = $this->getRequestParam('tab');
        if(!empty($tab_name)) {
            $this->setState('tab', $tab_name);
        }
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

        // load metadata
        $this->loadMetadata();

        // load filters.
        $this->loadFilters();

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

        $this->loadTicketFilters();

        $this->setState('search_term', $search_term);

        $search_term = $this->getRequestParam('search_term');
        if(empty($search_term)) {
            // default will be empty string.
            $search_term = "";
        }
        $this->setState('search_term', $search_term);
    }

    /**
     * calculate total items for differtne states.
     */
    public function calculateTotal($query) {

        return $this->calcTicketsTotal($query);
    }

    /**
     * build query based on the metadata and filters. on context..
     */
    public function buildQuery() {

        return $this->buildTicketQuery();
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
