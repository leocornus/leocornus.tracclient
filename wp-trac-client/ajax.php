<?php

/**
 *
 */
add_action('wp_ajax_nopriv_wptc_username_autocomplete', 'wptc_username_suggestion_cb');
add_action('wp_ajax_wptc_username_autocomplete', 'wptc_username_suggestion_cb');
function wptc_username_suggestion_cb() {

    $searchTerm = $_POST['term'];
    // query wp_users table for the given term.
    $users = array("abc", "cde");

    $suggestions = array();
    foreach($users as $user) {
        $suggestion = array();
        $suggestion['label'] = 'Display Name - Email';
        $suggestion['value'] = 'user_login';
        $suggestions[] = $suggestion;
    }

    $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
    echo $response;
    exit;
}

/**
 * the ajax wrappers for the trac functions
 * The _cb suffix stands for Call Back
 */

add_action('wp_ajax_ticket_versions', 'wptc_get_versions_cb');
add_action('wp_ajax_nopriv_ticket_versions', 'wptc_get_versions_cb');
function wptc_get_versions_cb() {

    $proxy = get_wptc_client()->getProxy("ticket.version");
    $versions = $proxy->getAll();

    echo json_encode($versions);
    exit;
}

/**
 */
add_action('wp_ajax_nopriv_wptc_get_tickets_cb', 'wptc_get_tickets_cb');
add_action('wp_ajax_wptc_get_tickets_cb', 'wptc_get_tickets_cb');
function wptc_get_tickets_cb() {

    // get the query infomation from $_POST.
    if(isset( $_POST['version'])) {
        $version = $_POST['version'];
    }
    if(isset( $_POST['milestone'])) {
        $milestone= $_POST['milestone'];
    }

    // get paging infor.
    if( isset( $_POST['iDisplayStart'] ) && isset($_POST['iDisplayLength']) ) { 
        $start = intval($_POST['iDisplayStart']);
        $max = intval($_POST['iDisplayLength']);
        $pageNumber = $start / $max + 1;
    } else {
        $max = 10;
        $pageNumber = 1;
    }

    // the output array follow DataTables format.
    // the contents will be save under name aaData as an array.
    $amount = wptc_get_tickets_amount($milestone, $version);
    $output = array(
        "sEcho" => intval($_POST['sEcho']),
        "iTotalRecords" => $amount,
        "iTotalDisplayRecords" => $amount,
        "aaData" => array()
    );

    // prepareing aaData
    $tickets = wptc_get_tickets_m($milestone, $version, $max, $pageNumber);
    //$tickets = wptc_get_tickets_m('OPSpedia v2.2.0');
    foreach ($tickets as $ticket) {

        $id = $ticket[0];
        $ticket[0] = "<a href='#' id='ticket-{$id}' name='ticket-{$id}'>{$id}</a>";

        // add to aaData.
        $output['aaData'][] = $ticket;
    }

    echo json_encode($output);
    exit;
}

/**
 * get ticket details.
 */
add_action('wp_ajax_nopriv_wptc_get_ticket_cb', 'wptc_get_ticket_cb');
add_action('wp_ajax_wptc_get_ticket_cb', 'wptc_get_ticket_cb');
function wptc_get_ticket_cb() {

    // get the ticket id from the $_POST.

    $id = $_POST['id'];
    $output = wptc_get_ticket($id);

    echo json_encode($output);
    exit;
}
