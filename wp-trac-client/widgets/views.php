<?php
/**
 * display a list of tickets by using jQuery DataTable.
 * The jQuery DataTable lib will handle the basic funtionalities
 * like pagination, sorting, filtering...
 * We only need provide the query criterias
 *
 * @param $query is the search criteria.
 * @param $per_page default tickets per page is 15.
 */
function wptc_view_tickets_dt($query, 
                              $ticket_page_slug="trac/ticket") {

    // the empty blog_id will tell to use the current blog.
    $blog_path = get_site_url();

    // query tickets and load ticket details
    // will load all qualified tickets at one query.
    $ids = wptc_ticket_query($query, 0);
    $tickets = wptc_get_tickets_list_m($ids);

    $trs = array();
    foreach($tickets as $ticket) {

        $ticket_url = $blog_path . "/" . $ticket_page_slug . "?id=" . 
                      $ticket['id'];
        $owner_href = wptc_widget_user_href($ticket['owner']);
        $one_tr = <<<EOT
<tr>
  <td><a href="{$ticket_url}" class="{$ticket['status']}">
    {$ticket['id']}</a>
  </td>
  <td><a href="{$ticket_url}">{$ticket['summary']}</a></td>
  <td>{$owner_href}</td>
  <td>{$ticket['priority']}</td>
  <td>{$ticket['status']}</td>
</tr>
EOT;
        $trs[] = $one_tr;
    }

    // get ready for the datatable.
    $tickets_tr = implode("\n", $trs);
    $table_id = "tracTickets";
    // prepar the data table javascript code.
    $dt_js = wptc_view_dt_js($table_id);

    $dt = <<<EOT
<table cellpadding="0" cellspacing="0" border="0" id="{$table_id}">
<thead>
  <th width="18px">ID</th>
  <th>Summary</th>
  <th width="58px">Owner</th>
  <th width="38px">Priority</th>
  <th width="38px">Status</th>
</thead>
<tbody>
  {$tickets_tr}
</tbody>
<tfoot>
  <th>ID</th>
  <th>Summary</th>
  <th>Owner</th>
  <th>Priority</th>
  <th>Status</th>
</tfoot>
</table>
{$dt_js}
EOT;

    return $dt;
}

/**
 * a re-usable function to generate JavaScript code to configurate
 * and load jQuery DataTable for the given table id.
 */
function wptc_view_dt_js($table_id, $per_page=10) {

    $js = <<<EOT
<script type="text/javascript" charset="utf-8">
<!--
jQuery(document).ready(function() {
    jQuery('#{$table_id}').dataTable( {
        "bProcessing": true,
        "bServerSide": false,
        // trun off the length change drop down.
        "bLengthChange" : true,
        // define the length memn option
        "aLengthMenu" : [[10, 25, 50, -1], [10, 25, 50, "All"]],
        // turn off filter.
        "bFilter" : true,
        // turn off sorting.
        "bSort" : true,
        // items per page.
        "iDisplayLength" : {$per_page},
        "sPaginationType": "full_numbers",
        "aoColumns" : [
            {"bSortable":false},
            {"bSortable":false},
            {"bSortable":true},
            {"bSortable":true},
            {"bSortable":true},
        ]
    } );
} );
-->
</script>
EOT;

    return $js;
}

/**
 * display a list of tickets by using jQuery DataTable.
 * The jQuery DataTable lib will handle the basic funtionalities
 * like pagination, sorting, filtering...
 * We only need provide the query criterias
 *
 * @param $query is the search criteria.
 * @param $per_page default tickets per page is 15.
 */
function wptc_view_mytickets_dt($query, 
                                $ticket_page_slug="trac/ticket") {

    // the empty blog_id will tell to use the current blog.
    $blog_path = get_site_url();

    // query tickets and load ticket details
    // will load all qualified tickets at one query.
    $ids = wptc_ticket_query($query, 0);
    $tickets = wptc_get_tickets_list_m($ids);

    $trs = array();
    foreach($tickets as $ticket) {

        $ticket_url = $blog_path . "/" . $ticket_page_slug . "?id=" . 
                      $ticket['id'];
        $sprint_href = wptc_widget_version_href($ticket['version']);
        $one_tr = <<<EOT
<tr>
  <td><a href="{$ticket_url}" class="{$ticket['status']}">
    {$ticket['id']}</a>
  </td>
  <td><a href="{$ticket_url}">{$ticket['summary']}</a></td>
  <td><a href="{$sprint_href}">{$ticket['version']}</a></td>
  <td>{$ticket['priority']}</td>
  <td>{$ticket['status']}</td>
</tr>
EOT;
        $trs[] = $one_tr;
    }

    // get ready for the datatable.
    $tickets_tr = implode("\n", $trs);
    $table_id = "tracTickets";
    // prepar the data table javascript code.
    $dt_js = wptc_view_dt_js($table_id);

    $dt = <<<EOT
<table cellpadding="0" cellspacing="0" border="0" id="{$table_id}">
<thead>
  <th width="18px">ID</th>
  <th>Summary</th>
  <th width="78px">Sprint</th>
  <th width="28px">Priority</th>
  <th width="28px">Status</th>
</thead>
<tbody>
  {$tickets_tr}
</tbody>
<tfoot>
  <th>ID</th>
  <th>Summary</th>
  <th>Sprint</th>
  <th>Priority</th>
  <th>Status</th>
</tfoot>
</table>
{$dt_js}
EOT;

    return $dt;
}

