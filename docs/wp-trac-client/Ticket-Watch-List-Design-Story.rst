`wp-trac-client Release 0.4.2 <wp-trac-client-0.4.2.rst>`_ > 
Introduce the Watch / Unwatch function for each ticket.

Use Cases
=========

- logged in user can watch and unwatch any ticket.
- logged in user can review all tickets
  that she/he is watching...
- list of users watching a ticket, from ticket's perspective.
- show total number of users who is watching a ticket.
- user will receive email notification for all watching tickets...

Design Overview
===============

- we will using the Trac's **cc** field to save all watchers's
  email addresses.
- Trac client plugin will associate the email address with
  user login and user full name, back and forth.
- Ticket owners and reporter will automatically watch the ticket.
  Ticket owners include all users who has been assigned or has
  accepted.
  [Should we include users who add comment to this ticket?]
- wptc_widget_ticket_info_topnav function.

Views and Widgets Need
======================

- image / button for logged in user to watch / unwatch a ticke
- [logged in user] view to list all watching tickets for a user
- [anonymous] view to list all users who are watching a ticket
- [anonymous] ability to show the total number of watchers for a ticket.
- [anonymous] ability to show the tooltip: Please login to watch this
  ticket.

Functions and Components
========================

- function **wptc_watch_ticket($ticket_id, $watching = true, 
  $user_email = null)**
  will be the one for watch and unwatch a ticket.
- function **wptc_ticket_watchers($ticket_id)**
  will return a list of watchers
- function **wptc_ticket_is_watching($ticket_id, 
  $user_field="email", $user_value=null)** returns true if the user 
  is watching the given ticket.
- New PHP function (**wptc_widget_ticket_watching($ticket)**)
  to generate the HTML for the watch/unwatch button 
  and the total number of watching users.
- Create new `wp_ajax_(action)`_ to handle watch and unwatch action.
  TODO: What's the action name? what's the call back function name?
- AJAX call back PHP function to handle the AJAX requst
  for watch and unwatch a ticket.
- [NEXT PHASE] page template to show the list of watchers 
  for a ticket! Maybe just a jQuery UI Dialog.
- JavaScript client to update page and handle user's activities,
  mainly: mouse click.

Rough Flow
----------

User's watch/unwatch click will triger AJAX request,
The AJAX Request be handle through `wp_ajax_(action)`_
**wptc_watch_ticket**.
The PHP callback function **wptc_watch_ticket_cb** will update 
ticket based on the request (watch or unwatch).
The client site JavaScript parse the response and 
reload the page!

**Values for user_field**

We will use the same possible values for user_field as the
WordPress `function get_user_by`_.
Here are the list of possible values, the default value is email.

- id
- slug
- email
- login

Code Samples
============

The watch button will be located between ticket title and
ticket details div.
In function **wptc_widget_ticket_info**.
Markup for the watch/unwatch and summary button::

  <div id="content">
    <h1 id="ticket-title">...</h1>
    <span class="watching" style="
          float: right;
          font-size: 100%;
          border: solid #ffd 1px;
          margin-top: -15px;
          background-color: #ffd;
    ">
          <span class="watching-button" style="
              border-right: solid #996 1px;
              margin-right: -4px;
              padding-right: 4px;
          ">Watch</span>
          <span class="watching-sum" style="
              padding-left: 3px;
         ">222</span>
    </span>
    <div id="ticket">...</div>
  ...
  </div>

How to update ticket for the 

.. _function get_user_by: http://codex.wordpress.org/Function_Reference/get_user_by
.. _function wp_get_current_user: http://codex.wordpress.org/Function_Reference/wp_get_current_user
.. _wp_ajax_(action): http://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
