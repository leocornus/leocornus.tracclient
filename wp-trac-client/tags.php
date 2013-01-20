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

        $id = $ticket[0][0];
        $created = $ticket[0][1];
        $modified = $ticket[0][2];
        $status = $ticket[0][3]['status'];
        $summary = $ticket[0][3]['summary'];
        $owner = $ticket[0][3]['owner'];
        $priority = $ticket[0][3]['priority'];

        $row = array();
        $row[] = $id;
        $row[] = $summary;
        $row[] = $owner;
        $row[] = $priority;
        $row[] = $status;

        // add to aaData.
        $tickets[] = $row;
    }

    return apply_filters('wptc_get_tickets_m', $tickets);
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
 * return all details about a ticket.
 *
 * @param $id the ticket id.
 */
function wptc_get_ticket($id) {

    do_action('wptc_get_ticket');

    // we only need the ticket proxy.
    $proxy = get_wptc_client()->getProxy('ticket');
    $raw = $proxy->get($id);
    $ticket = $raw[3];
    $ticket['id'] = $id;
    $ticket['created'] = $raw[1];
    $ticket['modified'] = $raw[2];
    // we will use separate function for ticket changelog
    //$changeLog = $proxy->changeLog($id);
    //$ticket_details = array_merge($ticket, $changeLog);

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
 * retrun all metadata values specified by the given
 * meta name.
 *
 * @param $metaName the name of this metadata, 
 *        it could be: type, milestone, versions,
 *        component, priority.
 */
function wptc_get_ticket_metas($metaName) {

    $proxy = get_wptc_client()->getProxy('ticket.'. $metaName);
    $metas = $proxy->getAll();
    return $metas;
}

/**
 * return all ticket types.
 */
function wptc_get_ticket_types() {

    $types = wptc_get_ticket_metas('type');
    return apply_filters('wptc_get_ticket_types', $types);
}

/**
 * return all ticket priority.
 */
function wptc_get_ticket_priorities() {

    $prios = wptc_get_ticket_metas('priority');
    return apply_filters('wptc_get_ticket_priorities', $prios);
}

/**
 * return all ticket milestone.
 */
function wptc_get_ticket_milestones() {

    $stones = wptc_get_ticket_metas('milestone');
    return apply_filters('wptc_get_ticket_milestones', 
                         $stones);
}

/**
 * retrun all ticket components.
 */
function wptc_get_ticket_components() {

    $comps = wptc_get_ticket_metas('component');
    return apply_filters('wptc_get_ticket_components',
                         $comps);
}

/**
 * return all ticket versions.
 */
function wptc_get_ticket_versions() {

    $versions = wptc_get_ticket_metas('version');
    return apply_filters('wptc_get_ticket_versions', 
                         $versions);
}
