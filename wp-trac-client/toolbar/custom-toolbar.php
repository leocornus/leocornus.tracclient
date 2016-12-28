<?php
/**
 * add the projects menu on the toolbar 
 * that lines the top of WordPress sites.
 */
function add_projects_toolbar_menu() {

    global $wp_admin_bar;

    // css way to use dashicons.
    $icon = '<span class="ab-icon"></span>';
    // HTML way to use dashicons.
    //$icon = '<span class="dashicons dashicons-portfolio"></span>';

    // add Projects menu for logged in users.
    $wp_admin_bar->add_menu( array(
        'id' => 'projects',
        'parent' => 'top-secondary',
        'title' => $icon . 'Projects',
        'href' => '/projects'
    ));
    add_sub_menu( 'Create Ticket', '/trac/ticket', 'projects');

    if ( is_user_logged_in() ) {
    
        add_sub_menu( 'My Tickets', '/projects?tab=mytickets', 
                      'projects');
        add_sub_menu( 'GitWeb', '/gitweb', 'projects');
    }
}
// hook to action 'admin_bar_menu'.
add_action('admin_bar_menu', 'add_projects_toolbar_menu',25);
