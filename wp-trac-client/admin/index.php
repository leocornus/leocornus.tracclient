<?php

require_once(WPTC_PLUGIN_PATH . '/admin/init.php');

// we need a admin page on dashboard for configuration.
add_action('network_admin_menu', 'wptc_admin_init');
/**
 * the main function to set up admin page.
 */
function wptc_admin_init() {

    // add the wp-trac-client section on wp-admin dashboard.
    add_menu_page('TracClient', 'TracClient', 
                  'manage_options', // this is only for administrator
                  // menu slug, slug is like keyword.
                  'wp-trac-client/admin-settings.php', 
                  // the function name leave it empty to use the value in slug.
                  // this will also be the default option page.
                  ''
                 );
    // the general settings page.
    add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                     'Trac Client General Settings', 'General Settings',
                     'manage_options', 
                     'wp-trac-client/admin-settings.php'
                    );
    // some attachment settings here.
    add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                     'Trac Attachment Management', 'Attachments',
                     'manage_options', 
                     'wp-trac-client/admin/attachments.php'
                    );
    // the template management page.
    add_submenu_page('wp-trac-client/admin-settings.php',
                     'Trac Client Templates', 'Templates',
                     'manage_options',
                     'wp-trac-client/admin/templates.php'
                    );
    // some management work here.
    add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                     'Trac Client Management', 'Project Management',
                     'manage_options', 
                     'wp-trac-client/admin-manager.php'
                    );
    if(wptc_is_debug()) {
        add_submenu_page('wp-trac-client/admin-settings.php', // parent slug.
                         'Trac Client Testing', 'APIs Testing',
                         'manage_options', 
                         'wp-trac-client/admin-testing.php'
                        );
    }
}
