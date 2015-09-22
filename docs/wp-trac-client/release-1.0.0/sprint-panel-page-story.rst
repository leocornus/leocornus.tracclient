Sprint Panel for a Project
==========================

Sprint panel page for a project will be 2 columns page.
::

  <div id="sprint-panel" class="container-fluid">
    <div class="row">
      <div class="col-sm-6" id="sprint-list">
        <!-- list of sprints, sort by date desc -->
      </div>
      <div class="col-sm-6" id="open-tickets-list">
        <!-- list of open tickets, sort by priority -->
      </div>
    </div>
  </div>

Right column will have a list of sprint, sorting by date desc.
Left column will be a list of tickets / stories,
which don't assign to any sprint.
Tickets will be sorted by priority.

Simple Rules
------------

We should call it conventions instead of rules.

- sprint will be saved in version custom field.
- sprint list will sort by sprint date, desc
- tickets without sprint (sprint field is empty) 
  will be considered as backlog tickets.
