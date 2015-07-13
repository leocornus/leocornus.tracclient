`wp-trac-client Release 0.4.3 <README.rst>`_ >
Introducing AngularJS for wp-trac-client

We will explore how AngularJS_ can help to present Trac_ project
on WordPress context. 

Questions and Challenges
------------------------

- How to manage AngularJS modules, directives, etc? Using bower_.
- How to communicate with WordPress_ server side functions.
- What's the best way to incorporate both bootstrap_ and AngularJS_
  into WordPress theme or plugin?

Stories
-------

- `Options for AngularJS communicate with WordPress backend 
  <How-AngularJS-Talk-to-WordPress.rst>`_
- `Best Practice to Organize Source Code for AngularJS APP
  in WordPress Context <AngularJS-Code-Organization-Story.rst>`_
- `Design the JSON APIs aiming at AngularJS apps
  <Trac-Client-JSON-APIs-Design-Story.rst>`_

Package Mangement
-----------------

AngularJS_ is using bower_ to manage all packages 
including modules, directives, etc.
There are handreds of AngularJS_ packages, and they are growing.
How are we manage them within WordPress_ context?

Build a `private bower registry`_ on Intranet might be 
the best choice.

Another alternative way will be using PHP functions / classes
to manage those AngularJS_ modules / packages.
PHP functions will have benefit on get ready the JavaScripts,
and it will be very helpful for newbies and casual 
developers / programmers.

[This might good enough to be a WordPress Plugin]

Form Builder
------------

The GitHob project angular-schema-form_ offers a easy way to
generate forms based on the JSON schemas.

Load AngularJS
--------------

Load and manage AngularJS in a WordPress_ plugin.
We will using WordPress functions:

- wp_register_script
- wp_enqueue_script
- wp_localize_script
- wp_register_style
- wp_enqueue_style
- wp_enqueue_scripts action

How to manage the handle names?

List of Handle Names and Files
------------------------------

:wptc-angular-core: js/angular.js
:wptc-angularui-bootstrap: 

:wptc-bootstrap:
:wptc-bootstrap-docs:

Reference `AngularJS for WordPress Plugin`_

.. _bootstrap: https://github.com/twbs/bootstrap
.. _d3js: https://github.com/mbostock/d3
.. _bower: http://bower.io
.. _AngularJS: https://github.com/angular/angular.js
.. _Trac: http://trac.edgewall.org/
.. _AngularUI Bootstrap: http://angular-ui.github.io/bootstrap/
.. _AngularJS for WordPress Plugin: http://plugins.svn.wordpress.org/angularjs-for-wp/
.. _private bower registry: http://hacklone.github.io/private-bower/
.. _WordPress: http://www.wordpress.org
.. _angular-schema-form: https://github.com/Textalk/angular-schema-form
