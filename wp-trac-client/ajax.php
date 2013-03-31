<?php

/**
 * wordpress AJAX callback for user anme suggestions.
 */
add_action('wp_ajax_nopriv_wptc_username_autocomplete', 'wptc_username_suggestion_cb');
add_action('wp_ajax_wptc_username_autocomplete', 'wptc_username_suggestion_cb');
function wptc_username_suggestion_cb() {

    $searchTerm = $_REQUEST['term'];
    // query wp_users table for the given term.
    $users = wptc_username_suggestion_query($searchTerm);

    $suggestions = array();
    foreach($users as $user) {
        $suggestion = array();
        // preparing label and value for each user.
        // TODO: should we add the avatar too?
        // label: Display Name - Email
        // value: user_login
        $suggestion['label'] = $user->display_name . ' - ' .
            $user->user_email;
        $suggestion['value'] = $user->user_login;
        $suggestions[] = $suggestion;
    }

    // we are using jQuery.getJSON to trigger AJAX request,
    // it is different from direct AJAX call.
    $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
    echo $response;
    exit;
}

/**
 *
 */
add_action('wp_ajax_nopriv_wptc_valid_username', 'wptc_valid_username_cb');
add_action('wp_ajax_wptc_valid_username', 'wptc_valid_username_cb');
function wptc_valid_username_cb() {
    $username = $_POST['username'];
    $ret = array();
    $ret['valid'] = True;
    global $wpdb;
    $query = $wpdb->prepare("
        SELECT id 
        FROM wp_users
        WHERE user_login = %s
    ", $username);
    $userId = $wpdb->get_var($query);
    if($userId === NULL) {
        // username not exist.
        $ret['valid'] = False;
        $ret['username'] = $username;
    }

    echo json_encode($ret);
    exit;
}

/**
 *
 */
add_action('wp_ajax_nopriv_wptc_toggle_select_opts',
           'wptc_toggle_select_opts_cb');
add_action('wp_ajax_wptc_toggle_select_opts',
           'wptc_toggle_select_opts_cb');
function wptc_toggle_select_opts_cb() {

    $type = $_POST['type'];
    $name = $_POST['name'];

    switch($type) {
        case "project":
            // query milestones for the project.
            $opts = wptc_widget_optgroups_html(
                wptc_get_ticket_milestones($name), '');
            break;
        case "milestone":
            // query version for the milestone
            $project = wptc_get_project_name($name);
            $opts = wptc_widget_optgroups_html(
                wptc_get_ticket_versions($project, $name), '');
            break;
        default:
            $opts = "";
            break;
    }

    echo json_encode($opts);
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
