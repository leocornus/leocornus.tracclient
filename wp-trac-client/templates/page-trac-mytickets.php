<?php
/*
 * Template Name: Trac My Tickets
 * Description: a page templage to show a list of tickets for 
 * current user.
 */
?>

<?php 
get_header(); 
wp_enqueue_style('wptc-css');
wp_enqueue_script('jquery.dataTables');
wp_enqueue_style('jquery.dataTables');
?>

  <div id="left_column">
    <div class='leftnav'>
      <div id='ticket-finder' class="widget">
        <h2 class='widgettitle'>Ticket Toolbar</h2>
        <?php echo wptc_widget_trac_toolbar('trac/ticket')?>
      </div>
    </div>
  </div> <?php // END left_column ?>

  <div id="content">

  <h2>Tickets I am working on ...</h2>

  <?php 
    $current_user = wp_get_current_user();
    $query = 'owner=' . $current_user->user_login . 
             '&status!=closed';
    echo wptc_view_tickets_dt($query);
  ?>

  </div> <?php // END right_column ?>

<?php get_footer(); ?>
