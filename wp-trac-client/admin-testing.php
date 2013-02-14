<?php

if(isset($_POST['wptc_testing_form_submit']) &&
   $_POST['wptc_testing_form_submit'] === 'Y') {

    $functionName = $_POST['wptc_function'];
    $functionInputs = $_POST['wptc_function_params'];
    $functionParams = explode(',', $functionInputs);

    //switch($functionName) {
    //    case 'wptc_get_ticket_projects':
    //        $testResult = wptc_get_ticket_projects();
    //        break;
    //    case 'wptc_get_ticket_milestones':
    //        $testResult = wptc_get_ticket_milestones($params[0]);
    //        break;
    //    case 'wptc_get_ticket_versions':
    //        $testResult = wptc_get_ticket_versions($params[0], $params[1]);
    //    default:
    //        break;
    //}

    // call user func is the way to execute a function on the fly.
    $testResult = call_user_func_array($functionName, $functionParams);
}
?>

<div class="wrap">
  <h2>WordPress Trac Client - Testing APIs</h2>

  <form name="wptc_testing_form" method="post">
    <input type="hidden" name="wptc_testing_form_submit" value="Y"/>
    <table class="form-table"><tbody>
      <tr>
        <th>Function Name: </th>
        <td>
          <select type="text" id="wptc_function" 
            name="wptc_function">
            <?php //TODO: generate on the fly  ?>
            <option></option>
            <option>wptc_get_project</option>
            <option>wptc_get_projects</option>
            <option>wptc_get_project_mandv</option>
            <option>wptc_get_ticket</option>
            <option>wptc_get_ticket_projects</option>
            <option>wptc_get_ticket_milestones</option>
            <option>wptc_get_ticket_versions</option>
            <option>wptc_get_ticket_types</option>
            <option>wptc_get_ticket_priorities</option>
            <option>wptc_get_ticket_components</option>
            <option>wptc_get_ticket_metas</option>
            <option>wptc_update_ticket_meta</option>
            <option>wptc_remove_ticket_meta</option>
            <option>wptc_widget_ticket_defaults</option>
            <option>wptc_widget_version_nav</option>
          </select>
        </td>
      </tr>
      <tr>
        <th>Function Description: </th>
        <td>
          <span id="wptc_function_desc">
            The details description for this function.
          </span>
        </td>
      </tr>
      <tr>
        <th scope="row">Function Input(s): </th>
        <td>
          <input type="text" id="wptc_function_params" 
            name="wptc_function_params" size="88"/>
        </td>
      </tr>
      <tr>
        <th scope="row">
          <input type="submit" name="test" class="button-primary" 
            value="Test" />
        </th>
        <td></td>
      </tr>
      <tr>
        <th scope="row">Test Result: </th>
        <td><pre>
<b>Function:</b> <?php echo $functionName; ?>
<br/>
<b>Parameters:</b> 
<?php var_dump($functionParams); ?>
<b>Results:</b>
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
