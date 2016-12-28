<?php
/**
 * add the projects menu on the toolbar 
 * that lines the top of WordPress sites.
 */
function add_projects_toolbar_menu() {

    global $wp_admin_bar;

    if ( is_user_logged_in() ) {
    
        // add Projects menu for logged in users.
        $wp_admin_bar->add_menu( array(
            'id' => 'projects',
            'parent' => 'top-secondary',
            'title' => '<span class="ab-icon"></span>Projects',
            'href' => '/projects'
        ));
        add_sub_menu( 'Create Ticket', '/trac/ticket', 'projects');
        add_sub_menu( 'My Tickets', '/projects?tab=mytickets', 'projects');
        add_sub_menu( 'GitWeb', '/gitweb', 'projects');
    } else {

        // add Projects menu for anonymous users.
        $wp_admin_bar->add_menu( array(
            'id' => 'projects',
            'parent' => 'top-secondary',
            'title' => '<span class="ab-icon"></span>Projects',
            'href' => '/projects'
        ));
        add_sub_menu( 'Create Ticket', '/trac/ticket', 'projects');
    }
}
// hook to action 'admin_bar_menu'.
add_action('admin_bar_menu', 'add_projects_toolbar_menu',25);
