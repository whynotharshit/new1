jQuery(document).ready(function($){

  jQuery(document.body).on('click', '.modal_download_challenge_tablinks', function(e){

    var values = jQuery(this).attr("data-values");



    jQuery(this).parent('.download-challenge-user-subscriptions-and-participation-button-cover').find( ".modal_download_challenge_tablinks" ).removeClass( "active" );

    jQuery(this).addClass( "active" );

    jQuery(this).parent('.download-challenge-user-subscriptions-and-participation-button-cover').parent('.modal-content.modal-content-download-challenge').find(".modal-download-challenge-cover").find( ".modal-download-challenge-vc-ac-cb-div" ).removeClass( "active" );



    if( values == 'subscriptions' ){

      jQuery(this).parent('.download-challenge-user-subscriptions-and-participation-button-cover').parent('.modal-content.modal-content-download-challenge').find(".modal-download-challenge-cover").find( ".modal-download-challenge-vc-ac-cb-div.modal_download_challenge_user_subscriptions_data" ).addClass( "active" );

    }else{

      jQuery(this).parent('.download-challenge-user-subscriptions-and-participation-button-cover').parent('.modal-content.modal-content-download-challenge').find(".modal-download-challenge-cover").find( ".modal-download-challenge-vc-ac-cb-div.modal_download_challenge_user_participation_data" ).addClass( "active" );

    }

    jQuery(this).parent('.download-challenge-user-subscriptions-and-participation-button-cover').parent('.modal-content.modal-content-download-challenge').parent('.modal-download-challenge').find('.challenge-download-cover').find('input.download_type').val(values);

  });







  jQuery('.challenge_analytics_countdown_for_view').each(function () {

    jQuery(this).prop('Counter',0).animate({

        Counter: jQuery(this).text()

    }, {

        duration: 4000,

        easing: 'swing',

        step: function (now) {

          jQuery(this).text(Math.ceil(now));

        }

    });

  });

  jQuery('.challenge_analytics_countdown').each(function () {

    jQuery(this).prop('Counter',0).animate({

        Counter: jQuery(this).text()

    }, {

        duration: 4000,

        easing: 'swing',

        step: function (now) {

          jQuery(this).text(now.toFixed(2));

        }

    });

  });



  jQuery("input#want_to_welcome_page").on("click",function(event){

    if (jQuery('input#want_to_welcome_page').is(':checked')) {

      jQuery("select#challenge_submit_welcome_page").removeAttr("disabled");

    }else{

      jQuery("select#challenge_submit_welcome_page").attr("disabled", true);

    }

  });

  

  jQuery('.np-edit.add_marks').click(function(){

    //console.log('jony');

    jQuery(this).next('.modal-overlay').addClass('active');

    jQuery(this).next('.modal-overlay').find('.modal').addClass('active');

  });



  jQuery('.close-modal').click(function(){

      jQuery(this).closest('.modal-overlay').removeClass('active');

      jQuery(this).closest('.modal').removeClass('active');

  });



  jQuery('.vgen_challenge_data_export').click(function(){

    jQuery(this).next('.modal-overlay').addClass('active');

    jQuery(this).next('.modal-overlay').find('.modal').addClass('active');

  });



  jQuery('.challenge_subscriptions_button').click(function(){

    jQuery(this).next('.modal-overlay').addClass('active');

    jQuery(this).next('.modal-overlay').find('.modal').addClass('active');

  });





  if(jQuery('.jquerydatatable').length){

    jQuery('.jquerydatatable').DataTable();

  }



  jQuery(document.body).on('click', '.update-marks', function(){  

        

    var action                      = 'vc_marking_update_filterAjax';

    var file_id                     = jQuery(this).closest('div.vc_add_marks_cover').find('input.file_id').val();

    var vc_add_marks_creativity     = jQuery(this).parent('div.vc_add_marks_cover').find('input.vc_add_marks_creativity').val();

    var vc_add_marks_innovation     = jQuery(this).parent('div.vc_add_marks_cover').find('input.vc_add_marks_innovation').val();

    var vc_add_marks_invention      = jQuery(this).parent('div.vc_add_marks_cover').find('input.vc_add_marks_invention').val();



    if( vc_add_marks_creativity <= 10 && vc_add_marks_innovation <= 10 && vc_add_marks_invention <= 10 ){



      jQuery('.vc_marking_update-loder-loder').fadeIn();

      jQuery.ajax({

          method:"POST",

          dataType: 'json',

          data:{

              action                     :action,

              'file_id'                  :file_id,

              'vc_add_marks_creativity'  :vc_add_marks_creativity,

              'vc_add_marks_innovation'  :vc_add_marks_innovation,

              'vc_add_marks_invention'   :vc_add_marks_invention,

          },

          url: vgen_challengeAjax.ajax,

          success:function(data){

            

              //console.log(data);

              jQuery('.vgen-user-marks-creativity'+data.file_id).text(data.vc_add_marks_creativity);

              jQuery('.vgen-user-marks-innovation'+data.file_id).text(data.vc_add_marks_innovation);

              jQuery('.vgen-user-marks-invention'+data.file_id).text(data.vc_add_marks_invention);

              jQuery('.modal-overlay').removeClass('active');

              jQuery('.modal').removeClass('active');

              jQuery('.vc_marking_update-loder-loder').fadeOut();

          }

      });

    }else{

      alert("You can't enter more than 10 marks");

    }



  });



  jQuery(document.body).on('click', '.challenge-download-button', function(){

      

    jQuery('.vc_marking_update-loder-loder').fadeIn();

    var action           = 'vc_download_challenge_filterAjax';

    var user_id          = jQuery(this).closest('div.challenge-download-cover').find('input.user_id').val();

    var challenge_id     = jQuery(this).closest('div.challenge-download-cover').find('input.challenge_id').val();

    var download_type    = jQuery(this).closest('div.challenge-download-cover').find('input.download_type').val();



    var user_mata_access_vgen_challenge = jQuery(this).closest('div.challenge-download-cover').prev('.modal-content.modal-content-download-challenge').find('input[name="user_mata_access_vgen_challenge[]"]:checked');

    var aIds = [];

    for(var x = 0, l = user_mata_access_vgen_challenge.length; x < l;  x++)

    {

        aIds.push(user_mata_access_vgen_challenge[x].value);

    }

    var user_mata_access_vgen_challenge_value = aIds.join(',');



    var user_mata_access_vgen_subscriptions_challenge_by_admin = jQuery(this).closest('div.challenge-download-cover').prev('.modal-content.modal-content-download-challenge').find('input[name="user_mata_access_vgen_subscriptions_challenge_by_admin[]"]:checked');

    var aIds = [];

    for(var x = 0, l = user_mata_access_vgen_subscriptions_challenge_by_admin.length; x < l;  x++)

    {

        aIds.push(user_mata_access_vgen_subscriptions_challenge_by_admin[x].value);

    }

    var user_mata_access_vgen_subscriptions_challenge_by_admin_value = aIds.join(',');

    console.log('user_mata_access_vgen_subscriptions_challenge_by_admin: '+ user_mata_access_vgen_subscriptions_challenge_by_admin);



    jQuery.ajax({

        method:"POST",

        dataType: 'json',

        data:{

            action                                   : action,

            'user_id'                                : user_id,

            'challenge_id'                           : challenge_id,

            'download_type'                          : download_type,

            'user_mata_access_vgen_challenge_value'  : user_mata_access_vgen_challenge_value,

            'user_mata_access_vgen_subscriptions_challenge_by_admin_value'  : user_mata_access_vgen_subscriptions_challenge_by_admin_value,

        },

        url: vgen_challengeAjax.ajax,

        success:function(data){

          if(data.message == 'success'){

            var download_type = data.download_type;

            var aTag = document.createElement('a');

            aTag.setAttribute('href', data.download_url);

            aTag.setAttribute('id', 'csvdownload');

            aTag.setAttribute('download', 'vgen_challenge_user_'+download_type+'_data.csv');

            aTag.click();

            //console.log(data);

            jQuery('.vc_marking_update-loder-loder').fadeOut();

          }

        }

    });



  });



  

  jQuery(document.body).on('submit', 'form.user_subscriptions_mail_submit', function(e){

    e.preventDefault();

    jQuery('.vgen_challenge_filter-loder').fadeIn();

    jQuery(".progress").attr("style", "display:block");

    var form_data = new FormData(this);

    form_data.append("action", 'mail_subscriptions_send_Ajax');

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

        jQuery('#test_uploadStatus').html('<p style="color:#003752;">Mail sending, please wait...</p>');

      }, 

      error:function(){

        jQuery('#test_uploadStatus').html('<p style="color:#ff0d0d;">Mail sending failed! please try again.</p>');

      },

      success:function(data){

        jQuery('.vgen_challenge_filter-loder').fadeOut();

        jQuery('.vgen_challenge_mail_sending_status_error').html(data.email_sending_notes);

      }

    });

  });



  jQuery(document.body).on('click', '.challenge-download-user-email-button', function(){

      

    jQuery('.vgen_challenge_filter-loder').fadeIn();

    var action           = 'vc_download_challenge_filterAjax';

    var user_id          = jQuery(this).closest('div.challenge-download-cover').find('input.user_id').val();

    var challenge_id     = jQuery(this).closest('div.challenge-download-cover').find('input.challenge_id').val();

    var user_mata_access_vgen_challenge = 'user_email';

    var subscribe_user_mail = 'yes';

    jQuery.ajax({

        method:"POST",

        dataType: 'json',

        data:{

            action                                   : action,

            'user_id'                                : user_id,

            'challenge_id'                           : challenge_id,

            'user_mata_access_vgen_challenge_value'  : user_mata_access_vgen_challenge,

            'subscribe_user_mail'                    : subscribe_user_mail,

        },

        url: vgen_challengeAjax.ajax,

        success:function(data){

          //console.log(data);

          if(data.message == 'success'){

            var aTag = document.createElement('a');

            aTag.setAttribute('href', data.download_url);

            aTag.setAttribute('id', 'csvdownload');

            aTag.setAttribute('download', 'vgen_challenge_user_data.csv');

            aTag.click();

            //console.log(data);

            jQuery('.vgen_challenge_filter-loder').fadeOut();

          }

        }

    });



  });



  jQuery(document.body).on('click', '.vgen_single_challenge_data', function(){  

        

    var post_id          = jQuery(this).data('post_id');

    var action           = 'vc_single_challenge_data_filterAjax';

    

    jQuery.ajax({

        method:"POST",

        dataType: 'json',

        data:{

          'post_id'               : post_id,

          action                  : action,

        },

        url: vgen_challengeAjax.ajax,

        success:function(data){

          if(data.message == 'success'){

            //console.log(data);

            var post_id = data.post_id;



            jQuery("#vgen_challenge_checkbox_subscriptions_all_"+post_id).change(function(){

              if(this.checked){

                jQuery(".vgen_challenge_checkbox_subscription_checkSingle").each(function(){

                    this.checked=true;

                })              

              }else{

                jQuery(".vgen_challenge_checkbox_subscription_checkSingle").each(function(){

                    this.checked=false;

                })              

              }

            });

            

            jQuery(".vgen_challenge_checkbox_subscription_checkSingle").click(function () {

              if (jQuery(this).is(":checked")){

              var isAllChecked = 0;

              jQuery(".vgen_challenge_checkbox_subscription_checkSingle").each(function(){

                  if(!this.checked)

                      isAllChecked = 1;

              })              

              if(isAllChecked == 0){ jQuery("#vgen_challenge_checkbox_subscriptions_all_"+post_id).prop("checked", true); }     

              }else {

              jQuery("#vgen_challenge_checkbox_subscriptions_all_"+post_id).prop("checked", false);

              }

          });



            // particular and unique start

            var single_challenge_particular_page_views = data.particular_page_views_value;

            var single_challenge_unique_page_views = data.unique_page_views_value;

            var single_challenge_particular_page_views_values = [];

            for (var j in single_challenge_particular_page_views) {

                var newObject = {};

                newObject['date'] = j;

                newObject['value'] = single_challenge_particular_page_views[j];

                single_challenge_particular_page_views_values.push(newObject);

            }

            var single_challenge_unique_page_views_values = [];

            for (var j in single_challenge_unique_page_views) {

                var newObject = {};

                newObject['date'] = j;

                newObject['value'] = single_challenge_unique_page_views[j];

                single_challenge_unique_page_views_values.push(newObject);

            }

            // Themes begin

            am4core.useTheme(am4themes_animated);

            // Themes end

            // Create chart instance

            var chart = am4core.create("challenge_particular_and_unique_page_views_par_challenge"+post_id, am4charts.XYChart);

            // Enable chart cursor

            chart.cursor = new am4charts.XYCursor();

            chart.cursor.lineX.disabled = true;

            chart.cursor.lineY.disabled = true;

            // Enable scrollbar

            chart.scrollbarX = new am4core.Scrollbar();

            // Add data

            chart.data = single_challenge_particular_page_views_values;

            // Create axes

            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

            dateAxis.renderer.grid.template.location = 0.5;

            dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";

            dateAxis.renderer.minGridDistance = 40;

            dateAxis.tooltipDateFormat = "MMM dd, yyyy";

            dateAxis.dateFormats.setKey("day", "dd");

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            // Create series

            var series = chart.series.push(new am4charts.LineSeries());

            series.tooltipText = "{date}\n[font-size: 17px]Particular Page Views: {valueY}[/]";

            series.dataFields.valueY = "value";

            series.dataFields.dateX = "date";

            series.strokeDasharray = 3;

            series.strokeWidth = 2

            series.strokeOpacity = 1;

            series.strokeDasharray = "3,3"

            var bullet = series.bullets.push(new am4charts.CircleBullet());

            bullet.strokeWidth = 2;

            bullet.stroke = am4core.color("#fff");

            bullet.setStateOnChildren = true;

            bullet.propertyFields.fillOpacity = "opacity";

            bullet.propertyFields.strokeOpacity = "opacity";

            var hoverState = bullet.states.create("hover");

            hoverState.properties.scale = 1.7;

            function createTrendLine_particular_and_unique(data) {

              var trend = chart.series.push(new am4charts.LineSeries());

              trend.dataFields.valueY = "value";

              trend.dataFields.dateX = "date";

              trend.strokeWidth = 2

              trend.stroke = trend.fill = am4core.color("#a367dc");

              trend.data = data;

              var bullet = trend.bullets.push(new am4charts.CircleBullet());

              bullet.tooltipText = "{date}\n[font-size: 17px]Unique Page Views: {valueY}[/]";

              bullet.strokeWidth = 2;

              bullet.stroke = am4core.color("#fff")

              bullet.circle.fill = trend.stroke;

              var hoverState = bullet.states.create("hover");

              hoverState.properties.scale = 1.7;

              return trend;

            };

            createTrendLine_particular_and_unique(single_challenge_unique_page_views_values);

            // particular and unique end

            

            // average and Viewing start

            // var single_challenge_average_time_on_that_pages = data.average_time_on_that_page_value;

            // var single_challenge_time_on_that_page = data.time_on_that_page_value;

            // var single_challenge_average_time_on_that_pages_values = [];

            // for (var j in single_challenge_average_time_on_that_pages) {

            //     var newObject = {};

            //     newObject['date'] = j;

            //     newObject['value'] = single_challenge_average_time_on_that_pages[j];

            //     single_challenge_average_time_on_that_pages_values.push(newObject);

            // }

            // var single_challenge_time_on_that_page_values = [];

            // for (var j in single_challenge_time_on_that_page) {

            //     var newObject = {};

            //     newObject['date'] = j;

            //     newObject['value'] = single_challenge_time_on_that_page[j];

            //     single_challenge_time_on_that_page_values.push(newObject);

            // }

            // am4core.useTheme(am4themes_animated);

            // var chart = am4core.create("challenge_time_on_that_page_par_challenge"+post_id, am4charts.XYChart);

            // chart.cursor = new am4charts.XYCursor();

            // chart.cursor.lineX.disabled = true;

            // chart.cursor.lineY.disabled = true;

            // chart.scrollbarX = new am4core.Scrollbar();

            // chart.data = single_challenge_average_time_on_that_pages_values;

            // var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

            // dateAxis.renderer.grid.template.location = 0.5;

            // dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";

            // dateAxis.renderer.minGridDistance = 40;

            // dateAxis.tooltipDateFormat = "MMM dd, yyyy";

            // dateAxis.dateFormats.setKey("day", "dd");

            // var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            // var series = chart.series.push(new am4charts.LineSeries());

            // series.tooltipText = "[font-size: 17px]Average Time On that Page: {valueY}[/] Minutes";

            // series.dataFields.valueY = "value";

            // series.dataFields.dateX = "date";

            // series.strokeDasharray = 3;

            // series.strokeWidth = 2

            // series.strokeOpacity = 1;

            // series.strokeDasharray = "3,3"

            // var bullet = series.bullets.push(new am4charts.CircleBullet());

            // bullet.strokeWidth = 2;

            // bullet.stroke = am4core.color("#fff");

            // bullet.setStateOnChildren = true;

            // bullet.propertyFields.fillOpacity = "opacity";

            // bullet.propertyFields.strokeOpacity = "opacity";

            // var hoverState = bullet.states.create("hover");

            // hoverState.properties.scale = 1.7;

            // function createTrendLine_average_and_Viewing(data) {

            //   var trend = chart.series.push(new am4charts.LineSeries());

            //   trend.dataFields.valueY = "value";

            //   trend.dataFields.dateX = "date";

            //   trend.strokeWidth = 2

            //   trend.stroke = trend.fill = am4core.color("#a367dc");

            //   trend.data = data;

            //   var bullet = trend.bullets.push(new am4charts.CircleBullet());

            //   bullet.tooltipText = "{date}\n[font-size: 17px]Time On that Page: {valueY}[/] Minutes";

            //   bullet.strokeWidth = 2;

            //   bullet.stroke = am4core.color("#fff")

            //   bullet.circle.fill = trend.stroke;

            //   var hoverState = bullet.states.create("hover");

            //   hoverState.properties.scale = 1.7;

            //   return trend;

            // };

            // createTrendLine_average_and_Viewing(single_challenge_time_on_that_page_values);

            // average and Viewing end



            // subscribe and participation start

            var single_challenge_participation = data.challenge_participation;

            var single_challenge_subscriptions = data.challenge_subscriptions;

            var single_challenge_participation_values = [];

            for (var j in single_challenge_participation) {

                var newObject = {};

                newObject['date'] = j;

                newObject['value'] = single_challenge_participation[j];

                single_challenge_participation_values.push(newObject);

            }



            var single_challenge_subscriptions_values = [];

            for (var j in single_challenge_subscriptions) {

                var newObject = {};

                newObject['date'] = j;

                newObject['value'] = single_challenge_subscriptions[j];

                single_challenge_subscriptions_values.push(newObject);

            }

            am4core.useTheme(am4themes_animated);

            var chart = am4core.create("challenge_subscribe_and_participation_rate_par_challenge"+post_id, am4charts.XYChart);

            chart.cursor = new am4charts.XYCursor();

            chart.cursor.lineX.disabled = true;

            chart.cursor.lineY.disabled = true;

            chart.scrollbarX = new am4core.Scrollbar();

            chart.data = single_challenge_participation_values;

            var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

            dateAxis.renderer.grid.template.location = 0.5;

            dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";

            dateAxis.renderer.minGridDistance = 40;

            dateAxis.tooltipDateFormat = "MMM dd, yyyy";

            dateAxis.dateFormats.setKey("day", "dd");

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            var series = chart.series.push(new am4charts.LineSeries());

            series.tooltipText = "{date}\n[font-size: 17px]User Participation Value: {valueY}[/]";

            series.dataFields.valueY = "value";

            series.dataFields.dateX = "date";

            series.strokeDasharray = 3;

            series.strokeWidth = 2

            series.strokeOpacity = 1;

            series.strokeDasharray = "3,3"

            var bullet = series.bullets.push(new am4charts.CircleBullet());

            bullet.strokeWidth = 2;

            bullet.stroke = am4core.color("#fff");

            bullet.setStateOnChildren = true;

            bullet.propertyFields.fillOpacity = "opacity";

            bullet.propertyFields.strokeOpacity = "opacity";

            var hoverState = bullet.states.create("hover");

            hoverState.properties.scale = 1.7;

            function createTrendLine_subscribe_and_participation(data) {

              var trend = chart.series.push(new am4charts.LineSeries());

              trend.dataFields.valueY = "value";

              trend.dataFields.dateX = "date";

              trend.strokeWidth = 2

              trend.stroke = trend.fill = am4core.color("#a367dc");

              trend.data = data;

              var bullet = trend.bullets.push(new am4charts.CircleBullet());

              bullet.tooltipText = "{date}\n[font-size: 17px]User Subscriptions Value: {valueY}[/]";

              bullet.strokeWidth = 2;

              bullet.stroke = am4core.color("#fff")

              bullet.circle.fill = trend.stroke;

              var hoverState = bullet.states.create("hover");

              hoverState.properties.scale = 1.7;

              return trend;

            };

            createTrendLine_subscribe_and_participation(single_challenge_subscriptions_values);

            // subscribe and participation end







          // top 5 users values start

          var challenge_top_users_name_array = data.challenge_top_users_name;

          var challenge_top_users_user_creativity_marks_array = data.challenge_top_users_user_creativity_marks;

          var challenge_top_users_user_innovation_marks_array = data.challenge_top_users_user_innovation_marks;

          var challenge_top_users_user_invention_marks_array = data.challenge_top_users_user_invention_marks;

          var marks_first_like_creativity = vgen_challengeAjax.marks_first_like_creativity;

          var marks_second_like_innovation = vgen_challengeAjax.marks_second_like_innovation;

          var marks_third_like_invention = vgen_challengeAjax.marks_third_like_invention;



          var challenge_top_users_values = [];

          for (var j in challenge_top_users_name_array) {

              var newObject = {};

              newObject['category'] = challenge_top_users_name_array[j];

              newObject['first'] = challenge_top_users_user_creativity_marks_array[j];

              newObject['second'] = challenge_top_users_user_innovation_marks_array[j];

              newObject['third'] = challenge_top_users_user_invention_marks_array[j];

              challenge_top_users_values.push(newObject);

          }

          am4core.useTheme(am4themes_animated);

          var chart = am4core.create('challenge_top_10_users'+post_id, am4charts.XYChart)

          chart.colors.step = 2;

          chart.legend = new am4charts.Legend()

          chart.legend.position = 'top'

          chart.legend.paddingBottom = 20

          chart.legend.labels.template.maxWidth = 95

          var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())

          xAxis.dataFields.category = 'category'

          xAxis.renderer.cellStartLocation = 0.1

          xAxis.renderer.cellEndLocation = 0.9

          xAxis.renderer.grid.template.location = 0;

          var yAxis = chart.yAxes.push(new am4charts.ValueAxis());

          yAxis.min = 0;

          function createSeries(value, name) {

              var series = chart.series.push(new am4charts.ColumnSeries())

              series.dataFields.valueY = value

              series.dataFields.categoryX = 'category'

              series.name = name

              series.events.on("hidden", arrangeColumns);

              series.events.on("shown", arrangeColumns);

              var bullet = series.bullets.push(new am4charts.LabelBullet())

              bullet.interactionsEnabled = false

              bullet.dy = 30;

              bullet.label.text = '{valueY}'

              bullet.label.fill = am4core.color('#ffffff')

              return series;

          }

          chart.data = challenge_top_users_values;

          createSeries('first', marks_first_like_creativity);

          createSeries('second', marks_second_like_innovation);

          createSeries('third', marks_third_like_invention);

          function arrangeColumns() {

              var series = chart.series.getIndex(0);

              var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);

              if (series.dataItems.length > 1) {

                  var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");

                  var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");

                  var delta = ((x1 - x0) / chart.series.length) * w;

                  if (am4core.isNumber(delta)) {

                      var middle = chart.series.length / 2;

                      var newIndex = 0;

                      chart.series.each(function(series) {

                          if (!series.isHidden && !series.isHiding) {

                              series.dummyData = newIndex;

                              newIndex++;

                          }

                          else {

                              series.dummyData = chart.series.indexOf(series);

                          }

                      })

                      var visibleCount = newIndex;

                      var newMiddle = visibleCount / 2;

                      chart.series.each(function(series) {

                          var trueIndex = chart.series.indexOf(series);

                          var newIndex = series.dummyData;

                          var dx = (newIndex - trueIndex + middle - newMiddle) * delta

                          series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);

                          series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);

                      })

                  }

              }

          }

          // top 5 users values end



          }

        }

    });



  });







});



