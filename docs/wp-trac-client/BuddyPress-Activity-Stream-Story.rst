`wp-trac-client Release 0.4.2 <wp-trac-client-0.4.2.rst>`_ > 
Design story for adding Trac activity to BuddyPress activity stream

Overview
--------

As BuddyPress is often used with WordPRess...

Use Cases
---------

Try to add all Trac activities to BuddyPress activity stream...

Design
------

We will hood in the following actions:

- **wptc_create_ticket**
  We will using the id, author and summary,
- **wptc_update_ticket**
  We will use the id, comment and author.

depends on the following BuddyPress_ functions:

- BuddyPress function **bp_activity_add**
