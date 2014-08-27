<?php
/*
 * Template Name: Trac My Watchlist 
 * Description: a page template to show a list of tickets 
 * that current user is watching.
 */
?>

<?php 
get_header(); 
wp_enqueue_style('wptc-css');
wp_enqueue_script('jquery.dataTables');
wp_enqueue_style('jquery.dataTables');

// get the user
$owner = $_GET['owner'];
if(empty($owner)) {
    $current_user = wp_get_current_user();
    $owner = $current_user->user_login;
    $owner_email = $current_user->user_email;
} else {
    $user = get_user_by('login', $owner);
    $owner_email = $user->user_email;
}
// include closed or not
$include_closed = $_GET['includeClosed'];
if(!empty($include_closed) && 
   strtolower($include_closed) === "true" ) {
    $query = "cc~={$owner_email}";
    $checked = "checked";
} else {
    $query = "cc~={$owner_email}" . '&status!=closed';
    $checked = "";
}
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

  <h2>Tickets in My Watchlist</h2>

  <div style="float: right">
    <input type="hidden" id="owner" value="<?php echo $owner;?>"/>
    <input type="checkbox" id="includeClosed" 
        <?php echo $checked;?>
    />
    Include Closed Tickets
    <script type="text/javascript" charset="utf-8">
    <!--
      jQuery('#includeClosed').on("click", function() {
          var owner = jQuery('#owner').val();
          var checked = jQuery(this).attr('checked');
          var local = jQuery(location);
          var params = {};
          if(checked == 'checked') {
              params['includeClosed'] = 'true';
          }
          if(owner.length > 0) {
              params['owner'] = owner;
          }
          var newHref = local.attr('protocol') + "://" + 
                        local.attr('host') + 
                        local.attr('pathname') + "?" +
                        jQuery.param(params);
          window.location = newHref;
 
      });
    -->
    </script>
  </div>

  <?php 
    echo wptc_view_mytickets_dt($query);
  ?>

  </div> <?php // END right_column ?>

<?php get_footer(); ?>
