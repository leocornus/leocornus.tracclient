<?php
/**
 * @namespace
 */
namespace Wptc\View;

// use the parent class.
use Wptc\View\ProjectViewBase;

/**
 * the page for project homepage main class.
 */
class ProjectHome extends ProjectViewBase {

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
  <!--h2 class="bg-info text-center">Loading ...</h3 -->
  <div class="row">
    <div class="col-sm-6" id="sprint-list">
      <!-- list of sprints, sort by date desc -->
      <div class="panel panel-danger">
        <div class="panel-heading">
          <span class="panel-title">Current Sprint</span>
          <span class="pull-right">12 closed in 15 total tickets</span>
        </div>
        <div class="panel-body">
panel body brief description.
        </div> <!-- panel-body -->
<ul class="list-group"
    style="min-height:210; max-height:210; overflow-y: auto;"
>
  <li class="list-group-item">
    <span class="badge">14</span>
    Cras justo odio
  </li>
  <li class="list-group-item">
    <span class="badge">2</span>
    Dapibus ac facilisis in
  </li>
  <li class="list-group-item">
    <span class="badge">1</span>
    Morbi leo risus
  </li>
</ul>
        <div class="panel-footer">
          <span>Panel footer</span>
        </div>
      </div>

      <!-- done sprint. -->
      <div class="panel panel-success">
        <div class="panel-heading">
          <span class="panel-title">Last Closed Sprint</span>
          <span class="pull-right">18 closed tickets</span>
        </div>
        <div class="panel-body">
some brief overview.
        </div> <!-- panel-body -->
<ul class="list-group"
    style="min-height:210; max-height:210; overflow-y: auto;"
>
  <li class="list-group-item">
    <span class="badge">14</span>
    Cras justo odio
  </li>
  <li class="list-group-item">
    <span class="badge">2</span>
    Dapibus ac facilisis in
  </li>
  <li class="list-group-item">
    <span class="badge">1</span>
    Morbi leo risus
  </li>
  <li class="list-group-item">
    <span class="badge">14</span>
    Cras justo odio
  </li>
  <li class="list-group-item">
    <span class="badge">2</span>
    Dapibus ac facilisis in
  </li>
  <li class="list-group-item">
    <span class="badge">1</span>
    Morbi leo risus
  </li>
  <li class="list-group-item">
    <span class="badge">14</span>
    Cras justo odio
  </li>
  <li class="list-group-item">
    <span class="badge">2</span>
    Dapibus ac facilisis in
  </li>
  <li class="list-group-item">
    <span class="badge">1</span>
    Morbi leo risus
  </li>
</ul>
        <div class="panel-footer">
          <span>Panel footer</span>
        </div>
      </div>

    </div>

    <div class="col-sm-6" id="open-tickets-list">
      <!-- list of open tickets, sort by priority -->
      <!-- backlog, open tickets.. -->
      <div class="panel panel-primary">
        <div class="panel-heading">
          <span class="panel-title">Backlog</span>
          <span class="pull-right">34 tickets</span>
        </div>
        <div class="panel-body">
list of open tickets, in backlog.
        </div> <!-- panel-body -->
<ul class="list-group"
    style="min-height:550; overflow-y: auto;"
>
  <li class="list-group-item">
    <span class="badge">14</span>
    Cras justo odio
  </li>
  <li class="list-group-item">
    <span class="badge">2</span>
    Dapibus ac facilisis in
  </li>
  <li class="list-group-item">
    <span class="badge">1</span>
    Morbi leo risus
  </li>
</ul>
        <div class="panel-footer">
panel footer will have buttons and search
        </div> <!-- panel-footer-->
      </div>
    </div>
  </div>
</div> <!-- project-content -->
EOT;

        return $content;
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
