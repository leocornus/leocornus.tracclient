<?php
// load context.
$pm_context = wptc_widget_pm_context();
?>

<div class='wrap'>

<?php
// handle submit
wptc_handle_pm_submit($pm_context);

switch($pm_context['action']) {
    case 'manageproject':
        wptc_widget_manage_project();
        break;
    case 'list':
    default:
        wptc_widget_projects_list();
        break;
}
?>

<?php
if (wptc_is_debug()) {
  global $wptc_db_version;
  //wptc_create_tables();
  $file = __FILE__;
  $path = MY_PLUGIN_PATH;
  $filename = basename(__FILE__);
  echo <<<EOT
  <p>$wptc_db_version</p>
  <p>file: $file, basename: $filename</p>
  <p>MY_PLUGIN_PATH: {$path}</p>
EOT;
}
?>  
</div>
