Project Homepage
================

Project page will have 4 tabs:

- Project Home, Kanban page.
- Sprint Dashboard
- Tickets
- Commits

Sprint Dashboard
----------------

commit `2867eb3 <https://github.com/leocornus/leocornus.tracclient/commit/2867eb3969e9d425ae893132ccceacae9a1626aa>`_ 
create the sprint dashboad tab.

There are quiet a lot files need to change in order to
add a new tab.

Kanban board
------------

This commit `47a2014 <https://github.com/leocornus/leocornus.tracclient/commit/47a2014809f6663fe1bc50e0a2bd1e74bd697e35>`_ 
will provide clues for the whole design.

Basic idea:

- the new **My Tickets** tab will trigger the whole process.
- The request context will take care of most work on server side.
- buildMyTicketQuery will be responsible to check the user name:
  could be the current logged in user or the user from the 
  **owner** query parameter. 
