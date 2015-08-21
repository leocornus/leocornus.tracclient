<?php
/**
 * @namespace
 */
namespace Wptc\View;

/**
 * the page for project tickets main class.
 */
class ProjectTicketsHome {

    /**
     */
    protected $context = null;

    /**
     * constructor
     */
    public function __construct($context) {

        $this->context = $context;
    }

    /**
     * render the page.
     */
    public function renderPage() {

        $header = $this->buildHeader();
        $navbar = $this->buildNavbar();
        $content = $this->buildContent();
        $footer = $this->buildFooter();

        $page_view = <<<VIEW
{$header}
{$navbar}
{$content}
{$footer}
VIEW;

        return $page_view;
    }

    /**
     * build header
     */
    public function buildHeader() {

        $projects_url = "/projects";
        $project_name = $this->context->getState('project');
        $project_url = "/projects?project={$project_name}";
        $project = wptc_get_project($project_name);

        $header = <<<EOT
<div class="page-header" id="project-header">
  <form class="navbar-form navbar-right" role="search">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search">
    </div>
    <button type="submit" class="btn btn-success">Go</button>
  </form>
  <h3>
    <a href="{$projects_url}"> All Projects</a> / 
    <a href="{$project_url}">{$project_name}</a>
  </h3>
  <p>{$project['description']}</p> 
</div> <!-- project-header -->
EOT;

        return $header;
    }

    /**
     * build nav bar.
     */
    public function buildNavbar() {

        // main query to get the total number.
        $main_query = $this->context->buildMainQuery();
        $ids = wptc_ticket_query($main_query, 0);
        $total = count($ids);

        $nav = <<<EOT
<div id="project-nav" class="container-fluid h4">
  <ul class="nav nav-tabs">
    <li><a href="#">Project Home</a></li>
    <li class="active"><a href="#">
      <span class="badge">{$total}</span> Tickets</a>
    </li>
    <li><a href="#">
      <span class="badge"></span> Commits
    </a></li>
    <li class="dropdown pull-right">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Actions<span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="#">Report an Issue</a></li>
        <li role="separator" class="divider"></li>
        <li class="dropdown-header">My...</li>
        <li><a href="#">My Tickets</a></li>
        <li><a href="#">My Watchlist</a></li>
      </ul>
    </li>
  </ul>
</div> <!-- project-nav -->
EOT;

        return $nav;
    }

    /**
     * build content.
     */
    public function buildContent() {

        $content = <<<EOT
<div id="project-content" class="container-fluid">
  <div class="h4" id="summary">
    <span>
      Filters:
      <div class="btn-group">
        <a href="#" class="btn btn-success btn-xs dropdown-toggle"
                    data-toggle="dropdown">
          Owner <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">
             <span class="glyphicon glyphicon-check"></span>
             Action</a></li>
          <li><a href="#">
             <span class="glyphicon glyphicon-check"></span>
             Another action</a></li>
          <li><a href="#">
             <span class="glyphicon glyphicon-check"></span>
             Something else here</a></li>
          <li><a href="#">
             <span class="glyphicon glyphicon-unchecked"></span>
             Separated link</a></li>
        </ul>
      </div>
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

  <div class="table-responsive">
    <table class="table table-striped table-hover" 
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
        <label for="ticket-search">Search:</label>
        <input type="text" class="form-control" id="ticket-search"
               placeholder="Search Tickets">
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

    /**
     * build footer.
     */
    public function buildFooter() {

        // return empty for now.
        return '';
    }
}
