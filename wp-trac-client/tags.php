<?php
/**
 * template tags for easy use on theme templage.
 */

/**
 * return the base url to git repository.
 */
function wptc_get_git_base_url() {

    return get_site_option('wptc_git_base_url');
}

/**
 * return a list of tickets.
 *
 * @param $milestone 
 * @param $version
 * @param $max the number of results to receive default is 25
 * @param $page the page number, starts from 0
 *
 * @return 
 */
function wptc_get_tickets($milestone, $version=null, $max=25, $page=1) {

    do_action('wptc_get_tickets');

    // get the ticket proxy.
    $proxy = get_wptc_client()->getProxy('ticket');
    $queryStr = wptc_build_query($milestone, $version, $max, $page);

    $ids = $proxy->query($queryStr);
    $tickets = array();
    foreach ($ids as $id) {
        $ticket = $proxy->get($id);
        array_push($tickets, $ticket);
    }

    return apply_filters('wptc_get_tickets', $tickets);
}

/**
 * return the amount of tickets for the given criteria
 */
function wptc_get_tickets_amount($milestone, $version=null) {

    $proxy = get_wptc_client()->getProxy('ticket');
    // set the max to 0 will get all tickets.
    $queryStr = wptc_build_query($milestone, $version, 0);
    $ids = $proxy->query($queryStr);
    return count($ids);
}

/**
 * the multicall version to return all tickets under the given 
 * criteria.
 */
function wptc_get_tickets_m($milestone, $version=null, $max=25, $page=1) {

    do_action('wptc_get_tickets_m');

    // get the ticket proxy.
    $proxy = get_wptc_client()->getProxy('ticket');
    $queryStr = wptc_build_query($milestone, $version, $max, $page);

    $ids = $proxy->query($queryStr);
    $tickets = wptc_get_tickets_list_m($ids);

    return apply_filters('wptc_get_tickets_m', $tickets);
}

/**
 * return a list of tickets by version.
 */
function wptc_get_tickets_by_version($version) {

    $proxy = get_wptc_client()->getProxy('ticket');
    // query by version order by prority
    $queryStr = 'version=' . $version .
                '&order=priority&desc=0';
    $ids = $proxy->query($queryStr);

    $tickets = wptc_get_tickets_list_m($ids);
    return apply_filters('wptc_get_tickets_by_version', 
                         $tickets);
}

/**
 * return a list of tickets for the given owner.
 */
function wptc_get_tickets_by_owner($owner) {

    $proxy = get_wptc_client()->getProxy('ticket');
    // query by version order by prority
    $queryStr = 'owner=' . $owner .
                '&status!=closed' .
                '&order=priority&desc=0';
    $ids = $proxy->query($queryStr);

    $tickets = wptc_get_tickets_list_m($ids);
    return apply_filters('wptc_get_tickets_by_owner', 
                         $tickets);
}

/*
 * return a list fo tickets for the given milstone.
 */
function wptc_get_tickets_by_milestone($milestone, $max=100, $page=1) {

    $query = "milestone=" . $milestone .
             "&order=id";
    $ids = wptc_ticket_query($query, $max, $page);
    $tickets = wptc_get_tickets_list_m($ids);

    return $tickets;
}

/**
 * using multi-call to get ticket info for a list of 
 * ids.
 */
function wptc_get_tickets_list_m($ids) {

    // preparing the signature.
    $signatures = array();
    foreach ($ids as $id) {
        $sign = array(
            'methodName' => 'ticket.get',
            'params' => array($id)
            );
        array_push($signatures, $sign);
    }
    // get the system proxy.
    $proxy = get_wptc_client()->getProxy('system');
    $tics = $proxy->multicall($signatures);

    $tickets = array();
    foreach ($tics as $ticket) {

        $row = $ticket[0][3];
        $row['id'] = $ticket[0][0];
        $row['created'] = $ticket[0][1];
        $row['modified'] = $ticket[0][2];
        //$status = $ticket[0][3]['status'];
        //$summary = $ticket[0][3]['summary'];
        //$owner = $ticket[0][3]['owner'];
        //$priority = $ticket[0][3]['priority'];

        // add to aaData.
        $tickets[] = $row;
    }

    return $tickets;
}

