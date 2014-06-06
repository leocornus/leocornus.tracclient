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
