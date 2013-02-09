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
 */
function wptc_get_projects() {

    global $wpdb;

    $query = "select * from " . WPTC_PROJECT;
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
    $query = "select * from " . WPTC_PROJECT_METADATA .
             " where project_id = %d order by due_date DESC";
    $query = $wpdb->prepare($query, $project['id']);
    $meta = $wpdb->get_results($query, ARRAY_A);
    $project['meta'] = $meta;

    return $project;
}

function wptc_remove_byname($table_name, $name) {

    global $wpdb;

    $query = "delete from " . $table_name . 
             " where name = %s";
    $query = $wpdb->prepare($query, $name);
    // if error, false is return.
    // else number of rows affected/selected.
    $rows = $wpdb->query($query);

    return $rows;
}

function wptc_update_mandv($project_name, $type, $name,
                           $description, $duedate) {

    global $wpdb;
    $project = wptc_get_project($project_name);

    $success = $wpdb->insert(
        WPTC_PROJECT_METADATA,
        array(
            'name' => $name,
            'description' => $description,
            'type' => $type,
            'due_date' => $duedate->format('Y-m-d H:i:s'),
            'project_id' => $project['id']
        ),
        array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%d'
        )
    );

    return $success;
}

function wptc_get_mandv($name) {

    global $wpdb;
    $query = "select * from " . WPTC_PROJECT_METADATA .
             " where name = %s";
    $query = $wpdb->prepare($query, $name);
    $mandv = $wpdb->get_row($query, ARRAY_A);

    return $mandv;
}
