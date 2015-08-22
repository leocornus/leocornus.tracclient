/**
 * utility JavaScript functions for projects page.
 */

// create the ProjectRequestContext class.
// NOTE: this function itself will be the constructor for thi class.
function ProjectRequestContext() {

    // call the init method to construct the object.
    this.init();
} 

// using prototype to define those methods.
jQuery.extend(ProjectRequestContext.prototype, {

    init: function() {
        // get value from cookie...
        // NOTE: assume all variables will be set on server side.
    },

    // set the cookie:
    setState: function(name, value) {
        jQuery.cookie(name, value, {expires: 1});
    },

    // get the cookie
    getState: function(name) {

        var value = jQuery.cookie(name);
        if(name == 'per_page' || name == 'page_number'
           || name == 'total_items') {
            value = parseInt(value);
        }

        return value;
    },

    // get all states, return as an object.
    getStates: function() {

        var states = {
            'per_page' : this.getState('per_page'),
            'page_number' : this.getState('page_number'),
            'project' : this.getState('project'),
            'status' : this.getState('status'),
            'priority' : this.getState('priority'),
            'search_term' : this.getState('search_term'),
            'current_query' : this.getState('current_query')
        };

        return states;
    },

    // update cookies.
    updateCookies: function(states) {

        for(var name in states) {
            this.setState(name, states[name]);
        }
    }
});

/**
 * toggle the filter.
 */
function toggleFilter(filter, type) {

      //alert(this.id);
      // this is the dom element, it could be used as
      // a jQuery selector.
      var icon = jQuery(filter).children('span');
      if(icon.hasClass("glyphicon-check")) {
          icon.removeClass("glyphicon-check");
          icon.addClass("glyphicon-unchecked");
      } else if(icon.hasClass("glyphicon-unchecked")) {
          icon.removeClass("glyphicon-unchecked");
          icon.addClass("glyphicon-check");
      }

      // check all status check/unchecked.
      var newFilters= [];
      var selector = 'a[id^=' + type + '-]';
      jQuery.each(jQuery(selector), function(index, btn) {
          // index and btn jquery object.
          var check_icon = jQuery(btn).children('span');
          if(check_icon.hasClass("glyphicon-check")) {
              // button id will follow pattern: status-[STATUS NAME]
              var theFilter= btn.id.split('-')[1];
              //alert(the_status);
              newFilters.push(theFilter);
          }
      });
      // update cookie state "status"
      var context = new ProjectRequestContext();
      context.setState(type, newFilters.join());
}

// function to load more tickets.
function loadMoreTickets(scroll2Bottom) {

    // by default, NOT scroll to bottom.
    scroll2Bottom = typeof scroll2Bottom !== 'undefined' ?
                    scroll2Bottom : false;

    // get request context.
    var context = new ProjectRequestContext();

    // preparing the query data for AJAX request.
    var query_data = context.getStates();
    query_data['action'] = 'wptc_query_tickets';

    // update HTML page to indicate user the ruequest is going...
    // disable load more button and show waiting cursor.
    jQuery("a[id='project-load-more']").addClass('disabled');
    jQuery('html,body').css('cursor', 'wait');
    jQuery('a').css('cursor', 'wait');

    // AJAX request to get tickets of next page.
    // ajax_url is set by using wp_localize_script
    jQuery.post(wptc_projects.ajax_url, 
                query_data, function(response) {
        var res = JSON.parse(response);
        var items = res['items'];
        var states = res['states'];
        // update cookies based on the states.
        context.updateCookies(states);
        //console.log(items);
        // clean table if page_number < 1
        if (context.getState('page_number') < 1) {
            jQuery("table[id='project-items'] > tbody").html("");
        }
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
        var loaded_items = context.getState('per_page') * 
                           context.getState('page_number') + 
                           items.length;
        console.log("loaded items: " + loaded_items);
        // update the page number.
        context.setState('page_number', 
                         context.getState('page_number') + 1);
        // set total number for loaded items.
        var total_items = context.getState('total_items');
        jQuery("span[id='loaded-items']").html(loaded_items);
        jQuery("span[id='total-items']").html(total_items);

        // scroll down to page bottom.
        if(scroll2Bottom) { 
            jQuery('html,body').scrollTop(jQuery(window).height());
        }

        // reset cursor.
        jQuery('html,body').css('cursor', 'default');
        jQuery('a').css('cursor', 'default');

        if(loaded_items < total_items) {
            // only enable the load more if still more items to load.
            jQuery("a[id='project-load-more']").removeClass('disabled');
        }
    });

    // update table html.
    // caculate next page. update request context.
}

// utility function to build the panel for a project.
function buildProjectPanel(project) {

    var panel = '<div class="col-sm-4">' + 
        '<h2><a href="' + project['project_url'] + '">' +
        project['name'] + '</a></h2>' +
        '<p>' + 
          '<button type="button" class="btn btn-xs btn-danger">' + 
          '  <span class="badge">' + project['total_tickets'] + 
          '</span> Tickets' +
          '</button>' +
          ' ' +
          '<button type="button" class="btn btn-xs btn-primary">' + 
          '  <span class="badge">' + project['total_commits'] + 
          '</span> Commits' +
          '</button>' +
          ' ' +
          '<button type="button" class="btn btn-xs btn-success">' + 
          '  <span class="badge">' + project['total_contributors'] + 
          '</span> Contributors' +
          '</button>' +
        '</p>' +
        '<p>' + project['description'] + '</p>' + 
        '</div>';
    return panel;
}

