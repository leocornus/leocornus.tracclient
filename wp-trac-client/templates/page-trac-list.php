<?php
/**
 * Template Name: Trac Tickets List
 */

/**
 * This template's file name is following the pattern page-{slug}.php.
 * So WordPress will using this templage to render the page
 * who has the slug called `trac-list`.
 */
?>

<?php 
get_header(); 
wp_enqueue_script('jquery.dataTables');
wp_enqueue_style('jquery.dataTables');
// we should use a dedicated header, whick will load all additional js libs.
?>

<div id="right_column">

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#tickets').dataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo admin_url('admin-ajax.php'); ?>",
            "sServerMethod" : "POST",
            "fnServerParams" : function (aoData) {
                aoData.push(
                    {"name" : "action", "value" : "tickets_list"}
                );
            }
        } );
    } );
</script>

  <h2>Just a testing</h2>

  <table cellpadding="0" cellspacing="0" border="0" id="tickets">
  <thead><th>ID</th><th>Summary</th><th>Priority</th><th>Status</th></thead>
  <tbody>
    <tr>
      <td colspan="4" class="dataTables_empty">Loading data from server</td>
    </tr>
  <tfoot><th>ID</th><th>Summary</th><th>Priority</th><th>Status</th></tfoot>
  </tbody>
  </table>

<p><input type="button" onclick="javascript: showVersions()" name="versions" value="versions"/></p>

<script type="text/javascript" charset="utf-8">
<!--
function showVersions() {

    var data = {
        "action" : 'tickets_list',
    };

    jQuery.post('wp-admin/admin-ajax.php', data, function(response) {

        alert('got from server: ' + response);
    });
}
-->
</script>

  <pre>
<?php
//var_dump(wptc_get_ticket(525));
?>
  </pre>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
