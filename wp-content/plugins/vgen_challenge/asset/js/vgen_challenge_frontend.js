jQuery(document).ready(function(){
  jQuery('.vgen_challenge-related-items .owl-carousel').owlCarousel({
      loop:true,
      margin:20,
      items:1,
      nav:true,
      autoplay:true,
  });
});
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

window.onload = function () {
    var post_id = vgen_challengeAjax.post_id;
    //var all_post_ids = vgen_challengeAjax.all_post_ids;
    var current_page_is_valid = vgen_challengeAjax.current_page_is_valid;
    //document.getElementById("cf7_user_id").value = post_id;
    jQuery("#cf7_user_id").val(post_id);
    //console.log('jony');

    if( current_page_is_valid == 'yes' ){
      //console.log('jony');
      let unique_page_views = getCookie("unique_page_views"+post_id);
      exdays_values = 365;
      if (unique_page_views == "") {
        setCookie("unique_page_views"+post_id, "yes", exdays_values);
      
        var action           = 'vc_unique_page_views_filterAjax';
        jQuery.ajax({
            method:"POST",
            dataType: 'json',
            data:{
                action                     :action,
                'post_id'                  :post_id,
            },
            url: vgen_challengeAjax.ajax,
            success:function(data){
                //console.log(data);
            }
        });

      }

    }
}

setInterval(function() {
  reduseUserCredit();
}, 1 * 5000); // 30 * 1000 milsec

function reduseUserCredit(){
  
  var current_page_is_valid = vgen_challengeAjax.current_page_is_valid;
  if( current_page_is_valid == 'yes' ){
    var action           = 'vc_time_on_that_page_filterAjax';
    var post_id          = vgen_challengeAjax.post_id;
    jQuery.ajax({
        method:"POST",
        dataType: 'json',
        data:{
            action                     :action,
            'post_id'                  :post_id,
        },
        url: vgen_challengeAjax.ajax,
        success:function(data){
        }
    });
  }
  
}
jQuery(document).ready(function($){

  // $('input.vgen_challenge_url_submit_button').click(function(e) {
  //     e.preventDefault();
  // });
  
  // var challenge_submit_welcome_page_url = document.getElementById("challenge_submit_welcome_page_url").value;
  // //window.location.href = challenge_submit_welcome_page_url;
  // console.log( 'yes:' +challenge_submit_welcome_page_url);
  jQuery('.vc_unique_id_44752 input.wpcf7-file').attr("accept", ".zip");
  jQuery('.vc_unique_id_44141 input.wpcf7-file').attr("accept", ".pdf");
});

document.addEventListener( 'wpcf7mailsent', function( event ) {
  var challenge_submit_welcome_page_url = vgen_challengeAjax.challenge_submit_welcome_page_url;
  var want_to_welcome_page = vgen_challengeAjax.want_to_welcome_page;

  if( want_to_welcome_page == 1 ){

      // var action           = 'vc_submit_remove_cache_filterAjax';
      // var post_id          = vgen_challengeAjax.post_id;
      // jQuery.ajax({
      //     method:"POST",
      //     dataType: 'json',
      //     data:{
      //         action                     :action,
      //         'post_id'                  :post_id,
      //     },
      //     url: vgen_challengeAjax.ajax,
      //     success:function(data){
      //       console.log(data);
            jQuery(".vgen-challenge-cover").remove();
            window.location.href = challenge_submit_welcome_page_url;

      //     }
      // });

  }else{
    location.reload();
  }

}, false ); 

jQuery(document).ready(function($){
  jQuery(document.body).on('click', '.vgen_challenge-delete', function(e){
    jQuery(this).parent('.preload-col').remove();
  });


  jQuery(document.body).on('submit', 'form#vgen_challenge_file_submit', function(e){
    e.preventDefault();
    jQuery('.vgen_challenge_filter-loder').fadeIn();
    jQuery(".progress").attr("style", "display:block");
    var form_data = new FormData(this);
    form_data.append("action", 'custom_vgen_challenge_file_sendajax');
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
      url : vgen_challengeAjax.ajax,
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
        jQuery('#uploadStatus').html('<p style="color:#ff0d0d;">File has uploaded failed! Try again.</p>');
      },
      success:function(data){
        console.log(data);
        jQuery('#uploadStatus').html(data.mas);
        jQuery('.vgen_challenge_filter-loder').fadeOut();
        setTimeout(
            function() 
            {   
              jQuery('.vgen_Challenge_submit_cover').html('<div class="vgen_challenge-submit-title">Thank you! for your participation.</div>');
            }, 2000);
        }
    });
  });
 
});


