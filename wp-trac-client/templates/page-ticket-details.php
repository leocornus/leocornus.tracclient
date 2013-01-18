<?php
/*
 * Template Name: Trac Ticket Details
 * Description: a page to show the details for a ticket.
 */

$DEBUG = False;

get_header();
wp_enqueue_style('wptc-trac-ticket');

// get parent page's slug
$parent_slug = get_page($post->post_parent)->post_name;
$ticket_id = $_GET['id'];

if ($DEBUG) {
    $ticket = wptc_get_ticket_changelog($ticket_id);
    echo '<pre>';
    var_dump($ticket);
    echo '</pre>';
}
?>

<div id="content" class="column-wapper">
  <div id="left_column">
    <div id='sprint-nav' class='leftnav'>
      <div class='photocap nav_top grey'>&nbsp;</div>
      <h2 class='widgettitle'>Sprint Navigation</h2>
      <?php wptc_sprint_nav()?>
    </div>
  </div>

  <div id="right_column">

    <?php echo wptc_widget_ticket_info($ticket_id); ?>
    <?php echo wptc_widget_ticket_changelog($ticket_id); ?>

<?php
if ($DEBUG) {
    $parent_post = get_page($post->post_parent);
    echo <<<EOT
    <p>get to know current page:</p>
    <pre>
    REQUEST_URI: {$_SERVER['REQUEST_URI']}<br/>
    Request args: 
    Current Page's slug (post_name): {$post->post_name}<br/>
    Current page's parent page ID (post_parent): {$post->post_parent}<br/>
    Current page's parent page slug: {$parent_post->post_name}
    </pre>
EOT;
}
?>
  </div>
</div> <!-- // END content -->

<?php get_footer(); ?>