/**
 * build query based on the given parameters.
 *
 */
function wptc_build_query($milestone, $version=null, $max=25, $page=1) {

    // build the query string.
    $queryStr = 'milestone=' . $milestone;
    if ($version != null) {
        $queryStr = $queryStr . '&version=' . $version;
    }
    $queryStr = $queryStr . '&max=' . $max . '&page=' . $page;

    return $queryStr;
}

/**
 * generic way to query tickets.
 * set max to 0 to retrun all tickets.
 */
function wptc_ticket_query($query, $max=25, $page=1) {

    $proxy = get_wptc_client()->getProxy('ticket');
    $query = $query . '&max=' . $max . '&page=' . $page;
    $ids = $proxy->query($query);
    return $ids;
}

/**
 * multicall query tickets.
 */
function wptc_ticket_query_m($querys) {

    // preparing the signature.
    $signatures = array();
    foreach ($querys as $query) {
        $sign = array(
            'methodName' => 'ticket.query',
            'params' => array($query)
            );
        array_push($signatures, $sign);
    }
    // get the system proxy.
    $proxy = get_wptc_client()->getProxy('system');
    $results = $proxy->multicall($signatures);

    return $results;
}

/**
 * return ticket timeline as an array.
 * The from and to date should use the following format:
 * 01/31/2013
 * the return of the timeline should looks like the following:
 *
 * array(
 *     '01/31/2013' => array(
 *         '14:30' => 'message',
           '13:20' => 'message',

 *         ),
 *     '01/30/2013' => array(
           '12:45' => array(
               
               ),
 *         ),
 * )
 *
 * @param $from start date
 * @param $to end date, default is null, which means now today.
 */
function wptc_get_tickets_timeline($from, $to=null) {

    if($to === null) {
        // today will be used.
        $to = date("Ymd\TH:i:s", time());
    }
    $proxy = get_wptc_client()->getProxy('ticket');
    $query = 'changetime=' . $from . '..' . $to .
             '&order=changetime&desc=1';
    $ids = $proxy->query($query);
    // get change logs for all of them at once.
    $tickets = wptc_get_tickets_list_m($ids);
    $tickets = array_combine($ids, $tickets);
    $changelogs = wptc_get_tickets_changelog_m($ids);

    $timeline = array();
    foreach($changelogs as $id => $logs) {
        $ticket = $tickets[$id];
        // check the created date first.
        if(strtotime($ticket['created']) > strtotime($from)) {
            // this is a new created ticket in the time range.
            $line = array(
                'id' => $id,
                'title' => $ticket['summary'],
                'author' => $ticket['reporter'],
                // action will be one of created, accepted, assigned,
                // reopened, closed, and updated.
                // basically it will 
                'action' => 'created',
                // TODO: get first line of desc as the message
                'summary' => substr($ticket['description'], 0,  120)
            );
            // this change time should not exist yet!
            $timeline[$ticket['created']] = $line;
        }

        // reverse to make the latest change first.
        $logs = array_reverse($logs[0]);
        $changes = array();
        foreach($logs as $log) {

            $change_time = $log[0];
            if(strtotime($change_time) < strtotime($from)) {
                // this change is too old.
                // skip to the next change log directly.
                break;
            }
            $change = wptc_combine_changes($changes, $log);
            if($change !== null) {
                $changes[$change_time] = $change;
            }
        }

        foreach($changes as $change_time => $change) {

            $line = array(
                'id' => $id,
                'title' => $ticket['summary'],
                'author' => $change['author'],
                'action' => 'updated',
            );
            if(array_key_exists('comment', $change)) {
                $line['summary'] = substr($change['comment'], 0, 120);
            }
            if (empty($line['summary']) &&
                array_key_exists('fields', $change)) {
            
                $line['summary'] = implode(" ", $change['fields']);
            }
            // TODO: How about comments?
            $timeline[$change_time] = $line;
        }
    }

    // sort the array by key.
    ksort($timeline);

    // return the reverse order, so the latest change will go
    // first.
    return array_reverse($timeline);
}

