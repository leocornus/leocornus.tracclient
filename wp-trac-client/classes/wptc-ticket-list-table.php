<?php

// load the WP_List_Table class.
if(!class_exists('WP_List_Table')){
    // the WP_List_Table class depends on a set of function in
    // screen.php.
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * the customize list table class for a project's tickets.
 */
class WPTC_Ticket_List_Table extends WP_List_Table {

    /**
     * we need the query string for the ticket list
     */
    function __construct($query, $blog_path, $ticket_page_slug) {

        //global $status, $page;

        parent::__construct(array(
            'singular' => 'ticket',
            'plural'   => 'tickets',
            'ajax'     => false
        ));
        $this->query = $query;
        $this->blog_path = $blog_path;
        $this->ticket_page_slug = $ticket_page_slug;
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
            'id'        => 'Id',
            'summary'   => 'Summary',
            'status'    => 'Status',
            'owner'     => 'Owner',
            'priority'  => 'Priority',
            'type'      => 'Type'
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
    function column_id($item) {

        // Build row actions: Edit and Delete
        $idHref = <<<EOT
<a href="{$this->blog_path}{$this->ticket_page_slug}?id={$item['id']}"
  title="View Ticket #{$item['id']}"
  class="{$item['status']}">#{$item['id']}</a>
EOT;

        return $idHref;
    }

    function column_summary($item) {

        // Build row actions: Edit and Delete
        $summaryHref = <<<EOT
<a href="{$this->blog_path}{$this->ticket_page_slug}?id={$item['id']}"
  title="View Ticket #{$item['id']}">{$item['summary']}</a>
EOT;

        return $summaryHref;
    }

    function column_owner($item) {

        return wptc_widget_user_href($item['owner']);
    }


    /**
     * here is for easy columns.
     * column_name should be one the keys defined in
     * method get_columns.
     */
    function column_default($item, $column_name) {

        switch($column_name) {
            case 'owner':
                return wptc_widget_user_href($item[$column_name]);
            case 'status':
                return $item[$column_name];
            case 'priority':
                return $item[$column_name];
            case 'type':
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
            'priority' => array('priority',false),
            'status'   => array('status',false),
            'type'     => array('type',false)
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
        $per_page = 20;
        $columns = $this->get_columns();
        // no hidden for now.
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
        //$this->process_bulk_action();

        // query by version order by prority
        $queryStr = $this->query . '&order=priority&desc=0';
        $ids = wptc_ticket_query($queryStr, 0);
        $data = wptc_get_tickets_list_m($ids);

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
