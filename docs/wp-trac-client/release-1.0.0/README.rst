wp-trac-client Release 1.0

wp-trac-client release 1.0 will be a overall redesign release
focused on Agile project management.

Release 1.0 will be a major release with the following changes:

- introduce Bootstrap page template.
- introduce request context
- using cookie to track stats on js client.

Get Started
-----------

Project Home Page:

- one page application.
- cookie driving request context
- utility functions for page layout, page parts. 
  following the best practice from bootstrap.
- basic AJAX call back or JSON endpoint
- source code re-organize.

Transition Strategy
-------------------

- start from ticket list page


Questions and Challenges
------------------------

- could we use the request context in wp_ajax call back funtions?

How jQuery handle cookies
-------------------------

here are some examples::

  jQuery.cookie('project', 'myproject');


.. _jquery-cookie: https://github.com/carhartl/jquery-cookie
