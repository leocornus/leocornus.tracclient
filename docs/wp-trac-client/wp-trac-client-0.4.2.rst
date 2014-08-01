Get ready for releae 0.4.2

New features
------------

- introduce option to turn on/off unique file name.
- Ability for user to watch/unwatch a ticket
- Introduce new page my watchlist. 

Stories
-------

- `Unique File Name Option Story`_
- `Ticket Watch List Story <Ticket-Watch-List-Design-Story.rst>`_

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
