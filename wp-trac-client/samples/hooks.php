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


add_action('wptc_update_ticket', 'testing_update_ticket', 10, 3);
function testing_update_ticket($id, $attrs, $author) {

    $msg = $id . "===>" . $author;
    update_site_option("update-result", $msg);
}
