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
        this.per_page = jQuery.cookie('per_page');
        this.page_number = jQuery.cookie('page_number');
    }
});

// function to load more tickets.
function loadMoreTickets() {

    // get request context.
    var context = new ProjectRequestContext();
    var per_page = context.per_page
    var page_number = context.page_number

    alert(per_page + " " + page_number);
    // caculate next page. update request context.

    // AJAX request to get tickets of next page.

    // update table.
}

// add the click event on load more button.
jQuery(function($) {
  $('#project-load-more').click(function() {
    loadMoreTickets();
  });
});
