<?php
/**
 * We are trying to use heredoc syntax to preparing some
 * common view and forms.
 */

/**
 * clean up the textarea input value to remove 
 * un-necessary characters, such as \r\n
 */
function wptc_widget_clean_textarea($input) {

    $ret = str_replace("\r\n", "\n", stripslashes($input));

    return apply_filters('wptc_widget_clean_textarea', $ret);
}

/**
 * parse wiki format to prepare HTML.
 */
function wptc_widget_parse_content($wiki) {

    //$ret = Markdown($wiki);
    $wkr = new WikiRenderer('trac_to_xhtml');
    $ret = $wkr->render($wiki);
    //$ret = $wiki;

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
        $weeks = (int)($totalDays / 7);
        $age = $weeks . ' weeks';
    } else if ($totalDays > 0) {
        // older than 1 day, show days only
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
                                  $hasEmpty=true) {

    $ret = $hasEmpty ? "<option></option>" : "";
    foreach ($options as $option => $label) {
        $sel = "";
        if($option === $selected) {
            $sel = "selected=\"selected\"";
        }

        $opt = <<<EOT
<option value="{$option}" {$sel}>{$label}</option>
EOT;
        $ret = $ret . $opt;
    }

    return apply_filters('wptc_widget_options_html', $ret);
}

/**
 * 
 */
