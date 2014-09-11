`wp-trac-client Release 0.4.3 <wp-trac-client-0.4.3.rst>`_
> Progress Custom Field Design Story

Introduce custom field **progress** to track progress status for 
any ticket in Trac_ project.
The progress status could be very flexible.
Here are some possible status:

- Development Testing
- Stage Testing
- Production Testing
- Done
- Design Review
- Requirement Collection

UI Design Thinking
------------------

Add a **Update Progress** field on the **Action** section.
The progress field will be an autocomplete field,
the cadidate suggestions will be all existing progress values.
User can add new values to this field too.

The progress status will show on the ticket title right beside 
the ticket status and type.

Relation with Status
--------------------

If the ticket is closed, the **progress** field will not show.

TODO: should we set the **progress** field automatically when the 
ticket is closed.

Relation with Project
---------------------

Some questions about the relation with project field:

#. should we allow project owner to restrict the available status of
   the progress field?

wp_ajax Actions
---------------

Need create a ajax action to provied the suggestions for the progress
autocomplete field.
