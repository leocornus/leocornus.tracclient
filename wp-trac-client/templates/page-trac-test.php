<?php
/**
 * Template Name: Trac Sandbox Page
 *
 * This is a page template for testing purpose!
 */

get_header();

// inqueue plupload lib
wp_enqueue_script('plupload-handlers');
wp_enqueue_script('wptc-angularjs-core');
?>

<!-- testing angularjs -->
<script type="text/javascript">
jQuery('html').attr('ng-app', '');
</script>
<div>
  <label>Name:</label>
  <input type="text" ng-model="yourName" 
         placeholder="Enter a name here">
  <h1>Hello {{yourName}}!</h1>
</div>

<!-- script type="text/javascript" src="/plupload-test.js"></script --> 
<script type="text/javascript">
// Custom example logic
jQuery(document).ready(function() { 
  var uploader = new plupload.Uploader({
//  jQuery("#uploader").plupload({

      runtimes : 'html5,flash,silverlight,html4',
      unique_names : false,
      browse_button : 'pickfiles', // you can pass in id...
      //container: 'uploader',
      multi_selection : true,

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

      // views to activate
      //views: {
      //    list: true,
      //    thrumbs: true,
      //    active: 'thumbs'
      //},

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
	      var si = info.response.indexOf("{");
	      var ei = info.response.lastIndexOf("}");
              var res = JSON.parse(info.response.substring(si, ei + 1));
              // get ready the wikitext for the uploaded file,
              // based on the mimetype.
              var fileWikiText = "\n[" + res.fileUrl + "]\n";
              if(res.mimeType.search(/^image/) == 0) {
                  fileWikiText = "\n [[Image(" + 
                       res.fileUrl + ", 500px)]]\n";
              }
              var desc = jQuery('textarea#description');
              desc.val(desc.val() + fileWikiText);
          },

          UploadComplete: function(up, files) {
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
<div>

  <div id="uploader">
    
  </div>
  <p/>
  Category: <input id="cates" type="text" cols="80"/>
  <br />
  Description: <br/>
<textarea id="description" cols="80" rows="16">abcd</textarea>
  <br />
  Comment: <input id="comment" type="text" cols="40"/>
  <br />
  <input type="button" id="pickfiles" value="[Select files]"/>

  <br />
  <pre id="console"></pre>
</div>

<?php
get_footer();
