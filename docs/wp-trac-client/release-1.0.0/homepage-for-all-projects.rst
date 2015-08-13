Homepage for All Projects
=========================

Create homepage to list all projects by what criteria?

- project timeline
- 

Design Ideas
------------

- bootstrap Jumbotron to highligh WP Trac Projects
- Slogan: Open Soure, Open Mind, Project Management in Agile.
- boostrap grid to divide the screen to 3 column
- using bootstrap panel to show snapshot of a project.
- a snapshot will have some summary and / or highlight of a project
- SUMMARY: list of tickets
- using glyphicons for tickets.
- SUMMARY: list of commits.
- SUMMARY: list of contributors.

Projects List
-------------

Projects list will be 3-column rows.
Here is a markup::

  <div id="projects-list" class="container-fluid">
    <div class="row">
      <div class="col-sm-4">
        ... project 1
      </div>
      <div class="col-sm-4">
        ... project 2
      </div>
      <div class="col-sm-4">
        ... project 3
      </div>
    </div>
    <div class="row">
      ... more rows
    </div>
    <div id="projects-pager">
      ...
    </div>
  </div>

Projects Pager
--------------

Here is the markup for pager::

  <div class="alert alert-info text-right h4" id="projects-pager">
    Showing <span id="loaded-items" class="badge">20</span> of 
    <span id="loaded-items" class="badge">120</span> Projects
    <a class="btn btn-success"
     id="projects-load-more">
      Load More...
    </a>
  </div>

Project Panel Design
--------------------

project name
description of project
total tickets, total commits, total contributors.
total blogs, total wikis.
Here is markup for a project::

  <div class="col-sm-4">
    <h2><a href="?project=TracCore">TracCore</a></h2>
    <p>The core module for WP Trac Projects</p>
    <p>
      <button type="button" class="btn btn-xs btn-danger">
        <span class="badge">809</span> Tickets
      </button>
      <button type="button" class="btn btn-xs btn-primary">
        <span class="badge">1002</span> Commits 
      </button>
      <button type="button" class="btn btn-xs btn-success">
        <span class="badge">15</span> Contributors 
      </button>
      <button type="button" class="btn btn-xs btn-warning">
        <span class="badge">200</span> Wiki Pages 
      </button>
      <button type="button" class="btn btn-xs btn-info">
        <span class="badge">80</span> Blog Posts
      </button>
    </p>
  </div>

Project Popularity Algorithm
----------------------------

Try to find a reasonable way to decide the popularity for a project.
Here are facts we should consider:

- total number of tickets
- total number of commits
- total number of wiki page
- age of the last change (from search)

ideas for algorithm:

- search ticket system to find the most recent modified tickets.

wp_ajax callback function
-------------------------

specs:

- ajax action name: wptc_projects
- callback function name: wptc_projects_cb

Idealy, the projects list should return in the order of popularity.
However, we don't have a good algorithm to calculate a project's 
popularity.
For the first releae, we will just return the list of projects 
directly from database.

Steps

#. get all projects page by page.
   Just project title with href link to project page 
   and project desctiption for each project. 
#. load summary for each project: tickets, contributors, 
   commits total number will depends on the new table to 
   associate projects with repositor.


jQuery Tricks
-------------

- go to the last row?
- append to last of the row.

SQL Tricks
----------

- using **LIMIT** to query db page by page.

Example::

  SELECT * FROM wptc_project LIMIT 0, 10;

Mics
----

Some candidate icons for tickets::

  glyphicon glyphicon-list-alt
  glyphicon glyphicon-file
  glyphicon glyphicon-tasks
  glyphicon glyphicon-user

