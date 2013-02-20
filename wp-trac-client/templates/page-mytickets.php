<?php
/*
 * Template Name: Trac My Tickets
 * Description: a page templage to show a list of tickets for 
 * current user.
 */
?>

<?php 
get_header(); 
wp_enqueue_style('wptc-trac-ticket');
global $current_user;
?>

</div>

  <div id="left_column">
    <div class='leftnav'>
      <div id='ticket-finder' class="widget">
        <h2 class='widgettitle'>Ticket Finder</h2>
        <?php echo wptc_widget_ticket_finder('trac/ticket')?>
      </div>
    </div>
  </div> <?php // END left_column ?>

  <div id="right_column">

  <h2>Tickets I am working on ...</h2>

  <?php 
    $tickets = wptc_get_tickets_by_owner($current_user->user_login);
    echo wptc_widget_tickets_list($tickets, 'trac/ticket');
  ?>

  </div> <?php // END right_column ?>

<?php get_footer(); ?>
