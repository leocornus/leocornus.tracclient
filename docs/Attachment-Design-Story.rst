Design Story for the Ability to Attache Files to A Ticket

Dependences
===========

- PLupload js lib, shipped with wordpress
- MediaWiki
- WordPress
- wp-trac-client plugin

Basic Flow
==========

- wp-trac-client plugin to render the uploader HTML on
  page template.
- get ready the plupload uploader javascript
- MediaWiki special page as the server side callback for
  plupload.uploader
- MediaWiki special page will save the attachment files
  as wiki page
- MediaWiki Special page will return the wiki URL to 
  the attachment file to plupload.uploader as response
- Java Script client on the page template will update 
  description / comment textarea to include the 
  attachment url.

Components
==========

- Mediawiki special page to handle PLupload AJAX request.
- PHP function to render HTML for file selection and
  upload section (row). It will be used on both ticket
  details form and comment form.
- PHP function to generate JavaScript client to build
  and initialize the plupload.uploader object.

  #. set up plupload.Uploader object: browse_button,
     container, multi_selection, etc.
  #. Filter for file selection: images, zip, pdf,
     etc.
  #. Be able to set up metadata for MediaWiki File:
     file description, file category, comment,
     file name prefix, etc. (multipart_param option
     and BeforeUpload event)
  #. Be able to switch cursor between normal and
     wait.
  #. Error handling. (Error event from plupload).

- PHP function to generate JavaScript code to
  handle AJAX response from MediaWiki Special page.
  Bascially how to handle (display) the URLs for
  those uploaded files.
