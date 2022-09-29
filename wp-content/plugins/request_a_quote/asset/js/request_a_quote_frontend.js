function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
  
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

window.addEventListener("load",function(){

  let raq_open_input_name = getCookie("raq_open_input_name");
  if (raq_open_input_name != "") {
    jQuery("input#raq_open_input_name").val(raq_open_input_name);
  }
  let raq_open_input_title = getCookie("raq_open_input_title");
  if (raq_open_input_title != "") {
    jQuery("input#raq_open_input_title").val(raq_open_input_title);
  }

  let raq_open_input_project_status = getCookie("raq_open_input_project_status");
  if (raq_open_input_project_status != "") {
    jQuery('select#raq_open_input_project_status option[value="'+raq_open_input_project_status+'"]').attr("selected", "selected");
  }

  let raq_open_input_phone_number = getCookie("raq_open_input_phone_number");
  if (raq_open_input_phone_number != "") {
    jQuery("input#raq_open_input_phone_number").val(raq_open_input_phone_number);
  }
  let raq_open_input_email = getCookie("raq_open_input_email");
  if (raq_open_input_email != "") {
    jQuery("input#raq_open_input_email").val(raq_open_input_email);
  }

  let raq_open_input_budget = getCookie("raq_open_input_budget");
  if (raq_open_input_budget != "") {
    jQuery('select#raq_open_input_budget option[value="'+raq_open_input_budget+'"]').attr("selected", "selected");
  }
  var raq_open_input_checkbox = getCookie("raq_open_input_checkbox");
  if (raq_open_input_checkbox != "") {
    jQuery('input.raq_open_input_checkbox[type=checkbox]').map(function () { 
      raq_open_input_checkbox.includes(jQuery(this).val()) ? jQuery(this).attr('checked', true) : jQuery(this).attr('checked', false) 
    });

  }
  let raq_open_input_Message = getCookie("raq_open_input_Message");
  if (raq_open_input_Message != "") {
    jQuery("textarea.raq_open_input_Message").val(raq_open_input_Message);
  }
},false);

jQuery(document).ready(function($){
    var exdays_values = 365;
    jQuery(document.body).on('keyup', "input#raq_open_input_name", function(){
        var raq_open_input_name = this.value;
        console.log( 'jony name: ' + raq_open_input_name);
        setCookie("raq_open_input_name", raq_open_input_name, exdays_values);
    });
    jQuery(document.body).on('keyup', "input#raq_open_input_title", function(){
        var raq_open_input_title = this.value;
        setCookie("raq_open_input_title", raq_open_input_title, exdays_values);
    });

    jQuery(document.body).on('click', 'select#raq_open_input_project_status', function(e){
        var raq_open_input_project_status = this.value;
        setCookie("raq_open_input_project_status", raq_open_input_project_status, exdays_values);
    });

    jQuery(document.body).on('keyup', "input#raq_open_input_phone_number", function(){
        var raq_open_input_phone_number = this.value;
        setCookie("raq_open_input_phone_number", raq_open_input_phone_number, exdays_values);
    });
    jQuery(document.body).on('keyup', "input#raq_open_input_email", function(){
        var raq_open_input_email = this.value;
        console.log( 'jony email: ' + raq_open_input_email);
        setCookie("raq_open_input_email", raq_open_input_email, exdays_values);
    });

    jQuery(document.body).on('click', 'select#raq_open_input_budget', function(e){
        var raq_open_input_budget = this.value;
        setCookie("raq_open_input_budget", raq_open_input_budget, exdays_values);
    });

    jQuery(document.body).on('click', 'select#raq_open_input_budget', function(e){
        var raq_open_input_budget = this.value;
        setCookie("raq_open_input_budget", raq_open_input_budget, exdays_values);
    });

    jQuery(document.body).on('click', 'input.raq_open_input_checkbox', function(e){
      var raq_open_input_checkbox = jQuery('input.raq_open_input_checkbox[type=checkbox]:checked').map(function () {
        return jQuery(this).val()
      }).get().join(',');
      setCookie("raq_open_input_checkbox", raq_open_input_checkbox, exdays_values);
    });

    jQuery(document.body).on('keyup', "textarea.raq_open_input_Message", function(){
        var raq_open_input_Message = this.value;
        setCookie("raq_open_input_Message", raq_open_input_Message, exdays_values);
    });

});

