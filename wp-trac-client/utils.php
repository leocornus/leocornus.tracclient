<?php

/**
 * generate href link to a commit id.
 */
function wptc_auto_link_commit_id($subject) {

    // #12 or #3
    $pattern = '/([0-9a-fA-F]{7,40})/';
    if(preg_match($pattern, $subject) === 1) {
        $base_url = wptc_get_git_base_url();
        $href = "<a href='" . $base_url . "?id=\\1'>\\1</a>";
        $subject = preg_replace($pattern, $href, $subject);
    }

    return $subject;
}
// filter all wiki content to link commit_id.
add_filter('wptc_widget_parse_content', 'wptc_auto_link_commit_id', 
           10, 1);

/**
 *
 */
function wptc_apply_page_template($template) {

    if(!is_main_site()) {
        // current site is not main site.
        // skip it.
        return $template;
    }
    
    // using default value to make things easier.
    // trac datatable homepage.
    $page_id = get_site_option('wptc_page_trac_dt', "-1");
    if(($page_id != "-1") && is_page($page_id)) {
        // apply trac homepage template.
        $template = WPTC_PLUGIN_PATH . '/templates/page-trac-list.php';
        return $template;
    }
    // trac homepage.
    $page_id = get_site_option('wptc_page_trac', "-1");
    if(($page_id != "-1") && is_page($page_id)) {
        // apply trac homepage template.
        $template = WPTC_PLUGIN_PATH . '/templates/page-trac.php';
        return $template;
    }
    // the ticket details page template.
    $page_id = get_site_option('wptc_page_trac_ticket', "-1");
    if(($page_id != "-1") && is_page($page_id)) {
        // apply trac homepage template.
        $template = WPTC_PLUGIN_PATH . 
                    '/templates/page-trac-ticket.php';
        return $template;
    }
    // my tickets page.
    $page_id = get_site_option('wptc_page_trac_mytickets', "-1");
    if(($page_id != "-1") && is_page($page_id)) {
        // apply trac homepage template.
        $template = WPTC_PLUGIN_PATH . 
                    '/templates/page-trac-mytickets.php';
        return $template;
    }
    // the testing page.
    $page_id = get_site_option('wptc_page_trac_testing', "-1");
    if(($page_id != "-1") && is_page($page_id)) {
        // apply trac homepage template.
        $template = WPTC_PLUGIN_PATH . 
                    '/templates/page-trac-test.php';
        return $template;
    }


    return $template;
}
add_filter('template_include', 'wptc_apply_page_template');
