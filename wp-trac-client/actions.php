<?php
ob_start();
global $current_user;
/**
 * a convenient function to handle form post.
 */
function wptc_form_submit() {

    // get the submit action from $_POST
    if (!isset($_POST['submit'])) {
        // nothing to do.
        return;
    }

    if(!is_user_logged_in()) {

        // user not logged in!
        // TODO: error message
        return;
    }

    $id = $_POST['id'];
    if(isset($id)) {
        // modify ticket.
        wptc_handle_ticket_modification($id);
    } else {
        wptc_handle_ticket_creation();
    }

}

/**
 * handle the ticket modification submit.
 */
function wptc_handle_ticket_modification($id) {

    // TODO: validate the reassign action
    // to make sure the owner is valid.

    $comment = 
        wptc_widget_clean_textarea($_POST['wikicomment']);
    // available workflow actions.
    $workflow_actions = wptc_analyze_workflow_action();
    // ticket attributes.
    $ticket_props = wptc_analyze_ticket_props();

    // get ready the attributes.
    $attributes = array();
    // timestamp is must!
    $attributes['_ts'] = $_POST['ts'];
    $attributes = array_merge($attributes, $ticket_props,
                              $workflow_actions);

    $ticket = wptc_update_ticket($id, $comment, $attributes);
}

/**
 * handle ticket creation submit.
 */
function wptc_handle_ticket_creation() {

    // TODO: validate the create ticket 
    // to make sure owner and reporter are valid.

    $ticket_props = wptc_analyze_ticket_props();
    $ticket_props['project'] = $_POST['field_project'];
    $ticket_props['owner'] = $_POST['field_owner'];

    $id = wptc_create_ticket($ticket_props['summary'],
                             $ticket_props['description'],
                             $ticket_props);
    // if success!
    header("Location: " . get_permalink() . "?id=" . $id);
}

/**
 * collect ticket props from the form POST.
 */
function wptc_analyze_ticket_props() {

    $attributes = array();
    $fields = array(
        'summary',
        'reporter',
        'description',
        'project',
        'type',
        'priority',
        'milestone',
        'component',
        'version',
        'keywords');
    foreach($fields as $field) {
        $attributes[$field] = $_POST['field_' . $field];
        if ($field === 'description') {
            $attributes[$field] = 
                wptc_widget_clean_textarea($attributes[$field]);
        } else if ($field === 'summary') {
            $attributes[$field] = 
                stripslashes($attributes[$field]);
        }
    }

    return apply_filters('wptc_analyze_ticket_props', 
                         $attributes);
}

/**
 * analyze workflow actions from the action section.
 * all field is defined in function
 * wptc_widget_action_fieldset.
 */
function wptc_analyze_workflow_action() {

    // analyze action:
    // based on the default workflow.
    // we should always have 'action' field.
    $action = $_POST['action'];
    $attributes = array();
    $attributes['action'] = $action;
    switch($action) {
        case 'leave':
            // leave as it is.
            break;
        case 'reopen':
            // status update to reopened
            $attributes['status'] = 'reopened';
            break;
        case 'accept':
            $attributes['status'] = 'accepted';
            global $current_user;
            // set owner to self.
            $attributes['owner'] = $current_user->user_login;
            break;
        case 'resolve':
            // resove to a resolution.
            $attributes['status'] = 'closed';
            $attributes['resolution'] = 
                $_POST['action_resolve_resolve_resolution'];
            break;
        case 'reassign':
            // reassign to another person.
            $attributes['status'] = 'assigned';
            // set owner to selected user.
            $attributes['owner'] = 
                $_POST['field_owner'];
            break;
    }

    return apply_filters('wptc_analyze_workflow_action',
                         $attributes);
}
