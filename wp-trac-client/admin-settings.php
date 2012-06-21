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

if (isset($_POST['wptc_quicktest_form_submit']) &&
    $_POST['wptc_quicktest_form_submit'] == 'Y') {

    $rpcurl = get_blog_option(get_current_blog_id(), 'wptc_rpcurl');
    $username = get_blog_option(get_current_blog_id(), 'wptc_username');
    $password = get_blog_option(get_current_blog_id(), 'wptc_password');
    if ($rpcurl) {
        require_once 'Zend/XmlRpc/Client.php';
        $client = new Zend_XmlRpc_Client($rpcurl);
        $client->getHttpClient()->setAuth($username, $password);
        $func = $_POST['wptc_function'];
        //$proxy = $client->getProxy('system');
        //$methods = $proxy->listMethods();
        $testResult = get_wptc_client()->call($func, array());
    }
   
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

  <h2>Quick Test</h2>
  <p>A quick test to make sure the connection is good...</p>

  <form name="wptc_quicktest_form" method="post">
    <input type="hidden" name="wptc_quicktest_form_submit" value="Y"/>
    <table class="form-table"><tbody>
      <tr>
        <th>Trac XML-RPC Function: </th>
        <td><input type="text" id="wptc_function" name="wptc_function" 
                   value="system.listMethods" size="88"/>
        </td>
      </tr>
      <tr>
        <th scope="row"><input type="submit" name="quickTest" class="button-primary" value="Quick Test" />
        </th>
        <td></td>
      </tr>
      <tr>
        <th scope="row">Test Result: </th>
        <td><pre>
<?php 
if ($testResult) { 
    var_dump($testResult); 
} 
?>
        </pre></td>
      </tr>
    </tbody></table>
  </form>

  <h3>Some temp Testing:</h3>
  <p>Current Blog ID: <?php echo get_current_blog_id() ?></p>
  <p>Current User ID: <?php echo get_current_user_id() ?></p>
  <p>Option blog name: <?php echo get_option('blogname') ?></p>
  <p>include path: <?php echo get_include_path() ?></p>
  <p>PLUGIN URL: <?php echo plugins_url('wp-trac-client/js/jquery.dataTables.js'); ?> </p>
</div>
