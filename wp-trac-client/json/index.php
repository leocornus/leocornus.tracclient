<?php
/**
 * JSON APIs for wp-trac-client.
 */

// if this file is called directly, abort!
if(!defined('WPINC')) {
    die;
}

//require_once(WPTC_PLUGIN_PATH . '/json/tickets.php');

add_action('wp_ajax_nopriv_wptc_trac_tickets', 
           'wptc_trac_tickets_cb');
add_action('wp_ajax_wptc_trac_tickets', 
           'wptc_trac_tickets_cb');
function wptc_trac_tickets_cb() {

    // get the query infomation from $_POST.
    if(isset( $_POST['version'])) {
        $version = $_POST['version'];
    }
    if(isset( $_POST['milestone'])) {
        $milestone= $_POST['milestone'];
    }

    $tickets = wptc_get_tickets_m($milestone, $version);
    $output = array();
    foreach($tickets as $ticket) {

        $one = array();
        $one['name'] = $ticket['id'];
        $one['description'] = $ticket['summary'];
        $one['site'] = $ticket['status'];
        $output[] = $one;
    }

    echo json_encode($output);
    exit;
}
