<?php
/*
 * Template Name: Trac Ticket Details
 * Description: a page to show the details for a ticket.
 */
get_header();
wp_enqueue_style('wptc-trac-ticket');
wp_enqueue_script('wptc-trac-wikitoolbar');
$ticket_id = $_GET['id'];
// TODO:
// what if there is not id specified? create new ticket?
?>

</div>

  <div id="left_column">
    <div class='leftnav'>
      <div class='widget'>
      <h2 class='widgettitle'>Sprint Navigation</h2>
      <?php echo wptc_widget_version_nav()?>
      </div>
    </div>
  </div>
 
  <div id="right_column">

    <?php wptc_widget_ticket_details($ticket_id); ?>

<?php
// =========================================================
// debug message...
$DEBUG = True;
if ($DEBUG) {
    global $post, $current_blog;
    // dump the change log 
    $ticket = wptc_get_ticket_actions($ticket_id);
    echo '<pre>';
    var_dump($ticket);
    echo '</pre>';

    $parent_post = get_page($post->post_parent);
    echo <<<EOT
    <p>get to know current page:</p>
    <pre>
    REQUEST_URI: {$_SERVER['REQUEST_URI']}<br/>
    Request args: 
    Current Blog's Path: {$current_blog->path}<br/>
    Current Page's URL: {$_SERVER['PHP_SELF']}<br/>
    Current Page's slug (post_name): {$post->post_name}<br/>
    Current page's parent page ID (post_parent): {$post->post_parent}<br/>
    Current page's parent page slug: {$parent_post->post_name}
    </pre>
EOT;
// end debuging
// ==========================================================
}
?>
  </div>

<?php get_footer(); ?>
