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

        $kanbanContent = $this->buildKanbanContent();

        return $kanbanContent;
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
