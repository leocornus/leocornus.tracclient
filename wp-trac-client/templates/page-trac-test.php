<?php
/**
 * Template Name: Trac Sandbox Page
 *
 * This is a page template for testing purpose!
 */

get_header();

// inqueue plupload lib
wp_enqueue_script('plupload-handlers');
?>

<!-- script type="text/javascript" src="/plupload-test.js"></script --> 
<script type="text/javascript">
// Custom example logic
jQuery(document).ready(function() { 
  var uploader = new plupload.Uploader({

      runtimes : 'html5,flash,silverlight,html4',
      unique_names : false,
      browse_button : 'pickfiles', // you can pass in id...
      multi_selection : false,
       
      //url : "/plupload.php",
      url : "/wiki/Special:SpecialPlupload",
      multipart_params : {
          action : "plupload",
          desc : "testing upload from ticket",
          comment : "from code, plupload"
      },
       
      filters : {
          max_file_size : '10mb',
          mime_types: [
              {title : "Image files", extensions : "jpg,gif,png"},
              {title : "Zip files", extensions : "zip"}
          ]
      },

      // set the file data name for MediaWiki Upload class:
      file_data_name : 'wpUploadFile',
      // Flash settings
      flash_swf_url : '/wp-includes/js/plupload/plupload.flash.swf',
      // Silverlight settings
      silverlight_xap_url : '/wp-includes/js/plupload/plupload.sliverlight.swf',
       
      init: { 
          
          PostInit : function() {

              alert("post init");
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
              jQuery(':text').css('cursor', 'wait');
              jQuery(':button').css('cursor', 'wait');
              jQuery('textarea').css('cursor', 'wait');
              jQuery('body').css('cursor', 'wait');
              this.start();
              return false;
          },
   
          UploadProgress: function(up, file) {
              //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
          },
   
          Error: function(up, err) {
              document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
          },
          FileUploaded: function(up, file, info) {
              console.log("info: %O", info.response);
              var res = JSON.parse(info.response);
              var desc = jQuery('textarea#description');
              desc.val(desc.val() + "\n\n [[Image(" + 
                       res.fileUrl + ", 500px)]]\n\n");
              // switch cursor...
              jQuery(':text').css('cursor', 'text');
              jQuery(':button').css('cursor', 'default');
              jQuery('textarea').css('cursor', 'text');
              jQuery('body').css('cursor', 'default');
          }
      }
  });

  uploader.init();
});
</script>
<div id="content">

  Description: <br/>
<textarea id="description"> abcd
</textarea>

  <input type="button" id="pickfiles" value="[Select files]"/>

  <br />
  <pre id="console"></pre>
</div>

<?php
get_footer();
