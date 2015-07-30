<?php
/**
 * Template Name: Trac Homepage
 */
add_action('wp_enqueue_scripts', 'wptc_enqueue_project_resources');
// initializing to not include cookie
$context = new Wptc\RequestContext(false);
?>
<html>
<head>
  <?php wp_head();?>
</head>
<body>
  <div class="container">
    <?php
    echo wptc_view_project_header($context);
    echo wptc_view_project_nav($context);
    echo wptc_view_project_content($context);
    echo wptc_view_project_footer($context);
    ?>

    <div class="well" id="local-project-footer">
      <div class="row">
        <div class="col-sm-4">
          <h2>Column 1</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
          <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
        </div>
        <div class="col-sm-4">
          <h2>Column 2</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
          <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
        </div>
        <div class="col-sm-4">
          <h2>Column 3</h2> 
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
          <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
        </div>
      </div>
    </div> <!-- local-project-footer -->
  </div> <!-- container -->

  <?php 
  // wp footer will bring the admin bar.
  wp_footer(); 
  ?>
</body>
</html>
