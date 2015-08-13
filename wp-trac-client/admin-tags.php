<?php

function wptc_logging($msg) {
   
    // TODO: this should never go prodcution!
    $DEBUG = false;
    if($DEBUG) {
        $logfile = fopen(ABSPATH . 'logging.log', "a");
        // time stamp
        fwrite($logfile, $msg . "\n"); 
        fclose($logfile);
    }
}

/**
 * create database tables for the project management
 * if force create tables, all data will be droped.
 * default is NOT force to create tables.
 */
function wptc_create_tables($force=false) {

    require_once(ABSPATH . "wp-admin/includes/upgrade.php");

    // the project table.
    $sql = "CREATE TABLE " . WPTC_PROJECT . " (
        id mediumint(9) not null auto_increment,
        name varchar(100) not null,
        description varchar(512) not null default '',
        PRIMARY  KEY  (id),
        UNIQUE KEY name (name)
    );";
    wptc_logging("create project table: " . $sql);
    dbDelta($sql);

    // the project metadata table.
    // the type will be one of [milestone, version]
    $sql = "CREATE TABLE " . WPTC_PROJECT_METADATA . " (
        id mediumint(9) not null auto_increment,
        name varchar(100) not null, 
        project_id mediumint(9) not null,
        type varchar(64) not null,
        description varchar(512) not null default '',
        due_date datetime not null default '0000-00-00 00:00:00',
        PRIMARY  KEY  (id),
        UNIQUE KEY name (name)
    );";
    wptc_logging("create project metadata table: ". $sql);
    dbDelta($sql);
}

/**
 * return all projects 
 * @param $page_number, starts from 0, if -1, will return all.
 *        default id -1, return all records.
 * @param $per_page, default is 10
 */
function wptc_get_projects($page_number=-1, $per_page=10) {

    global $wpdb;

    // here are the default query.
    $query = "select * from " . WPTC_PROJECT;
    if($page_number >= 0) {
        $starts_from = $page_number * $per_page;
        $query = "{$query} LIMIT {$starts_from}, {$per_page}";
    }

    $projects = $wpdb->get_results($query, ARRAY_A);
    return $projects;
}

function wptc_add_project($name, $description) {

    global $wpdb;

    $success = $wpdb->insert(
        WPTC_PROJECT,
        array(
            'name' => $name,
            'description' => $description
        ),
        array(
            '%s',
            '%s'
        )
    );

    return $success;
}

/**
 * return all details information about the given project.
 * it will return a empty array if the project is not exist.
 */
function wptc_get_project($name) {

    global $wpdb;

    // query project.
    $query = "select * from " . WPTC_PROJECT . 
             " where name = %s";
    $query = $wpdb->prepare($query, $name);
    $project = $wpdb->get_row($query, ARRAY_A);
    if(count($project) < 1) {
        // not such project exist.
        return $project;
    }

    // query all metadats 
    $query = "SELECT * FROM " . WPTC_PROJECT_METADATA .
             " where project_id = %d order by due_date DESC";
    $query = $wpdb->prepare($query, $project['id']);
    $meta = $wpdb->get_results($query, ARRAY_A);
    $project['meta'] = $meta;

    return $project;
}

/**
 * return the project name for the given milestone name
 * for version name.
 */
function wptc_get_project_name($mandvName) {

    global $wpdb;

    $query = "SELECT name FROM " . WPTC_PROJECT .
             " WHERE id = (" .
             "SELECT project_id FROM " . WPTC_PROJECT_METADATA .
             " WHERE name = %s)";
    $query = $wpdb->prepare($query, $mandvName);
    $project = $wpdb->get_row($query, ARRAY_A);
    if(count($project) < 1) {
        // no such milestone / version exist.
        // TODO: Exception/Error Handling.
        return null;
    } else {
        return $project['name'];
    }
}