function wptc_combine_changes($changes, $log) {

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

    if ($change_field === "cc") {
        // skip changes on cc field, we will have long 
        // value for cc field. Since we use is as watch list.
        return null;
    }

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
        $change['comment'] = $change_newvalue;
    } else if (strpos($change_field, '_comm') !== false) {
        // do nothing here,
        // this log is tracking the comments' 
        // change history, it looks like '_comment0'
        // We do NOT need now!
    } else {
        $msg = wptc_ticket_field_change_msg($change_field,
            $change_oldvalue, $change_newvalue);
        $change['fields'][] = $msg;
    }

    return $change;
}

function wptc_ticket_field_change_msg($field, $old, $new) {

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

    return "<strong>{$field}</strong> " . $change_msg;
}

/**
 * return all details about a ticket.
 *
 * @param $id the ticket id.
 */
function wptc_get_ticket($id) {

    do_action('wptc_get_ticket');

    // we only need the ticket proxy.
    $proxy = get_wptc_client()->getProxy('ticket');
    try {
        $raw = $proxy->get($id);
        $ticket = $raw[3];
        $ticket['id'] = $id;
        $ticket['created'] = $raw[1];
        $ticket['modified'] = $raw[2];
        // we will use separate function for ticket changelog
        //$changeLog = $proxy->changeLog($id);
        //$ticket_details = array_merge($ticket, $changeLog);
    } catch (Zend_XmlRpc_Client_FaultException $e) {
        // ticket is not exist. we will create one.
        //return $e->getMessage();
        // return a empty array!
        $ticket = array();
    } 

    return apply_filters('wptc_get_ticket', $ticket);
}

/**
 * return all hangelogs for a ticket.
 */
function wptc_get_ticket_changelog($id) {

    $proxy = get_wptc_client()->getProxy('ticket');
    $changeLog = $proxy->changeLog($id);

    return apply_filters('wptc_get_ticket_changelog', 
                         $changeLog);
}

/**
 * multi call to return all changelog for a list of tickets.
 */
function wptc_get_tickets_changelog_m($ids) {

    // prepare the signatures.
    $signatures = array();
    foreach($ids as $id) {
        $sign = array(
            'methodName' => 'ticket.changeLog',
            'params' => array($id)
        );
        array_push($signatures, $sign);
    }
    // using the system proxy
    $proxy = get_wptc_client()->getProxy('system');
    $logs = $proxy->multicall($signatures);

    return array_combine($ids, $logs);
}

function wptc_quick_test() {

    //$logs = wptc_get_tickets_changelog_m(array(981, 535, 538));
    ////return $logs;
    //foreach($logs as $id => $log) {

    //    $log = array_reverse($log[0]);
    //    return empty($log);
    //}

    global $wp_styles;

    $from = date('m/d/Y', strtotime("-2 Weeks"));
    return $wp_styles;
}

/**
 * return all available actions for the given ticket.
 */
function wptc_get_ticket_actions($id) {

    $proxy = get_wptc_client()->getProxy('ticket');
    $actions = $proxy->getActions($id);

    return apply_filters('wptc_get_ticket_actions', $actions);
}

/**
 * retrun all metadata values specified by the given
 * meta name.
 *
 * @param $metaName the name of this metadata, 
 *        it could be: type, milestone, versions,
 *        component, priority.
 * @return a list of metadata names.
 */
function wptc_get_ticket_metas($metaName) {

    $proxy = get_wptc_client()->getProxy('ticket.'. $metaName);
    $metas = $proxy->getAll();
    return $metas;
}

/**
 * return the details attributes for the given meta.
 *
 * @param $metaType the type of metadata: type, milestone, veraion,
 *        etc.
 * @param $metaName the name of the metadata.
 * @return the full attributes for the given metadata.
 */
function wptc_get_ticket_meta($metaType, $metaName) {

    $proxy = get_wptc_client()->getProxy('ticket.' . $metaType);
    $attrs = $proxy->get($metaName);

    return $attrs;
}

/**
 * update a milestone of version, create new one if it is not exist.
 *
 * @param type milestone or version
 */
