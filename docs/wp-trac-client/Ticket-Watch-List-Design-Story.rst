iIntroduce the Watch /Unwatch function for each ticket.

Use Cases
=========

- logged in user can watch and unwatch any ticket.
- logged in user can review all tickets
  that she/he is watching...
- list of users watching a ticket, from ticket's perspective.
- user will receive email notification for all watching tickets...

Design Overview
===============

- we will using the Trac's **cc** field to save all watchers's
  email addresses.
- Trac client plugin will associate the email address with
  user login and user full name, back and forth.
- ticket owner and reporter will automatically watch the ticket.
- wptc_widget_ticket_info_topnav function.

Views and Widgets Need
======================

- [logged in user] view to list all watching tickets for a user
- [anonymous user] view to list all users who are watching a ticket
- image / button for logged in user to watch / unwatch a ticke
- [anonymouns] ability to show the total number of watchers for a ticket.

Functions and Components
========================

- function **wptc_watch_ticket($ticket_id, $watching = true, $user_email = null)**
  will be the one for watch and unwatch ticket.
- function **wptc_ticket_watchers($ticket_id)**
  will return a list of watchers
- page template to show the list of watchers for a ticket!
  maybe just a jQuery UI Dialog.
- [HOLD ON] AJAX call back function to call the

Code Samples
============

Markup for the watch/unwatch and summary button::

  <span class="watching" style="
      float: right;
      font-size: 78%;
      border: solid black 1px;
  ">
      <span class="watching-button" style="
      border-right: solid black 1px;
      margin-right: -4px;
      padding-right: 4px;
      ">Watch</span>
      <span class="watching-sum">21</span>
  </span>
