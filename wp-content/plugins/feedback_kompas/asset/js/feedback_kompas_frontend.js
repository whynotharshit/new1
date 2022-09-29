
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
jQuery(document).ready(function($){

    jQuery(document.body).on('click', '.feedback_kompas_open_button', function(e){
        jQuery(this).next(".feedback_kompas_cover").toggleClass("open_flipped");
        jQuery(this).toggleClass("open_button_flipped");
    });
    jQuery(document.body).on('click', '.feedback_kompas_happy_icon', function(e){
        jQuery('#feedback_kompas_modal_happy_overlay').addClass('active');
        jQuery('#feedback_kompas_modal_happy_overlay').find('.modal').addClass('active');
        jQuery(".progress-bar").width(0 + '%');
        jQuery('.progress').attr("style", "display:none");
    });
    jQuery(document.body).on('click', '.feedback_kompas_unhappy_icon', function(e){
        jQuery('#feedback_kompas_modal_unhappy_overlay').addClass('active');
        jQuery('#feedback_kompas_modal_unhappy_overlay').find('.modal').addClass('active');
        jQuery(".progress-bar").width(0 + '%');
        jQuery('.progress').attr("style", "display:none");
    });
    jQuery('.close-modal svg').click(function(){
        jQuery(this).closest('.modal-overlay').removeClass('active');
        jQuery(this).closest('.modal').removeClass('active');
    });

    jQuery(document.body).on('submit', 'form#feedback_kompas_submit', function(e){
        e.preventDefault();
        jQuery(".progress").attr("style", "display:block");
        var form_data = new FormData(this);
        form_data.append("action", 'feedback_kompas_sendajax');
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
          url : feedback_kompas_Ajax.ajax,
          type : 'POST',
          data: form_data,
          dataType: 'json',
          contentType: false,
          cache: false,
          processData: false,
          beforeSend:function(){
            jQuery('.uploadStatus').html('<p style="color:#003752;">vent venligst...</p>');
          }, 
          error:function(){
            jQuery('.uploadStatus').html('<p style="color:#ff0d0d;">Afsendelse af mail mislykkedes!</p>');
          },
          success:function(data){
            jQuery('.uploadStatus').html('<p style="color:#28A74B;">Mail blev afsendt!</p>');
              var exdays_values = 1;
              setCookie("your_feedback_kompas_done", 'yes', exdays_values);
              jQuery('input#feedback_kompas_email').val('');
              jQuery('textarea.feedback_kompas_Message_textarea').val('');
              setTimeout(
                function() 
                {
                    jQuery('.feedback_kompas_massage_ocver').html('<p style="color:#28A74B; margin-top: 20px;">Tak for din besked. Vi vil tage dine ord alvorligt.</p>');
                }, 1000);
                setTimeout(
                  function() 
                  {
                    // jQuery('.modal-overlay').removeClass('active');
                    // jQuery('.modal').removeClass('active');
                    jQuery('.feedback_kompas_start').empty();
                  }, 3000);
          }
        });
      });


});