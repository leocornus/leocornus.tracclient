<?php
/**
 * settings page for Trac ticket attachments.
 */

/**
 * the settings form for ticket attachments.
 */
function wptc_attachments_admin_form($echo = false) {

    $handler_url = get_site_option('wptc_attachments_handler_url');
    $desc_template = get_site_option('wptc_attachments_description');
    $tags_template = get_site_option('wptc_attachments_tags');
    $comment = get_site_option('wptc_attachments_comment');

    $form = <<<EOT
<form name="wptc_attachments_admin_form" method="post">
  <input type="hidden" name="wptc_attachments_admin_form_submin"
         value="Y"
  />
  <table class="form-table"><tbody>
    <tr>
      <th>Attachment Handler URL: </th>
      <td>
        <input type="text" id="wptc_attachments_handler_url"
               name="wptc_attachments_handler_url"
               value="{$handler_url}"
               size="88"
        />
      </td>
    </tr>
    <tr>
      <th>Attachment Description Template: </th>
      <td>
        <textarea id="wptc_attachments_description"
                  name="wptc_attachments_description"
                  rows="8" cols="98"
        >{$desc_template}</textarea>
      </td>
    </tr>
    <tr>
      <th>Attachment Tags Template: </th>
      <td>
        <textarea id="wptc_attachments_tags"
                  name="wptc_attachments_tags"
                  rows="8" cols="98"
        >{$tags_template}</textarea>
      </td>
    </tr>
    <tr>
      <th></th>
      <th scope="row">
        <input type="submit" name="save" 
               class="button-primary" value="Save" />
      </th>
    </tr>
    <tr>
      <th>Attachment comment: </th>
      <td>
        <input type="text" id="wptc_attachments_comment"
               name="wptc_attachments_comment"
               value="{$comment}"
               size="88"
        />
      </td>
    </tr>
  </tbody></table>
</form>
EOT;

    if($echo) {
        echo $form;
    } else {
        return $form;
    }
}

// handle form submition:
if (isset($_POST['wptc_attachments_admin_form_submin']) &&
    $_POST['wptc_attachments_admin_form_submin'] == 'Y') {

    // form submit.
    update_site_option('wptc_attachments_handler_url', 
                       $_POST['wptc_attachments_handler_url']);
    update_site_option('wptc_attachments_description', 
        stripslashes($_POST['wptc_attachments_description']));
    update_site_option('wptc_attachments_tags', 
        stripslashes($_POST['wptc_attachments_tags']));
    update_site_option('wptc_attachments_comment', 
        $_POST['wptc_attachments_comment']);

    // show the confirm message.
    $msg = <<<EOT
<div class="updated">
  <p><strong>Attachment settings updated</strong></p>
</div>
EOT;
    echo $msg;
}

?>

<div class="wrap">
  <h2>Wordpress Trac Client - Attachment Settings</h2>

  <p>General Setting for Attachment to a Ticket</p>

  <?php echo wptc_attachments_admin_form(); ?>
</div>
