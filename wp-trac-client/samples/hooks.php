<?php
/**
 * some sample hooks functions.
 */

/**
 * the action wptc_create_ticket.
 */
add_action('wptc_create_ticket', 'testing_create_ticket', 10, 4);
function testing_create_ticket($id, $summary, $desc, $attrs) {

    $msg = $id . "===>" . $summary . "===>" . $desc;
    update_site_option("create-result", $msg);
    return;
}

add_action('wptc_update_ticket', 'testing_update_ticket', 10, 4);
function testing_update_ticket($id, $comment, $attrs, $author) {

    $msg = $id . "===>" . $comment . "===>" . $author;
    update_site_option("update-result", $msg);
}

// testing the wptc_widget_project_href filter.
// try to change the page slug to a different one.
add_filter('wptc_widget_project_href', 'temp_project_href', 10, 1);
function temp_project_href($href) {

    // assume the default project href is in the following format:
    // http://www.domain.com/page_slug?project=projectname

    // get the project param from ther default href.
    $project = explode('?', $href)[1];
    $thehref = "/projects?{$project}";

    return $thehref;
}

// wptc_project_contributor_total filter.
add_filter('wptc_project_contributor_total', 
           'project_contributor_total', 10, 2);
function project_contributor_total($total, $project_name) {

    // adding 100 more for quick example.
    $total = $total + 100;

    return $total;
}

add_filter('wptc_project_commit_total', 
           'project_commit_total', 10, 2);
function project_commit_total($total, $project_name) {

    $total = $total + strlen($project_name);
    return $total;
}

add_filter('wptc_project_view_footer', 
           'project_view_footer', 10, 1);
function project_view_footer($footer) {

    // demo the footer:
    $footer = <<<EOT
<div class="well well-inverse" id="local-project-footer">
  <div class="row success">
    <div class="col-sm-4">
      <h3 class="header">About Trac</h3>
      <p><a href="http://trac.edgewall.org/">The Trac Project</a></p>
    </div>
    <div class="col-sm-4">
      <h3 class="header">About WordPress</h3>
      <p><a href="http://wordpress.org">WordPress Homepage</a></p>
    </div>
    <div class="col-sm-4">
      <h3 class="header">About WordPress Trac Client</h3>
      <p><a href="https://github.com/leocornus/leocornus.tracclient">Trac Client Project</a></p>
    </div>
</div> <!-- local-project-footer -->
EOT;

    return $footer;
}

/**
 * sample function to demo the usage of filter.
 */
add_filter('wptc_project_repo_pathes', 'project_repo_pathes', 10, 2);
function project_repo_pathes($pathes, $project_name) {

    $project_repos = array(
        'CoreModule' => '/usr/path/to/core/module',
        'TrainingPlugin' => '/usr/projects/training-plugin'
    );

    $repos = [];
    if ($project_name == null) {
        // the root repo path.
        $repos[] = '/usr/root/repo';
    } else {
        if (array_key_exists($project_name, $project_repos)) {
            $repos[] = $project_repos[$project_name];
        }
    }

    return $repos;
}
