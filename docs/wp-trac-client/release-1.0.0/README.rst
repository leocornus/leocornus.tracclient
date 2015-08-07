wp-trac-client Release 1.0.0

wp-trac-client release 1.0 will be a overall redesign release
focused on Agile project management.

Release 1.0.0 will be a major release with the following changes:

- introduce Bootstrap page template.
- introduce request context
- using cookie to track stats on js client.

**mics features**:

- ability to change project name.
  this will require update all ticket under from project name
  to the new project name.
  Project is a custom field in trac.

Stories
-------

- `Transition Strategy <transition-strategy.rst>`_
  all things about the transition phase..
- associate project to a Git repository.
- `Homepages for all projects <homepage-for-all-projects.rst>`_

Get Started
-----------

We will get started with project Home Page:

- one page application.
- cookie driving request context
- utility functions for page layout, page parts. 
  following the best practice from bootstrap.
- basic AJAX call back or JSON endpoint
- source code re-organize.

Long Term Plan
--------------



Questions and Challenges
------------------------

- could we use the request context in wp_ajax call back funtions?

How jQuery handle cookies
-------------------------

here are some examples::

  jQuery.cookie('project', 'myproject');


.. _jquery-cookie: https://github.com/carhartl/jquery-cookie
