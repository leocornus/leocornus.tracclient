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
add_action('wp_ajax_nopriv_tickets_list', 'wptc_get_tickets_cb');
add_action('wp_ajax_tickets_list', 'wptc_get_tickets_cb');
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

        $id = $ticket[0][0];
        $created = $ticket[0][1];
        $modified = $ticket[0][2];
        $status = $ticket[0][3]['status'];
        $summary = $ticket[0][3]['summary'];
        $owner = $ticket[0][3]['owner'];
        $priority = $ticket[0][3]['priority'];

        $row = array();
        $row[] = "<a href='http://www.google.com'>{$id}</a>";
        $row[] = $summary;
        $row[] = $owner;
        $row[] = $priority;
        $row[] = $status;

        // add to aaData.
        $output['aaData'][] = $row;
    }

    echo json_encode($output);
    exit;
}
