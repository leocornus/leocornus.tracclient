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
 * create request context 
 */
function wptc_context_factory() {

    $context = new Wptc\Context\RequestContext();
    $project = $context->getState('project');
    if(empty($project)) {
        // this is the projects list page.
        // clean the cookies.
        $context->setCookieStates(-3600);
        $context = new Wptc\Context\ProjectsRequestContext();
    }

    return $context;
}

/**
 * view generator based on context.
 * 
 * @param $context Wptc\Context\RequestContext
 */
function wptc_view_generator($context) {

    $project = $context->getState('project');
    if(empty($project)) {
        $projectsHome = new Wptc\View\ProjectsHome($context);
        echo $projectsHome->renderPage();
    } else {
        $ticketHome = new Wptc\View\ProjectTicketsHome($context);
        echo $ticketHome->renderPage();
    }
}
