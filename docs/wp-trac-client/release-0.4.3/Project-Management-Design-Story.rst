`wp-trac-client Release 0.4.3 <wp-trac-client-0.4.3.rst>`_ > 
Project Management Design Story

Overview
--------

Try to build some simple and easy-to-use project management toolkits
on top of the wp-trac-client plugin.
Here are list tools we may offer:

- concise ticket creation form
- Project summary page: milestones, list of tickets, 
  activity reports, ticket reports, user reports, etc.
- Project management page: project ID, project name, project owners,
  project milestone, components, description, wiki documents
- Project Explore page.

We will use the following new tech to make the development work easier:

- `AngularJS`_ and `angular_ui`_ for lightweight front end client.
- Twitter `bootstrap`_ for stylesheet
- `d3js`_ for reporting...

Project Metadata
----------------

- project id, the unique short identity for a project.
  It will be used as the value for the Trac_ custom field project.
  We need a convension for he project id: only letter, number,
  -, and _.
- project name, the full readable name of the project
- project summary / description, the details description for the
  project.

Concise Ticket Creation Form
----------------------------

Here are the minimium list of fields:

- Ticket summary
- Ticket description
- Project (autocomplete),

Use Cases
---------

we will work on to make everybody can create and manage projet 
through wp-tracl-client plugin.

Use cases will be devided by user roles.
We will have the following roles:

#. General User, anonymous user
#. Developer / Tester / Contributor
#. Project Owner, Project Manager

General User Story
------------------

As a general user, I should be able to:

- view the summary of a project [Project Summary Page]:

  - project name, description
  - list of tickets
  - list of wiki pages, documentations,
  - list (tag cloud) of contributors
  - report of the projects.
  - list of milestones / roadmap
  - list of sprints

- View the summary of any milestone of a project 
  [Milestone Summary Page]:
- View the summary of any sprint [Sprint Page]:
- View the summary of any Ticket [Ticket Page]:

Initial Thinkings
-----------------

Here are some initial ideas about Agile project management:

- create a project through wordpress dashboard or regular page
- create a git repository sandbox through user profile
- set up project metadata: components, milestones, sprint,
- page to create ticket
- page to list / search ticket
- widget to generate milestone list
- widget to list most recent changes
- widget to autocomplete

.. _Trac: http://trac.edgewall.org/
.. _AngularJS: https://github.com/angular/angular.js
.. _angular_ui: https://github.com/angular-ui/ui-utils
.. _bootstrap: https://github.com/twbs/bootstrap
.. _d3js: https://github.com/mbostock/d3