/**
 * project homepage header
 */
function wptc_view_project_header($context) {

    $projects_url = "/projects";
    $project_name = $context['project'];
    $project_url = "/projects?project={$project_name}";
    $project = wptc_get_project($project_name);

    $header = <<<EOT
<div class="page-header" id="project-header">
  <form class="navbar-form navbar-right" role="search">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search">
    </div>
    <button type="submit" class="btn btn-success">Go</button>
  </form>
  <h3>
    <a href="{$projects_url}"> All Projects</a> / 
    <a href="{$project_url}">{$project_name}</a>
  </h3>
  <p>{$project['description']}</p> 
</div> <!-- project-header -->
EOT;

    return $header;
}

/**
 * project home nav bar .
 */
function wptc_view_project_nav($context) {

    $nav = <<<EOT
<div id="project-nav" class="container-fluid h4">
  <ul class="nav nav-tabs">
    <li><a href="#">Project Home</a></li>
    <li class="active"><a href="#">Issues</a></li>
    <li><a href="#">Commits</a></li>
    <li class="dropdown pull-right">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Actions<span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="#">Report an Issue</a></li>
        <li role="separator" class="divider"></li>
        <li class="dropdown-header">My...</li>
        <li><a href="#">My Tickets</a></li>
        <li><a href="#">My Watchlist</a></li>
      </ul>
    </li>
  </ul>
</div> <!-- project-nav -->
EOT;

    return $nav;
}

/**
 * project content.
 */
function wptc_view_project_content($context) {

    // the empty blog_id will tell to use the current blog.
    $blog_path = get_site_url();
    $ticket_page_slug = "projets/ticket";

    $query = "project={$context['project']}&status!=closed";
    // query tickets and load ticket details
    // will load all qualified tickets at one query.
    $ids = wptc_ticket_query($query, 0);
    $tickets = wptc_get_tickets_list_m($ids);

    //get ready rows for table.
    $trs = array();
    foreach($tickets as $ticket) {

        $ticket_url = "{$blog_path}/{$ticket_page_slug}?id={$ticket['id']}";
        $owner_href = wptc_widget_user_href($ticket['owner']);
        $one_tr = <<<EOT
<tr>
  <td><a href="{$ticket_url}" class="{$ticket['status']}">
    {$ticket['id']}</a>
  </td>
  <td><a href="{$ticket_url}">{$ticket['summary']}</a></td>
  <td>{$owner_href}</td>
  <td>{$ticket['priority']}</td>
  <td>{$ticket['status']}</td>
</tr>
EOT;
        $trs[] = $one_tr;
    }

    $ticket_tr = implode("\n", $trs);

    $content = <<<EOT
<div id="project-content" class="container-fluid">
  <div class="h4" id="summary">
    <span>
      Filters:
      <div class="btn-group">
        <a href="#" class="btn btn-success btn-xs dropdown-toggle"
                    data-toggle="dropdown">
          Owner<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
      <div class="btn-group">
        <a href="#" class="btn btn-success btn-xs dropdown-toggle" 
                    data-toggle="dropdown" aria-expanded="false">
          Status <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
    </span>
    <span id="numbers" class="pull-right">
      Summary:
      <span class="label label-primary">140 in total</span>
      <span class="label label-success">10 closed</span>
      <span class="label label-warning">20 assigned</span>
      <span class="label label-danger">3 critical</span>
    </span>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="success">
          <th>ID</th>
          <th>Summary</th>
          <th>Owner</th>
          <th>Priority</th>
          <th>Status</th>
        </tr>
      </thead>
      <tfoot>
        <tr class="success">
          <th>ID</th>
          <th>Summary</th>
          <th>Owner</th>
          <th>Priority</th>
          <th>Status</th>
        </tr>
      </tfoot>
      <tbody>
        {$ticket_tr}
      </tbody>
    </table>
    <div id="item-pager" class="h4 text-right">
      Showing 20 of 120 tickets!
      <a href="#" class="btn btn-success btn-sm">Load More</a>
    </div>
  </div>
</div> <!-- project-content -->
EOT;

    return $content;
}

/**
 * project footer.
 */
function wptc_view_project_footer($context) {

    // return empty for now.
    return '';
}