// upload file by mail
const dropArea = document.querySelector(".vgen_challenge-drag-area"),
dragText = dropArea.querySelector("header"),
button = dropArea.querySelector(".vgen_challenge-upload-button"),
button2 = dropArea.querySelector(".vgen_challenge-upload-icon"),
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
  var format = jQuery('.accept_file').val();
  var accept_file = '';
  if( format == '.zip' ){
    accept_file = ["application/x-zip-compressed"];
  }else if( format == '.pdf' ){
    accept_file = ["application/pdf"];
  }else{
    accept_file = ["application/pdf", "application/x-zip-compressed"];
  }

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
      let fileType = files[i].type;
      let validExtensions = accept_file; 
      if(validExtensions.includes(fileType)){
        var file_name = files[i].name;
        jQuery(".vgen_challenge-file_list").append("<div class='preload-col' id=preload_"+uid+" title='"+file_name+"'><div class='vgen_challenge-file-title'>"+file_name+"</div><div class='vgen_challenge-delete'><svg viewBox='0 0 20 20'><path fill='#ffffff' d='M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z'></path></svg></div><input type='file' id='myfile_"+uid+"' class='myfile' name='myfile[]' accept='"+ format +"' multiple='multiple' hidden></div>");
        let back = document.getElementById("myfile_"+uid);
        back.files = dt.files;
        jQuery('#uploadStatus').html('');
      }else{
        jQuery('#uploadStatus').html('<p style="color:#ff0d0d;">The '+ fileType +' file format is not valid! We suggest you upload a '+ format +' file.</p>');
      }
    }
  } else {
    alert("No support for the File API in this web browser");
  }

});

input.addEventListener("change", function(event){
  files = this.files;
  var format = jQuery('.accept_file').val();
  var accept_file = '';
  if( format == '.zip' ){
    accept_file = ["application/x-zip-compressed"];
  }else if( format == '.pdf' ){
    accept_file = ["application/pdf"];
  }else{
    accept_file = ["application/pdf", "application/x-zip-compressed"];
  }

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
      let fileType = files[i].type;
      let validExtensions = accept_file; 
      if(validExtensions.includes(fileType)){
        var file_name = files[i].name;
        jQuery(".vgen_challenge-file_list").append("<div class='preload-col' id=preload_"+uid+" title='"+file_name+"'><div class='vgen_challenge-file-title'>"+file_name+"</div><div class='vgen_challenge-delete'><svg viewBox='0 0 20 20'><path fill='#ffffff' d='M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z'></path></svg></div><input type='file' id='myfile_"+uid+"' class='myfile' name='myfile[]' accept='"+ format +"' multiple='multiple' hidden></div>");
        let back = document.getElementById("myfile_"+uid);
        back.files = dt.files;
        jQuery('#uploadStatus').html('');
      }else{
        jQuery('#uploadStatus').html('<p style="color:#ff0d0d;">The '+ fileType +' file format is not valid! We suggest you upload a '+ format +' file.</p>');
      }
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
  var format = jQuery('.accept_file').val();
  var accept_file = '';
  if( format == '.zip' ){
    accept_file = ["application/x-zip-compressed"];
  }else if( format == '.pdf' ){
    accept_file = ["application/pdf"];
  }else{
    accept_file = ["application/pdf", "application/x-zip-compressed"];
  }
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
      let fileType = files[i].type;
      let validExtensions = accept_file; 
      if(validExtensions.includes(fileType)){
        var file_name = files[i].name;
        jQuery(".vgen_challenge-file_list").append("<div class='preload-col' id=preload_"+uid+" title='"+file_name+"'><div class='vgen_challenge-file-title'>"+file_name+"</div><div class='vgen_challenge-delete'><svg viewBox='0 0 20 20'><path fill='#ffffff' d='M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z'></path></svg></div><input type='file' id='myfile_"+uid+"' class='myfile' name='myfile[]' accept='"+ format +"' multiple='multiple' hidden></div>");
        let back = document.getElementById("myfile_"+uid);
        back.files = dt.files;
        jQuery('#uploadStatus').html('');
      }else{
        jQuery('#uploadStatus').html('<p style="color:#ff0d0d;">The '+ fileType +' file format is not valid! We suggest you upload a '+ format +' file.</p>');
      }
    }
  } else {
    alert("No support for the File API in this web browser");
  }
});

function showFile(file){
    jQuery(".vgen_challenge-file_list").append('<li ">' + file.name + '</li>');
}


