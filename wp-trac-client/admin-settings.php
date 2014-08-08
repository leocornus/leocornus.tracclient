<?php

wp_enqueue_script("wp_ajax_response");

if (isset($_POST['wptc_settings_form_submit']) &&
    $_POST['wptc_settings_form_submit'] == 'Y') {

    // save settings submit. save user input to database.
    update_site_option('wptc_rpcurl', $_POST['wptc_rpcurl']);
    update_site_option('wptc_username', $_POST['wptc_username']);
    update_site_option('wptc_password', $_POST['wptc_password']);
    update_site_option('wptc_git_base_url', 
                       $_POST['wptc_git_base_url']);
    update_site_option('wptc_debug', $_POST['wptc_debug']);

    // show the message.
    echo '<div class="updated"><p><strong>Settings Updated</strong></p></div>';
}

if (isset($_POST['wptc_quicktest_form_submit']) &&
    $_POST['wptc_quicktest_form_submit'] == 'Y') {

    $rpcurl = get_site_option('wptc_rpcurl');
    $username = get_site_option('wptc_username');
    $password = get_site_option('wptc_password');
    if ($rpcurl) {
        require_once 'Zend/XmlRpc/Client.php';
        $client = new Zend_XmlRpc_Client($rpcurl);
        $client->getHttpClient()->setAuth($username, $password);
        $func = $_POST['wptc_function'];
        // ";" will be used as the delimiter between function name and 
        // parameters.
        // For example, 
        // the following will return the details info for ticket #12
        // ticket.get;12
        $func = explode(";", $func);
        //$proxy = $client->getProxy('system');
        //$methods = $proxy->listMethods();
        // the array_shift will remove the first item from the given array.
        $testResult = get_wptc_client()->call(array_shift($func), $func);
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
                   value="<?php echo get_site_option('wptc_rpcurl'); ?>" size="88"/>
        </td>
      </tr>
      <tr>
        <th>Trac User Name: </th>
        <td><input type="text" id="wptc_username" name="wptc_username" 
                   value="<?php echo get_site_option('wptc_username'); ?>" size="58"/>
        </td>
      </tr>
      <tr>
        <th scope="row">Trac Password: </th>
        <td><input type="password" id="wptc_password" name="wptc_password" 
                   value="<?php echo get_site_option('wptc_password'); ?>" size="58"/>
        </td>
      </tr>
      <tr>
        <th>Base URL to Git Commit View: </th>
        <td><input type="text" id="wptc_git_base_url" 
                   name="wptc_git_base_url" 
                   value="<?php echo get_site_option('wptc_git_base_url'); ?>" size="88"/>
        </td>
      </tr>
      <tr>
        <th scope="row">Debug Mode: </th>
        <td>
          <input type="radio" name="wptc_debug" value="true"
            <?php echo (get_site_option('wptc_debug') == 'true') ?
                'checked' : '';?>
          >On
          <input type="radio" name="wptc_debug" value="false"
            <?php echo (get_site_option('wptc_debug') == 'false') ?
                'checked' : '';?>
          >Off
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
  <p>A quick test to make sure the connection is good...
     A trac XML-RPC function is something like 
     <strong>ticket.type.getAll</strong>.</p>

  <form name="wptc_quicktest_form" method="post">
    <input type="hidden" name="wptc_quicktest_form_submit" value="Y"/>
    <table class="form-table"><tbody>
      <tr>
        <th>Trac XML-RPC Function: </th>
        <td><input type="text" id="wptc_function" name="wptc_function" 
                   value="" size="88"/>
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
</div>
