`AngularJS Introduction Story <AngularJS-Introduction-Story.rst>`_
> Explore the best practice to manage and organize source code 
of an AngularJS APP in WordPress context

One AngularJS app is an ONE page app in most of time.
But there way a lot more components for a software application.

What source code?
-----------------

- html template
- javascript controller
- stylesheets
- images
- karma_ unit test cases
- Protractor_ e2e test cases

Which WordPress context?
------------------------

- WordPress page template to load the AngularJS apps in WordPress
  context. 
- PHP helper function to register js libs, css files
- PHP function to load js files, css files, and all imges.

How a AngularJS app looks like in WordPress
-------------------------------------------

folder layout::

  page-myapp.php
  myapp/
   |- app/
       |- css/
       |- js/
       |- img/
       |- view/
       |- index.html
   |- test/
       |- unit/
       |- e2e/

Important WordPress Technique
-----------------------------

We will use the following basic and important WordPress techniques
to wire up an AngularJS app in WordPress context.

- wp_enqueue_script
- wp_localize_script
- wp_enqueue_scripts anction
- WordPress page template

This post `use wp_localize_script it is awesome`_ has a very good
example about how to use those techniques.

.. _use wp_localize_script it is awesome: https://pippinsplugins.com/use-wp_localize_script-it-is-awesome/
.. _karma: http://karma-runner.github.io/
.. _Protractor: https://github.com/angular/protractor
