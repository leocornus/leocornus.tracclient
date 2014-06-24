<?php
/**
 * widgets and utils related to plupload libs.
 */

/**
 * this function will build the container for plupload uploader.
 */
function wptc_widget_plupload_container($textarea_id = "desc",
        $description="new file", $comment="plupload Upload") {

    // generate a random number as postfix, so we can have
    // multiple plupload contanier in one page.
    $random = rand();
    $browse_id = "pickfiles-" . $random;

    // TODO: get url from attachement admin page.
    $url = "/wiki/Special:SpecialPlupload";

    $options = array(
      'browse_button' => $browse_id,
      'url' => $url,
    );

    $plupload_js = wptc_widget_plupload_js(
        $options, $description, $comment, $textarea_id);

    $container = <<<EOT
<input type="button" id="{$browse_id}" value="Choose File"/>
{$plupload_js}
EOT;

    return $container;
}

/**
 * generate all necessary Javascript for plupload container.
 */
function wptc_widget_plupload_js($browse_button, $textarea_id, 
                                 $ticket) {

    $settings = wptc_attachment_get_settings();
    $tags = str_replace(array("\n", "\r"), 
                        array("\\n", " "), $settings['tags']);
    $description = $settings['desc'] . "\\n\\n " . $tags;
    $comment = $settings['comment'];

    $search = array('[TICKET_ID]', '[PROJECT]', '[MILESTONE]');
    $replace = array($ticket['id'], $ticket['project'], 
                     $ticket['milestone']);
    $description = str_ireplace($search, $replace, $description);
    $comment = str_ireplace($search, $replace, $comment);

    $uploader_js = <<<EOT
<script type="text/javascript">
// Custom example logic
jQuery(document).ready(function() { 
  var uploader = new plupload.Uploader({

      runtimes : 'html5,flash,silverlight,html4',
      unique_names : false,
      // you can pass in id...
      browse_button : '{$browse_button}', 
      multi_selection : false,

      url : "{$settings['handler_url']}",
      //url : "/wiki/Special:SpecialPlupload",
      multipart_params : {
          action : "plupload",
          desc : "{$description}",
          comment : "{$comment}"
      },

      filters : {
          mime_types: [
              {title : "Image Files plupload", 
               extensions : "jpg,jpeg,gif,png"}
          ],
          max_file_size : '10mb',
      },

      // set the file data name:
      file_data_name : 'wpUploadFile',
      // Flash settings
      flash_swf_url : '/wp-includes/js/plupload/plupload.flash.swf',
      // Silverlight settings
      silverlight_xap_url : '/wp-includes/js/plupload/plupload.sliverlight.swf',
       
      init: { 
          
          PostInit : function() {
          },

          BeforeUpload: function(up, file) {
              //console.log('up object %O', up);
              //console.log('file object: %O', file);
              // attach the uploader id as prefix to 
              // make the file name unique.
              up.settings.multipart_params.wpDestFile = 
                file.id + '-' + file.name;
          },
   
          FilesAdded: function(up, files) {
              // switch cursor...
              // for type is text
              jQuery(':text').css('cursor', 'wait');
              // for type is button
              jQuery(':button').css('cursor', 'wait');
	      // for the following html tags.
              jQuery('select').css('cursor', 'wait');
              jQuery('textarea').css('cursor', 'wait');
              jQuery('body').css('cursor', 'wait');
              this.start();
              return false;
          },
   
          UploadProgress: function(up, file) {
          },
   
          Error: function(up, err) {
              document.getElementById('console').innerHTML += "\\nError #" + err.code + ": " + err.message;
          },

          FileUploaded: function(up, file, info) {
              //console.log("info: %O", info.response);
              var res = JSON.parse(info.response);
              var desc = jQuery('textarea#{$textarea_id}');
              desc.val(desc.val() + "\\n [[Image(" + 
                       res.fileUrl + ", 500px)]]\\n");
              // scroll to the bottom of the textarea.
              desc.scrollTo(99999);
              // switch cursor...
              jQuery(':text').css('cursor', 'text');
              jQuery(':button').css('cursor', 'default');
              jQuery('select').css('cursor', 'default');
              jQuery('textarea').css('cursor', 'text');
              jQuery('body').css('cursor', 'default');
          }
      }
  });

  uploader.init();

});
</script>

EOT;

    return $uploader_js;
}
