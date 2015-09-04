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
class AllTicketsHome extends AllProjectsHome {

    /**
     * build content main for tickets list.
     */
    public function buildContent() {

        // build the filter for priority.
        $priority_filter = $this->buildFilterPriority();
        $status_checkbox = $this->buildCheckboxStatus();

        $content = <<<EOT
<div id="project-content" class="container-fluid">
  <div class="h4" id="summary">
    <span>
      Filters:
      {$priority_filter}
    </span>
    <span id="numbers" class="pull-right">
      Status:
      {$status_checkbox}
    </span>
  </div>

  <div>
    <table class="table table-striped table-hover table-responsive" 
           id="project-items">
      <thead>
        <tr class="success">
          <th>ID</th>
          <th>Summary</th>
          <th>Owner</th>
          <th>Priority</th>
          <th>Status</th>
        </tr>
      </thead>
      <tfoot>
        <tr class="success">
          <th>ID</th>
          <th>Summary</th>
          <th>Owner</th>
          <th>Priority</th>
          <th>Status</th>
        </tr>
        <tr class="success">
          <th colspan="6">
  <div class="row">
    <div class="form-inline col-sm-6">
      <div class="form-group">
        <label for="ticket-search">Filter by Search:</label>
        <input type="text" class="form-control input-sm" 
               id="ticket-search"
               placeholder="description summary owner">
      </div>
    </div>
    <div class="text-right col-sm-6" id="item-pager">
      Showing <span id="loaded-items" class="badge">0</span> of 
      <span id="total-items" class="badge">0</span> tickets!
      <a class="btn btn-success btn-xs" 
         id="project-load-more"
      >Load More...</a>
    </div>
  </div>
          </th>
        </tr>
      </tfoot>
      <tbody>
      </tbody>
    </table>
  </div>
</div> <!-- project-content -->
EOT;

        return $content;
    }
}
