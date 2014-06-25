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

**Seamless Design**

The seamless way to implement the attachment function will
be incorporate with the img button of Trac_ wiki toolbar.
The Trac_ wiki toolbar has a add-image-button (id is **img**)
to insert **[[Image()]]** into the wiki text textarea.
We could update the java script for **img** button to 
leverage plupload js lib and MediaWiki special page to 
incorporate all the following actions in one single click:

- Browse the select file from local desktop [plupload]
- Upload file to server [plupload]
- Save file as MediaWiki File [MediaWiki Special Page]
- Return the MediaWiki File's URL to Javascript client
  [MediaWiki Special Page]
- Insert the wiki text (based on mime type) for the file into 
  textarea [plupload, jQuery]

Components
==========

- `Mediawiki Special Page`_ to handle PLupload AJAX request.
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

Attachment Admin Page
=====================

Create a admin page on dashbord to provide options for attachments.
We will have the following options:

- URL to handle plupload AJAX request.
- General Wiki Text
- Extract Wiki Text [PHASE 2]
- attachment comment
- General Tags add to attachement: Wiki Categories for the 
  MediaWiki special page case.
- Extra Tags for different mime type of attachment. [PHASE 2]

Site admin could use the following keywords to get metadata from 
the current ticket:

- [TICKET_ID]
- [PROJECT]
- [MILESTONE]

**Components**

- dashboard admin page.
- associated functions to get options.
- save settings to Database or site options? we will use site options
  for the first phase.
- a list of site options:

  - wptc_attachment_handler_url
  - wptc_attachment_description the template for description.
  - wptc_attachment_tags a list of tags for a attachment.
  - wptc_attachment_comment the template for comment.

.. _plupload wiki: https://github.com/moxiecode/plupload/wiki
.. _MediaWiki Special Page: http://www.mediawiki.org/wiki/Manual:Special_pages
.. _Trac: http://trac.edgewall.org/
