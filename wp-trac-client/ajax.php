<?php

/**
 * the ajax wrappers for the trac functions
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
    // the output array follow DataTables format.
    // the contents will be save under name aaData as an array.
    $output = array(
        "iTotalRecords" => 500,
        "aaData" => array()
    );

    // prepareing aaData
    $tickets = wptc_get_tickets_m('OPSpedia v2.2.0', null, 20);
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
