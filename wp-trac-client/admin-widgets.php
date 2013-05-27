<?php

require_once(WPTC_PLUGIN_PATH . '/classes/wptc-project-list-table.php');

/**
 * load and analyzt the request context.
 * the context will be array with 
 */
function wptc_widget_pm_context() {

    $context = array();
    // what's the action.
    if (isset($_REQUEST['action'])) { 
        $context['action'] = $_REQUEST['action'];
    } else {
        $context['action'] = 'list';
    }

    // what's the manage action.
    if (isset($_REQUEST['manageaction'])) {
        $context['manageaction'] = $_REQUEST['manageaction'];
    }

    return $context;
}

/**
 * get ready the list of projects.
 */
function wptc_widget_projects_list() {

    $projectListTable = new WPTC_Project_List_Table();
    $projectListTable->prepare_items();
    $page = $_REQUEST['page'];

    echo <<<EOT
<div id="icon-edit-pages" class="icon32"><br/></div>
<h2>Trac Project Management</h2>
EOT;
    // new projcet form.
    wptc_widget_new_project();

    echo <<<EOT
<h3>List of Projects</h3>

<form id="projects-filter" method="get">
  <input type="hidden" name="page" value="{$page}"/>
EOT;
    // show the list table.
    $projectListTable->display();
    echo "</form>";
}

function wptc_widget_new_project() {

    $page = $_REQUEST['page'];
    //$form_id = 'wptc_new_project_form';
    $form_id = 'wptcaddproject';
    
    echo <<<EOT
<!-- div id="icon-edit-pages" class="icon16"><br/></div -->
<h3>Add New Project</h3>

<form id="{$form_id}" method="post" name="{$form_id}"
      action=""
      class="add:projects: validate">
  <input type="hidden" name="page" value="{$page}"/>
  <input type="hidden" name="{$form_id}_submit" value="Y"/>
EOT;
    wp_nonce_field('add-new-project', 
                   '_wpnonce_add-new-project' );
    echo <<<EOT
  <table class="form-table"><tbody>
    <tr>
      <th scope="row">
        <label for="wptc_projectname">Project Name: 
          <span class="description">(required)</span>
        </label>
      </th>
      <td><input type="text" id="wptc_projectname" 
                 name="wptc_projectname" 
                 value="" size="58"/>
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="wptc_projectdesc">Description: 
          <span class="description">(required)</span>
        </label>
      </th>
      <td><input type="text" id="wptc_projectdesc" 
                 name="wptc_projectdesc" 
                 value="" size="88"/>
      </td>
    </tr>
  </tbody></table>
EOT;
    submit_button('Add New Project', 'primary',
                  'addproject', true,
                  array('id' => 'addproject'));
    echo "</form>";
}

/**
 * facility to handle submit in project management page.
 */
function wptc_handle_pm_submit($context) {

    //if(isset($_POST['wptc_new_project_form_submit']) &&
    //   $_POST['wptc_new_project_form_submit'] === 'Y') {
    if(isset($_POST['wptcaddproject_submit']) &&
       $_POST['wptcaddproject_submit'] === 'Y') {
 
        wptc_handle_add_new_project();
    } else if(isset($_POST['wptc_mandv_form_submit']) &&
              $_POST['wptc_mandv_form_submit'] === 'Y') {
        wptc_handle_mandv_formsubmit();
    } else if(isset($_REQUEST['action'])) { 
        switch($_REQUEST['action']) {
            case 'deleteproject': 
                // 
                wptc_handle_delete("project");
                break;
            case 'deletemandv':
                wptc_handle_delete("mandv");
                break; 
        }
    }
}

/**
 * handle add new project.
 */
function wptc_handle_add_new_project() {

    $name = trim($_POST['wptc_projectname']);
    $desc = trim($_POST['wptc_projectdesc']);

    // Server site validation.
    if($name === "" || $desc === "") {
        echo <<<EOT
<div class="error"><p>
  Both <em><b>Name</b></em> and <em><b>Description</b></em> 
  are <em><b>required</b></em> for a new project.
</p></div>
EOT;
    } else if(count(wptc_get_project($name)) > 0) {
        echo <<<EOT
<div class="error"><p>
  Project <em><b>$name</b></em> already exist!
</p></div>
EOT;
    } else {

        wptc_add_project($name, $desc);

        echo <<<EOT
<div class="updated"><p><strong>
  Project Added!
</strong></p></div>
EOT;
    }
}

/**
 * delete a project for mandv
 */
