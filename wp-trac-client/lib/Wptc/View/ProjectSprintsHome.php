<?php
/**
 * @namespace
 */
namespace Wptc\View;

// use the parent class.
use Wptc\View\ProjectViewBase;
use Wptc\Helper\ProjectHelper;

/**
 * the page for project homepage main class.
 */
class ProjectSprintsHome extends ProjectHome {

    /**
     * build content main for tickets list.
     */
    public function buildContent() {

        // build the filter for priority.
        $priority_filter = $this->buildFilterPriority();
        $order_select = $this->buildSelectOrder();

        $project_name = $this->context->getState('project');
        // get project running sprints.
        $project = wptc_get_project($project_name);
        $panels = array();
        foreach($project['meta'] as $version) {
            if($version['type'] == 'version') {
                $desc = "Sprint due on: {$version['due_date']}";
                $panels[] = $this->buildSprintPanel($version['name'],
                                                    'danger',
                                                    $desc);
            }
            if(count($panels) > 1) {
                break;
            }
        }

        $sprint_panels = implode(" ", $panels);
        $backlog_panel = 
            $this->buildSprintPanel('BACKLOG', 'primary');

        $content = <<<EOT
<div id="project-content" class="container-fluid">
  <!--h2 class="bg-info text-center">Loading ...</h3 -->
  <div class="row">
    <div class="col-sm-6" id="sprint-list">
      <!-- list of sprints, sort by date desc -->
      {$sprint_panels}
    </div>

    <div class="col-sm-6" id="backlog-column">
      <!-- list of open tickets, sort by priority -->
      <!-- backlog, open tickets.. -->
      {$backlog_panel}
    </div>
  </div>
</div> <!-- project-content -->
EOT;

        return $content;
    }

    /**
     * build the column for backlog,
     * backlog is special
     */
    public function buildSprintPanel($sprint_name, $panel_color,
                                     $panel_desc=null) {

        if($panel_desc == null) {
            $panel_desc = "Tickets which have not been assigned to any sprint.";
        }

        //$status_checkbox = $this->buildCheckboxStatus();
        $status_checkbox = <<<CHECK
<a href="#" class="btn btn-xs btn-success" 
   id="sprint-{$sprint_name}-status-closed"
   data-toggle="tooltip" title="Show/Hide Closed Tickets"
>
  <span class="glyphicon glyphicon-unchecked"></span> closed
</a>
CHECK;

        $panel = <<<PANEL
      <div class="panel panel-{$panel_color}" 
           id="sprint-{$sprint_name}"
      >
        <div class="panel-heading">
          <span class="panel-title">{$sprint_name}</span>
          <span class="pull-right" id="sprint-{$sprint_name}-summary"
            34 tickets
          </span>
        </div>
        <div class="panel-body bg-info" 
             id="sprint-{$sprint_name}-body"
        >
          {$panel_desc}
          <span class="pull-right">{$status_checkbox}</span>
        </div> <!-- panel-body -->
<div class="list-group" id="sprint-{$sprint_name}-list-group">
</div>
      </div>
PANEL;

        return $panel;
    }
}
