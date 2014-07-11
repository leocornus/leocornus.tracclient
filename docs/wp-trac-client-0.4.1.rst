This will be a minor release for 0.4.1.
We will have the following features:

- `More options for attachment`_: multifile support, wiki
  template for image attachment and none-image attachment.

More options for attachment
---------------------------

We will add more options for attachments to make it 
easier for developers to attache files/images to a ticket.
Here are the new options:

wptc_attachment_multi_selection  
  Option to allow multifile selection. Default is **false**.

wptc_attachment_image_wikitext   
  Trac wiki text template for image.
  This is the wiki text that will be inserted into the ticket 
  descrption or commet after the image is successfully uploaded.

wptc_attachment_file_wikitext
  Trac wiki text template for none-image attachment.
  It is similar with the previous one.
