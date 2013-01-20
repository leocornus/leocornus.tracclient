<?php
/**
 * We are trying to use heredoc syntax to preparing some
 * common view and forms.
 */

/**
 * parse wiki format to prepare HTML.
 */
function wptc_widget_parse_content($wiki) {

    //$ret = Markdown($wiki);
    $wkr = new WikiRenderer('trac_to_xhtml');
    $ret = $wkr->render($wiki);

    // apply filters to allow user to tweak.
    return apply_filters('wptc_widget_parse_content', $ret);
}

/**
 * genarate time since for the given time.
 * the given time is on UTC/GMT, that is
 * the centre time zone.  it has no offset.
 * trac is save time in UTC format.
 */
function wptc_widget_time_age($time) {

    $date = new DateTime($time, new DateTimeZone('UTC'));
    $interval = $date->diff(new DateTime('now'));
    $totalDays = $interval->days;
    $minutes = $interval->m;
    $hours = $interval->h;
    $days = $interval->d;
    $years = $interval->y;
    $months = $interval->m;

    if ($totalDays >= 42) {
        // older than 6 weeks, show month only 
        $age = $years * 12 + $months + 1;
        $age = $age . ' months';
    } else if ($totalDays >= 14) {
        // older than 2 weeks, show weeks only
        $weeks = $totalDays / 7;
        $age = $weeks . ' weeks';
    } else if ($totalDays > 0) {
        $age = $totalDays . ' days';
    } else if ($hours > 0) {
        $age = $hours . ' hours';
    } else if ($minutes > 0) {
        $age = $minutes . ' minutes';
    } else {
        $age = $interval->s . ' seconds';
    }

    $fullAge = 
        $interval->format('%y years, %m months, %d days, %h hours and %i minutes');

    $ret = <<<EOT
<a title="$fullAge">$age</a>
EOT;

    return $ret;
}

/**
 * preparing the options for select tag.
 * this could be used by type, milestone, version,
 * priority dropdown
 */
function wptc_widget_options_html($options, $selected,
                                  $showAsGroup=false) {

    $ret = "";

    foreach ($options as $option) {
        if($option === $slelected) {
            $opt = <<<EOT
<option value="{$option}" selected="selected">{$option}</option>
EOT;
        } else {
            $opt = <<<EOT
<option value="{$option}">{$option}</option>
EOT;
        }

        $ret = $ret . $opt;
    }

    return apply_filters('wptc_widget_options_html', $ret);
}

/**
 * prepare the field change maeesage.  default format for
 * a field change is from trac project and looks like 
 * following:
 *   <li>
 *     <strong>Status</strong>
 *       changed from <em>new</em> to <em>closed</em>
 *   </li>
 *   <li>
 *     <strong>Resolution</strong>
 *       set to <em>fixed</em>
 *   </li>
 */
function wptc_widget_field_change_msg($field, $old, $new) {

    if (empty($old)) {
        $change_msg = "set to <em>" . $new . "</em>";
    } else {
        $change_msg = "changed from <em>" . $old . 
            "</em> to <em>" . $new . "</em>";
    }

    $msg = <<<EOT
   <li>
     <strong>{$field}</strong>
     {$change_msg}
   </li>
EOT;

    return apply_filters('wptc_widget_field_change_msg', $msg);
}

/**
 * preparing the sprint navigation bar.
 */
function wptc_widget_sprint_nav() {

    echo <<<EOT
    <ul>
      <li>MileStone
        <ul>
          <li>Sprint 1</li>
          <li>Sprint 2</li>
        </ul>
      </li>
    </ul>
    </div>
EOT;
}

/**
 * preparing the user href by using wordpress user information.
 */
function wptc_widget_user_href($userName) {

    // get WordPress user object.
    $wpUser = get_user_by('login', $userName);

    if (empty($wpUser)) {
        // could not find the user in wordpress database,
        // just return the user name.
        $href = <<<EOT
<a title="$userName">$userName</a>
EOT;
    } else {
        $href = <<<EOT
<a href="{$server_url}/members/{$wpUser->user_login}/profile"
  title="Email: {$wpUser->user_email}">
{$wpUser->display_name}
</a>
EOT;
    }

    return apply_filters('wptc_widget_user_href', $href);
}

/**
 * preparing the ticket properties update/creation form.
 */