function wptc_handle_delete($actionType) {

    $name = $_REQUEST[$actionType];
    $metaType = $_REQUEST['type'];
    switch($actionType) {
        case 'project':
            $table_name = WPTC_PROJECT;
            $label = 'Project';
            break;
        case 'mandv':
            $table_name = WPTC_PROJECT_METADATA;
            $label = 'Milestone / Version';
            break;
        default:
            // do nothing.
            return;
    }
    wptc_remove_byname($table_name, $metaType, $name);
    echo <<<EOT
<div class="updated"><p><strong>
  {$label} <em><b>{$name}</b></em> Removed!
</strong></p></div>
EOT;
}

/**
 * page to manage a project.
 */
function wptc_widget_manage_project($context) {

    $name = $_REQUEST['project'];
    $milestoneList = new WPTC_Milestone_List_Table($name);
    $milestoneList->prepare_items();
    $page = $_REQUEST['page'];

    echo <<<EOT
<div id="icon-edit-pages" class="icon32"><br/></div>
<h2>Manage Project <b>$name</b></h2>
EOT;
    // milestone edit form.
    wptc_widget_mandv_form($name, $context);

    echo <<<EOT
<h3>List of Milestones</h3>

<form id="projects-filter" method="get">
  <input type="hidden" name="page" value="{$page}"/>
EOT;
    // show the list table.
    $milestoneList->display();
    echo "</form>";
}

/**
 * milestone and version list for a project.
 */
function wptc_widget_mandv_form($project, $context) {

    $page = $_REQUEST['page'];
    $form_id = 'wptc_mandv_form';

    echo <<<EOT
<h3>Add New Milestone / Version</h3>

<form id="{$form_id}" method="post" name="{$form_id}"
      action=""
      class="validate">
  <input type="hidden" name="page" value="{$page}"/>
  <input type="hidden" name="wptc_projectname" value="{$project}"/>
  <input type="hidden" name="{$form_id}_submit" value="Y"/>
EOT;
    wp_nonce_field('create-edit-mandv',
                   '_wpnonce_create_edit_mandv');
    echo <<<EOT
  <table class="form-table"><tbody>
    <tr>
      <th scope="row">
        <label for="wptc_mandvtype">Type: </label>
      </th>
      <td>
        <select id="wptc_mandvtype" name="wptc_mandvtype">
          <option value="version" selected>Version</option>
          <option value="milestone">Milestone</option>
        </select>
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="wptc_mandvname">Name: 
          <span class="description">(required)</span>
        </label>
      </th>
      <td><input type="text" id="wptc_mandvname" 
                 name="wptc_mandvname" 
                 value="" size="58"/>
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="wptc_mandvdesc">Description: 
          <span class="description">(required)</span>
        </label>
      </th>
      <td><input type="text" id="wptc_mandvdesc" 
                 name="wptc_mandvdesc" 
                 value="" size="88"/>
      </td>
    </tr>
    <tr>
      <th scope="row">
        <label for="wptc_mandvduedate">Due Date: 
          <span class="description">(required)</span>
        </label>
      </th>
      <td><input type="text" id="wptc_mandvduedate" 
                 name="wptc_mandvduedate"/>
      </td>
    </tr>
  </tbody></table>
EOT;

    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui');
    submit_button('Add New Milestone / Version', 'primary',
                  'mandvsubmit', true,
                  array('id' => 'mandvsubmit'));
    echo <<<EOT
  <script>
    jQuery(function() {
      jQuery("#wptc_mandvduedate").datepicker();
    });
  </script>
</form>
EOT;
}

/**
 * handle form submit for milestone and version
 */
function wptc_handle_mandv_formsubmit() {

    $projectName = trim($_POST['wptc_projectname']);
    $type = trim($_POST['wptc_mandvtype']);
    $name = trim($_POST['wptc_mandvname']);
    $desc = trim($_POST['wptc_mandvdesc']);
    // due date with format mm/dd/yyyy from date picker
    $due = trim($_POST['wptc_mandvduedate']);

    if($name === "" || $desc === "" || $due === "") {
        echo <<<EOT
<div class="error"><p>
  Fields <em><b>Name</b></em>, <em><b>Description</b></em> 
  and <em><b>Type</b></em>
  are <em><b>required</b></em> for a new {$type}.
</p></div>
EOT;
    } else if(count(wptc_get_mandv($name)) > 0){
        echo <<<EOT
<div class="error"><p>
  {$type} <em><b>$name</b></em> already exist!
</p></div>
EOT;
    } else {
        // adding time to the end of the date
        $dueStr = ($type === 'milestone') ? $due . ' 17:00:01' :
            $due . ' 17:00:00';
        $duedate = DateTime::createFromFormat('m/d/Y H:i:s', $dueStr);
        $success = wptc_update_mandv($projectName, $type, $name, 
                                     $desc, $duedate);
        echo <<<EOT
<div class="updated"><p><strong>
  {$type} <b>$name</b> Added!
</strong></p></div>
EOT;
    }
}
