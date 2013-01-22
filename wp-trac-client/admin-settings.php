<?php

$DEBUG = True;

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

<?php if ($DEBUG) {
    // some testing code.
?>
    <h3>Some temp Testing:</h3>
    <p>Current Blog ID: <?php echo get_current_blog_id() ?></p>
    <p>Current Blog Path: <?php echo $current_blog->path ?></p>
    <p>Current User ID: <?php echo get_current_user_id() ?></p>
    <p>Option blog name: <?php echo get_option('blogname') ?></p>
    <p>include path: <?php echo get_include_path() ?></p>
    <p>PLUGIN URL: <?php echo plugins_url('wp-trac-client/js/jquery.dataTables.js'); ?> </p>

    <p>
    <?php
    $a = "123 abc cde";
    list($a1, $a2) = explode(" ", $a);
    var_dump($a1);
    var_dump($a2);
    $aa = explode(" ", $a);
    echo "<pre>";
    var_dump($aa);
    echo "</pre>";
    echo "<pre>shift out ";
    echo array_shift($aa);
    echo "</pre>";
    echo "<pre> again!";
    var_dump(wptc_widget_version_nav());
    echo "</pre>";
    ?></p>
<?php 
    // end debuging.
} ?>

</div>