function wptc_widget_ticket_props_table($ticket) {

    // preparing options for select tag
    $ticket_type_options = 
        wptc_widget_options_html(wptc_get_ticket_types(),
                                 $ticket['type']);
    $ticket_priority_options = 
        wptc_widget_options_html(wptc_get_ticket_priorities(),
                                 $ticket['property']);
    $ticket_milestone_options = 
        wptc_widget_options_html(wptc_get_ticket_milestones(),
                                 $ticket['milestone']);
    $ticket_component_options = 
        wptc_widget_options_html(wptc_get_ticket_components(),
                                 $ticket['component']);
    $ticket_version_options = 
        wptc_widget_options_html(wptc_get_ticket_versions(),
                                 $ticket['version']);

    $propsTable = <<<EOT
<table><tbody>
  <tr>
    <th><label for="field-summary">Summary:</label></th>
    <td class="fullrow" colspan="3">
      <input type="text" id="field-summary" name="field_summary" 
             value="{$ticket['summary']}" size="70">
    </td>
  </tr>
  <tr>
    <th><label for="field-reporter">Reporter:</label></th>
    <td class="fullrow" colspan="3">
      <input type="text" id="field-reporter" 
             name="field_reporter" 
             value="{$ticket['reporter']}" size="70">
    </td>
  </tr>
  <tr>
    <th><label for="field-description">Description:</label></th>
    <td class="fullrow" colspan="3">
      <fieldset class="iefix">
        <label for="field-description" id="field-description-help">You may use
          <a tabindex="42" href="/trac/egov_opspedia-search/wiki/WikiFormatting">WikiFormatting</a> here.</label>
        <div class="trac-resizable"><div>
          <textarea id="field-description" name="field_description" class="wikitext trac-resizable" rows="10" cols="68">
          {$ticket['description']}
          </textarea>
          <div class="trac-grip" style="margin-left: 2px; margin-right: -2px;">
          </div>
        </div></div>
      </fieldset>
    </td>
  </tr>
  <tr>
    <th class="col1">
      <label for="field-type">Type:</label>
    </th>
    <td class="col1">
      <select id="field-type" name="field_type">
        {$ticket_type_options}
      </select>
    </td>
    <th class="col2">
      <label for="field-priority">Priority:</label>
    </th>
    <td class="col2">
      <select id="field-priority" name="field_priority">
        {$ticket_priority_options}
      </select>
    </td>
  </tr>
  <tr>
    <th class="col1">
      <label for="field-milestone">Milestone:</label>
    </th>
    <td class="col1">
      <select id="field-milestone" name="field_milestone">
        {$ticket_milestone_options}
      </select>
    </td>
    <th class="col2">
      <label for="field-component">Component:</label>
    </th>
    <td class="col2">
      <select id="field-component" name="field_component">
        {$ticket_component_options}
      </select>
    </td>
  </tr>
  <tr>
    <th class="col1">
      <label for="field-version">Version:</label>
    </th>
    <td class="col1">
      <select id="field-version" name="field_version">
        {$ticket_version_options}
      </select>
    </td>
    <th class="col2">
      <label for="field-keywords">Keywords:</label>
    </th>
    <td class="col2">
      <input type="text" id="field-keywords" name="field_keywords" 
             value="{$ticket['keywords']}">
    </td>
  </tr>
</tbody></table>
EOT;

    return apply_filters('wptc_widget_ticket_props_table', 
                         $propsTable);
}

/**
 * preparing the details info for a ticket
 */
