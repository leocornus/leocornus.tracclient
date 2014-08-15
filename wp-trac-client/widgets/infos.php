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
<span class="watching" style="
      float: right;
      font-size: 100%;
      border: solid #ffd 1px;
  
      margin-top: -15px;
      background-color: #ffd;
">
      <span class="watching-button" style="
          border-right: solid #996 1px;
          margin-right: -4px;
          padding-right: 4px;
      ">Watch</span>
      <span class="watching-sum" style="
          padding-left: 3px;
     ">222</span>
</span>
EOT;

    return $span;
}
