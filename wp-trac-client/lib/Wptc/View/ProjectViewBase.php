<?php
/**
 * @namespace
 */
namespace Wptc\View;

/**
 * The base class for all views related to a project.
 * this class will 
 * - define the base layout for all project views.
 * - provide the basic methods to render / generate the 
 *   common sections for a page, such as header, nabar, 
 *   footer, etc.
 */
class ProjectViewBase {

    /**
     * request context for a view
     */
    protected $context = null;

    /**
     * constructor
     */
    public function __construct($context) {

        $this->context = $context;
    }

    /**
     * render the page, with basic general layout.
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
     * build header, the gneral header for a project.
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
     * build nav bar, using the nav-tabs class.
     * - navigation bar, including the action drop down.
     * - the context object will tell which tab we are in now.
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
     * build content. the content section will basically have:
     * - filter bar based on what content is it.
     * - content, maybe list of items or summary panels.
     * - pager if needed.
     *
     * the context object will tell:
     * - what content to serve.
     * - what filter it will provide
     * - waht actions it should have
     */
    public function buildContent() {

        // empty container for now.
        $content = <<<EOT
<div id="project-content" class="container-fluid">
</div> <!-- project-content -->
EOT;

        return $content;
    }

    /**
     * build footer.
     */
    public function buildFooter() {

        // here is a sample footer:
        // a 3-column row in a well class.
        $footer = <<<EOT
<div class="well" id="local-project-footer">
  <div class="row">
    <div class="col-sm-4">
      <h2>Column 1</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h2>Column 2</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h2>Column 3</h2> 
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
  </div>
</div> <!-- local-project-footer -->
EOT;

        if(has_filter('wptc_project_view_footer')) {
            $footer = apply_filters('wptc_project_view_footer',
                                    $footer);
        }

        return $footer; 
    }

    // =====================
    // some mics components / setcions.
    // =====================

    /**
     * this one will build a dropdown with a list owner's name
     * by default it implemented as dropdown menu.
     *
     * NOTE:
     * This is just a markup for now.
     * we should pass a list user names
     */
    public function buildFilterOwner() {

        $owners = <<<OWNER
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
OWNER;

        return $owners;
    }

    /**
     * build the priority filter. this is based on the default
     * trac priorities.
     */
    public function buildFilterPriority() {

        $priority = <<<PRIO
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
PRIO;

        return $priority;
    }

    /**
     * build status checkbox, based on the default trac status.
     */
    public function buildCheckboxStatus() {

        $status = <<<STATUS
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
STATUS;

        return $status;
    }

    /**
     * build the select options for sorting order.
     */
    public function buildSelectOrder() {

        $order = <<<ORDER
<select class="success" id="order">
  <option value="priority">Priority</option>
  <option value="changetime">Last Modified Date</option>
  <option value="id">Ticket ID</option>
</select>
ORDER;

        return $order;
    }

    /**
     * build the 3 column kanban page.
     */
    public function buildKanbanContent() {

        $todo_panel = 
            $this->buildKanbanPanel('TODO', 'danger',
                   'Tickets with status <span class="label label-danger">new</span> and <span class="label label-danger">reopened</span>');
        $doing_panel = 
            $this->buildKanbanPanel('DOING', 'warning',
                   'Tickets with status <span class="label label-primary">assigned</span> and <span class="label label-primary">accepted</span>');
        $done_panel = 
            $this->buildKanbanPanel('DONE', 'success',
                   'Tickets with status <span class="label label-success">closed</span>');

        $content = <<<EOT
<div id="project-content" class="container-fluid">
  <!--h2 class="bg-info text-center">Loading ...</h3 -->
  <div class="row">
    <div class="col-sm-4" id="col-todo">
      {$todo_panel}
    </div>

    <div class="col-sm-4" id="col-doing">
      {$doing_panel}
    </div>

    <div class="col-sm-4" id="col-done">
      <!-- list of open tickets, sort by priority -->
      <!-- backlog, open tickets.. -->
      {$done_panel}
    </div>
  </div>
</div> <!-- project-content -->
EOT;

        return $content;
    }

    /**
     * build the kanban panel,
     */
    public function buildKanbanPanel($kanban_name, $panel_color,
                                     $panel_desc=null) {

        if($panel_desc == null) {
            $panel_desc = "Tickets which have not been assigned to any .";
        }

        //$status_checkbox = $this->buildCheckboxStatus();
        $status_checkbox = <<<CHECK
<a href="#" class="btn btn-xs btn-success" 
   id="kanban-{$kanban_name}-status-closed"
   data-toggle="tooltip" title="Show/Hide Closed Tickets"
>
  <span class="glyphicon glyphicon-unchecked"></span> closed
</a>
CHECK;
        // <i class="fa fa-chevron-left fa-border"></i><i class="fa fa-chevron-right fa-border"></i>
        $panel = <<<PANEL
      <div class="panel panel-{$panel_color}" 
           id="kanban-{$kanban_name}"
      >
        <div class="panel-heading">
          <span class="panel-title">
            <i class="fa fa-th-large fa-lg"></i> {$kanban_name}
            <span class="pull-right" id="kanban-{$kanban_name}-summary">
            <small>
              <span class="btn-group">
                <span id="start">1</span>-<span id="end"></span> 
                of <strong><span id="total"></span></strong>
              </span>
              <div class="btn-group">
                <a class="btn btn-xs btn-{$panel_color}" 
                   id="previous">
                  <i class="fa fa-chevron-left"></i>
                </a>
                <a class="btn btn-xs btn-{$panel_color}" 
                   id="next">
                  <i class="fa fa-chevron-right"></i>
                </a> 
              </div>
            </small>
            </span>
          </span>
        </div>
        <div class="panel-body bg-info" 
             id="kanban-{$kanban_name}-body"
        >
          {$panel_desc}
        </div> <!-- panel-body -->
<div class="list-group" id="kanban-{$kanban_name}-list-group">
</div>
      </div>
PANEL;

        return $panel;
    }
}
