<?php

/**
 * clean up the textarea input value to remove 
 * un-necessary characters, such as \r\n
 */
function wptc_widget_clean_textarea($input) {

    $ret = str_replace("\r\n", "\n", stripslashes($input));

    return apply_filters('wptc_widget_clean_textarea', $ret);
}

/**
 * using mediawiki api to parse wiki content
 */
function wptc_widget_mw_parse_content($wiki) {

    $wiki_client = get_wiki_client();
    $wiki_client->setParameterGet('action', 'parse');
    $wiki_client->setParameterGet('format', 'xml');
    $wiki_client->setParameterGet('text', $wiki);
    $response = $wiki_client->request('GET');
    $result = simplexml_load_string(trim($response->getBody()));
    //print_r($result);

    //echo $result->text();
    return (string)$result->parse->text;
}

/**
 * parse wiki format to prepare HTML.
 */
function wptc_widget_parse_content($wiki) {

    $wkr = new WikiRenderer('trac_to_xhtml');
    $ret = $wkr->render($wiki);
    //$ret = wptc_widget_mw_parse_content($wiki);

    // apply filters to allow user to tweak.
    return apply_filters('wptc_widget_parse_content', $ret);
}

/**
 * preparing the options for select tag.
 * this could be used by type, milestone, version,
 * priority dropdown
 */
function wptc_widget_options_html($options, $selected="", 
                                  $hasEmpty=true,
                                  $useNumeric=false) {

    $ret = $hasEmpty ? "<option></option>" : "";
    foreach ($options as $option => $label) {
        $sel = "";
        if (is_numeric($option)) {
            if (!$useNumeric) {
                // not use numeric!
                $option = $label;
            }
        }
        if($option === $selected) {
            $sel = "selected=\"selected\"";
        }

        $opt = <<<EOT
<option value="{$option}" {$sel}>{$label}</option>
EOT;
        $ret = $ret . $opt;
    }

    return apply_filters('wptc_widget_options_html', $ret);
}

/**
 * 
 */
function wptc_widget_optgroups_html($optgroups, $selected,
                                    $hasEmpty=True) {

    $groups = $hasEmpty ? "<option></option>" : "";
    foreach($optgroups as $group => $options) {

        $optsHtml = 
            wptc_widget_options_html($options, $selected, false);
        $group = <<<EOT
<optgroup label="{$group}">
  {$optsHtml}
</optgroup>
EOT;
        $groups = $groups . $group;
    }

    return apply_filters('wptc_widget_optgroups_html', $groups);
}

/**
 * preparing the user href by using wordpress user information.
 */
function wptc_widget_user_href($userName) {

    // get WordPress user object.
    $wpUser = get_user_by('login', $userName);

    if (empty($wpUser)) {
        // could not find the user in wordpress database,
        // just return the user name.
        $href = <<<EOT
<a title="$userName">$userName</a>
EOT;
    } else {
        $href = <<<EOT
<a href="{$server_url}/members/{$wpUser->user_login}/profile"
  title="Email: {$wpUser->user_email}">
{$wpUser->display_name}
</a>
EOT;
    }

    return apply_filters('wptc_widget_user_href', $href);
}

/**
 * generate the href link for version field.
 */
function wptc_widget_version_href($version) {

    // using the global variables.
    global $post, $current_blog;
    $blog_path = $current_blog->path;
    $page_slug = $post->post_parent ? 
        get_page($post->post_parent)->post_name :
        $post->post_name;

    $href = $blog_path . $page_slug . '?version=' . $version;

    return apply_filters('wptc_widget_version_href', $href);
}
