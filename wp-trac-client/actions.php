<?php
/**
 * a convenient function to handle form post.
 */
function wptc_form_submit() {

    // get the submit action from $_POST
    if (!isset($_POST['submit'])) {
        // nothing to do.
        return;
    }

    $comment = $_POST['wikicomment'];
    $id = $_POST['id'];
    $workflow_actions = wptc_analyze_workflow_action();

    // get ready the attributes.
    $attributes = array();
    // timestamp is must!
    $attributes['_ts'] = $_POST['ts'];
    $attributes = array_merge($attributes, $workflow_actions);
    // ticket attributes.

    $ticket = wptc_update_ticket($id, $comment, $attributes);
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
            $attributes['owner'] = 
                $_POST['action_reassign_reassign_owner'];
            break;
    }

    return apply_filters('wptc_analyze_workflow_action',
                         $attributes);
}
