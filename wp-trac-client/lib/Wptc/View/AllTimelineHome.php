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

        // using 3 weeks time range as the default.
        $from = date('m/d/Y', strtotime("-3 Weeks"));
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

            $wp_user = get_user_by('login', $aline['author']);
            $author_avatar = bp_core_fetch_avatar(array(
              'item_id' => $wp_user->ID,
              'html' => false
            ));

            $action = $aline['action'];
            $summary = $aline['summary'];

            // default class is empty, which will be default color
            $color_class = '';
            if($action == 'created') {
                $color_class = 'list-group-item-warning';
            }
            // set color to success if it is closed.
            if(strpos($summary, 'closed') === false) {
                // do nothing.
            } else {
                $color_class = 'list-group-item-success';
            }

            $ticket_dt = <<<EOT
<li class="list-group-item {$color_class}">
  <span class="badge">{$change_age} ago</span>
  <div class="media">
    <div class="media-left">
      <a href="/members/{$aline['author']}/profile">
        <img class="media-object" src="{$author_avatar}" alt="user name">
      </a>
    </div>
    <div class="media-body">
      <div class="media-heading h4">
        {$author_href} {$aline['action']} 
        <a href="{$ticket_href}">
        Ticket 
        (<em title="{$aline['title']}">#{$aline['id']}</em>)
        {$aline['title']} 
        </a> at project {$project_href}
      </div>
      <div>
      {$aline['summary']}
      </div>
    </div>
  </div>
</li>
EOT;

//<dt>
//  <a href="{$ticket_href}" class="ticket">
//  Ticket 
//  (<em title="{$aline['title']}">#{$aline['id']}</em>)
//  {$aline['title']} 
//  </a> {$aline['action']} by {$author_href} at {$project_href}
//</dt>
//<dd>
//  {$aline['summary']} [...]
//</dd>
//</li>
            $timeline_dts = $timeline_dts . $ticket_dt;
        }

        $content = <<<CONTENT
<div class="row">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading text-center">
        <span class="panel-title"><strong>Summary</strong></span>
      </div>
      <div class="panel-body bg-info">
        Activity summary in past 2 weeks.
      </div>
      <ul class="list-group">
        <li class="list-group-item">Total number for Projects</li>
        <li class="list-group-item">Total number for Tickets</li>
        <li class="list-group-item">Total number for Authors</li>
      </ul>
    </div>
  </div>
  <div class="col-md-8">
    <div class="panel panel-info">
      <div class="panel-heading text-center">
        <span class="panel-title"><strong>Activity</strong></span>
      </div>
      <ul class="list-group">
        {$timeline_dts}
      </ul>
    </div>
  </div>
</div>
CONTENT;

        return $content;
    }
}
