<?php
/*
 * Template Name: Trac Client 0.1
 * 
 * this is a page template based on jQuery DataTable js lib.
 */

get_header(); 

wp_enqueue_script('jquery.dataTables');
wp_enqueue_style('jquery.dataTables');
?>

<div id="content">
<h2>Just a testing from </h2>

<?php echo wptc_view_tickets_dt("milestone="); ?>
</div>

<?php 
get_sidebar();
get_footer();
