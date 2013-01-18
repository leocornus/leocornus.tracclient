
Page Templages for wp-trac-client
=================================

wp-trac-client provides a set of page templates to render tickets
from backend trac project.
Basically, there should be 2 pages for:

- **Trac Tickets** a list of tickets
- **Trac Ticket Detail** show ticket details

Following are quick instructions about how to use those templates.

Copy / Symlink page templates to your theme folder
==================================================

You will find some default WordPress page templates for wp-trac-client plugin.
The following steps will explain how to use them:

- Activate the wp-trac-client plugin for your blog.

- Configure the connection to your remote Trac project.

- copy those template php files to your blog's theme folder.

- create empty pages for your blog and apply the correspondding templates.

Create page and apply the template
==================================

There are the suggested page name list as following:

- trac_project_name
- trac_project_name/ticket, set the parent page to page trac_project_name

Using `php symlink`_ function to create automatically
=====================================================

Function `php symlink`_ will create a symbolic link without shell access.
It should be very convenient to create a symlink button in 
plugin's settings page.
This button will create the symlinks for page templates to 
current theme's folder.
WordPress function `get_template_directory`_ will retrieve template
directory Path for the current theme.

.. _`php symlink`: http://php.net/manual/en/function.symlink.php
.. _`get_template_directory`: http://codex.wordpress.org/Function_Reference/get_template_directory
