<?php
/**
 * generate the HTML markup for the ticket watching section.
 */
function wptc_widget_ticket_watching($ticket) {

    // findout the total number of watchers.
    $watching_emails = explode(',', $ticket['cc']);
    $total = count($watching_emails);

    if(is_user_logged_in()) {
        // logged in user will have a button to watch/unwatch
        $current_user = wp_get_current_user();
        // assume current user is not watching now.
        $a_class = "watching-but-watch";
        $a_label = "Watch";
        if (in_array($current_user->user_email, $watching_emails)) {
            // current user is in watching list.
            $a_class = "watching-but-unwatch";
            $a_label = "Watching";
        }

        $watching_label = <<<EOT
<a class="{$a_class}" id="watching-button" href="#">
{$a_label}</a>
EOT;
    } else {
        // user not logged in, only show how many user are watching.
        $watching_label = <<<EOT
<span class="watching-label">Watch</span>
EOT;
    }

    $span = <<<EOT
<span class="watching">
  <span class="watching-sum">{$total}</span>
  {$watching_label}
</span>
EOT;

    return $span;
}


// utility functions

/**
 * return true if the user is watching 
 * $user_field will be one of the following values:
 * id, slug, email, login.
 * same with the WordPress Function get_user_by
 */
function wptc_is_user_watching($ticket_id, $user_field="email", 
                               $user_value=null) {

    // eventually, we will use the correct email for a user to 
    // identify watching a ticket or not.
    $the_email = null;

    // now let's decide the email address.
    if($user_value === null) {
        // no user_value given, we will try to use current user.
        if(is_user_logged_in()) {
            // user logged in, use current user.
            $current_user = wp_get_current_user();
            $the_email = $current_user->user_email;
        } else {
            // user is no logged in.
            return false;
        }
    } else {
        if($user_field === "email") {
            // user field is email, use the user value directly.
            $the_email = $user_value;
        } else {
            // try the get the user and find out hte email address.
            $user = get_user_by($user_field, $user_value);
            $thie_email = $user->user_email;
        }
    }

    if(empty($the_email)) {
        // could find any email. return false.
        return false;
    }
    
    // now we should have the email we are query about.
    
}
