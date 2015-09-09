<?php
/**
 * @namespace
 */
namespace Wptc\View;

use Wptc\View\AllProjectsHome;
use Wptc\Helper\ProjectHelper;

/**
 * the class to render the view for all commits.
 */
class AllCommitsHome extends AllProjectsHome {

    /**
     * build content main for tickets list.
     */
    public function buildContent() {

        // build the filter for priority.
        $priority_filter = $this->buildFilterPriority();
        $status_checkbox = $this->buildCheckboxStatus();
        $order_select = $this->buildSelectOrder();

        $content = <<<EOT
<div id="project-content" class="container-fluid">
  <div class="h4" id="summary">
    <span>
      Authors:
      {$priority_filter}
    </span>
  </div>

  <div>
    <table class="table table-striped table-hover table-responsive" 
           id="project-items">
      <thead>
        <tr class="success">
          <th>Commit</th>
          <th>Date</th>
          <th>Comment</th>
          <th>Author</th>
          <th>UAT</th>
          <th>Prod</th>
        </tr>
      </thead>
      <tfoot>
        <tr class="success">
          <th>Commit</th>
          <th>Date</th>
          <th>Comment</th>
          <th>Author</th>
          <th>UAT</th>
          <th>Prod</th>
        </tr>
        <tr class="success">
          <th colspan="7">
  <div class="row">
    <div class="form-inline col-sm-6">
      <div class="form-group">
        <label for="commit-search">Filter by Search:</label>
        <input type="text" class="form-control input-sm" 
               id="commit-search"
               placeholder="comment owner">
      </div>
    </div>
    <div class="text-right col-sm-6" id="item-pager">
      Showing <span id="loaded-items" class="badge">0</span> of 
      <span id="total-items" class="badge">0</span> Commits!
      <a class="btn btn-success btn-xs" 
         id="load-more-commits"
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
