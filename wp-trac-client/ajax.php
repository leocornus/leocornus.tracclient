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
 * ajax call back for toggle select options.
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
 * ajax call back to preview wiki content.
 */
add_action('wp_ajax_nopriv_wptc_preview_wiki',
           'wptc_preview_wiki_cb');
add_action('wp_ajax_wptc_preview_wiki',
           'wptc_preview_wiki_cb');
function wptc_preview_wiki_cb() {

    $wiki = stripslashes($_POST['wiki']);
    $preview = wptc_widget_parse_content($wiki);

    echo json_encode($preview);
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
    foreach ($tickets as $ticket) {

        $id = $ticket['id'];
        $ticket['id'] = "<a href='#' id='ticket-{$id}' name='ticket-{$id}'>{$id}</a>";

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

/**
 * ajax callback function for action wptc_watch_ticket.
 * this action is only for authorized user.
 */
add_action('wp_ajax_wptc_watch_ticket', 'wptc_watch_ticket_cb');
function wptc_watch_ticket_cb() {

    // here are params we need:
    $ticket_id = intval($_POST['ticket_id']);
    $existing_cc = $_POST['existing_cc'];
    //$watcher_email = $_POST['watcher_email'];
    // possible actions: watch and unwatch
    $action = $_POST['watch_action'];

    // get current user.
    $current_user = wp_get_current_user();
    // preparing the watchers.
    $watchers = explode(', ', $existing_cc);
    // verify the email address.
    $watcher_email = $current_user->user_email;

    switch($action) {
        case 'watch':
            // prepare the comment.
            $comment = $current_user->display_name . 
                       " started watching this ticket!";
            // add the watcher email.
            $watchers[] = $watcher_email;
            break;
        case 'unwatch':
            // prepare the comment.
            $comment = $current_user->display_name .
                       " stopped watch this ticket!";
            // remove the watcher email.
            $key = array_search($watcher_email, $watchers);
            if($key !== false) {
                unset($watchers[$key]);
            }
            break;
    }

    $res = array();
    // update ticket.
    $id = wptc_update_ticket($ticket_id, $comment, 
                             array('cc' => implode(', ', $watchers)));
    $res['id'] = $id;
    $res['success'] = true;
    echo json_encode($res);
    exit;
}

/**
 * function to generate javascript for call wptc_watch_ticket action.
 */
function wptc_watch_ticket_js($button_id, $ticket_id, 
                              $existing_cc, $watch_action) {

    // the ajax_url
    $ajax_url = admin_url('admin-ajax.php');

    $js = <<<EOT
<script type="text/javascript" charset="utf-8">
<!--
jQuery("a#{$button_id}").click(function() {

    // preparing the data.
    var data = {
        "action" : "wptc_watch_ticket",
        "ticket_id" : {$ticket_id},
        "existing_cc" : "{$existing_cc}",
        "watch_action" : "{$watch_action}",
    };

    jQuery("body").css("cursor", "progress");
    jQuery("a").css("cursor", "progress");

    // AJAX Post
    jQuery.post("{$ajax_url}", data,
        function(response) {
            res = JSON.parse(response);
            location.reload();
        });
});
-->
</script>
EOT;

    return $js;
}

/**
 * query tickets.
 */
add_action('wp_ajax_nopriv_wptc_query_tickets', 
           'wptc_query_tickets_cb');
add_action('wp_ajax_wptc_query_tickets', 'wptc_query_tickets_cb');
function wptc_query_tickets_cb() {

    // get the request context.
    $context = new Wptc\Context\RequestContext();
    $per_page = $context->getState('per_page');
    $page_number = $context->getState('page_number');
    $project_name = $context->getState('project');

    // the empty blog_id will tell to use the current blog.
    $blog_path = get_site_url();
    $ticket_page_slug = "trac/ticket";

    //$query = "project={$project_name}&status!=closed";
    $query = $context->getState('current_query');
    // query tickets and load ticket details
    // will load all qualified tickets at one query.
    $ids = wptc_ticket_query($query, $per_page, 
                             $page_number + 1);
    $tickets = wptc_get_tickets_list_m($ids);

    //get ready rows for table.
    $items = array();
    foreach($tickets as $ticket) {

        // adding url and owner href
        $ticket['ticket_url'] = "{$blog_path}/{$ticket_page_slug}?id={$ticket['id']}";
        $ticket['owner_href'] = wptc_widget_user_href($ticket['owner']);
        $items[] = $ticket;
    }

    $response = array(
        'items' => $items,
        'states' => $context->getStates()
    );

    echo json_encode($response);
    exit;
}

/**
 * wptc projects.
 */
add_action('wp_ajax_nopriv_wptc_projects', 'wptc_projects_cb');
add_action('wp_ajax_wptc_projects', 'wptc_projects_cb');
function wptc_projects_cb() {

    // get the request context.
    $context = new Wptc\Context\ProjectsRequestContext();
    $per_page = $context->getState('per_page');
    // page number starts from 0
    $page_number = $context->getState('page_number');

    $projects = wptc_get_projects($page_number, $per_page);
    $items = array();
    foreach($projects as $project) {
        // add the URL to project homepage.
        $project['project_url'] = "/projects/?project={$project['name']}";
        // get total number of tickets.
        $query = "project={$project['name']}";
        $ids = wptc_ticket_query($query, 0);
        $project['total_tickets'] = count($ids);

        $items[] = $project;
    }

    $response = array(
        'items' => $items,
        'states' => $context->getStates()
    );

    echo json_encode($response);
    exit;
}
