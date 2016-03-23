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
class AllTimelineHome extends AllProjectsHome {

    /**
     * build content for timeline.
     */
    public function buildContent() {

        // using 5 weeks time range as the default.
        $from = date('m/d/Y', strtotime("-5 Weeks"));
        $timeline = wptc_get_tickets_timeline($from);
        $timeline_dts = "";
        foreach($timeline as $change_time => $aline) {

            $change_age = wptc_widget_time_age($change_time);
            $ticket_href = 'ticket?id=' . $aline['id'];
            $author_href = wptc_widget_user_href($aline['author']);
            $project_href = <<<PROJECT
<a href="/projects/?project={$aline['project']}">
{$aline['project']}
</a>
PROJECT;

            $action = $aline['action'];
            $summary = $aline['summary'];

            $color_class = 'list-group-item-info';
            if($action == 'created') {
                $color_class = 'list-group-item-danger';
            }
            // set color to success if it is closed.
            if(strpos($summary, 'closed') === false) {
                // do nothing.
            } else {
                $color_class = 'list-group-item-success';
            }

            $ticket_dt = <<<EOT
<li class="list-group-item {$color_class}">
<span class="badge">{$change_age}</span>
<dt>
  <a href="{$ticket_href}" class="ticket">
  Ticket 
  (<em title="{$aline['title']}">#{$aline['id']}</em>)
  {$aline['title']} 
  </a> {$aline['action']} by {$author_href} at {$project_href}
</dt>
<dd>
  {$aline['summary']} [...]
</dd>
</li>
EOT;
            $timeline_dts = $timeline_dts . $ticket_dt;
        }

        $content = <<<CONTENT
<div class="panel">
  <ul class="list-group">
    {$timeline_dts}
  </ul>
</div>
CONTENT;

        return $content;
    }
}
