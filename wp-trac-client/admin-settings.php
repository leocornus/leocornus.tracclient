<?php

if (isset($_POST['wptc_settings_form_submit']) &&
    $_POST['wptc_settings_form_submit'] == 'Y') {

    // save settings submit. save user input to database.
    update_blog_option(get_current_blog_id(), 'wptc_rpcurl', $_POST['wptc_rpcurl']);
    update_blog_option(get_current_blog_id(), 'wptc_username', $_POST['wptc_username']);
    update_blog_option(get_current_blog_id(), 'wptc_password', $_POST['wptc_password']);

    // show the message.
    echo '<div class="updated"><p><strong>Settings Updated</strong></p></div>';
}

?>

<div class="wrap">
  <h2>WordPress Trac Client - General Settings</h2>
  <p>General settings for Trac client.</p>

  <form name="wptc_settings_form" method="post">
    <input type="hidden" name="wptc_settings_form_submit" value="Y"/>
    <table class="form-table"><tbody>
      <tr>
        <th>Trac XML-RPC URL: </th>
        <td><input type="text" id="wptc_rpcurl" name="wptc_rpcurl" 
                   value="<?php echo get_blog_option(get_current_blog_id(), 'wptc_rpcurl'); ?>" size="88"/>
        </td>
      </tr>
      <tr>
        <th>Trac User Name: </th>
        <td><input type="text" id="wptc_username" name="wptc_username" 
                   value="<?php echo get_blog_option(get_current_blog_id(), 'wptc_username'); ?>" size="58"/>
        </td>
      </tr>
      <tr>
        <th scope="row">Trac Password: </th>
        <td><input type="password" id="wptc_password" name="wptc_password" 
                   value="<?php echo get_blog_option(get_current_blog_id(), 'wptc_password'); ?>" size="58"/>
        </td>
      </tr>
      <tr>
        <th scope="row"><input type="submit" name="saveSetting" class="button-primary" value="Save Settings" />
        </th>
        <td></td>
      </tr>
    </tbody></table>
  </form>

  <p>Some temp Testing:</p>
  <p>Current Blog ID: <?php echo get_current_blog_id() ?></p>
  <p>Current User ID: <?php echo get_current_user_id() ?></p>
  <p>Option blog name: <?php echo get_option('blogname') ?></p>
  <p>include path: <?php echo get_include_path() ?></p>

<?php
// quick testing...
$rpcurl = get_blog_option(get_current_blog_id(), 'wptc_rpcurl');
$username = get_blog_option(get_current_blog_id(), 'wptc_username');
$password = get_blog_option(get_current_blog_id(), 'wptc_password');
if ($rpcurl) {
    require_once 'Zend/XmlRpc/Client.php';
    $client = new Zend_XmlRpc_Client($rpcurl);
    $client->getHttpClient()->setAuth($username, $password);
    $proxy = $client->getProxy('system');
    $methods = $proxy->listMethods();
    echo '<pre>';
    var_dump($methods);
    echo '</pre>';
}
?>

</div>
