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
        var cookieName = 'wptc_' + name;
        jQuery.cookie(cookieName, value, {expires: 1});
    },

    // get the cookie
    getState: function(name) {

        var cookieName = 'wptc_' + name;
        var value = jQuery.cookie(cookieName);
        if(name == 'per_page' || name == 'page_number'
           || name == 'total_items') {
            value = parseInt(value);
        }

        return value;
    },

    // get all states, return as an object.
    getStates: function() {

        var states = {
            'tab' : this.getState('tab'),
            'per_page' : this.getState('per_page'),
            'page_number' : this.getState('page_number'),
            'order' : this.getState('order'),
            'project' : this.getState('project'),
            'status' : this.getState('status'),
            'priority' : this.getState('priority'),
            'search_term' : this.getState('search_term'),
            'current_query' : this.getState('current_query'),
            'repo_path' : this.getState('repo_path')
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
      // the default separator for join method is ','
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
              '<td>' + log['modified_age'] + '</td>' +
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

// function to load more tickets.
function loadMoreCommits(scroll2Bottom) {

    // by default, NOT scroll to bottom.
    scroll2Bottom = typeof scroll2Bottom !== 'undefined' ?
                    scroll2Bottom : false;

    // get request context.
    var context = new ProjectRequestContext();

    // preparing the query data for AJAX request.
    var query_data = context.getStates();
    query_data['action'] = 'wptc_get_log_list';

    // update HTML page to indicate user the ruequest is going...
    // disable load more button and show waiting cursor.
    jQuery("a[id='load-more-commits']").addClass('disabled');
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
        // append to table id = project-items.
        var projectName = context.getState('project');
        var tbody = jQuery("table[id='project-items'] > tbody:last");
        for(i = 0; i < items.length; i++) {
            var log = items[i];

            // we need decide show download column or not!
            // only show the download column for project page.
            var colspan = 3;
            var downloadTD = '';
            if ((typeof projectName == 'undefined') ||
                (projectName == '')) {
                // now project name, it is in all commits page.
                // we will using the default colspan and
                // download TD.
            } else {
                colspan = 4;
                downloadTD = 
                  '<td><button class="btn btn-sm btn-warning" id="download-' + 
                  log['id'] + 
                  '"><span class="text-primary glyphicon glyphicon-download"></span></button></td>';
            }

            // get the current date from the last 
            // tr[id='logdate']:last > td:last
            var lastLogDate = jQuery("table[id='project-items'] > tbody > tr[id='logdate']:last > td:last > span:last");
            var currentDate = null;
            if (lastLogDate != null) {
                // get the current date. the last log date.
                currentDate = lastLogDate.html();
            }

            if ((currentDate != null) && 
                (currentDate == log['date'])) {
                // still in the save date, do nothing!
            } else {
               // this will be the first logdate or a new date.
               // append an extra log date row.
               tbody.append('<tr id="logdate">' +
                 '<td colspan="' + colspan + 
                      '" class="h5 bg-info text-muted">' + 
                 '<span class="text-primary glyphicon glyphicon-list"></span> Commits on ' + 
                 '<span>' + log['date'] + '</span></td>' +
                 '</tr>');
            }

            // append the log info.
            tbody.append('<tr id="log">' +
              '<td><a href="' + 
                log['url'] + '">' + log['id'] + "</a></td>" +
              '<td>' + log['comment'] + '</td>' +
              '<td>' + log['email'] + '</td>' +
              downloadTD +
              //'<td id="uat-' + log['id'] + 
              //  '"><span></span></td>' +
              //'<td id="prod-' + log['id'] + 
              //  '"><span></span></td>' +
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
            jQuery("a[id='load-more-commits']").removeClass('disabled');
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

/**
 * Project name input field, check project name available or not.
 *
 * @param thename the name input jQuery object.
 */
function projectNameValidate(thename) {

    // query the project name from database.
    // name exist, show the error feedback
    var iconhtml = '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';
    // if not available, toggle the error style.
    var parentDivs = thename.parentsUntil('fieldset');
    // for input column.
    jQuery(parentDivs[0]).append(iconhtml);
    // for the form group div.
    jQuery(parentDivs[1]).addClass('has-feedback has-error');

    // otherwise show the success feedback.
}

/**
 * load the sprint panel for the project homepage.
 */
function loadProjectSprints() {

    // find all sprint panel by search pattern for id
    // sprint-[SPRINT NAME] and class is panel.
    var sprintSelector = 'div[id^=sprint-].panel';
    jQuery.each(jQuery(sprintSelector), function(index, panel) {
        var sprintName = panel.id.split('sprint-')[1];
        //alert(sprintName);
        loadSprintPanel(sprintName);
    });
}

function loadSprintPanel(sprintName) {

    // load the backlog first.
    // will use the context states plus the backlog special word.
    var context = new ProjectRequestContext();
    var query_data = context.getStates();
    //alert(query_data['project']);
    query_data['per_page'] = -1;
    // this will be one time query.
    query_data['action'] = 'wptc_query_tickets';
    // decide the version, sprint name will be the version,
    query_data['version'] = sprintName;

    // set cursor to waiting.

    // send request.
    jQuery.post(wptc_projects.ajax_url,
                query_data, function(response) {
        var res = JSON.parse(response);
        var items = res['items'];
        var states = res['states'];
        //console.log(states);
        // finteh backlog panel.
        var selectId = "div[id='sprint-" + sprintName + 
                            "-list-group']";
        var listgroup = jQuery(selectId);
        // set the sprint summary.
        var summary = items.length + " tickets";
        selectId = "span[id='sprint-" + sprintName + "-summary']"
        jQuery(selectId).html(summary);
        // ticket list group.
        for(i = 0; i < items.length; i++) {
            var ticket = items[i];
            // find the full name of owner
            var ownerHref = ticket['owner_href'];
            var ownerName = 
              ownerHref.substring(ownerHref.indexOf('>') + 1, 
                                  ownerHref.indexOf('</a>'));
            // priority label, 
            // decide the color based on the priority
            switch(ticket['priority']) {
            case 'blocker':
            case 'critical':
                labelColor = 'label-danger';
                break;
            case 'major':
                labelColor = 'label-warning';
                break;
            case 'minor':
            case 'trivial':
                labelColor = 'label-default';
                break;
            default: // minor and trival
                ticket['priority'] = 'NONE';
                labelColor = 'label-default';
                break;
            }
            var pLabel = '<span class="label ' + labelColor +
                '">' + ticket['priority'] + '</span>';
            // status label
            if (ticket['status'] == 'closed') {
                labelColor = 'label-success';
            } else {
                labelColor = 'label-primary';
            }
            var sLabel = '<span class="label ' + labelColor +
                '">' + ticket['status'] + '</span>';
            listgroup.append('<a href="' + ticket['ticket_url'] +
              '" class="list-group-item clearfix bg-primary">' +
              '<span class="badge">' + ticket['id'] + '</span>' +
              '#' + ticket['id'] + ' ' + ticket['summary'] + 
              '<br/>' + 
              '<div class="pull-right">' +
              pLabel + ' ' + sLabel + ' ' + 
              '<span class="label label-info">' + ownerName + '</span>' +
              '</div></a>');
        }
    });
}

// add the click event on load more button.
jQuery(document).ready(function($) {

  // get request context.
  var context = new ProjectRequestContext();
  var projectName = context.getState('project');
  var tabName = context.getState('tab');
  //console.log('Project Name: ' + projectName);
  if((typeof projectName == 'undefined') &&  
     (typeof tabName == 'undefined')) {
          // load homepage for all projects.
          loadMoreProjects();
  } else {
      if ((typeof tabName) == 'undefined') {
          // project homepage, load sprints.
          loadProjectSprints();
      } else {
          switch(tabName) {
          case "tickets":
              loadMoreTickets();
              break;
          case "commits":
              loadMoreCommits();
              break;
          }
      }
  }

  $('#project-load-more').click(function(event) {
      // prevent the default herf link event for this button.
      event.preventDefault();
      // load more when user click the button.
      loadMoreTickets(true);
  });

  $('#load-more-commits').click(function(event) {
      // prevent the default herf link event for this button.
      event.preventDefault();
      // load more when user click the button.
      loadMoreCommits(true);
  });

  $('#projects-load-more').click(function(event) {
      // prevent the default herf link event for this button.
      event.preventDefault();
      // load more when user click the button.
      loadMoreProjects(true);
  });

  // the sorting order.
  $('#order').change(function(event) {
      var order = $(this).val();
      context.setState('order', order);
      loadMoreTickets();
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

  // validate the project name.
  $('#inputName').blur(function(event) {
      var projectName = $(this).val();
      // check if the project name is still available?
      // if not available, toggle the error style.
      var parentDivs = $(this).parentsUntil('fieldset');
      // for input column.
      $(parentDivs[0]).append('<span class="glyphicon glyphicon-refresh form-control-feedback"></span>');
      // for the form group div.
      $(parentDivs[1]).addClass('has-feedback');
      //$(parentDivs[1]).addClass('has-error');
      $(parentDivs[1]).addClass('has-success');
  });

  // the download icon.
  $('tbody').on('click', 'button[id^=download-]', function(event) {
      // this will be the button which is clicked.
      var commitId = this.id;
      var ids = commitId.split('-');
      var context = new ProjectRequestContext();
      var path = context.getState('repo_path');
      // call dowload function.
      downloadGitArchive(path, ids[1]);
  });

  // list group sprint panel list group click.
  $('div.panel').on('click', 'a[id$=status-closed]', 
                    function(event) {
      //alert(this.id);
      // find the sprint name.
      var sprintName = 
          this.id.substring(this.id.indexOf('sprint-') + 7, 
                            this.id.indexOf('-status-closed'));
      console.log('Spring Name: ' + sprintName);
      // toggle the check and uncheck icon.
      var icon = jQuery(this).children('span');
      if(icon.hasClass("glyphicon-check")) {
          icon.removeClass("glyphicon-check");
          icon.addClass("glyphicon-unchecked");
      } else if(icon.hasClass("glyphicon-unchecked")) {
          icon.removeClass("glyphicon-unchecked");
          icon.addClass("glyphicon-check");
      }
      // check the status of check and uncheck.
      console.log(icon.hasClass("glyphicon-check"));
      var context = new ProjectRequestContext();

      if(icon.hasClass('glyphicon-check')) {
          // show closed tickets.
          context.setState('status', 
                           'accepted,assigned,closed,new,reopened');
      } else {
          context.setState('status',
                           'accepted,assigned,new,reopened');
      }

      // reload the list-group again.
      // clean the list group
      jQuery('#sprint-' + sprintName + '-list-group').html('');
      // load again.
      loadSprintPanel(sprintName);
  });
});

// download commit as zip file.
function downloadGitArchive(path, commit) {

    //jQuery("[id='ect-load-more']").addClass('disabled');
    jQuery('html,body').css('cursor', 'wait');
    jQuery('button').css('cursor', 'wait');
    jQuery('a').css('cursor', 'wait');

    // query data has to be a object.
    var query_data = {};
    query_data['action'] = 'wptc_git_archive';
    query_data['repo_path'] = path;
    query_data['commit_id'] = commit;
    jQuery.post(wptc_projects.ajax_url,
                query_data, function(resp) {
        var res = JSON.parse(resp);
        var url = res['download_url'];
        window.location=url;
        // reset cursor.
       jQuery('html,body').css('cursor', 'default');
       jQuery('button').css('cursor', 'default');
       jQuery('a').css('cursor', 'default');
   });
}
