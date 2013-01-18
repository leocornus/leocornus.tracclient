<?php
/*
 * Template Name: Trac Tickets
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

/**
 * call back function to load details info for a ticket.
 * ticket id will pass through the event.
 */
function ticketDetails(event) {

    var ticketId = event.data.ticketId;
    //alert(ticketId);
    jQuery("#ticketDetail").html("<strong>Loading #" + ticketId + " ...</strong>");
    jQuery('#mydialog').dialog("open");

    var data = {
        "action" : "wptc_get_ticket_cb",
        "id" : ticketId,
    };

    jQuery.post('wp-admin/admin-ajax.php', data, function(response) {
 
        // the response is in JSON format, parse it to objects.
        res = JSON.parse(response);
        alert ('Object Count: ' + res.length);

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
        "iDisplayLength" : 15,
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
            href.on("click", {ticketId: id}, ticketDetails);
            href.on("clickA", function() {
                var ticketId = jQuery(this).html();
                //alert (ticketId);
                // set the message on the blank page.
                jQuery("#ticketDetail").html("<strong>Loading Ticket #" + ticketId + " ...</strong>");
                // open the dialog first.
                jQuery('#mydialog').dialog("open");

                // get ready the post data.
                var data = {
                    // the call back action.
                    "action" : "wptc_get_ticket_cb",
                    "id" : ticketId,
                };

                // send the AJAX request.
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

  <h2>Just a testing from </h2>

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

<?php // the dialog to show details about a ticket. ?>
<div id="mydialog" title="For Testing">
  <h3>Ticket Summary</h3>
  <div id="ticketDetail">
    
    details about the ticket.
    <?php // ticket view start?>
<div id="ticket">
  <div class="date">
    <p>Opened <a class="timeline" href="/trac/egov_opspedia-search/timeline?from=2012-10-22T10%3A58%3A26-04%3A00&amp;precision=second" title="2012-10-22T10:58:26-04:00 in Timeline">4 days</a> ago</p>
  </div>
  <h2 class="summary searchable">Upgrade Solr to 4.0</h2>
  <table class="properties">
    <tbody><tr>
      <th id="h_reporter">Reported by:</th>
      <td headers="h_reporter" class="searchable">
        <a href="/trac/egov_opspedia-search/query?status=!closed&amp;reporter=tomsommerville">tomsommerville</a>
      </td>
      <th id="h_owner">Owned by:</th>
      <td headers="h_owner">
        <a href="/trac/egov_opspedia-search/query?status=!closed&amp;owner=seanchen">seanchen</a>
      </td>
    </tr>
    <tr>
        <th id="h_priority">
          Priority:
        </th>
        <td headers="h_priority">
              <a href="/trac/egov_opspedia-search/query?status=!closed&amp;priority=major">major</a>
        </td>
        <th id="h_milestone">
          Milestone:
        </th>
        <td headers="h_milestone">
              <a class="milestone" href="/trac/egov_opspedia-search/milestone/Search%200.1">Search 0.1</a>
        </td>
    </tr><tr>
        <th id="h_component">
          Component:
        </th>
        <td headers="h_component">
              <a href="/trac/egov_opspedia-search/query?status=!closed&amp;component=Search+Index">Search Index</a>
        </td>
        <th id="h_version">
          Version:
        </th>
        <td headers="h_version">
              <a href="/trac/egov_opspedia-search/query?status=!closed&amp;version=Sprint+2">Sprint 2</a>
        </td>
    </tr><tr>
        <th id="h_keywords">
          Keywords:
        </th>
        <td headers="h_keywords" class="searchable">
        </td>
        <th id="h_cc">
          Cc:
        </th>
        <td headers="h_cc" class="searchable">
        </td>
    </tr>
  </tbody></table>
  <div class="description">
    <h3 id="comment:description">
      Description
    <a class="anchor" href="#comment:description" title="Link to this section"> ¶</a></h3>
    <br style="clear: both">
  </div>
</div>
    <?php // ticket view end ?>
  </div>
</div>

  <pre>
<?php
//var_dump(wptc_get_ticket(525));
?>
  </pre>

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
