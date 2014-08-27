Get ready for releae 0.4.2

New features
------------

- Introduce option to turn on/off unique file name
  while user upload attachments to a ticket.
- Ability for user to watch/unwatch a ticket.
- Introduce new page my watchlist. 
- Introduce easy way for WordPress_ user to 
  managme project on Trac_.
- Ability to hook all Trac_ activities to BuddyPress_ activity stream.

Stories
-------

- `Unique File Name Option Story`_
- `AngularJS Introduction Story <AngularJS-Introduction-Story.rst>`_
- `Ticket Watch List Story <Ticket-Watch-List-Design-Story.rst>`_
- `BuddyPress Activity Stream Story <BuddyPress-Activity-Stream-Story.rst>`_

Unique File Name Option Story
-----------------------------

Introduce new site option **wptc_atachement_unique_filename**.
Its default value is true.
If it is **true**, the file name will be prefixed by
a unique random generated id.
If it is **false**, the original file name will be use.

Correspondingly, a new field named **Use Unique Filename:** 
will be introduced in attachment seetings page to manage
the new option.

.. _Trac: http://trac.edgewall.org/
.. _WordPress: http://www.wordpress.org/
.. _BuddyPress: http://www.buddypress.org/
