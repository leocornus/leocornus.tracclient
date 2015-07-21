/**
 * utility JavaScript functions for projects page.
 */

+function($) {

  // define the load more function.
  $.fn.loadMoreClick = function() {
    alert('hello');
    return this;
  };

  // the load more button.
  var loadMore = $('#project-load-more');
  // bind to on click event.
  //loadMore.on('click', this.loadMoreClick());

}(jQuery);

jQuery(document).ready(function($) {
  $('#project-load-more').click(function() {
    this.loadMoreClick();
  });
});
