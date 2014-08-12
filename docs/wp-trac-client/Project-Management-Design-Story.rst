> Project Management Design Story

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

.. _Trac: http://trac.edgewall.org/