function openTab(evt, cityName) {

  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");

  jQuery('.tabcontent').hide();

  tablinks = document.getElementsByClassName("tablinks");

  for (i = 0; i < tablinks.length; i++) {

      tablinks[i].className = tablinks[i].className.replace(" active", "");

  }

  document.getElementById(cityName).style.display = "block";

  evt.currentTarget.className += " active";

}







window.onload = function () {

  var is_analytics_page = vgen_challengeAjax.is_analytics_page;

  if( is_analytics_page == 'yes' ){



    // var challenge_title = vgen_challengeAjax.challenge_title;

    // var challenge_subscribe = vgen_challengeAjax.challenge_subscribe;

    // var challenge_participation = vgen_challengeAjax.challenge_participation;



    // var challenge_subscribe_and_participation_rate_value = [];



    // for(var i in challenge_title){

    //     var newObject = {};

    //     newObject['challenge_title'] = challenge_title[i];

    //     newObject['challenge_subscribe'] = challenge_subscribe[i];

    //     newObject['challenge_participation'] = challenge_participation[i];

    //     challenge_subscribe_and_participation_rate_value.push(newObject);

    // }

    // //console.log(challenge_subscribe_and_participation_rate_value);



    // am4core.ready(function() {



    //   // Themes begin

    //   am4core.useTheme(am4themes_animated);

    //   // Themes end

      

    //   // Create chart instance

    //   var chart = am4core.create("challenge_subscribe_and_participation_rate", am4charts.XYChart3D);

      

    //   // Add data

    //   chart.data = challenge_subscribe_and_participation_rate_value;

      

    //   // Create axes

    //   var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());

    //   categoryAxis.dataFields.category = "challenge_title";

    //   categoryAxis.renderer.grid.template.location = 0;

    //   categoryAxis.renderer.minGridDistance = 30;

      

    //   var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    //   valueAxis.title.text = "Challenge Subscribe and Participation Rate";

    //   valueAxis.renderer.labels.template.adapter.add("text", function(text) {

    //     return text;

    //   });

        

    //   // Create series

    //   var series = chart.series.push(new am4charts.ColumnSeries3D());

    //   series.dataFields.valueY = "challenge_participation";

    //   series.dataFields.categoryX = "challenge_title";

    //   series.name = "Challenge Participation";

    //   series.clustered = false;

    //   series.columns.template.tooltipText = "{challenge_title} Total Participation: [bold]{valueY}[/]";

    //   series.columns.template.fillOpacity = 0.9;

      

    //   var series2 = chart.series.push(new am4charts.ColumnSeries3D());

    //   series2.dataFields.valueY = "challenge_subscribe";

    //   series2.dataFields.categoryX = "challenge_title";

    //   series2.name = "Challenge Subscribe";

    //   series2.clustered = false;

    //   series2.columns.template.tooltipText = "{challenge_title} Total Subscriber: [bold]{valueY}[/]";

      

    // }); // end am4core.ready()



    // active challenge

    var active_challenge_title = vgen_challengeAjax.active_challenge_title;

    var active_challenge_subscribe = vgen_challengeAjax.active_challenge_subscribe;

    var active_challenge_participation = vgen_challengeAjax.active_challenge_participation;



    var active_challenge_subscribe_and_participation_rate_value = [];



    for(var i in active_challenge_title){

        var newObject = {};

        newObject['active_challenge_title'] = active_challenge_title[i];

        newObject['active_challenge_subscribe'] = active_challenge_subscribe[i];

        newObject['active_challenge_participation'] = active_challenge_participation[i];

        active_challenge_subscribe_and_participation_rate_value.push(newObject);

    }

    

    am4core.ready(function() {

      

      // Themes begin

      am4core.useTheme(am4themes_animated);

      // Themes end

      

      // Create chart instance

      var chart = am4core.create("active_challenge_subscribe_and_participation_rate", am4charts.XYChart3D);

      

      // Add data

      chart.data = active_challenge_subscribe_and_participation_rate_value;

      

      // Create axes

      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());

      categoryAxis.dataFields.category = "active_challenge_title";

      categoryAxis.renderer.grid.template.location = 0;

      categoryAxis.renderer.minGridDistance = 30;

      

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

      valueAxis.title.text = "Challenge Subscribe and Participation Rate";

      valueAxis.renderer.labels.template.adapter.add("text", function(text) {

        return text;

      });

        

      // Create series

      var series = chart.series.push(new am4charts.ColumnSeries3D());

      series.dataFields.valueY = "active_challenge_participation";

      series.dataFields.categoryX = "active_challenge_title";

      series.name = "Challenge Participation";

      series.clustered = false;

      series.columns.template.tooltipText = "{active_challenge_title} Total Participation: [bold]{valueY}[/]";

      series.columns.template.fillOpacity = 0.9;

      

      var series2 = chart.series.push(new am4charts.ColumnSeries3D());

      series2.dataFields.valueY = "active_challenge_subscribe";

      series2.dataFields.categoryX = "active_challenge_title";

      series2.name = "Challenge Subscribe";

      series2.clustered = false;

      series2.columns.template.tooltipText = "{active_challenge_title} Total Subscriber: [bold]{valueY}[/]";

      

    }); // end am4core.ready()





    //single challenge Particular and Unique Page



    // var single_challenge_particular_page_views = vgen_challengeAjax.single_challenge_particular_page_views;

    // var single_challenge_unique_page_views = vgen_challengeAjax.single_challenge_unique_page_views;

    // // console.log(single_challenge_particular_page_views);

    // var counter = 0;

    // for(var i in single_challenge_unique_page_views){

    //   // console.log(i);



    //   var single_challenge_particular_page_views_values = [];

    //   for (var j in single_challenge_particular_page_views[i]) {

    //       var newObject = {};

    //       newObject['date'] = j;

    //       newObject['value'] = single_challenge_particular_page_views[i][j];

    //       single_challenge_particular_page_views_values.push(newObject);

    //   }



    //   var single_challenge_unique_page_views_values = [];

    //   for (var j in single_challenge_unique_page_views[i]) {

    //       var newObject = {};

    //       newObject['date'] = j;

    //       newObject['value'] = single_challenge_unique_page_views[i][j];

    //       single_challenge_unique_page_views_values.push(newObject);

    //   }



    //   // console.log(single_challenge_particular_page_views_values);

    

    //   // Themes begin

    //   am4core.useTheme(am4themes_animated);

    //   // Themes end



    //   // Create chart instance

    //   var chart = am4core.create("challenge_particular_and_unique_page_views_par_challenge"+i, am4charts.XYChart);

    //   counter++;

    //   // Enable chart cursor

    //   chart.cursor = new am4charts.XYCursor();

    //   chart.cursor.lineX.disabled = true;

    //   chart.cursor.lineY.disabled = true;



    //   // Enable scrollbar

    //   chart.scrollbarX = new am4core.Scrollbar();



    //   // Add data

    //   chart.data = single_challenge_particular_page_views_values;



    //   // Create axes

    //   var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

    //   dateAxis.renderer.grid.template.location = 0.5;

    //   dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";

    //   dateAxis.renderer.minGridDistance = 40;

    //   dateAxis.tooltipDateFormat = "MMM dd, yyyy";

    //   dateAxis.dateFormats.setKey("day", "dd");



    //   var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());



    //   // Create series

    //   var series = chart.series.push(new am4charts.LineSeries());

    //   series.tooltipText = "{date}\n[font-size: 17px]Particular Page Views: {valueY}[/]";

    //   series.dataFields.valueY = "value";

    //   series.dataFields.dateX = "date";

    //   series.strokeDasharray = 3;

    //   series.strokeWidth = 2

    //   series.strokeOpacity = 1;

    //   series.strokeDasharray = "3,3"



    //   var bullet = series.bullets.push(new am4charts.CircleBullet());

    //   bullet.strokeWidth = 2;

    //   bullet.stroke = am4core.color("#fff");

    //   bullet.setStateOnChildren = true;

    //   bullet.propertyFields.fillOpacity = "opacity";

    //   bullet.propertyFields.strokeOpacity = "opacity";



    //   var hoverState = bullet.states.create("hover");

    //   hoverState.properties.scale = 1.7;



    //   function createTrendLine(data) {

    //     var trend = chart.series.push(new am4charts.LineSeries());

    //     trend.dataFields.valueY = "value";

    //     trend.dataFields.dateX = "date";

    //     trend.strokeWidth = 2

    //     trend.stroke = trend.fill = am4core.color("#a367dc");

    //     trend.data = data;



    //     var bullet = trend.bullets.push(new am4charts.CircleBullet());

    //     bullet.tooltipText = "{date}\n[font-size: 17px]Unique Page Views: {valueY}[/]";

    //     bullet.strokeWidth = 2;

    //     bullet.stroke = am4core.color("#fff")

    //     bullet.circle.fill = trend.stroke;



    //     var hoverState = bullet.states.create("hover");

    //     hoverState.properties.scale = 1.7;



    //     return trend;

    //   };



    //   createTrendLine(single_challenge_unique_page_views_values);

    // }



    //single challenge Time On that Page

    // var single_challenge_average_time_on_that_pages = vgen_challengeAjax.single_challenge_average_time_on_that_pages;

    // var single_challenge_time_on_that_page = vgen_challengeAjax.single_challenge_time_on_that_page;

    // // console.log(single_challenge_average_time_on_that_pages);

    // var counter = 0;

    // for(var i in single_challenge_time_on_that_page){

    //   // console.log(i);

      

      

    //   //jQuery(".challenge-subscribe-and-participation-title"+i).text(challenge_title[i]);



    //   var single_challenge_average_time_on_that_pages_values = [];

    //   for (var j in single_challenge_average_time_on_that_pages[i]) {

    //       var newObject = {};

    //       newObject['date'] = j;

    //       newObject['value'] = single_challenge_average_time_on_that_pages[i][j];

    //       single_challenge_average_time_on_that_pages_values.push(newObject);

    //   }



    //   var single_challenge_time_on_that_page_values = [];

    //   for (var j in single_challenge_time_on_that_page[i]) {

    //       var newObject = {};

    //       newObject['date'] = j;

    //       newObject['value'] = single_challenge_time_on_that_page[i][j];

    //       single_challenge_time_on_that_page_values.push(newObject);

    //   }



    //   // console.log(single_challenge_average_time_on_that_pages_values);

    

    //   // Themes begin

    //   am4core.useTheme(am4themes_animated);

    //   // Themes end



    //   // Create chart instance

    //   var chart = am4core.create("challenge_time_on_that_page_par_challenge"+i, am4charts.XYChart);

    //   counter++;

    //   // Enable chart cursor

    //   chart.cursor = new am4charts.XYCursor();

    //   chart.cursor.lineX.disabled = true;

    //   chart.cursor.lineY.disabled = true;



    //   // Enable scrollbar

    //   chart.scrollbarX = new am4core.Scrollbar();



    //   // Add data

    //   chart.data = single_challenge_average_time_on_that_pages_values;



    //   // Create axes

    //   var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

    //   dateAxis.renderer.grid.template.location = 0.5;

    //   dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";

    //   dateAxis.renderer.minGridDistance = 40;

    //   dateAxis.tooltipDateFormat = "MMM dd, yyyy";

    //   dateAxis.dateFormats.setKey("day", "dd");



    //   var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());



    //   // Create series

    //   var series = chart.series.push(new am4charts.LineSeries());

    //   series.tooltipText = "[font-size: 17px]Average Time On that Page: {valueY}[/] Minutes";

    //   series.dataFields.valueY = "value";

    //   series.dataFields.dateX = "date";

    //   series.strokeDasharray = 3;

    //   series.strokeWidth = 2

    //   series.strokeOpacity = 1;

    //   series.strokeDasharray = "3,3"



    //   var bullet = series.bullets.push(new am4charts.CircleBullet());

    //   bullet.strokeWidth = 2;

    //   bullet.stroke = am4core.color("#fff");

    //   bullet.setStateOnChildren = true;

    //   bullet.propertyFields.fillOpacity = "opacity";

    //   bullet.propertyFields.strokeOpacity = "opacity";



    //   var hoverState = bullet.states.create("hover");

    //   hoverState.properties.scale = 1.7;



    //   function createTrendLine(data) {

    //     var trend = chart.series.push(new am4charts.LineSeries());

    //     trend.dataFields.valueY = "value";

    //     trend.dataFields.dateX = "date";

    //     trend.strokeWidth = 2

    //     trend.stroke = trend.fill = am4core.color("#a367dc");

    //     trend.data = data;



    //     var bullet = trend.bullets.push(new am4charts.CircleBullet());

    //     bullet.tooltipText = "{date}\n[font-size: 17px]Time On that Page: {valueY}[/] Minutes";

    //     bullet.strokeWidth = 2;

    //     bullet.stroke = am4core.color("#fff")

    //     bullet.circle.fill = trend.stroke;



    //     var hoverState = bullet.states.create("hover");

    //     hoverState.properties.scale = 1.7;



    //     return trend;

    //   };



    //   createTrendLine(single_challenge_time_on_that_page_values);

    // }



    //single challenge subscribe and participation

    // var single_challenge_participation = vgen_challengeAjax.single_challenge_participation;

    // var single_challenge_subscriptions = vgen_challengeAjax.single_challenge_subscriptions;

    // // console.log(single_challenge_participation);

    // var counter = 0;

    // for(var i in single_challenge_subscriptions){

    //   // console.log(i);

      

      

    //   jQuery(".challenge-subscribe-and-participation-title"+i).text(challenge_title[i]);



    //   var single_challenge_participation_values = [];

    //   for (var j in single_challenge_participation[i]) {

    //       var newObject = {};

    //       newObject['date'] = j;

    //       newObject['value'] = single_challenge_participation[i][j];

    //       single_challenge_participation_values.push(newObject);

    //   }



    //   var single_challenge_subscriptions_values = [];

    //   for (var j in single_challenge_subscriptions[i]) {

    //       var newObject = {};

    //       newObject['date'] = j;

    //       newObject['value'] = single_challenge_subscriptions[i][j];

    //       single_challenge_subscriptions_values.push(newObject);

    //   }



    //   // console.log(single_challenge_participation_values);

    

    //   // Themes begin

    //   am4core.useTheme(am4themes_animated);

    //   // Themes end



    //   // Create chart instance

    //   var chart = am4core.create("challenge_subscribe_and_participation_rate_par_challenge"+i, am4charts.XYChart);

    //   counter++;

    //   // Enable chart cursor

    //   chart.cursor = new am4charts.XYCursor();

    //   chart.cursor.lineX.disabled = true;

    //   chart.cursor.lineY.disabled = true;



    //   // Enable scrollbar

    //   chart.scrollbarX = new am4core.Scrollbar();



    //   // Add data

    //   chart.data = single_challenge_participation_values;



    //   // Create axes

    //   var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

    //   dateAxis.renderer.grid.template.location = 0.5;

    //   dateAxis.dateFormatter.inputDateFormat = "yyyy-MM-dd";

    //   dateAxis.renderer.minGridDistance = 40;

    //   dateAxis.tooltipDateFormat = "MMM dd, yyyy";

    //   dateAxis.dateFormats.setKey("day", "dd");



    //   var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());



    //   // Create series

    //   var series = chart.series.push(new am4charts.LineSeries());

    //   series.tooltipText = "{date}\n[font-size: 17px]User Participation Value: {valueY}[/]";

    //   series.dataFields.valueY = "value";

    //   series.dataFields.dateX = "date";

    //   series.strokeDasharray = 3;

    //   series.strokeWidth = 2

    //   series.strokeOpacity = 1;

    //   series.strokeDasharray = "3,3"



    //   var bullet = series.bullets.push(new am4charts.CircleBullet());

    //   bullet.strokeWidth = 2;

    //   bullet.stroke = am4core.color("#fff");

    //   bullet.setStateOnChildren = true;

    //   bullet.propertyFields.fillOpacity = "opacity";

    //   bullet.propertyFields.strokeOpacity = "opacity";



    //   var hoverState = bullet.states.create("hover");

    //   hoverState.properties.scale = 1.7;



    //   function createTrendLine(data) {

    //     var trend = chart.series.push(new am4charts.LineSeries());

    //     trend.dataFields.valueY = "value";

    //     trend.dataFields.dateX = "date";

    //     trend.strokeWidth = 2

    //     trend.stroke = trend.fill = am4core.color("#a367dc");

    //     trend.data = data;



    //     var bullet = trend.bullets.push(new am4charts.CircleBullet());

    //     bullet.tooltipText = "{date}\n[font-size: 17px]User Subscriptions Value: {valueY}[/]";

    //     bullet.strokeWidth = 2;

    //     bullet.stroke = am4core.color("#fff")

    //     bullet.circle.fill = trend.stroke;



    //     var hoverState = bullet.states.create("hover");

    //     hoverState.properties.scale = 1.7;



    //     return trend;

    //   };



    //   createTrendLine(single_challenge_subscriptions_values);

    // }



    // // top 5 users



    // var all_single_challenge_top_users = vgen_challengeAjax.all_single_challenge_top_users;

    // for(var i in all_single_challenge_top_users){

    //   // console.log(i);

      

    //   var single_single_challenge_top_users_values = [];

    //   for (var j in all_single_challenge_top_users[i]) {

    //       var newObject = {};

    //       newObject['user_marks'] = j;

    //       newObject['user_name'] = all_single_challenge_top_users[i][j];

    //       single_single_challenge_top_users_values.push(newObject);

    //   }



    //   am4core.ready(function() {



    //     // Themes begin

    //     am4core.useTheme(am4themes_animated);

    //     // Themes end

        

    //     var chart = am4core.create("challenge_top_10_users"+i, am4charts.PieChart3D);

    //     chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

        

    //     chart.legend = new am4charts.Legend();

        

    //     chart.data = single_single_challenge_top_users_values;

        

    //     var series = chart.series.push(new am4charts.PieSeries3D());

    //     series.dataFields.value = "user_name";

    //     series.dataFields.category = "user_marks";

        

    //   }); // end am4core.ready()



    // }



    // top 5 users values start

    // var challenge_top_users_name_array = vgen_challengeAjax.challenge_top_users_name_array;

    // var challenge_top_users_user_creativity_marks_array = vgen_challengeAjax.challenge_top_users_user_creativity_marks_array;

    // var challenge_top_users_user_innovation_marks_array = vgen_challengeAjax.challenge_top_users_user_innovation_marks_array;

    // var challenge_top_users_user_invention_marks_array = vgen_challengeAjax.challenge_top_users_user_invention_marks_array;

    // var marks_first_like_creativity = vgen_challengeAjax.marks_first_like_creativity;

    // var marks_second_like_innovation = vgen_challengeAjax.marks_second_like_innovation;

    // var marks_third_like_invention = vgen_challengeAjax.marks_third_like_invention;

    // for(var i in challenge_top_users_name_array){



    //   var challenge_top_users_values = [];

    //   for (var j in challenge_top_users_name_array[i]) {

    //       var newObject = {};

    //       newObject['category'] = challenge_top_users_name_array[i][j];

    //       newObject['first'] = challenge_top_users_user_creativity_marks_array[i][j];

    //       newObject['second'] = challenge_top_users_user_innovation_marks_array[i][j];

    //       newObject['third'] = challenge_top_users_user_invention_marks_array[i][j];

    //       challenge_top_users_values.push(newObject);

    //   }



    //   // Themes begin

    //   am4core.useTheme(am4themes_animated);

    //   // Themes end







    //   var chart = am4core.create('challenge_top_10_users'+i, am4charts.XYChart)

    //   chart.colors.step = 2;



    //   chart.legend = new am4charts.Legend()

    //   chart.legend.position = 'top'

    //   chart.legend.paddingBottom = 20

    //   chart.legend.labels.template.maxWidth = 95



    //   var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())

    //   xAxis.dataFields.category = 'category'

    //   xAxis.renderer.cellStartLocation = 0.1

    //   xAxis.renderer.cellEndLocation = 0.9

    //   xAxis.renderer.grid.template.location = 0;



    //   var yAxis = chart.yAxes.push(new am4charts.ValueAxis());

    //   yAxis.min = 0;



    //   function createSeries(value, name) {

    //       var series = chart.series.push(new am4charts.ColumnSeries())

    //       series.dataFields.valueY = value

    //       series.dataFields.categoryX = 'category'

    //       series.name = name



    //       series.events.on("hidden", arrangeColumns);

    //       series.events.on("shown", arrangeColumns);



    //       var bullet = series.bullets.push(new am4charts.LabelBullet())

    //       bullet.interactionsEnabled = false

    //       bullet.dy = 30;

    //       bullet.label.text = '{valueY}'

    //       bullet.label.fill = am4core.color('#ffffff')



    //       return series;

    //   }



    //   chart.data = challenge_top_users_values;



    //   createSeries('first', marks_first_like_creativity);

    //   createSeries('second', marks_second_like_innovation);

    //   createSeries('third', marks_third_like_invention);



    //   function arrangeColumns() {



    //       var series = chart.series.getIndex(0);



    //       var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);

    //       if (series.dataItems.length > 1) {

    //           var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");

    //           var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");

    //           var delta = ((x1 - x0) / chart.series.length) * w;

    //           if (am4core.isNumber(delta)) {

    //               var middle = chart.series.length / 2;



    //               var newIndex = 0;

    //               chart.series.each(function(series) {

    //                   if (!series.isHidden && !series.isHiding) {

    //                       series.dummyData = newIndex;

    //                       newIndex++;

    //                   }

    //                   else {

    //                       series.dummyData = chart.series.indexOf(series);

    //                   }

    //               })

    //               var visibleCount = newIndex;

    //               var newMiddle = visibleCount / 2;



    //               chart.series.each(function(series) {

    //                   var trueIndex = chart.series.indexOf(series);

    //                   var newIndex = series.dummyData;



    //                   var dx = (newIndex - trueIndex + middle - newMiddle) * delta



    //                   series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);

    //                   series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);

    //               })

    //           }

    //       }

    //   }









    // }

    // top 5 users values end



  }

  // is_analytics_page end

}