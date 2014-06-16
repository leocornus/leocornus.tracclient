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
    $containter_id = "plupload-" . $random;
    $browse_id = "pickfiles-" . $random;
    $filelist_id = "filelist-" . $random;
    $uploadfile_id = "uploadfiles-" . $random;

    $plupload_js = wptc_widget_plupload_js(
        $container_id, $browse_id, $filelist_id, $uploadfile_id,
        $description, $comment, $textarea_id);

    $container = <<<EOT
<div id="{$containter_id}" style="text-align: left;">
  <input type="button" id="{$browse_id}" value="Choose File"/>
  <span id="{$filelist_id}">No File Choose</span>
  <input type="button" id="{$uploadfile_id}" value="Upload File"/>
</div>
{$plupload_js}
EOT;

    return $container;
}

/**
 * generate all necessary Javascript for plupload container.
 */
function wptc_widget_plupload_js(
        $container_id, $browse_id, $filelist_id, $uploadfile_id,
        $description, $comment, $textarea_id) {

    $uploader_js = <<<EOT
<script type="text/javascript">
// Custom example logic
jQuery(document).ready(function() { 
  var uploader = new plupload.Uploader({

      runtimes : 'html5,flash,silverlight,html4',
      unique_names : false,
      browse_button : '{$browse_id}', // you can pass in id...
      container: '{$container_id}', // ... or DOM Element itself
      multi_selection : false,

      //url : "/plupload.php",
      url : "/wiki/Special:SpecialPlupload",
      multipart_params : {
          action : "plupload",
          desc : "{$description}",
          comment : "{$comment}"
      },
       
      filters : {
          max_file_size : '10mb',
          mime_types: [
              {title : "Image files", extensions : "jpg,gif,png"},
              {title : "Zip files", extensions : "zip"}
          ]
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
              plupload.each(files, function(file) {
                  jQuery('#{$filelist_id}').html(file.name);
              });
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
              // switch cursor...
              jQuery('#{$filelist_id}').html('No file choose');
              jQuery(':text').css('cursor', 'text');
              jQuery(':button').css('cursor', 'default');
              jQuery('textarea').css('cursor', 'text');
              jQuery('body').css('cursor', 'default');
          }
      }
  });

  uploader.init();

  jQuery('#{$filelist_id}').html('No file choose');
  jQuery('#{$uploadfile_id}').click(function() {
      // switch cursor...
      jQuery(':text').css('cursor', 'wait');
      jQuery(':button').css('cursor', 'wait');
      jQuery('textarea').css('cursor', 'wait');
      jQuery('body').css('cursor', 'wait');
      uploader.start();
      return false;
  });
});
</script>

EOT;

    return $uploader_js;
}
