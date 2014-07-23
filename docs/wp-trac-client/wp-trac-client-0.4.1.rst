This will be a minor release for 0.4.1.
We will have the following features:

- `More options for attachment`_: multifile support, wiki
  template for image attachment and none-image attachment.
- add hooks to allow developer to update those options.

More options for attachment
---------------------------

We will add more options for attachments to make it 
easier for developers to attache files/images to a ticket.
Here are the new options:

wptc_attachment_multi_selection  
  Option to allow multifile selection. 
  If set to **true**, user can upload more than one attachment
  to the ticket at once.
  Default is **false**.

wptc_attachment_image_wikitext   
  Trac wiki text template for image.
  This is the wiki text that will be inserted into the ticket 
  descrption or commet after the image is successfully uploaded.

wptc_attachment_file_wikitext
  Trac wiki text template for none-image attachment.
  It is similar with the previous one.

The following keywords could be used to reference each
uploaded attachment:

[FILE_NAME]
  The name of the uploaded attachment file.

[FILE_URL]
  The full URL to the actural file.

[PAGE_URL]
  The full URL to the page associated to the file.

Here is a sample for image wiki text template::

   [[Image([FILE_URL], 500px)]]
  [[PAGE_URL] Edit Image]

Here is a sample for file wiki text templage::

  [[FILE_URL] [FILE_NAME]] ([[PAGE_URL] Edit File])

Some Code Memos
---------------

Here is how to do search and replace in JavaScript::

  var str = " [[Image([FILE_URL], 500px)]]";
  var newStr = str.replace(/\[FILE_URL\]/g, theUrl);