function wptc_widget_ticket_info($ticketId) {

    $ticket = wptc_get_ticket($ticketId);
    // the type and status for this ticket.
    $ticket_type_status = $ticket['status'] . " " . $ticket['type'];
    if ($ticket['resolution']) {
        $ticket_type_status = $ticket_type_status . ": " . $ticket['resolution'];
    }
    // get details info for the reportor 
    // assume the username is the same as WordPress login_name
    $ticket_reporter_href = wptc_widget_user_href($ticket['reporter']);
    $ticket_owner_href = wptc_widget_user_href($ticket['owner']);
    // preparing the descriptions.
    $ticket_description = 
        wptc_widget_parse_content($ticket['description']);

    $ticket_openedAge = 
        wptc_widget_time_age($ticket['created']);
    $ticket_modifiedAge = 
        wptc_widget_time_age($ticket['modified']);

    $ticketInfo = <<<EOT
<h1 id="ticket-title">
  Ticket #{$ticket['id']}
  <span class="status">({$ticket_type_status})</span>
</h1>

<div id="ticket">
  <div id="ticket-age" class="date">
    <p>Opened {$ticket_openedAge} ago</p>
    <p>Last modified {$ticket_modifiedAge} ago</p>
  </div> <!-- END ticket-age -->

  <h2 class="summary searchable">{$ticket['summary']}</h2>

  <table class="properties"><tbody>
    <tr>
      <th id="h_reporter">Reported by:</th>
      <td headers="h_reporter" class="searchable">
        {$ticket_reporter_href}
      </td>
      <th id="h_owner">Owned by:</th>
      <td headers="h_owner">
        {$ticket_owner_href}
      </td>
    </tr>
    <tr>
      <th id="h_priority">
        Priority:
      </th>
      <td headers="h_priority">
        {$ticket['priority']}
      </td>
      <th id="h_milestone">
        Milestone:
      </th>
      <td headers="h_milestone">
        {$ticket['milestone']}
      </td>
    </tr>
    <tr>
      <th id="h_component">
        Component:
      </th>
      <td headers="h_component">
        {$ticket['component']}
      </td>
      <th id="h_version">
        Version:
      </th>
      <td headers="h_version">
        {$ticket['version']}
      </td>
    </tr>
    <tr>
      <th id="h_keywords">
        Keywords:
      </th>
      <td headers="h_keywords" class="searchable">
        {$ticket['keywords']}
      </td>
      <th id="h_cc">
        Cc:
      </th>
      <td headers="h_cc" class="searchable">
        {$ticket['cc']}
      </td>
    </tr>
  </tbody></table>

  <div class="description">
    <h3 id="comment:description">
      Description
    <a class="anchor" href="#comment:description" title="Link to this section"> ¶</a></h3>
    <div class="searchable">
      <p>{$ticket_description}<br></p>
    </div>
  </div><!-- END ticket-description -->

</div><!-- END ticket -->
EOT;

    return apply_filters('wptc_widget_ticket_info', $ticketInfo);
}

/**
 * preparting the change history for a ticket.
 */
function wptc_widget_ticket_changelog($ticketId) {

    $changelog = wptc_get_ticket_changelog($ticketId);

    $changes = array();
    // reformat the change log to the following format.
    // change time is the array key.
    // $changes[time] =
    //   'id' => comment id,
    //   'author' => change arthor.
    //   'comment' =>
    //   'change_fields' =>
    foreach($changelog as $log) {
        // get message from each log.
        // trac tracking field's change in one log, 
        // even it appears to user as one change.
        $change_time = $log[0];
        $change_author = $log[1];
        // time and author will keep the same for all logs
        // within one change.
        $change_field = $log[2];
        $change_oldvalue = $log[3];
        $change_newvalue = $log[4];

        // start formating for each field.
        // we are using time as the key.
        if (array_key_exists($change_time, $changes)) {
            // we have this 'time' already, keep working on it.
            $change = $changes[$change_time];
        } else {
            // this is a new 'time', create new change,
            $change = array();
            $changes[$change_time] = $change;
            $change['author'] = $change_author;
        }

        // we handle comment field differently!
        if ($change_field === "comment") {
            $change['id'] = $change_oldvalue;
            // need a little parse for the comment content
            $change['comment'] = 
                wptc_widget_parse_content($change_newvalue);
        } else if (strpos($change_field, '_comm') !== false) {
            // do nothing here,
            // this log is tracking the comments' 
            // change history, it looks like '_comment0'
            // We do NOT need now!
        } else {
            $msg = wptc_widget_field_change_msg($change_field,
                $change_oldvalue, $change_newvalue);
            $change['fields'][] = $msg;
        }

        // set it back.
        $changes[$change_time] = $change;
    }

    $ticketChanges = array();
    // one time one change.
    // one change one record 
    //$ticketChange[] 
    foreach ($changes as $change_time => $change) {
        // preparing the fields.
        $change_age = wptc_widget_time_age($change_time);
        $change_author_href = 
            wptc_widget_user_href($change['author']);
        // check fields is exist or not.
        if (array_key_exists('fields', $change)) {
            $change_fields_list = implode(" ", $change['fields']);
        }

        $ticketChanges[] = <<<EOT
<div class="change" id="trac-change-{$change_id}">
  <h3 class="change">
    <span class="threading">
      <span id="comment:{$change['id']}" class="cnum">
        <a href="#comment:{$change['id']}">comment:{$change['id']}</a>
      </span>
    </span>
    Changed {$change_age} ago by {$change_author_href}
  </h3>
  <ul class="changes">
    {$change_fields_list}
  </ul>
  <div class="comment searchable">
    <p>{$change['comment']}</p>
  </div>
</div>
EOT;
    }

    $changes_html = implode(" ", array_reverse($ticketChanges));
    $ticketChangelog = <<<EOT
<div>
  <h2 class="foldable"><a id="no2" href="#no2">Change History</a></h2>
  <div id="changelog">
    {$changes_html}
  </div>
</div>
EOT;

    return apply_filters('wptc_widget_ticket_changelog', $ticketChangelog);
}
