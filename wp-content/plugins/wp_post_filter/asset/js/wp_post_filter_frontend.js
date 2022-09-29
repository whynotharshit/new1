jQuery(document).ready(function(){

    var wp_post_filter_checkbox_option = wp_filterAjax.wp_post_filter_checkbox_option;
    
    if( wp_post_filter_checkbox_option == 1 ){
        jQuery('input.wp_post_filter-common_selector.first_category').on('change', function() {
            jQuery('input.wp_post_filter-common_selector.first_category').not(this).prop('checked', false);
            var pagination_page_id = 1;
            filter_data(pagination_page_id); 
        });
        jQuery('input.wp_post_filter-common_selector.second_category').on('change', function() {
            jQuery('input.wp_post_filter-common_selector.second_category').not(this).prop('checked', false);
            var pagination_page_id = 1;
            filter_data(pagination_page_id);  
        });
    }

    jQuery(document.body).on('click', '.pagination_page_id', function(){
        jQuery("div.pagination_page_id").removeClass("current");
        jQuery(this).addClass('current');
        var pagination_page_id = jQuery(this).data('pagination_page_id');
        filter_data(pagination_page_id);
    });


    filter_data();

    function filter_data(pagination_page_id)
    {
        jQuery('.filter_data').html('<div id="loading" style="" ></div>');

        var action              = 'wp_post_filterAjax';
        var first_category      = get_filter('first_category');
        var second_category     = get_filter('second_category');
        var pagination_page_id  = pagination_page_id;

        jQuery.ajax({
            method:"POST",
            dataType: 'json',
            data:{
                action                     :action,
                'first_category'           :first_category,
                'second_category'          :second_category,
                'pagination_page_id'       :pagination_page_id,
            },
            url: wp_filterAjax.ajax,
            success:function(data){
                // console.log(data.output);
                jQuery('.wp_post_filter_data').html(data.output);
            }
        });
    }

    // function get_filter(class_name)
    // {
    //     var filter = [];
    //     if( wp_post_filter_checkbox_option == 1 ){
    //         jQuery('.'+class_name+':checked').each(function(){
    //             //filter.length = 0;
    //             filter.push(jQuery(this).val());
    //         });
    //     }else{
    //         jQuery('.'+class_name+':checked').each(function(){
    //             filter.push(jQuery(this).val());
    //         });
    //     }
    //     return filter;
    // }
    function get_filter(class_name)
    {
        var filter = [];
        jQuery('.'+class_name+':checked').each(function(){
            filter.push(jQuery(this).val());
        });
        return filter;
    }

    // jQuery('.wp_post_filter-common_selector').click(function(){
    //     filter_data();
    // });

});