<?php
/**
 * settings page for Trac ticket attachment.
 */

/**
 * the settings form for ticket attachment.
 */
function wptc_attachment_admin_form($echo = false) {

    $handler_url = get_site_option('wptc_attachment_handler_url');
    $desc_template = get_site_option('wptc_attachment_description');
    $tags_template = get_site_option('wptc_attachment_tags');
    $comment = get_site_option('wptc_attachment_comment');

    $form = <<<EOT
<form name="wptc_attachment_admin_form" method="post">
  <input type="hidden" name="wptc_attachment_admin_form_submin"
         value="Y"
  />
  <table class="form-table"><tbody>
    <tr>
      <th>Attachment Handler URL: </th>
      <td>
        <input type="text" id="wptc_attachment_handler_url"
               name="wptc_attachment_handler_url"
               value="{$handler_url}"
               size="88"
        />
      </td>
    </tr>
    <tr>
      <th>Attachment Description Template: </th>
      <td>
        <textarea id="wptc_attachment_description"
                  name="wptc_attachment_description"
                  rows="8" cols="98"
        >{$desc_template}</textarea>
      </td>
    </tr>
    <tr>
      <th>Attachment Tags Template: </th>
      <td>
        <textarea id="wptc_attachment_tags"
                  name="wptc_attachment_tags"
                  rows="8" cols="98"
        >{$tags_template}</textarea>
      </td>
    </tr>
    <tr>
      <th>Attachment comment: </th>
      <td>
        <input type="text" id="wptc_attachment_comment"
               name="wptc_attachment_comment"
               value="{$comment}"
               size="88"
        />
      </td>
    </tr>
    <tr>
      <th></th>
      <th scope="row">
        <input type="submit" name="save" 
               class="button-primary" value="Save" />
      </th>
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
if (isset($_POST['wptc_attachment_admin_form_submin']) &&
    $_POST['wptc_attachment_admin_form_submin'] == 'Y') {

    // form submit.
    update_site_option('wptc_attachment_handler_url', 
                       $_POST['wptc_attachment_handler_url']);
    update_site_option('wptc_attachment_description', 
        stripslashes($_POST['wptc_attachment_description']));
    update_site_option('wptc_attachment_tags', 
        stripslashes($_POST['wptc_attachment_tags']));
    update_site_option('wptc_attachment_comment', 
        $_POST['wptc_attachment_comment']);

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

  <p>General Setting for Attachment to a Ticket.
  The following keywords could be used to reference the 
  metadata of a ticket:</p>

  <dl>
    <dt><strong>[TICKET_ID]</strong></dt>
      <dd>It will be replace with ticket id</dd>
    <dt><strong>[PROJECT]</strong></dt>
      <dd>Project for current ticket</dd>
    <dt><strong>[MILESTONE]</strong></dt>
      <dd>Project milestone of the current ticket</dd>
  </dl>

  <?php echo wptc_attachment_admin_form(); ?>
</div>
