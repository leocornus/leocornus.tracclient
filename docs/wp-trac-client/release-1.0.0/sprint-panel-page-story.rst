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

Homepage Content
----------------

The sprint panel homepage will show backlog and the most recent
2 sprints.
One sprint will have the follwing contents::

  <div class="panel panel-danger">
    <!-- panel heading for sprint name and summary -->
    <div class="panel-heading">
      <span class="panel-title">Sprint Name</span>
      <span class="pull-right">tickets summary</span>
    </div>
    <!-- panel body for a sprint.-->
    <div class="panel-body">
      some brief description for this sprint,
      how about time range for this sprint?
    </div>

    <!-- list group with linked items for ticket list. -->
    <div class="list-group"
         style="min-height:300; max-height:350; overflow-y:auto;
                overflow-x:hidden;"
    >
      <a href="#" class="list-group-item clearfix">
        <span class="badge">ticket id</span>
        ticket summary more summary more and more
        Cras justo this will be long summary 
        <br/>
        <!-- status, priority for aticket. -->
        <div class="pull-right">
          <!-- we will using danger for blocker and critical -->
          <!-- we will using warning for major -->
          <!-- we will using default for minor and trival -->
          <span class="label label-danger">blocker</span>
          <span class="label label-primary">assigned</span> to 
          <span class="label label-info">Sean Chen</span>
        </div>
      </a>
    </div>
  </div>