
(function($){

  // trac wiki toolbar  
  window.addWikiFormattingToolbar = function(textarea) {
    if ((document.selection == undefined)
     && (textarea.setSelectionRange == undefined)) {
      return;
    }
  
    var toolbar = document.createElement("div");
    toolbar.className = "wikitoolbar";
  
    function addButton(id, title, fn) {
      var a = document.createElement("a");
      a.href = "#";
      a.id = id;
      a.title = title;
      a.onclick = function() { try { fn() } catch (e) { } return false };
      a.tabIndex = 400;
      toolbar.appendChild(a);
    }
  
    function encloseSelection(prefix, suffix) {
      textarea.focus();
      var start, end, sel, scrollPos, subst;
      if (document.selection != undefined) {
        sel = document.selection.createRange().text;
      } else if (textarea.setSelectionRange != undefined) {
        start = textarea.selectionStart;
        end = textarea.selectionEnd;
        scrollPos = textarea.scrollTop;
        sel = textarea.value.substring(start, end);
      }
      if (sel.match(/ $/)) { // exclude ending space char, if any
        sel = sel.substring(0, sel.length - 1);
        suffix = suffix + " ";
      }
      subst = prefix + sel + suffix;
      if (document.selection != undefined) {
        var range = document.selection.createRange().text = subst;
        textarea.caretPos -= suffix.length;
      } else if (textarea.setSelectionRange != undefined) {
        textarea.value = textarea.value.substring(0, start) + subst +
                         textarea.value.substring(end);
        if (sel) {
          textarea.setSelectionRange(start + subst.length, start + subst.length);
        } else {
          textarea.setSelectionRange(start + prefix.length, start + prefix.length);
        }
        textarea.scrollTop = scrollPos;
      }
    }
  
    addButton("strong", "Bold text: '''Example'''", function() {
      encloseSelection("'''", "'''");
    });
    addButton("em", "Italic text: ''Example''", function() {
      encloseSelection("''", "''");
    });
    addButton("heading", "Heading: == Example ==", function() {
      encloseSelection("\n== ", " ==\n", "Heading");
    });
    addButton("link", "Link: [http://www.example.com/ Example]", function() {
      encloseSelection("[", "]");
    });
    addButton("code", "Code block: {{{ example }}}", function() {
      encloseSelection("\n{{{\n", "\n}}}\n");
    });
    addButton("hr", "Horizontal rule: ----", function() {
      encloseSelection("\n----\n", "");
    });
    addButton("np", "New paragraph", function() {
      encloseSelection("\n\n", "");
    });
    addButton("br", "Line break: [[BR]]", function() {
      encloseSelection("[[BR]]\n", "");
    });
    addButton("img", "Image: [[Image()]]", function() {
      encloseSelection("[[Image(", ")]]");
    });
  
    $(textarea).before(toolbar);
  }

  // define the function for enable folding.
  $.fn.enableFolding = function(autofold, snap) {
    var fragId = document.location.hash;
    if (fragId && /^#no\d+$/.test(fragId))
      fragId = parseInt(fragId.substr(3));
    if (snap == undefined)
      snap = false;
    
    var count = 1;
    return this.each(function() {
      // Use first child <a> as a trigger, or generate a trigger from the text
      var trigger = $(this).children("a").eq(0);
      if (trigger.length == 0) {
        trigger = $("<a" + (snap? " id='no" + count + "'": "")
            + " href='#no" + count + "'></a>");
        trigger.text($(this).text());
        $(this).text("");
        $(this).append(trigger);
      }
      
      trigger.click(function() {
        var div = $(this.parentNode.parentNode).toggleClass("collapsed");
        return snap && !div.hasClass("collapsed");
      });
      if (autofold && (count != fragId))
        trigger.parents().eq(1).addClass("collapsed");
      count++;
    });
  }



})(jQuery);

// Add the toolbar to all <textarea> elements on the page with the class
// 'wikitext'.
jQuery(document).ready(function($) {
  $("textarea.wikitext").each(function() { addWikiFormattingToolbar(this) });

  // attach folding function to foldable class.
  $(".foldable").enableFolding(false, true);

  // username auto complete for re-assign action.
  var wptc_username_ac = "wptc_username_autocomplete"
  var username_ac_data = {
      source: function(request, response) {
          $.getJSON(WptcAjaxObj.url + "?callback=?&action=" +
                    wptc_username_ac, request, response);
      },
      minLength: 2,
      select: function(event, ui) {
          // do nothing for now.
          // selected value could get from ui param.
          // ui.item.id, ui.item.value.
          // testing...
          //alert (ui.item.value);
      },
      search: function(event, ui) {
          $("#field_owner").blur();
      },
      close: function(event, ui) {
          $("#field_owner").blur();
      }
  };

  $("#field_owner").autocomplete(username_ac_data);

  $(":input[type=submit]").click(function() {
      $("#field_owner").focus().blur();
  });

  $("#ticketform").submit(function() {
      // some validation here!

      // summary is a must-have field.
      if($.trim($("#field_summary").val()) == "") {
          alert("We need at lease a SUMMARY to create a ticket");
          $("#field_summary").focus();
          return false;
      }

      owner = $("#field_owner");
      if((owner.length <= 0) || owner.attr("disabled")) {
          // skip the owner validation.
          // for no exist field or disabled field.
          return true;
      }

      $("#field_owner").blur();
      if($.trim($("#field_owner").val()) == "") {
          alert("We need a OWNER for a ticket");
          $("#field_owner").focus();
          return false;
      }

      // make sure the invalid fields are empty.
      fields = $.trim($("#invalidFields").val());
      if (fields.length > 0) {
          names = fields.split(" ");
          for (var i = 0; i < names.length; i++) {
              
          }
          // show the alert message.
          alert("Owner \"" + $("#field_owner").val() +
                "\" is not a valid username");
          $("#field_owner").focus().select();
          return false;
      }
  });

  $("#field_owner").blur(function() {
      // now let't make sure the owner is a valid username.
      var data = {
          "action" : "wptc_valid_username",
          "username" : this.value,
      };
      jQuery.post(WptcAjaxObj.url, data, function(response) {
          //alert(response);
          var res = JSON.parse(response);
          if(!res.valid) {
              $("#field_owner").focus();
              //$("#field_owner").select();
              //alert("\"" + res.username + 
              //      "\" is not a valid user name");
              old = $.trim($("#invalidFields").val());
              // check if this field is already exist.
              if (old.indexOf("field_owner") < 0) {
                  newValue = $.trim(old + " " + "field_owner");
                  $("#invalidFields").val(newValue);
              }
          } else {
              var old = $.trim($("#invalidFields").val());
              newV = $.trim(old.replace("field_owner", ""));
              $("#invalidFields").val(newV);
          }
      });
  }); 

  // only enable control elements for 
  // the currently selected action
  var actions = $("#action input[name='action']");
  function updateActionFields() {
    actions.each(function () {
      findIds = $(this).siblings().find("*[id]");
      if (findIds.length > 0) {
        findIds[0].disabled = !this.checked;
      }
      filterIds = $(this).siblings().filter("*[id]");
      if (filterIds.length > 0) {
        filterIds[0].disabled = !this.checked;
      }
    });
  }
  actions.click(updateActionFields);
  updateActionFields();

});