function wptc_update_ticket_meta($type, $name, $attr) {

    $proxy = get_wptc_client()->getProxy('ticket.' . $type);
    try {
        $oldOne = $proxy->get($name);
        //return $oldOne;
        // update the existing version.
        $proxy->update($name, $attr);
    } catch (Zend_XmlRpc_Client_FaultException $e) {
        // new version. we will create one.
        //return $e->getMessage();
        $proxy->create($name, $attr);
    } 
}

/**
 * remove metadata identified by the given type and name.
 */
function wptc_remove_ticket_meta($type, $name) {

    $proxy = get_wptc_client()->getProxy('ticket.' . $type);
    try {
        $proxy->delete($name);
        return true;
    } catch(Zend_XmlRpc_Client_FaultException $e) {
        //var_dump($e->getMessage());
        return false;
    }
}

/**
 * retrun all project names.
 */
function wptc_get_ticket_projects() {

    $projects = wptc_get_projects();
    $names = array();
    foreach($projects as $project) {
        $names[] = $project['name'];
    }

    // using the name as the lable too.
    return array_combine($names, $names);
}

/**
 * return all ticket types.
 */
function wptc_get_ticket_types() {

    $types = wptc_get_ticket_metas('type');
    return apply_filters('wptc_get_ticket_types', 
                         array_combine($types, $types));
}

/**
 * return all ticket priority names.
 */
function wptc_get_ticket_priorities() {

    $prios = wptc_get_ticket_metas('priority');
    return apply_filters('wptc_get_ticket_priorities', 
                         array_combine($prios, $prios));
}

/**
 * return all ticket status names, which are defined in the workflow.
 * we assume all projects are using the same workflow.
 */
function wptc_get_ticket_status() {

   $status = wptc_get_ticket_metas('status');
   $ret = array();
   foreach($status as $s) {
       $s == 'closed' ? array_unshift($ret, $s) : 
             array_push($ret, $s);
   }
   return $ret;
}

/**
 * return all ticket milestone names.
 * grouped by due-date: future due-date in group Running
 * past due-date in group Closed.
 * following is a sample:
 *
 * 'Running' => array('mile 5', 'mile 4');
 * 'Closed' => array('mile 3', 'mile 2', 'mile 1');
 */
function wptc_get_ticket_milestones($project) {

    $mandvs = wptc_get_project_mandv($project);
    $optgroups = array();
    foreach(array_values($mandvs) as $stone) {
        //$stone['name']
        $duedate = $stone[0]['due_date'];
        $now = date('Y-m-d H:i:s');
        $name = $stone[0]['name'];
        $label = '['. substr($duedate, 0, 10) . '] ' . $name;
        if($duedate >= $now) {
            // this is Running mile stone.
            $optgroups['Running (by Due Date)'][$name] = $label;
        } else {
            $optgroups['Closed (by Due Date)'][$name] = $label;
        }
    }

    return apply_filters('wptc_get_ticket_milestones', 
                         $optgroups);
}

/**
 * return the ticket summary for the gieven milestone.
 */
function wptc_milestone_ticket_summary($milestone) {

    $queryBase = 'milestone=' . $milestone;
    $status = wptc_get_ticket_status();

    foreach ($status as $s) {
        $querys[$s] = $queryBase. '&status=' . $s . '&max=0';
    }

    $ids = wptc_ticket_query_m($querys);
    foreach($ids as $id) {
        $counts[] = count($id[0]);
    }

    return array_combine($status, $counts);
    //return round(($counts[0] / array_sum($counts)) * 100);
}

/**
 * retrun all ticket components.
 */
function wptc_get_ticket_components() {

    $comps = wptc_get_ticket_metas('component');
    return apply_filters('wptc_get_ticket_components',
                         array_combine($comps, $comps));
}

/**
 * return all ticket version names.
 */
function wptc_get_ticket_versions($project, $milestone) {

    $mandv = wptc_get_project_mandv($project);
    if(array_key_exists($milestone, $mandv)) {
        // the first entry is the milestone.
        $versions = array_slice($mandv[$milestone], 1); 
    } else {
        // using the first milestone as the default.
        $all = array_values($mandv);
        $versions =  array_slice($all[0], 1);
    }
    $optgroups = array();
    foreach($versions as $version) {
        $duedate = $version['due_date'];
        $now = date('Y-m-d H:i:s');
        $name = $version['name'];
        $label = '['. substr($duedate, 0, 10) . '] ' . $name;
        if($duedate >= $now) {
            $optgroups['Running (by Due Date)'][$name] = $label;
        } else {
            $optgroups['Closed (by Due Date)'][$name] = $label;
        }
    }

    return apply_filters('wptc_get_ticket_versions', 
                         $optgroups);
}

