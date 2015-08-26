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

        $content = <<<EOT
<div id="project-content" class="container-fluid">
  <div class="h4" id="summary">
    <span>
      Filters:
      <div class="btn-group">
        <a href="#" class="btn btn-success btn-xs dropdown-toggle" 
                    data-toggle="dropdown" aria-expanded="false">
          Priority <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" style="padding-left:3px">
          <li> <span class="text-nowrap">
            <a href="#" class="btn btn-danger btn-xs" id="priority-blocker">
              <span class="glyphicon glyphicon-check"></span>
              blocker
            </a>
            <a href="#" class="btn btn-warning btn-xs" id="priority-critical">
              <span class="glyphicon glyphicon-check"></span>
              critical
            </a>
            <a href="#" class="btn btn-primary btn-xs" id="priority-major">
              <span class="glyphicon glyphicon-check"></span>
              major
            </a>
            <a href="#" class="btn btn-info btn-xs" id="priority-minor">
              <span class="glyphicon glyphicon-check"></span>
              minor
            </a>
            <a href="#" class="btn btn-default btn-xs" id="priority-trivial">
              <span class="glyphicon glyphicon-check"></span>
              trivial
            </a>
            <a href="#" class="btn btn-default btn-xs" id="priority-none">
              <span class="glyphicon glyphicon-check"></span>
              none
            </a>
          </span> </li>
        </ul>
      </div>
    </span>
    <span id="numbers" class="pull-right">
      Status:
      <a href="#" class="btn btn-xs btn-primary" id="status-accepted">
        <span class="glyphicon glyphicon-check"></span> accepted
      </a>
      <a href="#" class="btn btn-xs btn-info" id="status-assigned">
        <span class="glyphicon glyphicon-check"></span> assigned
      </a>
      <a href="#" class="btn btn-xs btn-success" id="status-closed">
        <span class="glyphicon glyphicon-unchecked"></span> closed
      </a>
      <a href="#" class="btn btn-xs btn-danger" id="status-new">
        <span class="glyphicon glyphicon-check"></span> new 
      </a>
      <a href="#" class="btn btn-xs btn-warning" id="status-reopened">
        <span class="glyphicon glyphicon-check"></span> reopened
      </a>
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
      Showing <span id="loaded-items" class="badge">20</span> of 
      <span id="total-items" class="badge">120</span> tickets!
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
