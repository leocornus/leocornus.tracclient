Re-thinking about the user name autocomplete
============================================

ideas:

- still using jQuery-ui autocomplet
- integrate the jQuery-ui theme with bootstrap.

Current design
--------------

we have the user name auto complete on

- form to create ticket the owner field.
- form for ticket action section, reassign to field.
- wp-ajax action name wptc_username_autocomplet.
- the javascript is stored in file **js/wptc.js**. 

Using the jquery-ui-bootstrap
-----------------------------

the `jquery-ui-bootstrap <http://jquery-ui-bootstrap.github.io/jquery-ui-bootstrap>`_ will be used for the jquery ui suggestion
box.

Multi values support
--------------------

Basically adding customization around the input values.

jQuery UI autocomplete does not support multiple values by default.
We need customize source, select, and search options to support 
multi-values capability.

How to work with modal (dialog)
-------------------------------

The **appendTo** option will attach the suggestion box to the 
parent element.
The suggestion box will be tailored to fit in th parent element.

Turn on/off the loading icon
----------------------------

to turn on/off the loading icon for autocomplete::


  /* auto complete loading */
  .ui-autocomplete-loading {
    background: white url('../images/ui-anim_basic_16x16.gif') right center no-repeat;
  }

Use cases
---------

- project owners will be multi value auto complete.
-  