/**
 * return the default ticket version.
 */
function wptc_get_ticket_default_version() {

    // using the first version for now.
    $default = wptc_get_ticket_versions();
    $default = $default[0];
    return apply_filters('wptc_get_ticket_default_version',
                         $default);
}

/**
 * update the ticket .
 */
function wptc_update_ticket($id, $comment='', $attributes) {

    if($attributes === null) {
        // this is a easy signiture to add comment to a ticket.
        // load the ticket attributes 
        $ticket = wptc_get_ticket($id);
        if(empty($ticket)) {
            // no ticket found, retur.
            return null;
        } else {
            $attributes['summary'] = $ticket['summary'];
        }
    }

    // using current user's login as the author.
    $current_user = wp_get_current_user();
    if(has_filter('wptc_filter_ticket_attrs')) {
        $attributes = 
            apply_filters('wptc_filter_ticket_attrs', 
                          $attributes);
    }

    $proxy = get_wptc_client()->getProxy('ticket');
    // here is the signature
    // update(id, comment, attributes, notify, author, when)
    $author =  $current_user->user_login;
    $ticket = $proxy->update($id, $comment, $attributes, 
                             True, $author);
    // action to allow user hook logic after create ticket.
    if(has_action('wptc_update_ticket')) {
        do_action('wptc_update_ticket', $id, $comment, 
                  $attributes, $author);
    }

    // TODO:
    // 1. update memcached entry for ticket.
    // 2. load the ticket change log
    // 3. update memcached entry for ticket change log.
    // 4. load the ticket actions
    // 5. update memcached entry for ticket actions.
    // 6. update solr doc based on the new data.

    return $ticket;
}

/**
 * create new ticket.
 */
function wptc_create_ticket($summary, $description, $attrs) {

    if(has_filter('wptc_filter_ticket_attrs')) {
        $attrs = apply_filters('wptc_filter_ticket_attrs', 
                               $attrs);
    }

    $proxy = get_wptc_client()->getProxy('ticket');
    // notify reporter by default.
    $id = $proxy->create($summary, $description, 
                         $attrs, True);

    // action to allow user hook logic after create ticket.
    if(has_action('wptc_create_ticket')) {
        do_action('wptc_create_ticket', $id, $summary, 
                  $description, $attrs);
    }

    return $id;
}

// add the filter.
add_filter('wptc_filter_ticket_attrs', 
           'ensure_owner_reporter_in_cc', 10, 1);

/**
 * filter hook to ensure the email addresses of both owner and 
 * reporter are in the cc field.
 */
function ensure_owner_reporter_in_cc($attrs) {

    if($attrs && array_key_exists('cc', $attrs)) {
        // find user email by using wordpress fucntion.
        $owner = get_user_by('login', $attrs['owner']);
        $reporter = get_user_by('login', $attrs['reporter']);

        // load the current cc as an array.
        $cc_array = explode(",", trim($attrs['cc']));

        if($owner && !in_array($owner->user_email, $cc_array)) {
            $cc_array[] = $owner->user_email;
        }

        if($reporter && !in_array($reporter->user_email, $cc_array)) {
            $cc_array[] = $reporter->user_email;
        }

        $attrs['cc'] = implode(",", $cc_array);
    }

    return $attrs;
}

/**
 * preparing a list of user info based on the given
 * search term.
 */
function wptc_username_suggestion_query($searchTerm) {

    global $wpdb;
    $likeTerm = '%' . $searchTerm . '%';
    $query = $wpdb->prepare("
        SELECT user_login, user_email, display_name
        FROM wp_users
        WHERE user_login like %s
        OR display_name like %s
    ",
    $likeTerm, $likeTerm
    );

    // using the default OBJECT as the output format.
    $users = $wpdb->get_results($query);
    return  
      apply_filters('wptc_username_suggestion_query', $users);
}
