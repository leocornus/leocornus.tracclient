`wp-trac-client Release 0.4.3 <README.rst>`_ >
Project Management Plugin base on wp-trac-client

Create a blog level (in WordPress Multisite mode) plugin to
offer easy and simple ways to manage a project in Trac_.
This plugin will be created by following the best practice
in the GitHub project `WordPress Plugin Boilerplate`_.

Folder Structure
----------------

Here is the specs for folder name and file names::

  wp-trac-client/
    |- wp-trac-pm.php
    |- wp-trac-pm/
         |- public/
            |- class-wp-trac-pm.php
            |- includes/
            |- views/
            |- assets/
               |- css/
               |- js/
         |- admin/
            |- class-wp-trac-pm-admin.php
            |- includes/
            |- views/
            |- assets/
               |- css/
               |- js/
         |- includes/
         |- assets/
            |- css/
            |- js/
         |- languages
            |- wp-trac-pm.pot

.. _WordPress Plugin Boilerplate: https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate
