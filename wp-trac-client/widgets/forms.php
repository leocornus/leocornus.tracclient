<?php

/**
 * preparing the ticket ediging form, mainly for 
 * update ticket properties.
 */
function wptc_widget_new_ticket_form() {

    if (! is_user_logged_in()) {
        // user not logged in. do nothing here.
        $loginHref = get_option('siteurl') . 
            "/wp-login.php?redirect_to=" . 
            urlencode(get_permalink());
        $form = <<<EOT
<div>
  <h1 id="ticket-title">
    Please <a href="{$loginHref}">log in</a> to 
    create new ticket!
  </h1>
</div>
EOT;
        return $form;
    }

    $fieldset = wptc_widget_ticket_fieldset($ticket);
    $pmv_js = wptc_widget_ticket_pmv_js();
    $preview_js = wptc_widget_preview_dialog_js();
    // preparing the form.
    $form = <<<EOT
<div>
  <h1 id="ticket-title">Create New Ticket</h1>
  <form method="post" id="ticketform">
    <div id="modify">
      {$fieldset}
      {$pmv_js}
    </div>
    <div class="buttons">
      <input type="hidden" id="invalidFields" 
             name="invalidFields" value="">
      <input type="button" id="descpreview"
             name="descpreview" value="Preview Description"
             onclick="javascript: preview('#field-description')">
      <input type="submit" id="descsubmit" 
             name="submit" value="Submit changes">
    </div>
    {$preview_js}
  </form>
</div>
EOT;

    return $form;
}

/**
 * preparing the preview dialog js,
 * based on jQuery UI Dialog.
 */
function wptc_widget_preview_dialog_js() {

    $ajax_url = admin_url('admin-ajax.php');

    $js = <<<EOT
<script type="text/javascript">
function preview(fieldSelector) {
    wiki = jQuery(fieldSelector).val();
    //alert("click preview: " + wiki);
    jQuery("#previewContent").html("<b>Loading ...</b>");
    jQuery("#previewDialog").dialog("open");

    var data = {
        "action" : "wptc_preview_wiki",
        "wiki"   : wiki
    };

    jQuery.post("{$ajax_url}", data, function(response) {

        jQuery("#previewContent").html(JSON.parse(response));
    });
}

jQuery(function($) {
    $("#previewDialog").dialog({
        autoOpen: false,
        position: "center",
        minWidth: 580,
        height: 350,
        buttons: {
            "Close": function() {
                $(this).dialog("close");
            }
        },
        show: {
            effect: "blind",
            duration: 1000
        },
        hide: {
            effect: "explode",
            duration: 1000
        }
   });
});
</script>
    <div id="previewDialog" title="Description / Comment Preview">
      <div id="previewContent"></div>
    </div>
EOT;

    return $js;
}

/**
 * preparing the ticket ediging form, mainly for 
 * update ticket properties.
 */
function wptc_widget_ticket_form($ticket, $actions) {

    if (! is_user_logged_in()) {
        // user not logged in. do nothing here.
        return;
    }

    $ticket_fieldset = wptc_widget_ticket_fieldset($ticket);
    $pmv_js = wptc_widget_ticket_pmv_js();
    $comment_fieldset = wptc_widget_comment_fieldset($ticket);
    $action_fieldset = 
        wptc_widget_action_fieldset($actions, $ticket['status']);
    $preview_js = wptc_widget_preview_dialog_js();

    // the editing form, it should only show up for
    // logged in users.
    // the ticket editing form
    $form = <<<EOT
<form method='post' id='ticketform'>
<div class="collapsed">
  <h2 class="foldable">
    <a id="no1" href="#no1">Modify Ticket</a>
  </h2>
  <div id="modify" class="field">
    {$ticket_fieldset}
    {$pmv_js}
    {$preview_js}
  </div>
  <div class="buttons">
    <input type="button" id="descpreview"
           name="descpreview" value="Preview description"
           onclick="javascript: preview('#field-description')">
    <input type="submit" id="descsubmit" 
           name="submit" value="Submit changes">
  </div>
</div>

<div>
<h2 class="foldable">
  <a id="no2" href="#no2"
     onfocus="$('#wikicomment').get(0).focus()">Add Comment</a>
</h2>
<div id="commentaction">
    {$comment_fieldset}
    {$action_fieldset}
    <div class="buttons">
      <input type="hidden" id="ts" name="ts" value="{$ticket['_ts']}">
      <input type="hidden" id="id" name="id" value="{$ticket['id']}">
      <input type="hidden" id="invalidFields" 
             name="invalidFields" value="">
      <input type="button" id="descpreview"
             name="descpreview" value="Preview comment"
             onclick="javascript: preview('#wikicomment')">
      <input type="submit" id="wikisubmit" 
             name="submit" value="Submit changes">
    </div>
</div>
</div>
</from>
EOT;

    return $form;
}

/**
 * jQuery AJAX scripts to make following fields updating.
 */
function wptc_widget_ticket_pmv_js($project_id="field-project",
                                   $milestone_id="field-milestone",
                                   $version_id="field-version") {

    $ajax_url = admin_url('admin-ajax.php');
    
    $js = <<<EOT
<script type="text/javascript" charset="utf-8">
<!--
jQuery("select#{$project_id}").change(function() {
    project = this.value;
    //alert('change to [' + project + ']');
    if(project == "") {
        jQuery("select#{$milestone_id}").html("");
        jQuery("select#{$version_id}").html("");
    } else {
        // ajax request data.
        var data = {
          "action" : "wptc_toggle_select_opts",
          "type" : "project",
          "name" : project,
        };
        jQuery.post("{$ajax_url}",
          data,
          function(response) {
              res = JSON.parse(response);
              // update milestone options.
              jQuery("select#{$milestone_id}").html(res);
              jQuery("select#{$version_id}").html("");
          });
    }
});

jQuery("select#{$milestone_id}").change(function() {
    milestone = this.value;
    //alert('change to [' + milestone + ']');
    if(milestone == "") {
        jQuery("select#{$version_id}").html("");
    } else {
        // ajax request data.
        var data = {
          "action" : "wptc_toggle_select_opts",
          "type" : "milestone",
          "name" : milestone,
        };
        jQuery.post("{$ajax_url}",
          data,
          function(response) {
              res = JSON.parse(response);
              // update milestone options.
              jQuery("select#{$version_id}").html(res);
          });
    }
});
-->
</script>
EOT;

    return $js;
}


