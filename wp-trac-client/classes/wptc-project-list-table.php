<?php

require_once( ABSPATH . 'wp-admin/includes/template.php' );
// load the WP_List_Table class.
if(!class_exists('WP_List_Table')){
    // the WP_List_Table class depends on a set of function in
    // screen.php.
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
            //'cb'          => '<input type="checkbox" />',
            'name'        => 'Name',
            'description' => 'Description'
            // TODO: should provide the following information.
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
                                'manageproject',
                                $item['name'], 'Manage'),
            'delete' => sprintf($aTemp, $_REQUEST['page'],
                                'deleteproject',
                                $item['name'], 'Delete'),
        );

        // Return the title contents
        // <span style="color:silver">(id:%2$s)</span>
        // /*$2%s*/ $item['ID'],
        $nameHref = sprintf($aTemp, $_REQUEST['page'], 
                            'manageproject', $item['name'],
                            $item['name']);
        return sprintf('%1$s %2$s',
            /*$1%s*/ $nameHref,
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
    //function get_bulk_actions() {

    //    $actions = array(
    //        'delete'    => 'Delete'
    //    );

    //    return $actions;
    //}

    /**
     * handle bulk action here.
     */
    //function process_bulk_action() {
    //    
    //    if ('delete' === $this->current_action()) {
    //        wp_die('action place holder for now');
    //    }
    //}

    /**
     * get ready the data here.
     */
    function prepare_items() {

        //global $wpdb;

        // how many items per page.
        $per_page = 15;
        $columns = $this->get_columns();
        // no hidden for now.
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, 
                                       $sortable);
        //$this->process_bulk_action();

        $data = wptc_get_projects();

        // this is array sorting,
        // we could query database directly
        function wptc_project_usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'name'; //If no sort, default to name 
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            // Determine sort order
            $result = strcmp($a[$orderby], $b[$orderby]); 
            // Send final sort direction to usort
            return ($order==='asc') ? $result : -$result; 
        }
        usort($data, 'wptc_project_usort_reorder');

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
 * the customize list table class for a project's 
 * milestone and version list.
 */
class WPTC_Milestone_List_Table extends WP_List_Table {

    /**
     * we need the project name for the milestone list
     */
    function __construct($project) {

        global $status, $page;

        parent::__construct(array(
            'singular' => 'milestone',
            'plural'   => 'milestones',
            'ajax'     => false
        ));
        $this->project_name = $project;
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
            //'cb'          => '<input type="checkbox" />',
            'name'        => 'Name',
            'description' => 'Description',
            'due_date'    => 'Due Date'
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
        $aTemp = '<a href="?page=%s&action=%s&mandv=%s&type=%s">%s</a>';

        $editHref = '<a href="?page=' . $_REQUEST['page'] .
                    '&action=manageproject&project=' . 
                    $this->project_name . '&manageaction=edit' .
                    '&mandv=' . $item['name'] .
                    '&type=' . $item['type'] . '">Edit</a>';
        $actions = array(
            'edit'   => $editHref,
            'delete' => sprintf($aTemp, $_REQUEST['page'],
                                'deletemandv', $item['name'],
                                $item['type'], 'Delete'),
        );

        // Return the name contents
        // using ternary operator.
        $nameContent = ($item['type'] === 'version') ?
            ('— ' . $item['name']) :  $item['name'];    
        $nameContent = "<b><span style='font-size: 15px'>" . 
                       $nameContent . "</span></b>";
        $nameHref = sprintf($aTemp, $_REQUEST['page'],
                            'editmilestone', $item['name'],
                            $item['type'], $nameContent);
        return sprintf('%1$s %2$s',
            /*$1%s*/ $nameHref,
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
            case 'due_date':
                // TODO: we will handle due date later.
                return $item[$column_name];
            default:
                // should not happen.
                // in case it happens, print out details...
                return print_r($item, true);
        }
    }

    /**
     * set the sortable columns here.
     * TODO: milestones will only sort by due date.
     */
    function get_sortable_columns() {

        //$sortable_columns = array(
        //    // true means it's already sorted
        //    'name'        => array('title',false),
        //    'description' => array('director',false)
        //);

        //return $sortable_columns;
    }

    /**
     * set bulk actions for checkboxes.
     */
    //function get_bulk_actions() {

    //    $actions = array(
    //        'delete'    => 'Delete'
    //    );

    //    return $actions;
    //}

    /**
     * handle bulk action here.
     */
    //function process_bulk_action() {
    //    
    //    if ('delete' === $this->current_action()) {
    //        wp_die('action place holder for now');
    //    }
    //}

    /**
     * get ready the data here.
     */
    function prepare_items() {

        //global $wpdb;

        // how many items per page.
        $per_page = 10;
        $columns = $this->get_columns();
        // no hidden for now.
        $hidden = array();
        //$sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden);
        //$this->process_bulk_action();

        $data = wptc_get_project($this->project_name);
        $data = $data['meta'];

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
