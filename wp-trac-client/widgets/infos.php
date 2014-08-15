<?php
/**
 * generate the HTML markup for the ticket watching section.
 */
function wptc_widget_ticket_watching() {

    if(!is_user_logged_in()) {
        // user logged in, return empty string
        // to leave topnav empty.
        return "";
    }

    $span = <<<EOT
<span class="watching">
  <span class="watching-sum">222</span>
  <a class="watching-but-unwatch" id="watching-button" href="#">
  Watching</a>
</span>
EOT;

    return $span;
}
