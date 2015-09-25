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
        <div class="panel-body bg-info">
panel-body could be located before and after list-group.
        </div> <!-- panel-body -->
<div class="list-group">
    <!-- style="min-height:210; max-height:310; overflow-y: auto;
          overflow-x: hidden;" -->
  <a href="#" class="list-group-item clearfix">
    <span class="badge">ticket id</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-danger">blocker</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-danger">critical</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-warning">major</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-default">minor</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-danger">blocker</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-warning">major</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-warning">major</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
</div>
        <div class="panel-body bg-danger">
panel body brief description.
        </div> <!-- panel-body -->
      </div>

      <!-- done sprint. -->
      <div class="panel panel-success">
        <div class="panel-heading">
          <span class="panel-title">Last Closed Sprint</span>
          <span class="pull-right">18 closed tickets</span>
        </div>
<div class="list-group">
    <!-- style="min-height:210; max-height:210; overflow-y: auto;
          overflow-x: hidden;" -->
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-danger">critical</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-warning">major</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-warning">major</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-danger">blocker</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-warning">major</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
  <a href="#" class="list-group-item clearfix">
    <span class="badge">1347</span>
    Cras justo odio more summary more and more
    Cras justo odio more summary more and more
    <br/>
    <div class="pull-right">
      <span class="label label-warning">major</span>
      <span class="label label-primary">assigned</span> to 
      <span class="label label-info">Sean Chen</span>
    </div>
  </a>
</div>
        <div class="panel-footer">
          <span>Panel footer</span>
        </div>
      </div>

    </div>

    <div class="col-sm-6" id="backlog-column">
      <!-- list of open tickets, sort by priority -->
      <!-- backlog, open tickets.. -->
      <div class="panel panel-primary" id="panel-backlog">
        <div class="panel-heading">
          <span class="panel-title">Backlog</span>
          <span class="pull-right" id="panel-backlog-summary"
            34 tickets
          </span>
        </div>
        <div class="panel-body bg-info">
what we should put here on panel body?
how about leave it empty?
        </div> <!-- panel-body -->
<div class="list-group" id="panel-backlog-list-group">
</div>
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
