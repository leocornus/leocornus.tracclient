<?php
/**
 * some sample hooks functions.
 */

/**
 * the action wptc_create_ticket.
 */
add_action('wptc_create_ticket', 'testing_create_ticket', 10, 4);
function testing_create_ticket($id, $summary, $desc, $attrs) {

    $msg = $id . "===>" . $summary . "===>" . $desc;
    update_site_option("create-result", $msg);
    return;
}

add_action('wptc_update_ticket', 'testing_update_ticket', 10, 4);
function testing_update_ticket($id, $comment, $attrs, $author) {

    $msg = $id . "===>" . $comment . "===>" . $author;
    update_site_option("update-result", $msg);
}

// testing the wptc_widget_project_href filter.
// try to change the page slug to a different one.
add_filter('wptc_widget_project_href', 'temp_project_href', 10, 1);
function temp_project_href($href) {

    // assume the default project href is in the following format:
    // http://www.domain.com/page_slug?project=projectname

    // get the project param from ther default href.
    $project = explode('?', $href)[1];
    $thehref = "/projects?{$project}";

    return $thehref;
}

