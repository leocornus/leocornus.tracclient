<?php
/*
 * Template Name: Trac Client
 */
?>

<?php 
get_header(); 
wp_enqueue_script('jquery.dataTables');
wp_enqueue_style('jquery.dataTables');
wp_enqueue_script('jquery-ui-dialog');
wp_enqueue_style('jquery-ui');
?>

<script type="text/javascript" charset="utf-8">
<!--
function ticketDetails(event) {

    var ticketId = event.data.ticketId;
    alert(ticketId);
    jQuery("#ticketDetail").html("<strong>Loading</strong>");

    var data = {
        "action" : "wptc_get_ticket_cb",
        "id" : ticketId,
    };

    jQuery.post('wp-admin/admin-ajax.php', data, function(response) {

        alert (response);

        jQuery("#ticketDetail").html('<strong>Here is ticket: <br/>' + response + '</strong>');
    });
}

jQuery(document).ready(function() {
    jQuery('#tickets').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        // trun off the length change drop down.
        "bLengthChange" : false,
        // turn off filter.
        "bFilter" : false,
        // turn off sorting.
        "bSort" : false,
        "iDisplayLength" : 25,
        "sAjaxSource": "<?php echo admin_url('admin-ajax.php'); ?>",
        "sServerMethod" : "POST",
        "fnServerParams" : function (aoData) {
            aoData.push(
                {"name" : "action", "value" : "wptc_get_tickets_cb"}
            );
        },
        "fnRowCallback" : function (nRow, aData, iDisplayIndex) {
            var column = jQuery('td:eq(0)', nRow);
            var href = jQuery('a', column);
            var id = href.html();
            //alert('id=' + id);
            // this is the event handler way, we can pass
            // some data to the handler function.
            href.on("clickA", {ticketId: id}, ticketDetails);
            href.on("click", function() {
                var ticketId = jQuery(this).html();
                //alert (ticketId);
                jQuery("#ticketDetail").html("<strong>Loading Ticket #" + ticketId + " ...</strong>");
                // open the dialog first.
                jQuery('#mydialog').dialog("open");

                var data = {
                    "action" : "wptc_get_ticket_cb",
                    "id" : ticketId,
                };

                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
                    //alert(response);
                    jQuery("#ticketDetail").html('<strong>Here is ticket: <br/>' + response + '</strong>');
                });
            });
        }
    } );

    jQuery('#mydialog').dialog({
        autoOpen: false,
        height: 300,
        width: 450,
        modal: true,
        buttons: {
            "Save" : function() {
                jQuery(this).dialog("close");
            }
        }
    });

    jQuery("#testDialog").button().click(function() {
        jQuery("#mydialog").dialog("open");
        return false;
    });
} );

jQuery("a[name^='ticket-']").click(function() {
    alert('id=' + 123);
});
-->
</script>

  <h2>Just a testing</h2>

  <p>
  <table cellpadding="0" cellspacing="0" border="0" id="tickets">
  <thead><th width="18px">ID</th><th>Summary</th><th width="68px">Owner</th><th width="58px">Priority</th><th width="58px">Status</th></thead>
  <tbody>
    <tr>
      <td colspan="4" class="dataTables_empty">Loading data from server</td>
    </tr>
  </tbody>
  <tfoot><th>ID</th><th>Summary</th><th>Owner</th><th>Priority</th><th>Status</th></tfoot>
  </table>
  </p>

<p></p>

<p><input type="button" onclick="javascript: showVersions()" name="versions" value="versions"/></p>

<script type="text/javascript" charset="utf-8">
<!--
function showVersions() {

    var data = {
        "action" : 'wptc_get_tickets_cb',
    };

    jQuery.post('wp-admin/admin-ajax.php', data, function(response) {

        alert('got from server: ' + response);
    });
}
-->
</script>

<p><button id="testDialog" onclick="javascript: ticketDetails(12)">Test Dialog</button><br/>
</p>

<div id="mydialog" title="For Testing">
  <h3>Ticket Summary</h3>
  <div id="ticketDetail">
    details about the ticket.
  </div>
</div>

  <pre>
<?php
//var_dump(wptc_get_ticket(525));
?>
  </pre>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
