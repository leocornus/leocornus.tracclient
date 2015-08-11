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

jQuery Tricks
-------------



Mics
----

Some candidate icons for tickets::

  glyphicon glyphicon-list-alt
  glyphicon glyphicon-file
  glyphicon glyphicon-tasks
  glyphicon glyphicon-user

