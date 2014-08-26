<?php

/**
 * Template Name: Angular Testing Page
 */

get_header();
wp_enqueue_script('wptc-angular-core');
?>

<script type="text/javascript">
jQuery('html').attr('ng-app', '');
</script>

<div>
  <label>Name:</label>
  <input type="text" ng-model="yourName" 
         placeholder="Enter a name here">
  <hr>
  <h1>Hello {{yourName}}!</h1>
</div>

<?php
get_footer();
