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

    $comment = 
        wptc_widget_clean_textarea($_POST['wikicomment']);
    $id = $_POST['id'];
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
 * collect ticket props from the form POST.
 */
function wptc_analyze_ticket_props() {

    $attributes = array();
    $fields = array(
        'summary',
        'reporter',
        'description',
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
