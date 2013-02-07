<?php

/**
 * load and analyzt the request context.
 * the context will be array with 
 */
function wptc_widget_pm_context() {

    $context = array();
    if (isset($_REQUEST['action'])) { 
        $context['action'] = $_REQUEST['action'];
    } else {
        $context['action'] = 'list';
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
    } else if(isset($_REQUEST['action']) &&
              $_REQUEST['action'] === 'deleteproject') {
        // 
        wptc_handle_delete_project();
    }
}

/**
 * handle add new project.
 */
function wptc_handle_add_new_project() {

    $name = trim($_POST['wptc_projectname']);
    $desc = trim($_POST['wptc_projectdesc']);

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
 * delete projects.
 */
function wptc_handle_delete_project() {

    $name = $_REQUEST['project'];
    wptc_remove_project($name);
    echo <<<EOT
<div class="updated"><p><strong>
  Project <em><b>$name</b></em> Removed!
</strong></p></div>
EOT;
}

/**
 * page to manage a project.
 */
function wptc_widget_manage_project() {

    $name = $_REQUEST['project'];

    echo <<<EOT
<div id="icon-edit-pages" class="icon32"><br/></div>
<h2>Manage Project <b>$name</b></h2>
EOT;
}
