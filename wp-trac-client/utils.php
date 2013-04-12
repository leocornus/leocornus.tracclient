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
