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
wp_enqueue_script('wptc-d3');
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

<div id="svgtesting">
  <div id="svgcontent">
  <svg height="210" width="500"><g><polygon points="200,10 250,190 160,210" style="fill:lime;stroke:purple;stroke-width:1" /></g></svg>
  </div>

  <canvas height="210" width="500" style="display: none;"></canvas>
  <input type="button" id="save" value="save"/>

<script type="text/javascript">
jQuery(document).ready(function($) { 
  $("#save").on("click", function() {
    //alert('hello');
    // Base64 need those attributes to do convertion properly.
    var svgHtml = d3.select("svg").attr("version", 1.1)
                                  .attr("xmlns",
                                        "http://www.w3.org/2000/svg")
                                  .node().parentNode.innerHTML;
    // We can using jQuery selector too, then we 
    // have to make sure the those version and xmlns are 
    // set properly!
    //var svgHtml = $('#svgcontent')[0].innerHTML;
    //console.log(svgHtml);
    // get the Base64 format data.
    var imgSrc = 'data:image/svg+xml;base64,' + btoa(svgHtml);
    //console.log(imgSrc);
    // get the canvas object.
    var canvas = $('canvas')[0];
    // get the canvas rendering context.
    var context = canvas.getContext('2d');
    // need a Image dom object to draw on the canvas.
    var img = new Image();
    img.src = imgSrc;
    // draw the image on canvas.
    context.drawImage(img, 0, 0);
    // get the image data URL in Base64 format.
    var canvasData = canvas.toDataURL('image/png');
    //console.log(canvasData);
    // strip out the encoding prefix, the data url will
    // have prefix like: data:image/png;base64,
    var base64Data = canvasData.substring(22);

    // now we will try to save the Base64 data on remote server
    // as wiki page.
    var handler_url = '/wiki/Special:SpecialPlupload';
    var data = {
      'action' : 'base64',
      'desc' : "testing upload from ticket [[Category:Base64]]",
      'comment' : "from code, plupload",
      'wpDestFile' : 'saved image from svg as png.png',
      'base64Data' : base64Data
    };
    $.post(handler_url, data, function(response) {

        //console.log(response);
	var si = response.indexOf("{");
	var ei = response.lastIndexOf("}");
        var res = JSON.parse(response.substring(si, ei + 1));
        //console.log(res);
        if(res.success) {
            // redirect to the image page
            window.location.href = res.pageUrl;
        } else {
            alert('You need Log in to save image on Wiki!');
        }
    });

    // hook on the image onload event to download image
    // to local file automatically.
    //img.onload = function() {
    //  // try to remove the previous images.
    //  $('savedImage').remove();
    //  // append the
    //  $('body').append("<a id='savedImage'></a>");
    //  var downloadLink = $('#savedImage')[0];
    //  // set the file name so it will save to local.
    //  downloadLink.download = 'mySavedImage.png';
    //  downloadLink.href = canvasData;
    //  // trigger the click event.
    //  downloadLink.click();
    //};
  });
});
</script>
</div>

<?php
get_footer();
