`wp-trac-client Release 0.4.2 <wp-trac-client-0.4.2.rst>`_ > 
Design story for adding Trac activity to BuddyPress activity stream

Overview
--------

As BuddyPress_ is often used with WordPress_ for social functionality,
it will be a usable feature to add all Trac_ activities to BuddyPress_
activity stream.
So the whole community could review how the tickets are going.

Use Cases
---------

Any updates on Trac_ will have a corresponding activity record on
BuddyPress_ activity stream.
Any user (including anonymous users) could see the updates through
the BuddyPress activity stream.

Design
------

We will hood in the following actions:

- **wptc_create_ticket**
  We will using the id, author and summary,
- **wptc_update_ticket**
  We will use the id, comment and author.

depends on the following BuddyPress_ functions:

- BuddyPress function **bp_activity_add**

A new site option will be used to turn on and off the BuddyPress_
activity hook.

:Option Name:
  **wptc-buddypress-activity**
:Description:
  This will be a site option on general settings admin page,
  It is responsible for turn on or off the action to 
  add all trac activities to BuddyPress_ activity stream.
  It depends on the BuddyPress_ plugin for WordPress_
:Conditions:
  This option will only show up on the general settings page if
  the BuddyPress_ plugin is activated.
  The **function_exists('bp_activity_add')** will be used to 
  check if BuddyPress_ is activated or ont.
:Default Value:
  **false**, which will turn off the action.

Actions
-------

List of actions to have this feature implemented:

- PHP function to add Trac_ activities to BuddyPress activity stream.
- add option on general settings page for network admin to 
  turn on and off this feature.
- hook on the action based on network admin's setting.

QUESTIONS
---------

How to decide the URL to a ticket?
  Currently, we are using WikiRenderer_ lib to parse the Trac_
  wiki text to HTML, which will parse the ticket id with format
  **#[1-9]+** to HREF link in format 
  **http://BASE.URL/?id=TICKET_ID**
  The **BASE.URL** is configured in WikiRenderer_ 
  rule **trac_to_xhtml**.
  The utility function **wptc_widget_parse_content** will parse 
  the Trac_ wiki text to HTML.

.. _BuddyPress: http://www.buddypress.org
.. _WordPress: http://www.wordpress.org
.. _WikiRenderer: https://github.com/laurentj/wikirenderer
.. _Trac: http://trac.edgewall.org/
