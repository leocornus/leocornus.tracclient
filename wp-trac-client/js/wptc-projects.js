/**
 * utility JavaScript functions for projects page.
 */

// create the ProjectRequestContext class.
// NOTE; this function itself will be the constructor!
function ProjectRequestContext() {

    // call the init method to construct the object.
    this.init();
} 

// using prototype to define those methods.
jQuery.extend(ProjectRequestContext.prototype, {

    init: function() {
        // get value from cookie...
        // NOTE: assume all variables will be set on server side.

        // Project metadata.
        this.project = jQuery.cookie('project');
        this.milestone = jQuery.cookie('milestone');
        this.version = jQuery.cookie('version');

        // pagination information.
        this.per_page = parseInt(jQuery.cookie('per_page'));
        // page number, starts from 0.
        this.page_number = parseInt(jQuery.cookie('page_number'));
        this.total_items = parseInt(jQuery.cookie('total_items'));
    },

    // set the cookie:
    set: function(name, value) {
        jQuery.cookie(name, value, {expires: 1});
    }
});

// function to load more tickets.
function loadMoreTickets() {

    // get request context.
    var context = new ProjectRequestContext();
    var per_page = context.per_page;
    var page_number = context.page_number;
    var total_items = context.total_items;

    // preparing the query data for AJAX request.
    var query_data = {
        'action' : 'wptc_query_tickets',
        'per_page' : per_page,
        'page_number' : page_number,
        'project' : context.project
    };
    // update HTML page to indicate user the ruequest is going...
    // disable load more button and show waiting cursor.
    jQuery("a[id='project-load-more']").addClass('disabled');
    jQuery('html,body').css('cursor', 'wait');
    jQuery('a').css('cursor', 'wait');

    // AJAX request to get tickets of next page.
    // ajax_url is set by using wp_localize_script
    jQuery.post(wptc_projects.ajax_url, 
                query_data, function(response) {
        var items = JSON.parse(response);
        //console.log(items);
        // append the ticket list table.
        for(i = 0; i < items.length; i++) {
            var log = items[i];
            // append to table id = project-items.
            var last = jQuery("table[id='project-items'] > tbody:last");
            last.append('<tr id="ticket">' +
              '<td><a href="' + log['ticket_url'] + '">' + 
              log['id'] + "</a></td>" +
              '<td><a href="' + log['ticket_url'] + '">' + 
              log['summary'] + "</a></td>" +
              '<td>' + log['owner_href'] + '</td>' +
              '<td>' + log['priority'] + '</td>' +
              '<td>' + log['status'] + '</td>' +
            '</tr>');
        }
        // calculate loaded item.
        var loaded_items = per_page * page_number + items.length;
        console.log("loaded items: " + loaded_items);
        // set total number for loaded items.
        jQuery("span[id='loaded-items']").html(loaded_items);
        jQuery("span[id='total-items']").html(total_items);

        // scroll down to page bottom.
        jQuery('html,body').scrollTop(jQuery(window).height());

        // reset cursor.
        jQuery('html,body').css('cursor', 'default');
        jQuery('a').css('cursor', 'default');

        if(loaded_items < total_items) {
            // only enable the load more if still more items to load.
            jQuery("a[id='project-load-more']").removeClass('disabled');
        }
    });
    // update the page number.
    context.set('page_number', page_number + 1);

    // update table html.
    // caculate next page. update request context.
}

// add the click event on load more button.
jQuery(function($) {

  // get started.
  loadMoreTickets();

  $('#project-load-more').click(function(event) {
      // prevent the default herf link event for this button.
      event.preventDefault();
      // load more when user click the button.
      loadMoreTickets();
  });

  // handle the click event for all status button.
  // using the starts with pattern selector.
  $('a[id^=status-]').click(function(event) {
      //alert(this.id);
      // this is the dom element, it could be used as
      // a jQuery selector.
      var icon = $(this).children('span');
      if(icon.hasClass("glyphicon-check")) {
          icon.removeClass("glyphicon-check");
          icon.addClass("glyphicon-unchecked");
      } else if(icon.hasClass("glyphicon-unchecked")) {
          icon.removeClass("glyphicon-unchecked");
          icon.addClass("glyphicon-check");
      }
      // check all status check/unchecked.
      // update cookie state "status"
      // load tickets again, start over by reset everything.
  });
});
