<?php
/**
 * @namespace
 */
namespace Wptc\View;

use Wptc\View\AllProjectsHome;
use Wptc\Helper\ProjectHelper;

/**
 * the projects main class.
 */
class AllMyTicketsHome extends AllProjectsHome {

    /**
     * build content main for tickets list.
     */
    public function buildContent() {

        // build the filter for priority.
        $priority_filter = $this->buildFilterPriority();
        $order_select = $this->buildSelectOrder();

        if(is_user_logged_in()) {
            $content = $this->buildKanbanContent();
            // JavaScript client will call server side to verify
            // user information.
        } else {
            $content = <<<LOGIN
<div class="alert alert-warning h3" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  Please log in to view <strong>My Tickets</strong>
</div>
LOGIN;
        }
        return $content;
    }
}
