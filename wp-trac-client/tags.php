<?php
/**
 * template tags for easy use on theme templage.
 */

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

    // using current user's login as the author.
    global $current_user;
    get_currentuserinfo();
    if(has_filter('wptc_filter_ticket_attrs')) {
        $attributes = 
            apply_filters('wptc_filter_ticket_attrs', 
                          $attributes);
    }

    $proxy = get_wptc_client()->getProxy('ticket');
    // here is the signature
    // update(id, comment, attributes, notify, author, when)
    $ticket = $proxy->update($id, $comment, $attributes, 
                             True, 
                             $current_user->user_login);
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
