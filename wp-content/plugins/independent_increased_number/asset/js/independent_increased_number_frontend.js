jQuery(document).ready(function($){
  jQuery(document.body).on('click', '.independent_increased_number-button', function(){  

    var action                      = 'independent_increased_number_filterAjax';

    jQuery.ajax({
        method:"POST",
        dataType: 'json',
        data:{
            action                     :action,
        },
        url: independent_increased_numberAjax.ajax,
        success:function(data){
            jQuery('.independent_increased_number-increment span').html(data.increment);
            jQuery('.independent_increased_number-nonce span').html(data.nonce);
        }
    });
  });

});