// function to load more projects.
function loadMoreProjects(scroll2Bottom) {

    // by default, NOT scroll to bottom.
    scroll2Bottom = typeof scroll2Bottom !== 'undefined' ?
                    scroll2Bottom : false;

    // get request context.
    var context = new ProjectRequestContext();

    // preparing the query data for AJAX request.
    // we shall only have per_page and page_number for now.
    var query_data = context.getStates();
    query_data['action'] = 'wptc_projects';

    // update HTML page to indicate user the ruequest is going...
    // disable load more button and show waiting cursor.
    jQuery("a[id='projects-load-more']").addClass('disabled');
    jQuery('html,body').css('cursor', 'wait');
    jQuery('a').css('cursor', 'wait');

    // AJAX request to get tickets of next page.
    // ajax_url is set by using wp_localize_script
    jQuery.post(wptc_projects.ajax_url, 
                query_data, function(response) {
        var res = JSON.parse(response);
        var items = res['items'];
        var states = res['states'];
        // update cookies based on the states.
        context.updateCookies(states);
        //console.log(items);
        // clean table if page_number < 1
        if (context.getState('page_number') < 1) {
            jQuery("div[id='projects-list']").html("");
        }
        // append the projects row....
        // 1. find the last row.
        //var lastRow = jQuery("div#projects-list > div.row:last");
        var projectsList = jQuery("#projects-list");
        var colQueue = [];
        // 2. append 3 projects for each row.
        for(i = 0; i < items.length; i++) {
            var project = items[i];
            // append to table id = project-items.
            var panel = buildProjectPanel(project);
            colQueue.push(panel);
            var ready2Row = (i + 1) % 3;
            if(ready2Row == 0) {
                // append the div.row and reset the panel queue
                projectsList.append('<div class="row">' +
                    colQueue.join(" ") +
                    '</div>');
                // reset the queue.
                colQueue = [];
            }
        }
        // check if we missed anything...
        if(colQueue.length > 0) {

            // append to the last row.
            projectsList.append('<div class="row">' +
                colQueue.join(" ") +
                '</div>');
        }
        // calculate loaded item.
        var loaded_items = context.getState('per_page') * 
                           context.getState('page_number') + 
                           items.length;
        console.log("loaded items: " + loaded_items);
        // update the page number.
        context.setState('page_number', 
                         context.getState('page_number') + 1);
        // set total number for loaded items.
        var total_items = context.getState('total_items');
        jQuery("span[id='loaded-items']").html(loaded_items);
        jQuery("span[id='total-items']").html(total_items);

        // scroll down to page bottom.
        if(scroll2Bottom) { 
            jQuery('html,body').scrollTop(jQuery(window).height());
        }
        // reset cursor.
        jQuery('html,body').css('cursor', 'default');
        jQuery('a').css('cursor', 'default');

        if(loaded_items < total_items) {
            // only enable the load more if still more items to load.
            jQuery("a[id='projects-load-more']").removeClass('disabled');
        }
    });
}

// add the click event on load more button.
jQuery(function($) {

  // get request context.
  var context = new ProjectRequestContext();
  var projectName = context.getState('project');
  //console.log('Project Name: ' + projectName);
  if(typeof projectName == 'undefined') {
    // load homepage for all projects.
    loadMoreProjects();
  } else {
    // load the homepage for a project..
    loadMoreTickets();
  }

  $('#project-load-more').click(function(event) {
      // prevent the default herf link event for this button.
      event.preventDefault();
      // load more when user click the button.
      loadMoreTickets(true);
  });

  $('#projects-load-more').click(function(event) {
      // prevent the default herf link event for this button.
      event.preventDefault();
      // load more when user click the button.
      loadMoreProjects(true);
  });

  // handle the click event for all status button.
  // using the starts with pattern selector.
  $('a[id^=status-]').click(function(event) {

      toggleFilter(this, 'status');
      // load tickets again, start over by reset everything.
      loadMoreTickets();
  });

  $('a[id^=priority-]').click(function(event) {

      toggleFilter(this, 'priority');
      // load tickets again, start over by reset everything.
      loadMoreTickets();
  });

  // keyboard keypress event for the project search input box.
  $('#project-search').keyup(function(event) {
      //console.log(event);
      // get what user is typing
      var term = $(this).val();
      if(term.length > 2 || term.length ==0) {
          var context = new ProjectRequestContext();
          context.setState('search_term', term);
          loadMoreProjects();
      }
  });

  // keyboard keypress event for the ticket search input box.
  $('#ticket-search').keyup(function(event) {
      //console.log(event);
      // get what user is typing
      var term = $(this).val();
      if(term.length > 1 || term.length ==0) {
          var context = new ProjectRequestContext();
          context.setState('search_term', term);
          loadMoreTickets();
      }
  });
});