function wptc_remove_byname($table_name, $type, $name) {

    global $wpdb;

    $query = "DELETE FROM " . $table_name . 
             " WHERE name = %s";
    $query = $wpdb->prepare($query, $name);
    // if error, false is return.
    // else number of rows affected/selected.
    $rows = $wpdb->query($query);

    do_action('wptc_remove_byname_action', $type, $name);

    return $rows;
}

add_action('wptc_remove_byname_action', 'wptc_remove_byname_trac',
           10, 2);

/**
 * action for remove metadata.
 */
function wptc_remove_byname_trac($type, $name) {

    if(isset($type)) {

        if(($type === 'milestone') || ($type === 'version')) {

            wptc_remove_ticket_meta($type, $name);
        }
    }
}

/**
 * create or update a milestone or version for the project.
 */
function wptc_update_mandv($project_name, $type, $name,
                           $description, $duedate) {

    global $wpdb;
    $project = wptc_get_project($project_name);
    $data = array(
            'name' => $name,
            'description' => $description,
            'type' => $type,
            'due_date' => $duedate->format('Y-m-d H:i:s'),
            'project_id' => $project['id']
           );
    $format = array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%d'
           );

    if (count(wptc_get_mandv($name)) > 0) {
        // update an exist metadata.
        $success = $wpdb->update(
            WPTC_PROJECT_METADATA,
            $data, array('name' => $name),
            $format, array('%s'));
    } else {
        // create a new one.
        $success = $wpdb->insert(WPTC_PROJECT_METADATA, 
                                 $data, $format);
    }

    if ($success)
        do_action('wptc_update_mandv', $type, $name, 
                  $description, $duedate);

    return $success;
}

// hook the update action.
add_action('wptc_update_mandv', 'wptc_update_mandv_trac', 10, 4);

/**
 * update a milestone or version on trac.
 */
function wptc_update_mandv_trac($type, $name, $desc, $duedate) {

    $attr = array();
    $attr['name'] = $name;
    $attr['description'] = $desc;
    switch($type) {
        case 'milestone':
            $attr['due'] = $duedate;
            break;
        case 'version':
            $attr['time'] = $duedate;
            break;
    }

    wptc_update_ticket_meta($type, $name, $attr);
}

/**
 * return all files 
 */
function wptc_get_mandv($name) {

    global $wpdb;
    $query = "SELECT * FROM " . WPTC_PROJECT_METADATA .
             " WHERE name = %s";
    $query = $wpdb->prepare($query, $name);
    $mandv = $wpdb->get_row($query, ARRAY_A);

    return $mandv;
}

/**
 * return a list of milestones and versions for the given project.
 * the result will be organized by milestones.
 * 
 * 'milestone1' => array(
 *     array('milesone1', 'milestone 1 desc', 'type', 'id', 'due_date'),
 *     array('version10', 'version 10 desc', 'version', 'id', 'due'),
 *     array('version09', 'version 09 desc', 'version', 'id', 'due')
 * );
 */
function wptc_get_project_mandv($name) {

    //global $wpdb;
    //$query = "SELECT * FROM " . WPTC_PROJECT_METADATA .
    //         " WHERE project_id = " .
    //         "(SELECT id FROM " . WPTC_PROJECT .
    //         " WHERE name = %s) ORDER BY due_date DESC";
    //$query = $wpdb->prepare($query, $type, $name);
    //$mandv = $wpdb->get_results($query, ARRAY_A);
    $project = wptc_get_project($name);
    // TODO: what if the project is not exist?
    $mandv = array();
    foreach($project['meta'] as $v) {
        if ($v['type'] === 'milestone') {
            //$milestone = array();
            // adding this milestone as the first one.
            //$milestone[] = $v;
            $mandv[$v['name']][] = $v;
        } else {
            end($mandv);
            $theKey = key($mandv);
            // this is a version
            $mandv[$theKey][] = $v;
        }
    }

    return $mandv;
}