function wptc_widget_optgroups_html($optgroups, $selected,
                                    $hasEmpty=True) {

    $groups = $hasEmpty ? "<option></option>" : "";
    foreach($optgroups as $group => $options) {

        $optsHtml = 
            wptc_widget_options_html($options, $selected, false);
        $group = <<<EOT
<optgroup label="{$group}">
  {$optsHtml}
</optgroup>
EOT;
        $groups = $groups . $group;
    }

    return apply_filters('wptc_widget_optgroups_html', $groups);
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

    if ($field === 'description') {
        // only flag description as modified for now.
        // TODO: add the diff view for the change.
        $change_msg = "modified";
    } else if (empty($old)) {
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
function wptc_widget_version_nav($project) {

    if(!isset($project)) {
        // let using the default project.
        $defaults = wptc_widget_ticket_defaults();
        $project = $defaults['project'];
    }

    // using the global variables.
    global $post, $current_blog;
    $blog_path = $current_blog->path;
    $page_slug = $post->post_parent ? 
        get_page($post->post_parent)->post_name :
        $post->post_name;

    $mandv = wptc_get_project_mandv($project);
    $milestones = array_keys($mandv);
    $stoneLis = array();
    foreach($milestones as $milestone) {
        $stone = $mandv[$milestone][0];
        $versions = array_slice($mandv[$milestone], 1);
        $versionHrefs = array();
        foreach($versions as $version) {
            $versionHrefs[] = <<<EOT
<li>
  <a href="{$blog_path}{$page_slug}?version={$version['name']}">
    {$version['name']}</a>
</li>
EOT;
        }
        $vHrefs = implode('', $versionHrefs);

        $stoneLis[] = <<<EOT
<li>{$stone['name']}
  <ul>
    $vHrefs
  </ul>
</li>
EOT;
    }

    $ret = "<ul>" . implode('', $stoneLis) . "</ul>";

    return apply_filters('wptc_widget_version_nav', $ret);
}

/**
 * the ticket finder widget.
 * this is more like a sample.
 */
function wptc_widget_ticket_finder($page_slug=null) {

    global $post, $current_blog;
    $blog_path = $current_blog->path;
    $goImageUrl = plugins_url('wp-trac-client/images/ticketGo.gif');
    if($page_slug == null) {
        // using the current page to display the ticket.
        $page_slug = $post->post_name;
    }
 
    $form = <<<EOT
<div id="findTicket">
  <div style="float: left;">
  <label for="ticketnumber"><b>Find Ticket by ID: </b></label>
  <input id="ticketnumber" type="text" 
    maxlength="10" size="8" name="ticket_id">
  </div>
  <input id="ticketGo" type="image" alt="Go to Ticket" 
    name="ticketGo" src="{$goImageUrl}" 
    title="Go to Ticket">

  <script>
    jQuery("#ticketGo").click(function(){
        var ticket_id = jQuery("#ticketnumber").val();
        if((ticket_id != "") && jQuery.isNumeric(ticket_id)) {
            // only handle number 
            // redirect to ticket details page.
            ticket_url = "{$blog_path}{$page_slug}?id=" + ticket_id;
            window.location = ticket_url;
        }
    })
  </script>
</div>
EOT;

    return apply_filters('wptc_widget_ticket_finder', $form);
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
 * preparing the ticket list
 */
function wptc_widget_tickets_list($version, $subpageSlug='ticket') {

    $tickets = wptc_get_tickets_by_version($version);

    $ticketTr = array();
    $index = 1;
    foreach($tickets as $ticket) {

        $ticket_owner_href = 
            wptc_widget_user_href($ticket['owner']);
        if($index % 2) {
            $evenOrOdd = "odd";
        } else {
            $evenOrOdd = "even";
        }
        $index = $index + 1;

        // TODO: get from priority object.
        $prioId = 3;

        $ticketTr[] = <<<EOT
<tr class="{$evenOrOdd} prio{$prioId}">
  <td class="id">
    <a href="{$subpageSlug}?id={$ticket['id']}" 
       title="View Ticket">
      #{$ticket['id']}
    </a></td>
  <td class="summary">
    <a href="{$subpageSlug}?id={$ticket['id']}" 
       title="View Ticket">
      {$ticket['summary']}
    </a></td>
  <td class="status">
    {$ticket['status']}
  </td>
  <td class="owner">
    {$ticket_owner_href}
  </td>
  <td class="priority">
    {$ticket['priority']}
  </td>
  <td class="type">
    {$ticket['type']}
  </td>
</tr>
EOT;
    }

    $ticketTrs = implode('', $ticketTr);
    $list = <<<EOT
<div>
<table width="100%" class="listing tickets">
  <tbody>
  <tr class="trac-columns">
    <th class="id">Id</th>
    <th class="summary">Summary</th>
    <th class="status">Status</th>
    <th class="owner">Owner</th>
    <th class="priority">Priority</th>
    <th class="type">Type</th>
  </tr>
  {$ticketTrs}
  </tbody>
</table>
</div>
EOT;

    return apply_filters('wptc_widget_tickets_list', $list);
}

/**
 * preparing the ticket properties update/creation form.
 */
function wptc_widget_ticket_fieldset($ticket) {

    // let's check this is a new ticket or not
    if(isset($ticket)) {
        // existing ticket.
        $ticket_reporter = $ticket['reporter'];
        // no need project and owner field for 
        // tiecket modification.
        $project_owner_tr = "";
    } else {
        // new ticket.
        global $current_user;
        $ticket_reporter = $current_user->user_login;
        // set up some NECESSARY default values 
        $ticket = wptc_widget_ticket_defaults();
        // preparing for project and owner fiels.
        // default owner will be reporter.
        $project_owner_tr = <<<EOT
  <tr>
    <th class="col1">
      <label for="field_owner">Owner:</label>
    </th>
    <td class="col1">
      <input id="field_owner" name="field_owner"
             value="{$ticket_reporter}">
    </td>
    <th class="col2">
    </th>
    <td class="col2">
    </td>
  </tr>
EOT;
    }

    // have to define it first.
    // project is a custom field.
    $project = array_key_exists('project', $ticket) ?
        $ticket['project'] : "";
    if($project === "") {
        $defaults = wptc_widget_ticket_defaults();
        $project = $defaults['project'];
    }

    // preparing options for select tag
    $ticket_project_options = 
        wptc_widget_options_html(wptc_get_ticket_projects(),
                                 $project);
    $ticket_type_options = 
        wptc_widget_options_html(wptc_get_ticket_types(),
                                 $ticket['type']);
    $ticket_priority_options = 
        wptc_widget_options_html(wptc_get_ticket_priorities(),
                                 $ticket['priority']);
    $ticket_milestone_options = 
        wptc_widget_optgroups_html(
            wptc_get_ticket_milestones($project),
            $ticket['milestone']);
    $ticket_component_options = 
        wptc_widget_options_html(wptc_get_ticket_components(),
                                 $ticket['component']);
    $ticket_version_options = 
        wptc_widget_optgroups_html(
            wptc_get_ticket_versions($project, $ticket['milestone']),
            $ticket['version']);

    // the form table.
    $propsTable = <<<EOT
<fieldset id="properties">
<legend>Ticket Properties</legend>
<table><tbody>
  <tr>
    <th><label for="field_summary">Summary:</label></th>
    <td class="fullrow" colspan="3">
      <input type="text" id="field_summary" name="field_summary" 
             value="{$ticket['summary']}" size="70">
    </td>
  </tr>
  <tr>
    <th><label for="field_reporter">Reporter:</label></th>
    <td class="fullrow" colspan="3">
      <input type="text" id="field_reporter" 
             name="field_reporter"
             value="{$ticket_reporter}" size="70">
    </td>
  </tr>
  <tr>
    <th><label for="field-description">Description:</label></th>
    <td class="fullrow" colspan="3">
      <fieldset class="iefix">
        <label for="field-description" id="field-description-help">You may use
          <a tabindex="42" href="http://trac.edgewall.org/wiki/WikiFormatting">WikiFormatting</a> here.</label>
        <div class="trac-resizable"><div>
          <textarea id="field-description" name="field_description" class="wikitext trac-resizable" rows="10" cols="68">{$ticket['description']}</textarea>
          <div class="trac-grip" style="margin-left: 2px; margin-right: -2px;">
          </div>
        </div></div>
      </fieldset>
    </td>
  </tr>
  {$project_owner_tr}
  <tr>
    <th class="col1">
      <label for="field-project">Project:</label>
    </th>
    <td class="col1">
      <select id="field-project" name="field_project">
        {$ticket_project_options}
      </select>
    </td>
    <th class="col2">
      <label for="field-type">Type:</label>
    </th>
    <td class="col2">
      <select id="field-type" name="field_type">
        {$ticket_type_options}
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
      <label for="field-version">Version:</label>
    </th>
    <td class="col1">
      <select id="field-version" name="field_version">
        {$ticket_version_options}
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
    <th>
      <label for="field-keywords">Keywords:</label>
    </th>
    <td class="fullrow" colspan="3">
       <input type="text" id="field-keywords" name="field_keywords" 
             value="{$ticket['keywords']}" size="70">
    </td>
 </tr>
</tbody></table>
</fieldset>
EOT;

    return apply_filters('wptc_widget_ticket_fieldset', 
                         $propsTable);
}

/**
 * preparing the fieldset for action form.
 * available actions,
 * current status
 * current ownder
 */
function wptc_widget_action_fieldset($actions, $status) {

    $actionDivs = array();
    foreach($actions as $action) {
        // each action has the following format:
        // [action, label, hints, [input_fields]]

        // now we handle actions one by one
        $the_action = "";
        $action_checked = '';
        switch($action[0]) {
            case "leave":
                // leave as current status.
                $the_action = "as " . $status;
                // leave as should alway be the default 
                // checked option.
                $action_checked = 'checked="checked"';
                break;
            case "reopen":
                // nothing here.
                $the_action = "";
                break;
            case "accept":
                // nothing here.
                $the_action = "";
                break;
            case "resolve":
                $the_action = wptc_widget_action_resolve($action);
                break;
            case "reassign":
                $the_action = wptc_widget_action_reassign($action);
                break;
        }

        // each action is a radio button.
        $actionDivs[] = <<<EOT
<div>
  <input type="radio" name="action" id="action_{$action[0]}"
         value="{$action[0]}" {$action_checked}>
  <label for="action_{$action[0]}">{$action[1]}</label>
  {$the_action}
  <span class="hint">{$action[2]}</span>
</div>
EOT;
    }

    $divs = implode('', $actionDivs);
    $ret = <<<EOT
<fieldset id="action">
  <legend>Action</legend>
  {$divs}
</fieldset>
EOT;

    return apply_filters('wptc_widget_action_fieldset', $ret);
}

/**
 * preparing the resolve action fields.
 */
function wptc_widget_action_resolve($action) {

    $fields = $action[3][0];
    $options = wptc_widget_options_html($fields[2], $fields[1]);
    // the resolve is a select element.
    // it will be disabled untile the radio button is selected.
    $select = <<<EOT
as 
<select name="{$fields[0]}" id="{$fields[0]}" disabled="">
  {$options}
</select>
EOT;

    return apply_filters('wptc_widget_action_resolve', $select);
}

/**
 * preparing the ressign action.
 */
function wptc_widget_action_reassign($action) {

    $fields = $action[3][0];
    //$options = wptc_widget_options_html($fields[2], $fields[1]);
    // the resolve is a select element.
    // it will be disabled untile the radio button is selected.

    // The following is the right one for default workflow,
    // which has the **set_owner** operation for reassign 
    // action.
// <select name="{$fields[0]}" id="{$fields[0]}" disabled="">
//   {$options}
// </select>
    $select = <<<EOT
to  
<input name="field_owner" 
       id="field_owner" disabled="">
EOT;

    return apply_filters('wptc_widget_action_reassign', $select);
}

/**
 * preparing the comment fieldset.
 */
function wptc_widget_comment_fieldset() {

    $fieldset = <<<EOT
<fieldset class="iefix">
  <label for="wikicomment">You may use
    <a tabindex="42" target="_blank"
       href="http://trac.edgewall.org/wiki/WikiFormatting">
      WikiFormatting</a>
    here.</label>
  <div class="trac-resizable"><div>
    <textarea id="wikicomment" name="wikicomment" class="wikitext trac-resizable" rows="10" cols="78"></textarea>
    <div class="trac-grip" style="margin-left: -1px; margin-right: 1px;">
    </div>
  </div></div>
</fieldset>
EOT;

    return apply_filters('wptc_widget_comment_fieldset', $fieldset);
}

/**
 * the login reminder for not logged in users.
 */
function wptc_widget_ticket_info_topnav($ticket_id) {

    if(is_user_logged_in()) {
        // user logged in, return empty string
        // to leave topnav empty.
        return "";
    }

    $loginHref = get_option('siteurl') . 
        "/wp-login.php?redirect_to=" . 
        urlencode(get_permalink() . "?id=" . $ticket_id);

    $topnav = <<<EOT
<div class="trac-topnav">
  Please <a href="{$loginHref}">log in</a> to update/comment
</div>
EOT;

    return $topnav;
}

/**
 * preparing the details info for a ticket
 */
function wptc_widget_ticket_info($ticket) {

    // the type and status for this ticket.
    $ticket_type_status = $ticket['status'] . " " . 
                          $ticket['type'];
    if ($ticket['resolution']) {
        $ticket_type_status = $ticket_type_status . ": " . 
                              $ticket['resolution'];
    }
    // get details info for the reportor 
    // assume the username is the same as WordPress login_name
    $ticket_reporter_href = 
        wptc_widget_user_href($ticket['reporter']);
    $ticket_owner_href = 
        wptc_widget_user_href($ticket['owner']);
    // preparing the descriptions.
    $ticket_description = 
        wptc_widget_parse_content($ticket['description']);

    $ticket_openedAge = 
        wptc_widget_time_age($ticket['created']);
    $ticket_modifiedAge = 
        wptc_widget_time_age($ticket['modified']);

    $permalink = get_permalink() . "?id=" . $ticket['id'];
    $topnav = wptc_widget_ticket_info_topnav($ticket['id']);

    $ticketInfo = <<<EOT
{$topnav}
<h1 id="ticket-title">
  <a href="{$permalink}">Ticket #{$ticket['id']}</a>
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
function wptc_widget_ticket_changelog($changelog) {

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
        $change = array();
        if (array_key_exists($change_time, $changes)) {
            // we have this 'time' already, keep working on it.
            $change = $changes[$change_time];
        } else {
            // this is a new 'time', create new change,
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
        // have to reset here, otherwise it will keep the 
        // previous change's value.
        $change_fields_list = "";
        if (array_key_exists('fields', $change)) {
            $change_fields_list = implode(" ", $change['fields']);
            $change_fields_list = <<<EOT
  <ul>
    {$change_fields_list}
  </ul>
EOT;
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
  {$change_fields_list}
  <div class="comment searchable">
    <p>{$change['comment']}</p>
  </div>
</div>
EOT;
    }

    $changes_html = implode(" ", array_reverse($ticketChanges));
    $ticketChangelog = <<<EOT
<div>
  <h2 class="foldable">
    <a id="no3" href="#no3">Change History</a>
  </h2>
  <div id="changelog">
    {$changes_html}
  </div>
</div>
EOT;

    return apply_filters('wptc_widget_ticket_changelog', $ticketChangelog);
}

/**
 * preparing the ticket ediging form, mainly for 
 * update ticket properties.
 */
function wptc_widget_new_ticket_form() {

    if (! is_user_logged_in()) {
        // user not logged in. do nothing here.
        $loginHref = get_option('siteurl') . 
            "/wp-login.php?redirect_to=" . 
            urlencode(get_permalink());
        echo <<<EOT
<div>
  <h1 id="ticket-title">
    Please <a href="{$loginHref}">log in</a> to 
    create new ticket!
  </h1>
</div>
EOT;
        return;
    }

    // preparing the form.
    echo <<<EOT
<div>
  <h1 id="ticket-title">Create New Ticket</h1>
  <form method="post" id="ticketform">
    <div id="modify">
EOT;
    // pass a not set variable
    echo wptc_widget_ticket_fieldset($ticket);
    echo <<<EOT
    </div>
    <div class="buttons">
      <input type="hidden" id="invalidFields" 
             name="invalidFields" value="">
      <input type="submit" id="descsubmit" 
             name="submit" value="Submit changes">
    </div>
  </form>
</div>
EOT;

}

/**
 * preparing the ticket ediging form, mainly for 
 * update ticket properties.
 */
function wptc_widget_ticket_form($ticket, $actions) {

    if (! is_user_logged_in()) {
        // user not logged in. do nothing here.
        return;
    }

    // the editing form, it should only show up for
    // logged in users.
    echo "<form method='post' id='ticketform'>";
    // the ticket editing form
    echo <<<EOT
<div class="collapsed">
  <h2 class="foldable">
    <a id="no1" href="#no1">Modify Ticket</a>
  </h2>
  <div id="modify" class="field">
EOT;
    echo wptc_widget_ticket_fieldset($ticket);
    echo <<<EOT
  </div>
  <div class="buttons">
    <input type="submit" id="descsubmit" name="submit" value="Submit changes">
  </div>
</div>
EOT;

    // combine comment and action sections together.
    echo <<<EOT
<div>
<h2 class="foldable">
  <a id="no2" href="#no2"
     onfocus="$('#wikicomment').get(0).focus()">Add Comment</a>
</h2>
<div id="commentaction">
EOT;
    echo wptc_widget_comment_fieldset();
    echo wptc_widget_action_fieldset($actions, $ticket['status']);
    // preparing the timestamp, should be the following format
    // value="2012-11-26 20:01:26.925065+00:00"
    //$now = new DateTime("now", new DateTimeZone('UTC'));
    //$ts = $now->format('Y-m-d H:i:sP');
    echo <<<EOT
    <div class="buttons">
      <input type="hidden" id="ts" name="ts" value="{$ticket['_ts']}">
      <input type="hidden" id="id" name="id" value="{$ticket['id']}">
      <input type="hidden" id="invalidFields" 
             name="invalidFields" value="">
      <!-- input type="submit" name="preview" value="Preview" -->&nbsp;
      <input type="submit" id="wikisubmit" name="submit" value="Submit changes">
    </div>
</div>
</div>
EOT;
    echo "</form>";
}

/**
 * entry point for ticket details page.
 */
function wptc_widget_ticket_details($ticket) {

    // one call to get ticket, changelog, and actions.
    $ticket_id = $ticket['id'];
    $changelog = wptc_get_ticket_changelog($ticket_id);
    $actions = wptc_get_ticket_actions($ticket_id);

    echo wptc_widget_ticket_info($ticket);

    // load the ticket editing form.
    wptc_widget_ticket_form($ticket, $actions);

    // Change log at the end.
    echo wptc_widget_ticket_changelog($changelog);
}

/**
 * preparing s set of values for new create ticket form.
 */
function wptc_widget_ticket_defaults() {

    $projects = array_keys(wptc_get_ticket_projects());
    $ticket['project'] = $projects[0];
    $mandvs = wptc_get_project_mandv($projects[0]);
    $milestones = array_keys($mandvs);
    $ticket['milestone'] = $milestones[0];
    $ticket['version'] = $mandvs[$milestones[0]][1]['name'];

    return $ticket;
}
