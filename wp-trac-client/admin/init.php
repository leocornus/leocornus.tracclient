<?php

// some other initialization functions.

/**
 * create empty page for 
 */
function wptc_create_pages() {

    // if the site option exists, we will skip the page creation.
    // function get_site_option will return FALSE if the 
    // option is not exist.
    if(!(get_site_option('wptc_page_trac', '-1') === '-1')) {

        // this site option is exist. we will return without do
        // anything.
        return;
    }

    // the trac page.
    $post_data = array(
        'post_status' => 'publish',
        'post_parent' => '',
        'post_type' => 'page',
        'ping_status' => get_option('default_ping_status'),
        'post_content' => ' ',
        'post_excerpt' => '',
        'post_title' => __('trac', 'wptc')
    );
    $trac_id = wp_insert_post($post_data, false);
    // update site option
    update_site_option('wptc_page_trac', $trac_id);

    // we will re-use the same $post_data, only update the 
    // necessary fields.

    // the ticket page using trac page as parent.
    $post_data['post_parent'] = $trac_id;
    $post_data['post_title'] = __('ticket', 'wptc');
    $ticket_id = wp_insert_post($post_data, false);
    update_site_option('wptc_page_trac_ticket', $ticket_id);

    // the ticket page using trac page as parent.
    $post_data['post_parent'] = $trac_id;
    $post_data['post_title'] = __('mytickets', 'wptc');
    $my_id = wp_insert_post($post_data, false);
    update_site_option('wptc_page_trac_mytickets', $my_id);

    // the testing page using trac page as parent.
    $post_data['post_parent'] = $trac_id;
    $post_data['post_title'] = __('testing', 'wptc');
    $my_id = wp_insert_post($post_data, false);
    update_site_option('wptc_page_trac_testing', $my_id);
}
