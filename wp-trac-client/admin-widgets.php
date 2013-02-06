<?php

// load the WP_List_Table class.
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * the customize list table class for projcts list.
 */
class WPTC_Project_List_Table extends WP_List_Table {

    function __construct() {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'project',
            'plural'   => 'projects',
            'ajax'     => false
        ));
    }

    /**
     * define the columns here,
     */
    function get_columns() {
        // the values for each key will show as the 
        // title columns
        // the key will be used in method name to 
        // customize the column value for each item/row.
        $columns = array(
            'cb'          => '<input type="checkbox" />',
            'name'        => 'Name',
            'description' => 'Description'
            // current milestones / versions
            // components
        );
        return $columns;
    }

    /**
     * cb is the checkbox column, it will be treated specially!
     * this method customize the value of cb column for each
     * item (each row).
     */
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="%1$s[]"
                    value="%2$s" />',
            // for %1$s, using lable for singular,
            // defined in the consturct method.
            $this->_args['singular'],
            // project id is the value.
            $item['id']
        );
    }

    /**
     * customize the value for name column.
     */
    function column_name($item) {

        // Build row actions: Edit and Delete
        $aTemp = '<a href="?page=%s&action=%s&project=%s">%s</a>';
        $actions = array(
            'edit'   => sprintf($aTemp, $_REQUEST['page'],
                                'editproject',$item['name'], 
                                'Edit'),
            'delete' => sprintf($aTemp, $_REQUEST['page'],
                                'deleteproject',$item['name'], 
                                'Delete'),
        );

        // Return the title contents
        // <span style="color:silver">(id:%2$s)</span>
        // /*$2%s*/ $item['ID'],
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['name'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }

    /**
     * here is for easy columns.
     * column_name should be one the keys defined in
     * method get_columns.
     */
    function column_default($item, $column_name) {

        switch($column_name) {
            case 'description':
                return $item[$column_name];
            default:
                // should not happen.
                // in case it happens, print out details...
                return print_r($item, true);
        }
    }

    /**
     * set the sortable columns here.
     */
    function get_sortable_columns() {

        $sortable_columns = array(
            // true means it's already sorted
            'name'        => array('title',false),
            'description' => array('director',false)
        );

        return $sortable_columns;
    }

    /**
     * set bulk actions for checkboxes.
     */
    function get_bulk_actions() {

        $actions = array(
            'delete'    => 'Delete'
        );

        return $actions;
    }

    /**
     * handle bulk action here.
     */
    function process_bulk_action() {
        
        if ('delete' === $this->current_action()) {
            wp_die('action place holder for now');
        }
    }

    /**
     * get ready the data here.
     */
    function prepare_items() {

        //global $wpdb;

        // how many items per page.
        $per_page = 5;
        $columns = $this->get_columns();
        // no hidden for now.
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, 
                                       $sortable);
        $this->process_bulk_action();

        $data = wptc_get_projects();

        // this is array sorting,
        // we could query database directly
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'name'; //If no sort, default to name 
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            // Determine sort order
            $result = strcmp($a[$orderby], $b[$orderby]); 
            // Send final sort direction to usort
            return ($order==='asc') ? $result : -$result; 
        }
        usort($data, 'usort_reorder');

        // for pagination.
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data, 
                            (($current_page - 1) * $per_page),
                            $per_page);

        // here is the data
        $this->items = $data;
        
        // tracking pages.
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

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
<h2>Trac Projects</h2>
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
