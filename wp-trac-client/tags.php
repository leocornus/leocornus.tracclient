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
function wptc_get_tickets($milestone, $max=25, $page=1) {

    do_action('wptc_get_tickets');

    $proxy = get_wptc_client()->getProxy('ticket');
    $ids = $proxy->query('milestone='. $milestone . 
        '&max=' . $max . '&page=' . $page);
    $tickets = array();
    foreach ($ids as $id) {
        $ticket = $proxy->get($id);
        array_push($tickets, $ticket);
    }

    return apply_filters('wptc_get_tickets', $tickets);
}
