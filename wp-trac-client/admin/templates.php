<?php
/**
 * generate the page select html to offer the available pages
 * 
 * @param $name template name
 * @param $page_id the page id.
 */
function wptc_page_list_html($name, $page_id) {

    $select_html = wp_dropdown_pages(
        array(
            'name'             => $name,
            'id'               => $name,
            'echo'             => false,
            'show_option_none' => '- None -',
            'selected'         => $page_id
        )
    );

    return $select_html;
}

/**
 * get ready the form for page template management page.
 */
function wptc_pages_admin_form($echo = false) {

    $trac_select_html = wptc_page_list_html('trac', 
        get_site_option('wptc_page_trac'));
    // jQuery DataTable JavaScript based trac homepage.
    $tracdt_select_html = wptc_page_list_html('tracdt', 
        get_site_option('wptc_page_trac_dt'));
    $ticket_select_html = wptc_page_list_html('trac-ticket',
        get_site_option('wptc_page_trac_ticket'));
    $mytickets_select_html = wptc_page_list_html('trac-mytickets',
        get_site_option('wptc_page_trac_mytickets'));
    $testing_select_html = wptc_page_list_html('trac-testing',
        get_site_option('wptc_page_trac_testing'));

    $form = <<<EOT
<form name="wptc_pages_form" method="post">
  <input type="hidden" name="wptc_pages_form_submit" value="Y"/>
  <table class="wp-list-table widefat">
  <thead>
    <tr>
      <th>Page Template</th>
      <th>Page</th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>Trac DataTable Homepage</td>
      <td>{$tracdt_select_html}</td>
    </tr>

    <tr>
      <td>Trac Homepage</td>
      <td>{$trac_select_html}</td>
    </tr>

    <tr>
      <td>Trac Ticket Page</td>
      <td>{$ticket_select_html}</td>
    </tr>

    <tr>
      <td>Trac My Tickets Page</td>
      <td>{$mytickets_select_html}</td>
    </tr>

    <tr>
      <td>Trac Testing Page</td>
      <td>{$testing_select_html}</td>
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
if (isset($_POST['wptc_pages_form_submit']) &&
    $_POST['wptc_pages_form_submit'] == 'Y') {

    // form submit.
    update_site_option('wptc_page_trac_dt', $_POST['tracdt']);
    update_site_option('wptc_page_trac', $_POST['trac']);
    update_site_option('wptc_page_trac_ticket', 
                       $_POST['trac-ticket']);
    update_site_option('wptc_page_trac_mytickets', 
                       $_POST['trac-mytickets']);
    update_site_option('wptc_page_trac_testing', 
                       $_POST['trac-testing']);

    // show the confirm message.
    $msg = <<<EOT
<div class="updated">
  <p><strong>Pages setting updated</strong></p>
</div>
EOT;
    echo $msg;
}
?>

<div class='wrap'>
  <h2>WordPress Trac Client - Page Templates Management</h2>

  <p>
  Associate a WordPress page with each Trac client template.
  </p>

  <?php echo wptc_pages_admin_form(); ?>

</div>
