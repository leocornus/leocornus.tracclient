`wp-trac-client Release 0.4.3 <wp-trac-client-0.4.3.rst>`_
> Introduce Trac Wiki Syntax Settings

The story is about adding some necessary settings to customize
how the Trac Wiki perform.

Background
----------

We are using the wikirender_ lib to parse Trac Wiki to HTML.
There is a php file to define those rules.

New Site Options
----------------

**wiki word base url**

The base url to link to the Trac Wiki word.
Trac Wiki has a convention to auto-link any qualified word
to a wiki page.

.. _wikirender: https://github.com/laurentj/wikirenderer
