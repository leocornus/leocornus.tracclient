How to setup the Plupload_ based attachment function 
for wp-trac-client plugin.

Installation Steps
------------------

- install PluploadUtils_ mediawiki extension
- Network Activate wp-trac-client_ plugin.
- Config templates on WordPress network dashboard 
  **TracClient -> Attachments** settings page.

We have two groups of templates on the attachment settings page:

Templates for attachment metadata
---------------------------------

The attachment component offers the following options for user to
set up the metadata for an attachment:

Attachment Descript Template
  This template will be processed and saved as the file description
  on the MediaWiki_ page.
  For example::

    Attachment for ticket <ticket>[TICKET_ID]</ticket>

Attachment Tags Template
  The tags list here will be saved at the end of the file description
  on the MediaWiki_ page.
  For example::

    [[Category: Some Keywork]]
    [[Category: [PROJECT]]]
    [[Category: [MILESTONE]]] 

Attachment Comment Template
  This will become the comment for the MediaWiki_ file creation.
  For example::

    Attached a file to ticket #[TICKET_ID]

Templates for current ticket
----------------------------

There are also templates to generate Trac_ wiki text for current 
ticket's description or comment field.

Image Wiki Text Template
  This template will be used to generate the wiki text for 
  an attachment which has image mime type.
  For example::

    [[Image([FILE_URL], 500ps)]]
    [[PAGE_URL] Edit Image]

None-Image Wiki Text Template
  This is for none-image attachments. For example::

    [[FILE_URL] [FILE_NAME]] ([[PAGE_URL] Edit File])

.. _Plupload: https://github.com/moxiecode/plupload
.. _PluploadUtils: https://github.com/leocornus/PluploadUtils
.. _wp-trac-client: https://github.com/leocornus/leocornus.tracclient
.. _MediaWiki: http://www.mediawiki.org
.. _Trac: http://trac.edgewall.org/
