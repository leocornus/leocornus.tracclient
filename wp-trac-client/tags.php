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
 * the multicall version.
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
    $tickets = $proxy->multicall($signatures);

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
