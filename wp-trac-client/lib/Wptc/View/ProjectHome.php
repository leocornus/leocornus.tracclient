<?php
/**
 * @namespace
 */
namespace Wptc\View;

// use the parent class.
use Wptc\View\ProjectViewBase;
use Wptc\Helper\ProjectHelper;

/**
 * the page for project Kanban view main class.
 */
class ProjectHome extends ProjectViewBase {

    /**
     * build content main for tickets list.
     */
    public function buildContent() {

        // build the filter for priority.
        $priority_filter = $this->buildFilterPriority();
        $order_select = $this->buildSelectOrder();

        $todo_panel = 
            $this->buildKanbanPanel('TODO', 'danger',
                   'Tickets with status <span class="label label-danger">new</span> and <span class="label label-danger">reopened</span>');
        $doing_panel = 
            $this->buildKanbanPanel('DOING', 'primary',
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

    /**
     * build nav bar, using the nav-tabs class.
     * - navigation bar, including the action drop down.
     * - the context object will tell which tab we are in now.
     */
    public function buildNavbar() {

        $project_name = $this->context->getState('project');
        $base_url = "/projects?project={$project_name}";

        $tab = $this->context->getState('tab');
        if(empty($tab)) {
            $home_active = ' class="active"';
        } else {
            switch($tab) {
                case 'tickets':
                    $tickets_active = ' class="active"';
                    break;
                case 'commits':
                    $commits_active = ' class="active"';
                    break;
                case 'sprints':
                    $sprints_active = ' class="active"';
                    break;
            }
        }

        // main query to get the total number.
        $main_query = $this->context->buildMainQuery();
        $ids = wptc_ticket_query($main_query, 0);
        $total_tickets = count($ids);
        $total_commits = $this->context->calcCommitsTotal('');

        $nav = <<<EOT
<div id="project-nav" class="container-fluid h4">
  <ul class="nav nav-tabs">
    <li {$home_active}><a href="{$base_url}">Project Home</a></li>
    <li {$sprints_active}><a href="{$base_url}&tab=sprints">Sprints</a></li>
    <li {$tickets_active}><a href="{$base_url}&tab=tickets">
      <span class="badge">{$total_tickets}</span> Tickets</a>
    </li>
    <li {$commits_active}><a href="{$base_url}&tab=commits">
      <span class="badge">{$total_commits}</span> Commits
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
}
