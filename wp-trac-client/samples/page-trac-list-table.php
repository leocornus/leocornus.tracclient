<?php
/*
 * Template Name: Trac Tickets
 * Description: a page templage to show a list of tickets.
 * this templage try to demostrate the usage of WP_List_Table class.
 */

require_once(WPTC_PLUGIN_PATH . '/classes/wptc-ticket-list-table.php');

?>

<?php 
get_header(); 
wp_enqueue_style('wptc-css');
wp_enqueue_script('jquery-masonry');

// the page slug will be the project name.
$version = $_GET['version'];
$milestone = $_GET['milestone'];
if (empty($version)) {
    // using the default sprint.
    //$defaults = wptc_widget_ticket_defaults();
    //$version = $defaults['version'];
    //$project = $defaults['project'];
} else {
    $project = wptc_get_project_name($version);
}
?>


  <div id="left_column">
    <div class='leftnav'>
<?php if (!empty($version)) { ?>
      <div id='sprint-nav' class="widget">
        <h2 class='widgettitle'>
          Project: <b><?php echo $project;?></b>
        </h2>
        <?php echo wptc_widget_version_nav($project)?>
      </div>
<?php } ?>
      <div id='ticket-finder' class="widget">
        <h2 class='widgettitle'>Ticket Toolbar</h2>
        <?php echo wptc_widget_trac_toolbar('trac/ticket')?>
      </div>
    </div>
  </div> <?php // END left_column ?>

  <div id="content">

<?php if (empty($version) && empty($milestone)) {

  echo wptc_widget_trac_homepage();

} else if (!empty($version)) { ?>

  <h2>Tickets for Version: <em><?php echo $version ?></em></h2>

  <?php 
    //$tickets = wptc_get_tickets_by_version($version);
    //echo wptc_widget_tickets_list($tickets, 'trac/ticket');
    global $current_blog;
    $blog_path = $current_blog->path;
    $query = "version=" . $version;
    $ticketsList = new WPTC_Ticket_List_Table($query, $blog_path,
                                              'trac/ticket');
    $ticketsList->prepare_items();
    $ticketsList->display();
} else if (!empty($milestone)) { ?>

  <h2>Tickets for Milestone: <em><?php echo $milestone ?></em></h2>

  <?php
    global $current_blog;
    $blog_path = $current_blog->path;
    $query = "milestone=" . $milestone;
    $ticketsList = new WPTC_Ticket_List_Table($query, $blog_path,
                                              'trac/ticket');
    $ticketsList->prepare_items();
    $ticketsList->display();
  ?>

<?php } ?>

  </div> <?php // END right_column ?>

<?php get_footer(); ?>
