<?php
/**
 * Template Name: Trac Homepage
 */
add_action('wp_enqueue_scripts', 'wptc_enqueue_project_resources');
// initializing to not include cookie
//$context = wptc_context_factory();
$factory = new Wptc\Helper\ViewFactory();
$context = $factory->createContext();
$context->setCookieStates(3600);
?>
<html>
<head>
  <?php wp_head();?>
</head>
<body>
  <div class="container">
    <?php
    $factory->generateView($context);
    ?>
  </div> <!-- container -->

  <?php 
  // wp footer will bring the admin bar.
  wp_footer(); 
  ?>
</body>
</html>