jQuery(document).ready(function($){

    jQuery(document).click( function(event){
      if ( !jQuery(event.target).closest('.request_a_quote_cover').length ) {
        jQuery('.raq_open').removeClass('raq_open-flipped');
        jQuery('.raq_open-button').removeClass('raq_open-button-flipped');
        jQuery('.raq_open-icon').removeClass('raq_open-icon-flipped');
      }
    });

    jQuery(document.body).on('click', '.raq_open-button', function(e){
        jQuery(this).next(".raq_open").toggleClass("raq_open-flipped");
        jQuery(this).toggleClass("raq_open-button-flipped");
        jQuery(this).prev(".raq_open-icon").toggleClass("raq_open-icon-flipped");
    });
    jQuery(document.body).on('click', '.raq_open-icon', function(e){
        jQuery(".raq_open").toggleClass("raq_open-flipped");
        jQuery(this).next(".raq_open-button").toggleClass("raq_open-button-flipped");
        jQuery(this).toggleClass("raq_open-icon-flipped");
    });

    
    jQuery(document.body).on('click', '.raq_open-delete', function(e){
        jQuery(this).parent('.preload-col').remove();
        e.stopPropagation();
        // jQuery(document).click( function(event){
        //   if ( !jQuery(event.target).closest('.request_a_quote_cover').length ) {
        //     jQuery('.raq_open').removeClass('raq_open-flipped');
        //     jQuery('.raq_open-button').removeClass('raq_open-button-flipped');
        //     jQuery('.raq_open-icon').removeClass('raq_open-icon-flipped');
        //   }
        // });
    });

    jQuery(document.body).on('submit', 'form#raq_open_submit', function(e){
      e.preventDefault();
      jQuery(this).animate({
        scrollTop: jQuery("input.raq_open_input_submit-btn").offset().top},
    'slow');
      jQuery('.raq_open_filter-loder').fadeIn();
      jQuery(".progress").attr("style", "display:block");
      var form_data = new FormData(this);
      form_data.append("action", 'request_a_quote_sendajax');
      jQuery.ajax({
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", function(evt) {
              if (evt.lengthComputable) {
                  var percentComplete = ((evt.loaded / evt.total) * 100);
                  jQuery(".progress-bar").width(percentComplete + '%');
                  jQuery(".progress-bar").html(Math.round(percentComplete)+'%');
              }
          }, false);
          return xhr;
        },
        url : raquoteAjax.ajax,
        type : 'POST',
        data: form_data,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend:function(){
          jQuery('#uploadStatus').html('<p style="color:#003752;">Files uploading, please wait...</p>');
        }, 
        error:function(){
          jQuery('#uploadStatus').html('<p style="color:#ff0d0d;">File has uploaded failed! Your File format is not available now! Please create zip and give again.. </p>');
          jQuery('.raq_open_filter-loder').fadeOut();
        },
        success:function(data){
          jQuery('#uploadStatus').html('<p style="color:#28A74B;">File has uploaded successfully!</p>');
            var exdays_values = 365;
            jQuery('input#raq_open_input_title').val('');
            setCookie("raq_open_input_title", '', exdays_values);
            jQuery('textarea.raq_open_input_Message').val('');
            setCookie("raq_open_input_Message", '', exdays_values);
            jQuery('.raq_open-file_list').empty();
            jQuery('.raq_open_filter-loder').fadeOut();
        }
      });
    });
    
});

const dropArea = document.querySelector(".raq_open-drag-area"),
dragText = dropArea.querySelector("header"),
button = dropArea.querySelector(".raq_open-upload-button"),
button2 = dropArea.querySelector(".raq_open-upload-icon"),
input = dropArea.querySelector("input");
let files;

button.onclick = ()=>{
  input.click();
}
let file = document.getElementById("camera_myfile");
button2.onclick = ()=>{
  file.click();
}

file.addEventListener('change', function() {
  files = this.files;

  if (typeof files !== "undefined") { 

    for (var i = 0, l = files.length; i < l; i++) {
      var uid = "id" + Math.random().toString(16).slice(2);

      let dt = new DataTransfer();
      let f = files[i];
      dt.items.add(
        new File(
          [f.slice(0, f.size, f.type)],
          f.name
      ));
      var file_name = files[i].name;
      jQuery(".raq_open-file_list").append("<div class='preload-col' id=preload_"+uid+" title='"+file_name+"'>"+file_name+"<div class='raq_open-delete'></div><input type='file' id='myfile_"+uid+"' class='myfile' name='myfile[]' accept='image/*;capture=camera' multiple='multiple' hidden></div>");
      let back = document.getElementById("myfile_"+uid);
      back.files = dt.files;
    }
  } else {
    alert("No support for the File API in this web browser");
  }

});

input.addEventListener("change", function(event){
  files = this.files;

  if (typeof files !== "undefined") {

    for (var i = 0, l = files.length; i < l; i++) {
      var uid = "id" + Math.random().toString(16).slice(2);

      let dt = new DataTransfer();
      let f = files[i];
      dt.items.add(
        new File(
          [f.slice(0, f.size, f.type)],
          f.name
      ));
      var file_name = files[i].name;
      jQuery(".raq_open-file_list").append("<div class='preload-col' id=preload_"+uid+" title='"+file_name+"'>"+file_name+"<div class='raq_open-delete'></div><input type='file' id='myfile_"+uid+"' class='myfile' name='myfile[]' accept='image/*;capture=camera' multiple='multiple' hidden></div>");
      let back = document.getElementById("myfile_"+uid);
      back.files = dt.files;
    }
  } else {
    alert("No support for the File API in this web browser");
  }
});

dropArea.addEventListener("dragover", (event)=>{
  event.preventDefault();
  dropArea.classList.add("active");
  dragText.textContent = "Release to Upload File";
});

dropArea.addEventListener("dragleave", ()=>{
  dropArea.classList.remove("active");
  dragText.textContent = "Drag & Drop to Upload File";
});


dropArea.addEventListener("drop", (event)=>{
  event.preventDefault();
  files = event.dataTransfer.files;

  if (typeof files !== "undefined") {
    
    for (var i = 0, l = files.length; i < l; i++) {
      var uid = "id" + Math.random().toString(16).slice(2);

      let dt = new DataTransfer();
      let f = files[i];
      dt.items.add(
        new File(
          [f.slice(0, f.size, f.type)],
          f.name
      ));
      var file_name = files[i].name;
      jQuery(".raq_open-file_list").append("<div class='preload-col' id=preload_"+uid+" title='"+file_name+"'>"+file_name+"<div class='raq_open-delete'></div><input type='file' id='myfile_"+uid+"' class='myfile' name='myfile[]' accept='image/*;capture=camera' multiple='multiple' hidden></div>");
      let back = document.getElementById("myfile_"+uid);
      back.files = dt.files;
    }
  } else {
    alert("No support for the File API in this web browser");
  }
});

function showFile(file){
    jQuery(".raq_open-file_list").append('<li ">' + file.name + '</li>');
}
