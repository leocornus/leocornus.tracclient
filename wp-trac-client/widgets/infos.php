<?php
/**
 * generate the HTML markup for the ticket watching section.
 */
function wptc_widget_ticket_watching() {

    if(!is_user_logged_in()) {
        // user not logged in, only show how many user are watching.
        $watching_label = <<<EOT
<span class="watching-label">Watch</span>
EOT;
    } else {
        // logged in user will have a button to watch/unwatch
        $watching_label = <<<EOT
<a class="watching-but-unwatch" id="watching-button" href="#">
Watching</a>
EOT;
    }

    $span = <<<EOT
<span class="watching">
  <span class="watching-sum">222</span>
  {$watching_label}
</span>
EOT;

    return $span;
}
