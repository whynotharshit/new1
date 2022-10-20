<?php
/*
* vgen_challenge Class 
*/

if (!class_exists('vgen_challengeClass')) {
    class vgen_challengeClass{
        public $plugin_url;
        public $plugin_dir;
        public $wpdb;
        public $option_tbl; 
        
        /**Plugin init action**/ 
        public function __construct() {
            global $wpdb;
            $this->plugin_url 				               = vgen_challengeURL;
            $this->plugin_dir 				               = vgen_challengeDIR;
            $this->wpdb 					               = $wpdb;	
            $this->option_tbl                              = $this->wpdb->prefix . 'options';
            $this->user_subscriptions_database             = $this->wpdb->prefix . 'user_subscriptions_database';
            $this->user_participation_database             = $this->wpdb->prefix . 'user_participation_database';
            $this->user_particular_page_views_database     = $this->wpdb->prefix . 'user_particular_page_views_database';
            $this->user_unique_page_views_database         = $this->wpdb->prefix . 'user_unique_page_views_database';
            $this->user_time_on_that_page_database         = $this->wpdb->prefix . 'user_time_on_that_page_database';
            $this->users                                   = $this->wpdb->prefix . 'users';
            
         
            $this->init();
        }

        private function init(){

            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'vgen_challenge_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'vgen_challenge_frontend_script') );
            //Add Menu Options
            add_action( 'admin_menu', array($this, 'vgen_challenge_admin_menu_function') );

            add_action( 'wpcf7_before_send_mail', array($this, 'wpcf7_add_nonce_to_mail_body') );
            add_action( 'wpcf7_mail_sent', array($this, 'your_wpcf7_mail_sent_function') );

            //vgen challenge save 
            add_action('admin_init', array($this, 'vgen_challenge_save_create_db') );

            //Add Shortcode
            add_shortcode('vgen-challenge', array($this, 'vgen_challenge_shortcode') );
            //Add Shortcode
            add_shortcode('vgen-challenge-related', array($this, 'vgen_challenge_related_shortcode') );
            
            //Add Shortcode
            add_shortcode('vgen-challenge-participate-list', array($this, 'vgen_challenge_participate_list_shortcode') );
            
            add_action( 'wp_head', array($this, 'vgen_challenge_add_style') );

            //Font Option
            //add_action('wp_footer', array($this, 'vgen_challenge_page_view_counter') );
            
            /* Send data ajax */ 
            // add_action('wp_ajax_nopriv_vgen_challenge_submitAjax', array($this, 'vgen_challenge_submitAjax'));
            // add_action( 'wp_ajax_vgen_challenge_submitAjax', array($this, 'vgen_challenge_submitAjax') );

            /* Send data ajax */ 
            add_action('wp_ajax_nopriv_vc_marking_update_filterAjax', array($this, 'vc_marking_update_filterAjax'));
            add_action( 'wp_ajax_vc_marking_update_filterAjax', array($this, 'vc_marking_update_filterAjax') );

            add_action('wp_ajax_nopriv_vc_download_challenge_filterAjax', array($this, 'vc_download_challenge_filterAjax'));
            add_action( 'wp_ajax_vc_download_challenge_filterAjax', array($this, 'vc_download_challenge_filterAjax') );

            add_action('wp_ajax_nopriv_vc_single_challenge_data_filterAjax', array($this, 'vc_single_challenge_data_filterAjax'));
            add_action( 'wp_ajax_vc_single_challenge_data_filterAjax', array($this, 'vc_single_challenge_data_filterAjax') );

            add_action('wp_ajax_nopriv_vc_unique_page_views_filterAjax', array($this, 'vc_unique_page_views_filterAjax'));
            add_action( 'wp_ajax_vc_unique_page_views_filterAjax', array($this, 'vc_unique_page_views_filterAjax') );

            add_action('wp_ajax_nopriv_vc_time_on_that_page_filterAjax', array($this, 'vc_time_on_that_page_filterAjax'));
            add_action( 'wp_ajax_vc_time_on_that_page_filterAjax', array($this, 'vc_time_on_that_page_filterAjax') );
            
            add_action('wp_ajax_nopriv_mail_subscriptions_send_Ajax', array($this, 'mail_subscriptions_send_Ajax'));
            add_action( 'wp_ajax_mail_subscriptions_send_Ajax', array($this, 'mail_subscriptions_send_Ajax') );
            /* Send data ajax */ 
            // add_action('wp_ajax_nopriv_vc_submit_answer_url_filterAjax', array($this, 'vc_submit_answer_url_filterAjax'));
            // add_action( 'wp_ajax_vc_submit_answer_url_filterAjax', array($this, 'vc_submit_answer_url_filterAjax') );
            
            // add_action('wp_ajax_nopriv_vc_submit_remove_cache_filterAjax', array($this, 'vc_submit_remove_cache_filterAjax'));
            // add_action( 'wp_ajax_vc_submit_remove_cache_filterAjax', array($this, 'vc_submit_remove_cache_filterAjax') );

            add_action('wp_ajax_nopriv_custom_vgen_challenge_file_sendajax', array($this, 'custom_vgen_challenge_file_sendajax') );
            add_action( 'wp_ajax_custom_vgen_challenge_file_sendajax', array($this, 'custom_vgen_challenge_file_sendajax') );
        }

        function vgen_challenge_save_create_db() {

            $charset_collate = $this->wpdb->get_charset_collate();

            $table = $this->user_subscriptions_database;
            $table2 = $this->user_participation_database;
            $table3 = $this->user_particular_page_views_database;
            $table4 = $this->user_unique_page_views_database;
            $table5 = $this->user_time_on_that_page_database;
            // $this->wpdb->query("DROP TABLE $table");
            // $this->wpdb->query("DROP TABLE $table2");

            $sql = "CREATE TABLE $table ( 
                id INT(20) NOT NULL AUTO_INCREMENT,
                user_id INT(20) NOT NULL,
                post_id VARCHAR(200) NOT NULL,
                insert_date VARCHAR(200) NOT NULL,
                UNIQUE KEY id (id)
                ) $charset_collate;";

            $sql2 = "CREATE TABLE IF NOT EXISTS $table2 (
                id INT(20) NOT NULL AUTO_INCREMENT,
                user_id INT(20) NOT NULL,
                post_id VARCHAR(200) NOT NULL,
                user_creativity_marks INT(20) NOT NULL,
                user_innovation_marks INT(20) NOT NULL,
                user_invention_marks INT(20) NOT NULL,
                uploaded_nonce VARCHAR(255) NOT NULL,
                uploaded_type VARCHAR(255) NOT NULL,
                uploaded_url VARCHAR(255) NOT NULL,
                insert_date VARCHAR(200) NOT NULL,
                insert_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY id (id)
                ) $charset_collate;";

            $sql3 = "CREATE TABLE IF NOT EXISTS $table3 (
                id INT(20) NOT NULL AUTO_INCREMENT,
                post_id VARCHAR(200) NOT NULL,
                particular_page_views VARCHAR(255) NOT NULL,
                insert_date VARCHAR(200) NOT NULL,
                insert_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY id (id)
                ) $charset_collate;";

            $sql4 = "CREATE TABLE IF NOT EXISTS $table4 (
                id INT(20) NOT NULL AUTO_INCREMENT,
                post_id VARCHAR(200) NOT NULL,
                unique_page_views VARCHAR(255) NOT NULL,
                insert_date VARCHAR(200) NOT NULL,
                insert_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY id (id)
                ) $charset_collate;";

            $sql5 = "CREATE TABLE IF NOT EXISTS $table5 (
                id INT(20) NOT NULL AUTO_INCREMENT,
                post_id VARCHAR(200) NOT NULL,
                time_on_that_page VARCHAR(255) NOT NULL,
                insert_date VARCHAR(200) NOT NULL,
                insert_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY id (id)
                ) $charset_collate;";
        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
            dbDelta( $sql2 );
            dbDelta( $sql3 );
            dbDelta( $sql4 );
            dbDelta( $sql5 );
        }

        /*
        * Appointment backend Script
        */
        function vgen_challenge_backend_script($hook){
            global $wpdb;
            $table_subscriptions = $this->user_subscriptions_database;
            // $query_subscriptions = $this->wpdb->get_results( "SELECT `post_id`, COUNT(*) AS `challenge_subscribe` FROM $table_subscriptions GROUP BY `post_id` ORDER BY `challenge_subscribe` DESC LIMIT 5" );
            // $query_subscriptions_array = json_decode(json_encode($query_subscriptions), true);

            $table_database = $this->user_participation_database;

            // $challenge_title = array();
            // $challenge_subscribe = array();
            // foreach( $query_subscriptions_array as $single ){
            //     $challenge_title[$single['post_id']] = get_the_title( $single['post_id'] );
            //     $challenge_subscribe[$single['post_id']] = $single['challenge_subscribe'];
            // }

            // $query_database_date = $this->wpdb->get_results( "SELECT * FROM $table_database GROUP BY `post_id`" );
            // $query_database_date_array = json_decode(json_encode($query_database_date), true);
            // $single_challenge_participation = array();
            // foreach( $query_database_date_array as $single ){
            //     $post_id = $single['post_id'];
            //     $query_single_challenge_participation = $this->wpdb->get_results( "SELECT `insert_date`, COUNT(*) AS `challenge_participation` FROM $table_database WHERE `post_id` = '$post_id' GROUP BY `insert_date`" );
            //     $query_single_challenge_participation_array = json_decode(json_encode($query_single_challenge_participation), true);
            //     $single_challenge_participation_time_value = array();
            //     foreach( $query_single_challenge_participation_array as $single_time_value ){
            //         $single_challenge_participation_time_value[$single_time_value['insert_date']] = $single_time_value['challenge_participation'];
            //     }
            //     $single_challenge_participation[$single['post_id']] = $single_challenge_participation_time_value;
            // }

            // $query_subscriptions_date = $this->wpdb->get_results( "SELECT * FROM $table_subscriptions GROUP BY `post_id`" );
            // $query_subscriptions_date_array = json_decode(json_encode($query_subscriptions_date), true);
            // $single_challenge_subscriptions = array();
            // foreach( $query_subscriptions_date_array as $single ){
            //     $post_id = $single['post_id'];
            //     $query_single_challenge_subscriptions = $this->wpdb->get_results( "SELECT `insert_date`, COUNT(*) AS `challenge_subscriptions` FROM $table_subscriptions WHERE `post_id` = '$post_id' GROUP BY `insert_date`" );
            //     $query_single_challenge_subscriptions_array = json_decode(json_encode($query_single_challenge_subscriptions), true);
            //     $single_challenge_subscriptions_time_value = array();
            //     foreach( $query_single_challenge_subscriptions_array as $single_time_value ){
            //         $single_challenge_subscriptions_time_value[$single_time_value['insert_date']] = $single_time_value['challenge_subscriptions'];
            //     }
            //     $single_challenge_subscriptions[$single['post_id']] = $single_challenge_subscriptions_time_value;
            // }


            // $challenge_participation = array();
            // foreach( $challenge_subscribe as $key => $single ){
            //     $post_id = $key;
            //     $query_database = $this->wpdb->get_results( "SELECT COUNT(*) AS `challenge_participation` FROM $table_database WHERE `post_id` = '$post_id'" );
            //     $query_database_array = json_decode(json_encode($query_database), true);
                
            //     foreach( $query_database_array as $single_value ){
            //         $challenge_participation[$key] = $single_value['challenge_participation'];
            //     }
                
            // }

            $wp_post_filter_choose_first_category = array();
            if ( get_option( 'wp_post_filter_choose_first_category' ) !== false ) {
                $wp_post_filter_choose_first_category = get_option( 'wp_post_filter_choose_first_category');
            }
            $wp_post_filter_choose_second_category = array();
            if ( get_option( 'wp_post_filter_choose_second_category' ) !== false ) {
                $wp_post_filter_choose_second_category = get_option( 'wp_post_filter_choose_second_category');
            }
            $wp_post_filter_choose_all_category = array_merge( $wp_post_filter_choose_first_category, $wp_post_filter_choose_second_category );
            $args = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'ABC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'id',
                        'terms' => $wp_post_filter_choose_all_category,
                    ),
                )
            );
            $all_post = new WP_Query( $args );
            $active_post_ids = array();
            $all_post_ids = array();
            foreach( $all_post->posts as $single ){
                $post_id = $single->ID;
                array_push( $all_post_ids, $post_id );
                $post_filter_award_coming_soon = get_post_meta( $post_id, 'post_filter_award_coming_soon', true );
                $post_deadline = get_post_meta( $post_id, 'post_deadline', true );
                $today_date = date("Y-m-d");
                if( $post_filter_award_coming_soon == 0 ){
                    if( $post_deadline >= $today_date ){
                        array_push( $active_post_ids, $post_id );
                    }
                }
            }

            $active_challenge_subscribe = array();
            $active_challenge_participation = array();
            $active_challenge_title = array();
            foreach( $active_post_ids as $single_id ){
                $query_subscriptions_active = $this->wpdb->get_row( "SELECT `post_id`, COUNT(*) AS `challenge_subscribe` FROM $table_subscriptions  WHERE `post_id` = '$single_id'" );
                $active_challenge_subscribe[$single_id] = $query_subscriptions_active->challenge_subscribe;
                $query_participation_active = $this->wpdb->get_row( "SELECT `post_id`, COUNT(*) AS `challenge_participation` FROM $table_database WHERE `post_id` = '$single_id'" );
                $active_challenge_participation[$single_id] = $query_participation_active->challenge_participation;
                
                $active_challenge_title[$single_id] = get_the_title( $single_id );
            }

            // $query_participation_active_all = $this->wpdb->get_results( "SELECT * FROM $table_database" );
            // echo 'query_participation_active_all<pre>';
            // print_r($query_participation_active_all);
            // echo '</pre>';

            // echo 'active_challenge_title<pre>';
            // print_r($active_challenge_title);
            // echo '</pre>';
            // echo 'active_challenge_subscribe<pre>';
            // print_r($active_challenge_subscribe);
            // echo '</pre>';
            // echo 'active_challenge_participation<pre>';
            // print_r($active_challenge_participation);
            // echo '</pre>';

            // $single_challenge_particular_page_views = array();
            // $single_challenge_unique_page_views = array();
            // $single_challenge_time_on_that_page = array();
            // $single_challenge_average_time_on_that_pages = array();
            // foreach( $all_post_ids as $single_id ){

            //     //user_particular_page_views_database
            //     $table_particular_page_views = $this->user_particular_page_views_database;
            //     $query_particular_page_views_date = $this->wpdb->get_results( "SELECT `insert_date`, `particular_page_views` FROM $table_particular_page_views WHERE `post_id` = '$single_id' GROUP BY `insert_date`" );
            //     $query_particular_page_views_date_array = json_decode(json_encode($query_particular_page_views_date), true);

            //     $single_challenge_particular_page_views_value = array();
            //     foreach( $query_particular_page_views_date_array as $single_page_views_date_value ){
            //         $single_challenge_particular_page_views_value[$single_page_views_date_value['insert_date']] = $single_page_views_date_value['particular_page_views'];
            //     }
            //     $single_challenge_particular_page_views[$single_id] = $single_challenge_particular_page_views_value;

            //     //user_unique_page_views_database
            //     $table_unique_page_views = $this->user_unique_page_views_database;
            //     $query_unique_page_views_date = $this->wpdb->get_results( "SELECT `insert_date`, `unique_page_views` FROM $table_unique_page_views WHERE `post_id` = '$single_id' GROUP BY `insert_date`" );
            //     $query_unique_page_views_date_array = json_decode(json_encode($query_unique_page_views_date), true);

            //     $single_challenge_unique_page_views_value = array();
            //     foreach( $query_unique_page_views_date_array as $single_unique_page_views_value ){
            //         $single_challenge_unique_page_views_value[$single_unique_page_views_value['insert_date']] = $single_unique_page_views_value['unique_page_views'];
            //     }
            //     $single_challenge_unique_page_views[$single_id] = $single_challenge_unique_page_views_value;
                
            //     //user_time_on_that_page_database
            //     $table_time_on_that_page = $this->user_time_on_that_page_database;
            //     $query_time_on_that_page_date = $this->wpdb->get_results( "SELECT `insert_date`, `time_on_that_page` FROM $table_time_on_that_page WHERE `post_id` = '$single_id' GROUP BY `insert_date`" );
            //     $query_time_on_that_page_date_array = json_decode(json_encode($query_time_on_that_page_date), true);

            //     $single_challenge_time_on_that_page_value = array();
            //     $single_average_time_on_that_page_value = array();
            //     foreach( $query_time_on_that_page_date_array as $single_time_on_that_page_value ){
            //         $single_time_on_that_page = $single_time_on_that_page_value['time_on_that_page'];
            //         $single_minute_time_on_that_page = $single_time_on_that_page/60;
            //         $single_challenge_time_on_that_page_value[$single_time_on_that_page_value['insert_date']] = number_format($single_minute_time_on_that_page, 2);

            //         //user_average_time_on_that_page_database
                    
            //         $single_challenge_time_on_that_page_for = get_post_meta( $single_id, 'time_on_that_page', true );
            //         $single_challenge_time_on_that_page_fors = $single_challenge_time_on_that_page_for/60;
            //         $single_challenge_particular_page_views_for = get_post_meta( $single_id, 'particular_page_views', true );

            //         $single_average_time_on_that_page = $single_challenge_time_on_that_page_fors / $single_challenge_particular_page_views_for;
            //         $single_average_time_on_that_page_value[$single_time_on_that_page_value['insert_date']] = number_format($single_average_time_on_that_page, 2);
            //     }
            //     $single_challenge_time_on_that_page[$single_id] = $single_challenge_time_on_that_page_value;
            //     $single_challenge_average_time_on_that_pages[$single_id] = $single_average_time_on_that_page_value;
            // }


            // $challenge_top_users_name_array = array();
            // $challenge_top_users_user_creativity_marks_array = array();
            // $challenge_top_users_user_innovation_marks_array = array();
            // $challenge_top_users_user_invention_marks_array = array();
            // foreach( $all_post_ids as $single_id ){

            //     $query_top_users_participation = $this->wpdb->get_results( "SELECT `post_id`, `user_id`, `user_creativity_marks`, `user_innovation_marks`, `user_invention_marks`, ( `user_creativity_marks` + `user_innovation_marks` + `user_invention_marks` ) as `user_total_marks` FROM $table_database WHERE `post_id` = '$single_id' ORDER BY `user_total_marks` DESC LIMIT 5" );
            //     $query_top_users_participation_array = json_decode(json_encode($query_top_users_participation), true);

            //     $challenge_top_users_name = array();
            //     $challenge_top_users_user_creativity_marks = array();
            //     $challenge_top_users_user_innovation_marks = array();
            //     $challenge_top_users_user_invention_marks = array();
            //     foreach( $query_top_users_participation_array as $single_top_user ){
            //         $singlee_top_user_id = $single_top_user['user_id'];
            //         $single_top_user_info = get_userdata( $singlee_top_user_id );
            //         $single_top_user_name = $single_top_user_info->display_name;
            //         $challenge_top_users_name[$singlee_top_user_id] = $single_top_user_name;
            //         $challenge_top_users_user_creativity_marks[$singlee_top_user_id] = $single_top_user['user_creativity_marks'];
            //         $challenge_top_users_user_innovation_marks[$singlee_top_user_id] = $single_top_user['user_innovation_marks'];
            //         $challenge_top_users_user_invention_marks[$singlee_top_user_id] = $single_top_user['user_invention_marks'];
            //     }
            //     $challenge_top_users_name_array[$single_id] = $challenge_top_users_name;
            //     $challenge_top_users_user_creativity_marks_array[$single_id] = $challenge_top_users_user_creativity_marks;
            //     $challenge_top_users_user_innovation_marks_array[$single_id] = $challenge_top_users_user_innovation_marks;
            //     $challenge_top_users_user_invention_marks_array[$single_id] = $challenge_top_users_user_invention_marks;

            // }

            
            $marks_first_like_creativity = 'Creativity';
            if ( get_option( 'marks_first_like_creativity' ) !== false ) {
                $marks_first_like_creativity = get_option( 'marks_first_like_creativity');
            }
            $marks_second_like_innovation = 'Innovation';
            if ( get_option( 'marks_second_like_innovation' ) !== false ) {
                $marks_second_like_innovation = get_option( 'marks_second_like_innovation');
            }
            $marks_third_like_invention = 'Invention';
            if ( get_option( 'marks_third_like_invention' ) !== false ) {
                $marks_third_like_invention = get_option( 'marks_third_like_invention');
            }

            // echo 'single_challenge_time_on_that_page<pre>';
            // print_r($single_challenge_time_on_that_page);
            // echo '</pre>';
            // echo 'single_challenge_average_time_on_that_pages<pre>';
            // print_r($single_challenge_average_time_on_that_pages);
            // echo '</pre>';
    
            wp_enqueue_style( 'dataTableCSS', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css', array(), true, 'all' );
            
            wp_enqueue_style( 'fontawesomeCSS', 'https://use.fontawesome.com/releases/v5.4.1/css/all.css', array(), true, 'all' );
            
            $is_analytics_page = 'no';
            if($hook == 'toplevel_page_vgen-challenge-analytics-and-marking-system'){
                wp_enqueue_script( 'bootstrapJS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js', array(), time(), true);
                wp_enqueue_style( 'bootstrapCSS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css', array(), true, 'all' );
                $is_analytics_page = 'yes';
            }
        
            wp_enqueue_style( 'b_vgen_challengeCSS', $this->plugin_url . 'asset/css/vgen_challenge_backend.css', array(), true, 'all' );

            wp_enqueue_script( 'amchartsjsCore', 'https://cdn.amcharts.com/lib/4/core.js', array(), time(), true);
            wp_enqueue_script( 'amchartsjs', 'https://cdn.amcharts.com/lib/4/charts.js', array(), time(), true);
            wp_enqueue_script( 'amchartsjsAnimated', 'https://cdn.amcharts.com/lib/4/themes/animated.js', array(), time(), true);

            wp_enqueue_script( 'dataTableJS', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js', array(), time(), true);
            wp_enqueue_script( 'b_vgen_challengeJS', $this->plugin_url . 'asset/js/vgen_challenge_backend.js', array(), true );
            //ajax
            wp_localize_script( 'b_vgen_challengeJS', 'vgen_challengeAjax', 
                array(
                    'ajax' => admin_url( 'admin-ajax.php' ),
                    // 'challenge_title' => $challenge_title,
                    // 'challenge_subscribe' => $challenge_subscribe,
                    // 'challenge_participation' => $challenge_participation,
                    // 'single_challenge_participation' => $single_challenge_participation,
                    // 'single_challenge_subscriptions' => $single_challenge_subscriptions,
                    'active_challenge_subscribe' => $active_challenge_subscribe,
                    'active_challenge_participation' => $active_challenge_participation,
                    'active_challenge_title' => $active_challenge_title,
                    // 'single_challenge_particular_page_views' => $single_challenge_particular_page_views,
                    // 'single_challenge_unique_page_views' => $single_challenge_unique_page_views,
                    // 'single_challenge_time_on_that_page' => $single_challenge_time_on_that_page,
                    // 'single_challenge_average_time_on_that_pages' => $single_challenge_average_time_on_that_pages,
                    // 'challenge_top_users_name_array' => $challenge_top_users_name_array,
                    // 'challenge_top_users_user_creativity_marks_array' => $challenge_top_users_user_creativity_marks_array,
                    // 'challenge_top_users_user_innovation_marks_array' => $challenge_top_users_user_innovation_marks_array,
                    // 'challenge_top_users_user_invention_marks_array' => $challenge_top_users_user_invention_marks_array,
                    'marks_first_like_creativity' => $marks_first_like_creativity,
                    'marks_second_like_innovation' => $marks_second_like_innovation,
                    'marks_third_like_invention' => $marks_third_like_invention,
                    'is_analytics_page' => $is_analytics_page,
                )
            );
        }

        /*
        * Appointment frontend Script
        */
        function vgen_challenge_frontend_script(){
            global $wpdb;

            $current_page_id = get_the_ID();

            $wp_post_filter_choose_first_category = array();
            if ( get_option( 'wp_post_filter_choose_first_category' ) !== false ) {
                $wp_post_filter_choose_first_category = get_option( 'wp_post_filter_choose_first_category');
            }
            $wp_post_filter_choose_second_category = array();
            if ( get_option( 'wp_post_filter_choose_second_category' ) !== false ) {
                $wp_post_filter_choose_second_category = get_option( 'wp_post_filter_choose_second_category');
            }
            $wp_post_filter_choose_all_category = array_merge( $wp_post_filter_choose_first_category, $wp_post_filter_choose_second_category );
            $args = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'ABC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'id',
                        'terms' => $wp_post_filter_choose_all_category,
                    ),
                )
            );
            $all_post = new WP_Query( $args );

            $all_post_ids = array();
            foreach( $all_post->posts as $single ){
                $post_id = $single->ID;
                if( !empty( get_post_meta( $post_id, 'post_filter_post_child', true ) ) ){
                    $post_filter_post_child = get_post_meta( $post_id, 'post_filter_post_child', true );
                    array_push( $all_post_ids, $post_filter_post_child );
                }
                array_push( $all_post_ids, $post_id );
            }
            // echo 'current_page_id : ' . $current_page_id . '</br>';
            // echo date("h:i:sa");
            // echo 'all_post_ids : <pre>';
            // print_r($all_post_ids);
            // echo '</pre>';

            $current_page_is_valid = 'no';
            $current_date = date("Y-m-d");
            if ( in_array( $current_page_id, $all_post_ids ) ){
                $current_page_is_valid = 'yes';
                $previous_particular_page_views = get_post_meta( $current_page_id, 'particular_page_views', true );
                $particular_page_views = $previous_particular_page_views + 1;
                update_post_meta( $current_page_id, 'particular_page_views', $particular_page_views );

                $table_particular_page_views = $this->user_particular_page_views_database;
                $query_particular_page_views = $this->wpdb->get_row( "SELECT * FROM $table_particular_page_views  WHERE `insert_date` = '$current_date' AND `post_id` = '$current_page_id' " );
                if( empty($query_particular_page_views) ){
                    $insert = $this->wpdb->insert(
                        $table_particular_page_views,
                        array(
                            'particular_page_views' => '1',
                            'post_id' => $current_page_id,
                            'insert_date' => $current_date,
                        ),
                        array( '%s', '%s', '%s')
                    );
                }else{
                    $particular_page_views_count = $query_particular_page_views->particular_page_views;
                    $particular_page_views_counts = $particular_page_views_count + 1;
                    $particular_page_views_id = $query_particular_page_views->id;
                    $wpdb->update(
                        $table_particular_page_views,
                    array(
                            'particular_page_views'  => $particular_page_views_counts
                        ),
                    array(
                        'id'=> $particular_page_views_id
                    ),
                    array('%s'),
                    array('%d')
                    );
                }


            }

            
            $challenge_submit_welcome_page_url = '';
            if ( get_option( 'challenge_submit_welcome_page' ) !== false ) {
                $challenge_submit_welcome_page = get_option( 'challenge_submit_welcome_page');
                $challenge_submit_welcome_page_url = get_permalink($challenge_submit_welcome_page);
            }

            $want_to_welcome_page = 0;
            if ( get_option( 'want_to_welcome_page' ) !== false ) {
                $want_to_welcome_page = get_option( 'want_to_welcome_page');
            }

                // $category_names = array();
                // foreach( $category as $single_cat ){
                //     $category_name = $single_cat->name;
                //     array_push( $category_names, $category_name );
                // }

            wp_enqueue_style( 'f_font_awesomeCSS', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', array(), true, 'all' );
            wp_enqueue_style( 'f_carouselCSS', $this->plugin_url . 'asset/css/owl.carousel.min.css', array(), true, 'all' );
            wp_enqueue_style( 'f_vgen_challengeCSS', $this->plugin_url . 'asset/css/vgen_challenge_frontend.css', array(), true, 'all' );
            
            wp_enqueue_script('f_cloudflareJS', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', array('jquery'), time(), true);
            wp_enqueue_script('f_carouselJS', $this->plugin_url . 'asset/js/owl.carousel.min.js', array('jquery'), time(), true);
            wp_enqueue_script('f_vgen_challengeJS', $this->plugin_url . 'asset/js/vgen_challenge_frontend.js', array('jquery'), time(), true);
            //ajax
            wp_localize_script( 'f_vgen_challengeJS', 'vgen_challengeAjax', 
            array(
                'ajax' => admin_url( 'admin-ajax.php' ),
                'post_id' => $current_page_id,
                'all_post_ids' => $all_post_ids,
                'current_page_is_valid' => $current_page_is_valid,
                'challenge_submit_welcome_page_url' => $challenge_submit_welcome_page_url,
                'want_to_welcome_page' => $want_to_welcome_page
                )
            );
        }

        // ajax
        function vc_time_on_that_page_filterAjax(){
            ob_start();
            // global $wpdb;
            // $current_date = date("Y-m-d");
            $current_page_id = $_POST['post_id'];
            $previous_time_on_that_page = get_post_meta( $current_page_id, 'time_on_that_page', true );
            $time_on_that_page = $previous_time_on_that_page + 5;
            update_post_meta( $current_page_id, 'time_on_that_page', $time_on_that_page );
            // $table_time_on_that_page = $this->user_time_on_that_page_database;
            // $query_time_on_that_page = $this->wpdb->get_row( "SELECT * FROM $table_time_on_that_page  WHERE `insert_date` = '$current_date' AND `post_id` = '$current_page_id' " );
            // if( empty($query_time_on_that_page) ){
            //     $insert = $this->wpdb->insert(
            //         $table_time_on_that_page,
            //         array(
            //             'time_on_that_page' => '1',
            //             'post_id' => $current_page_id,
            //             'insert_date' => $current_date,
            //         ),
            //         array( '%s', '%s', '%s')
            //     );
            // }else{
            //     $time_on_that_page_count = $query_time_on_that_page->time_on_that_page;
            //     $time_on_that_page_counts = $time_on_that_page_count + 1;
            //     $time_on_that_page_id = $query_time_on_that_page->id;
            //     $wpdb->update(
            //         $table_time_on_that_page,
            //     array(
            //             'time_on_that_page'  => $time_on_that_page_counts
            //         ),
            //     array(
            //         'id'=> $time_on_that_page_id
            //     ),
            //     array('%s'),
            //     array('%d')
            //     );
            // }
            $output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                )
            );
            die(); 
        }
        // ajax
        function vc_unique_page_views_filterAjax(){
            ob_start();
            global $wpdb;
            $current_date = date("Y-m-d");
            
            $current_page_id = $_POST['post_id'];
            $previous_unique_page_views = get_post_meta( $current_page_id, 'unique_page_views', true );
            $unique_page_views = $previous_unique_page_views + 1;
            update_post_meta( $current_page_id, 'unique_page_views', $unique_page_views );
            
            $table_unique_page_views = $this->user_unique_page_views_database;
            $query_unique_page_views = $this->wpdb->get_row( "SELECT * FROM $table_unique_page_views  WHERE `insert_date` = '$current_date' AND `post_id` = '$current_page_id' " );
            if( empty($query_unique_page_views) ){
                $insert = $this->wpdb->insert(
                    $table_unique_page_views,
                    array(
                        'unique_page_views' => '1',
                        'post_id' => $current_page_id,
                        'insert_date' => $current_date,
                    ),
                    array( '%s', '%s', '%s')
                );
            }else{
                $unique_page_views_count = $query_unique_page_views->unique_page_views;
                $unique_page_views_counts = $unique_page_views_count + 1;
                $unique_page_views_id = $query_unique_page_views->id;
                $wpdb->update(
                    $table_unique_page_views,
                array(
                        'unique_page_views'  => $unique_page_views_counts
                    ),
                array(
                    'id'=> $unique_page_views_id
                ),
                array('%s'),
                array('%d')
                );
            }
            
            
            $output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'current_page_id' => $current_page_id,
                    'unique_page_views' => $query_unique_page_views
                )
            );
            die(); 
        }

        function vgen_challenge_admin_menu_function(){

            $who_user_roles_can_access_vgen_challenge = array();
            if ( get_option( 'who_user_roles_can_access_vgen_challenge' ) !== false ) {
                $who_user_roles_can_access_vgen_challenge = get_option( 'who_user_roles_can_access_vgen_challenge');
            }
            array_push($who_user_roles_can_access_vgen_challenge, 'administrator');
            if( is_user_logged_in() ) {
                $user = wp_get_current_user();
                $roles = ( array ) $user->roles;
                $role = $roles[0];
                if ( in_array( 'administrator', $roles ) ){
                    $role = 'administrator';
                }
                if ( in_array( $role, $who_user_roles_can_access_vgen_challenge ) ){
                    add_menu_page( 'Vgen Challenge Analytics and Marking System', 'Vgen Challenge Analytics and Marking System', 'read', 'vgen-challenge-analytics-and-marking-system', array($this, 'marking_system_function'), 'dashicons-chart-area', 50 );
                    add_submenu_page( 'vgen-challenge-analytics-and-marking-system', 'Mail Settings', 'Mail Settings', 'manage_options', 'vgen-challenge-mail-settings', array($this, 'submenu_mail_settings_function') );
                    add_submenu_page( 'vgen-challenge-analytics-and-marking-system', 'Settings', 'Settings', 'manage_options', 'vgen-challenge-settings', array($this, 'submenu_settings_function') );
                }
            }

        }

        

        function vgen_challenge_add_style(){
            
            $vgen_challenge_button_text_color = '#ffffff';
            if ( get_option( 'vgen_challenge_button_text_color' ) !== false ) {
                $vgen_challenge_button_text_color = get_option( 'vgen_challenge_button_text_color');
            }
            $vgen_challenge_button_background_color = '#000000';
            if ( get_option( 'vgen_challenge_button_background_color' ) !== false ) {
                $vgen_challenge_button_background_color = get_option( 'vgen_challenge_button_background_color');
            }
            $vgen_challenge_button_hover_background_color = '#ffffff';
            if ( get_option( 'vgen_challenge_button_hover_background_color' ) !== false ) {
                $vgen_challenge_button_hover_background_color = get_option( 'vgen_challenge_button_hover_background_color');
            }
            $vgen_challenge_button_hover_text_color = '#bd1045';
            if ( get_option( 'vgen_challenge_button_hover_text_color' ) !== false ) {
                $vgen_challenge_button_hover_text_color = get_option( 'vgen_challenge_button_hover_text_color');
            }
            
            $vgen_challenge_css = '';
            if ( get_option( 'vgen_challenge_css' ) !== false ) {
                $vgen_challenge_css = get_option( 'vgen_challenge_css');
            }

            echo '<style>
                '. $vgen_challenge_css .'
                .vgen_challenge_subscribe, input.vgen_challenge_submit {
                    background-color: '.$vgen_challenge_button_background_color.';
                    color: '.$vgen_challenge_button_text_color.';
                }
                .vgen_challenge_subscribe:hover, input.vgen_challenge_submit:hover {
                    background-color: '.$vgen_challenge_button_hover_background_color.';
                    color: '.$vgen_challenge_button_hover_text_color.';
                }
                </style>';
        }

        
        // update Settings
        public function updateSettings($data){
            foreach($data as $k => $sd) update_option( $k, $sd );
        }
        function submenu_settings_function(){
            if(isset($_POST['vgen_challenge_submit_btn'])) $this->updateSettings($_POST);

            // //$result = $this->all_usermeta();
            

            
            // $table_name = $this->user_participation_database;
            // $qry_user_participation_database = $this->wpdb->get_row( "SELECT * FROM $table_name LIMIT 1" );
            // $all_user_participation_database = json_decode(json_encode($qry_user_participation_database), true);
            // $all_user_pd = array();
            // foreach( $all_user_participation_database as $key=>$value ){
            //     array_push($all_user_pd, $key);
            // }
            
            // $table_name = $this->user_participation_database;

            // $challenge_id = '215';
            $user_id = '2';
            // $uploaded_nonce = $this->wpdb->get_col( "SELECT `uploaded_nonce` FROM $table_name WHERE `post_id` = $challenge_id AND `user_id` = $user_id ");
            
            //$user_participation_database_values = $this->wpdb->get_row( "SELECT * FROM $table_name ssn WHERE `post_id` = $post_id AND `user_id` = $user_id ");
            //$all_files = json_decode(json_encode($qry), true);
            // foreach( $user_participation_database_values as $key => $value ){
            //     echo 'key : ' . $key . ' . value : ' . $value . '</br>';
            // }
            $single_usermeta = 'um_member_directory_data';
            $meta_values = get_user_meta( $user_id, $single_usermeta, true );
            //$meta_values = 'jony';
            // echo 'result<pre>';
            // print_r($meta_values);
            // echo '</pre>';

            if( is_array($meta_values) == 1 ){
                $meta_values = implode("|",$meta_values);
            }else{
                $meta_values = $meta_values;
            }

            // echo 'array to string : ' . $meta_values;

            // echo '</br>';

            $test = unserialize($meta_values);

            // echo 'test<pre>';
            // print_r($test);
            // echo '</pre>';

            // $data = @unserialize($meta_values);
            // if ($data !== false) {
            //     echo "ok" . implode("|",$data);
            // } else {
            //     echo "not ok" . implode("|",$data);
            // }

            ?>

            <div class="vgen-challenge-submenu">
                <div class="vgen-challenge-title-csv">
                    <div class="vgen-challenge-submenu-title">
                        <h1><?php _e('Vgen Challenge Settings', 'vgen_challenge'); ?></h1>
                    </div>
                </div>
                <!-- Settings -->
                <div class="vgen-challenge">

                    <div class="tabcontent vgen-challenge-marking-system" style="display:block;">
                        <div class="settingsInner">
                            <form id="vgen_challenge_submit" method="post" action="">
                                <table class="vgen-challenge-data-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left"><?php _e('Who User Roles Can Access Vgen Challenge Analytics and Marking System', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <div class="vc-ac-cb-div">
                                                    <ul class="vc-cboxtags">
                                                        <li>
                                                            <input type="checkbox" class="wssn-hidden" name="who_user_roles_can_access_vgen_challenge[]" id="checkbox" value="checkbox" checked/>
                                                        </li>
                                                        <?php 
                                                        global $wp_roles;
                                                        $roles = $wp_roles->get_names();
                                                        $who_user_roles_can_access_vgen_challenge = array();
                                                        if ( get_option( 'who_user_roles_can_access_vgen_challenge' ) !== false ) {
                                                            $who_user_roles_can_access_vgen_challenge = get_option( 'who_user_roles_can_access_vgen_challenge');
                                                        }
                                                        foreach($roles as $key => $role) {
                                                            if( $key == 'administrator'){
                                                            }else{
                                                            $checked = ( in_array( $key ,$who_user_roles_can_access_vgen_challenge ) ) ? 'checked' : '';
                                                        ?>
                                                            <li>
                                                                <input type="checkbox" name="who_user_roles_can_access_vgen_challenge[]" id="checkbox<?php echo $key ?>" value="<?php echo $key ?>" <?php echo $checked; ?>/>
                                                                <label for="checkbox<?php echo $key ?>"><?php echo $role ?></label>
                                                            </li>
                                                        <?php }} ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Submit Title', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_submit_title = 'UPLOAD';
                                                if ( get_option( 'challenge_submit_title' ) !== false ) {
                                                    $challenge_submit_title = get_option( 'challenge_submit_title');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_submit_title" value="<?php echo $challenge_submit_title; ?>" placeholder="Challenge Submit Title">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Subscribe Button Text', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $subscribe_text = 'SUBSCRIBE';
                                                if ( get_option( 'subscribe_text' ) !== false ) {
                                                    $subscribe_text = get_option( 'subscribe_text');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="subscribe_text" value="<?php echo $subscribe_text; ?>" placeholder="SUBSCRIBE">
                                            </td>
                                        </tr>
                                            <tr>
                                                <th class="text-left"><?php _e('Unsubscribe Button Text', 'vgen_challenge' ); ?></th>
                                                <td class="text-left">
                                                    <?php
                                                    $unsubscribe_text = 'UNSUBSCRIBE';
                                                    if ( get_option( 'unsubscribe_text' ) !== false ) {
                                                        $unsubscribe_text = get_option( 'unsubscribe_text');
                                                    }
                                                    ?>
                                                    <input type="text" class="form-control" name="unsubscribe_text" value="<?php echo $unsubscribe_text; ?>" placeholder="UNSUBSCRIBE">
                                                </td>
                                            </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Download Button Text', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $download_text = 'DOWNLOAD';
                                                if ( get_option( 'download_text' ) !== false ) {
                                                    $download_text = get_option( 'download_text');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="download_text" value="<?php echo $download_text; ?>" placeholder="DOWNLOAD">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Massage after the challenge is completed', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_after_participation_text = 'Thank you! for your participation.';
                                                if ( get_option( 'challenge_after_participation_text' ) !== false ) {
                                                    $challenge_after_participation_text = get_option( 'challenge_after_participation_text');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_after_participation_text" value="<?php echo $challenge_after_participation_text; ?>" placeholder="Thank you! for your participation.">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Login Page', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php $allpages = get_all_page_ids(); ?>
                                                <select name="subscribe_login_page_id" class="form-control" id="subscribe_login_page_id">
                                                    <?php foreach( $allpages as $sp):
                                                        $selected = (get_option( 'subscribe_login_page_id') == $sp ) ? 'selected' : '';
                                                        ?>
                                                        <option <?php echo $selected; ?> value="<?php echo $sp; ?>"><?php echo get_the_title($sp); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Add Your Fontend CSS', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $vgen_challenge_css = 'Enter your css...';
                                                if ( get_option( 'vgen_challenge_css' ) !== false ) {
                                                    $vgen_challenge_css = get_option( 'vgen_challenge_css');
                                                }
                                                ?>
                                                <textarea name="vgen_challenge_css" class="form-control-css"><?php echo $vgen_challenge_css; ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Button Background Color', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $vgen_challenge_button_background_color = '#000000';
                                                if ( get_option( 'vgen_challenge_button_background_color' ) !== false ) {
                                                    $vgen_challenge_button_background_color = get_option( 'vgen_challenge_button_background_color');
                                                }
                                                ?>
                                                <input class="vgen_challenge-color-style" name="vgen_challenge_button_background_color" type="color" value="<?php echo $vgen_challenge_button_background_color; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Button Text color', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $vgen_challenge_button_text_color = '#ffffff';
                                                if ( get_option( 'vgen_challenge_button_text_color' ) !== false ) {
                                                    $vgen_challenge_button_text_color = get_option( 'vgen_challenge_button_text_color');
                                                }
                                                ?>
                                                <input class="vgen_challenge-color-style" name="vgen_challenge_button_text_color" type="color" value="<?php echo $vgen_challenge_button_text_color; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Button Hover Background Color', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $vgen_challenge_button_hover_background_color = '#ffffff';
                                                if ( get_option( 'vgen_challenge_button_hover_background_color' ) !== false ) {
                                                    $vgen_challenge_button_hover_background_color = get_option( 'vgen_challenge_button_hover_background_color');
                                                }
                                                ?>
                                                <input class="vgen_challenge-color-style" name="vgen_challenge_button_hover_background_color" type="color" value="<?php echo $vgen_challenge_button_hover_background_color; ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Button Hover Text color', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $vgen_challenge_button_hover_text_color = '#bd1045';
                                                if ( get_option( 'vgen_challenge_button_hover_text_color' ) !== false ) {
                                                    $vgen_challenge_button_hover_text_color = get_option( 'vgen_challenge_button_hover_text_color');
                                                }
                                                ?>
                                                <input class="vgen_challenge-color-style" name="vgen_challenge_button_hover_text_color" type="color" value="<?php echo $vgen_challenge_button_hover_text_color; ?>" />
                                            </td>
                                        </tr>
                                        <tr style="display: none;">
                                            <th class="text-left"><?php _e('Do you want to Challenge author can get challenge answer mail?', 'vgen_challenge'); ?></th>
                                            <td class="text-left">
                                                <div class='checkbox' id='hideSearch'>
                                                    <label class='checkbox__container'>
                                                    <input type="hidden" name="challenge_author_can_get_challenge_answer_mail" value="0">
                                                    <input class='checkbox__toggle' type='checkbox' value="1" name='challenge_author_can_get_challenge_answer_mail' <?php echo $checked = (get_option( 'challenge_author_can_get_challenge_answer_mail' ) == 1) ? 'checked' : '' ; ?>/>
                                                    <span class='checkbox__checker'></span>
                                                    <span class='checkbox__txt-left'>Yes</span>
                                                    <span class='checkbox__txt-right'>No</span>
                                                    <svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
                                                        <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
                                                    </svg>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Do you want to submit the challenge through the link?', 'vgen_challenge'); ?></th>
                                            <td class="text-left">
                                                <div class='checkbox' id='hideSearch'>
                                                    <label class='checkbox__container'>
                                                    <input type="hidden" name="submit_the_challenge_through_the_link" value="0">
                                                    <input class='checkbox__toggle' type='checkbox' value="1" name='submit_the_challenge_through_the_link' <?php echo $checked = (get_option( 'submit_the_challenge_through_the_link' ) == 1) ? 'checked' : '' ; ?>/>
                                                    <span class='checkbox__checker'></span>
                                                    <span class='checkbox__txt-left'>Yes</span>
                                                    <span class='checkbox__txt-right'>No</span>
                                                    <svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
                                                        <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
                                                    </svg>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="display: none;">
                                            <th class="text-left"><?php _e('Do you want to Welcome Page?', 'vgen_challenge'); ?></th>
                                            <td class="text-left">
                                                <div class='checkbox' id='hideSearch'>
                                                    <label class='checkbox__container'>
                                                    <input id="want_to_welcome_page" type="hidden" name="want_to_welcome_page" value="0">
                                                    <input class='checkbox__toggle' id='want_to_welcome_page' type='checkbox' value="1" name='want_to_welcome_page' <?php echo $checked = (get_option( 'want_to_welcome_page' ) == 1) ? 'checked' : '' ; ?>/>
                                                    <span class='checkbox__checker'></span>
                                                    <span class='checkbox__txt-left'>Yes</span>
                                                    <span class='checkbox__txt-right'>No</span>
                                                    <svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
                                                        <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
                                                    </svg>
                                                    </label>
                                                </div>
                                                <div class="vc_note"><?php _e('Note: This feature is currently unable!', 'vgen_challenge'); ?></div>
                                            </td>
                                        </tr>
                                        <tr style="display: none;">
                                            <th class="text-left"><?php _e('Challenge Submit Welcome Page', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php $allpages = get_all_page_ids(); ?>
                                                <select name="challenge_submit_welcome_page" class="form-control" id="challenge_submit_welcome_page" <?php echo $disabled = (get_option( 'want_to_welcome_page' ) == 1) ? '' : 'disabled="disabled"' ; ?>>
                                                    <?php foreach( $allpages as $sp):
                                                        $selected = (get_option( 'challenge_submit_welcome_page') == $sp ) ? 'selected' : '';
                                                        ?>
                                                        <option <?php echo $selected; ?> value="<?php echo $sp; ?>"><?php echo get_the_title($sp); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Give a Name First Marks(Creativity)', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $marks_first_like_creativity = 'Creativity';
                                                if ( get_option( 'marks_first_like_creativity' ) !== false ) {
                                                    $marks_first_like_creativity = get_option( 'marks_first_like_creativity');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="marks_first_like_creativity" value="<?php echo $marks_first_like_creativity; ?>" placeholder="Give a Name First Marks">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Give a Name Second Marks(Innovation)', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $marks_second_like_innovation = 'Innovation';
                                                if ( get_option( 'marks_second_like_innovation' ) !== false ) {
                                                    $marks_second_like_innovation = get_option( 'marks_second_like_innovation');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="marks_second_like_innovation" value="<?php echo $marks_second_like_innovation; ?>" placeholder="Give a Name Second Marks">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Give a Name Third Marks(Invention)', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $marks_third_like_invention = 'Invention';
                                                if ( get_option( 'marks_third_like_invention' ) !== false ) {
                                                    $marks_third_like_invention = get_option( 'marks_third_like_invention');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="marks_third_like_invention" value="<?php echo $marks_third_like_invention; ?>" placeholder="Give a Name Third Marks">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Download Challenge User Subscriptions Data with (CSV)', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <div class="download-user-data-challenge-cover">
                                                    <div class="modal-download-challenge-vc-ac-cb-div">
                                                        <ul class="vc-cboxtags">
                                                            <?php 
                                                            $result = $this->all_usermeta();

                                                            $user_mata_access_vgen_subscriptions_challenges_by_admin = array();
                                                            if ( get_option( 'user_mata_access_vgen_subscriptions_challenge_by_admin' ) !== false ) {
                                                                $user_mata_access_vgen_subscriptions_challenges_by_admin = get_option( 'user_mata_access_vgen_subscriptions_challenge_by_admin');
                                                            }
                                                            foreach($result as $key => $value) {
                                                                $checked = ( in_array( $value ,$user_mata_access_vgen_subscriptions_challenges_by_admin ) ) ? 'checked' : '';
                                                            ?>
                                                                <li>
                                                                    <input type="checkbox" name="user_mata_access_vgen_subscriptions_challenge_by_admin[]" id="checkbox<?php echo $value . '_' . $post_id ?>" value="<?php echo $value ?>" <?php echo $checked; ?>/>
                                                                    <label for="checkbox<?php echo $value . '_' . $post_id ?>"><?php echo $value ?></label>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Download Challenge User Data with (CSV)', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <div class="download-user-data-challenge-cover">
                                                    <div class="modal-download-challenge-vc-ac-cb-div">
                                                        <ul class="vc-cboxtags">
                                                            <?php 
                                                            $result = $this->all_usermeta();

                                                            $user_mata_access_vgen_challenges_by_admin = array();
                                                            if ( get_option( 'user_mata_access_vgen_challenge_by_admin' ) !== false ) {
                                                                $user_mata_access_vgen_challenges_by_admin = get_option( 'user_mata_access_vgen_challenge_by_admin');
                                                            }
                                                            foreach($result as $key => $value) {
                                                                $checked = ( in_array( $value ,$user_mata_access_vgen_challenges_by_admin ) ) ? 'checked' : '';
                                                            ?>
                                                                <li>
                                                                    <input type="checkbox" name="user_mata_access_vgen_challenge_by_admin[]" id="checkbox_download<?php echo $value . '_' . $post_id ?>" value="<?php echo $value ?>" <?php echo $checked; ?>/>
                                                                    <label for="checkbox_download<?php echo $value . '_' . $post_id ?>"><?php echo $value ?></label>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Shortcode', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <div class="vc-note">[vgen-challenge], for challenge with contact form 7 = [vgen-challenge format="cf7"], for challenge with url = [vgen-challenge format="url"], for challenge with zip = [vgen-challenge format="zip"], for challenge with pdf = [vgen-challenge format="pdf"], for challenge with zip or pdf = [vgen-challenge format="zip or pdf"], for download = [vgen-challenge download-url="https://example.com/"], for unsubscribe = [vgen-challenge unsubscribe="yes"], for participate marks list = [vgen-challenge-participate-list challenge-id="604"], for related challeng = [vgen-challenge-related]</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"></th>
                                            <td class="text-left">
                                                <input type="submit" class="vgen_challenge-submit-btn" name="vgen_challenge_submit_btn" value="Submit" style="float:left">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <?php
        }

        function submenu_mail_settings_function(){
            if(isset($_POST['vgen_challenge_submit_btn'])) $this->updateSettings($_POST);
            ?>

            <div class="vgen-challenge-submenu">
                <div class="vgen-challenge-title-csv">
                    <div class="vgen-challenge-submenu-title">
                        <h1><?php _e('Vgen Challenge Settings', 'vgen_challenge'); ?></h1>
                    </div>
                </div>
                <!-- Settings -->
                <div class="vgen-challenge">

                    <div class="tabcontent vgen-challenge-marking-system" style="display:block;">
                        <div class="settingsInner">
                            <form id="vgen_challenge_submit" method="post" action="">
                                <table class="vgen-challenge-data-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left"><?php _e('Shortcode For mail', 'vgen_challenge' ); ?></th>
                                            <td class="text-left"><div class="">User Name = {user_name}, Challenge Name = {challenge_name}.</div></td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Admin Mail For Getting subscribe and unsubscribe', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $admin_mail_for_subscribe = 'iscrizioni@vgen.it';
                                                if ( get_option( 'admin_mail_for_subscribe' ) !== false ) {
                                                    $admin_mail_for_subscribe = get_option( 'admin_mail_for_subscribe');
                                                }
                                                ?>
                                                <input type="email" class="form-control" name="admin_mail_for_subscribe" value="<?php echo $admin_mail_for_subscribe; ?>" placeholder="Admin Mail">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Admin Mail For Getting Answer', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $admin_mail_for_getting_answer_with_link = 'iscrizioni@vgen.it';
                                                if ( get_option( 'admin_mail_for_getting_answer_with_link' ) !== false ) {
                                                    $admin_mail_for_getting_answer_with_link = get_option( 'admin_mail_for_getting_answer_with_link');
                                                }
                                                ?>
                                                <input type="email" class="form-control" name="admin_mail_for_getting_answer_with_link" value="<?php echo $admin_mail_for_getting_answer_with_link; ?>" placeholder="Admin Mail For Getting Answer with link">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Do you want the owner of the challenge to get answers?', 'vgen_challenge'); ?></th>
                                            <td class="text-left">
                                                <div class='checkbox' id='hideSearch'>
                                                    <label class='checkbox__container'>
                                                    <input type="hidden" name="challenge_owner_get_answers" value="0">
                                                    <input class='checkbox__toggle' type='checkbox' value="1" name='challenge_owner_get_answers' <?php echo $checked = (get_option( 'challenge_owner_get_answers' ) == 1) ? 'checked' : '' ; ?>/>
                                                    <span class='checkbox__checker'></span>
                                                    <span class='checkbox__txt-left'>Yes</span>
                                                    <span class='checkbox__txt-right'>No</span>
                                                    <svg class='checkbox__bg' space='preserve' style='enable-background:new 0 0 110 43.76;' version='1.1' viewbox='0 0 110 43.76'>
                                                        <path class='shape' d='M88.256,43.76c12.188,0,21.88-9.796,21.88-21.88S100.247,0,88.256,0c-15.745,0-20.67,12.281-33.257,12.281,S38.16,0,21.731,0C9.622,0-0.149,9.796-0.149,21.88s9.672,21.88,21.88,21.88c17.519,0,20.67-13.384,33.263-13.384,S72.784,43.76,88.256,43.76z'></path>
                                                    </svg>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        if(get_option( 'submit_the_challenge_through_the_link' ) == 1){
                                        ?>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge URL Submit Mail Title For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_submit_title_for_admin = 'Upload da {challenge_submit_user_email} per {challenge_name}';
                                                if ( get_option( 'challenge_submit_title_for_admin' ) !== false ) {
                                                    $challenge_submit_title_for_admin = get_option( 'challenge_submit_title_for_admin');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_submit_title_for_admin" value="<?php echo $challenge_submit_title_for_admin; ?>" placeholder="Challenge Submit Title For Admin">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge URL Submit Mail Body For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_submit_body_for_admin = '
                                                From: {challenge_submit_user_email}</br>
                                                Submit date: {challenge_submit_date}</br>
                                                Submit time: {challenge_submit_time}</br>
                                                Challenge name: {challenge_name}</br>
                                                Participant name: {user_name}</br>
                                                Challenge answer url: {challenge_submit_drive_link}</br>
                                                Provide a mark based on the answer to this project: {challenge_mark_url}
                                                ';
                                                if ( get_option( 'challenge_submit_body_for_admin' ) !== false ) {
                                                    $challenge_submit_body_for_admin = get_option( 'challenge_submit_body_for_admin');
                                                }
                                                wp_editor(
                                                    stripslashes( $challenge_submit_body_for_admin ),
                                                    'challenge_submit_body_for_admin',
                                                    array(
                                                        'media_buttons' => true,
                                                        'textarea_rows' => 20,
                                                        'tabindex' => 4,
                                                        'tinymce' => array(
                                                            'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                        ),
                                                    )
                                                )   
                                                ?>
                                                <div class="">User Email = {challenge_submit_user_email}, Date = {challenge_submit_date}, Time = {challenge_submit_time}, Challenge Name = {challenge_name}, User Mark URL = {challenge_mark_url}, Drive Link = {challenge_submit_drive_link}.</div>
                                            </td>
                                        </tr>
                                        <?php
                                        }else{
                                        ?>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Submit By Mail Title For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_submit_by_mail_title_for_admin = 'Upload da {challenge_submit_user_email} per {challenge_name}';
                                                if ( get_option( 'challenge_submit_by_mail_title_for_admin' ) !== false ) {
                                                    $challenge_submit_by_mail_title_for_admin = get_option( 'challenge_submit_by_mail_title_for_admin');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_submit_by_mail_title_for_admin" value="<?php echo $challenge_submit_by_mail_title_for_admin; ?>" placeholder="Challenge Submit Title For Admin">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Submit By Mail Body For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_submit_by_mail_body_for_admin = '
                                                From: {challenge_submit_user_email}</br>
                                                Submit date: {challenge_submit_date}</br>
                                                Submit time: {challenge_submit_time}</br>
                                                Challenge name: {challenge_name}</br>
                                                Participant name: {user_name}</br>
                                                Provide a mark based on the answer to this project: {challenge_mark_url}
                                                ';
                                                if ( get_option( 'challenge_submit_by_mail_body_for_admin' ) !== false ) {
                                                    $challenge_submit_by_mail_body_for_admin = get_option( 'challenge_submit_by_mail_body_for_admin');
                                                }
                                                wp_editor(
                                                    stripslashes( $challenge_submit_by_mail_body_for_admin ),
                                                    'challenge_submit_by_mail_body_for_admin',
                                                    array(
                                                        'media_buttons' => true,
                                                        'textarea_rows' => 20,
                                                        'tabindex' => 4,
                                                        'tinymce' => array(
                                                            'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                        ),
                                                    )
                                                )   
                                                ?>
                                                <div class="">User Email = {challenge_submit_user_email}, Date = {challenge_submit_date}, Time = {challenge_submit_time}, Challenge Name = {challenge_name}, User Mark URL = {challenge_mark_url}.</div>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Submit Title For User', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_submit_title_for_user = '{challenge_name} Challenge accepted';
                                                if ( get_option( 'challenge_submit_title_for_user' ) !== false ) {
                                                    $challenge_submit_title_for_user = get_option( 'challenge_submit_title_for_user');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_submit_title_for_user" value="<?php echo $challenge_submit_title_for_user; ?>" placeholder="Challenge Submit Title For User">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Submit Body For User', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_submit_body_for_user = '<div class="es-wrapper-color" style="background-color: #f6f6f6;">
                                                <table class="es-wrapper" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; padding: 0; margin: 0; width: 100%; height: 100%; background-repeat: repeat; background-position: center top;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td class="st-br" style="padding: 0; margin: 0;" valign="top">
                                                <table class="es-content" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; background-color: transparent;" align="center" bgcolor="transparent">
                                                <table class="es-content-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0px; background-position: left bottom; padding: 10px 20px; text-align: center;" align="left"><a href="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale.png"><img class=" wp-image-17903 aligncenter" src="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale-1024x373.png" alt="" width="203" height="74" /></a></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0; border-radius: 10px 10px 0px 0px; background-color: #ffffff; background-position: left bottom; padding: 30px 30px 15px 30px;" align="left" bgcolor="#ffffff">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="540">
                                                <table style="border-collapse: collapse; border-spacing: 0px; background-position: left bottom; width: 100%; height: 448px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; font-size: 0px; height: 334px;" align="center"><img class="adapt-img" style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" src="https://fxopkh.stripocdn.email/content/guids/CABINET_d30b682b069734e7d9a20df1b8a47513/images/48821587547954283.png" alt="" width="530" /></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0px; font-size: 0px; height: 25px;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #FFFFFF; background: #FFFFFFnone repeat scroll 0% 0%; height: 1px; width: 100%;"> </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 27px;" align="center"><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">Thank you so much for participating in the challenge.</span></strong></span><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">{user_name}!</span></strong></span></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px 0px 0px; margin: 0px; height: 62px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 20px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 30px; color: #131313;"><span style="font-size: 14pt; font-family: helvetica, arial, sans-serif;">Hopefully, we got the right solution for this <strong><span style="font-size: 18pt;">{challenge_name}</span></strong> challenge!</span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 24px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">If you have any problems, please contact us at <span style="color: #3300cc;"><a style="-moz-text-size-adjust: none; font-size: 16px; text-decoration: underline; color: #3300cc;" href="mailto:info@vgen.it" target="_blank" rel="noopener">info@vgen.it</a></span></span></p>
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">                             <br /></span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <table class="es-footer" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%; background-color: #f6f6f6; background-repeat: repeat; background-position: center top;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <table class="es-footer-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="left">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="600">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0; font-size: 0;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #CCCCCC; background: none; height: 1px; width: 100%;"> </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-bottom: 15px; font-size: 0px;" align="center">
                                                <table class="es-table-not-adapt es-social" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.facebook.com/v0gen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Facebook" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/facebook-circle-gray.png" alt="Fb" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.instagram.com/vgen_ig/?hl=it" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Instagram" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/instagram-circle-gray.png" alt="Ig" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.linkedin.com/company/vgen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Linkedin" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/linkedin-circle-gray.png" alt="In" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.youtube.com/channel/UCoOsUJXt0ZwPGy2JkHHFZ5A" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Youtube" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/youtube-circle-gray.png" alt="Yt" width="32" height="32" /></a></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><span style="color: #a9a9a9;"><strong><span style="font-family: helvetica, arial, sans-serif;">You are receiving this email because you have visited our site or asked us about regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).</span></strong></span></p>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-top: 5px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><strong><span style="font-family: helvetica, arial, sans-serif;"><a style="-moz-text-size-adjust: none; font-size: 13px; text-decoration: underline; color: #a9a9a9;" href="https://www.vgen.it/privacy-policy/" target="_blank" rel="noopener">Privacy</a> </span></strong></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </div>';
                                                if ( get_option( 'challenge_submit_body_for_user' ) !== false ) {
                                                    $challenge_submit_body_for_user = get_option( 'challenge_submit_body_for_user');
                                                    // $challenge_submit_body_for_user_r = str_replace( "\&quot;","", $challenge_submit_body_for_user );
                                                }
                                                wp_editor(
                                                    stripslashes( $challenge_submit_body_for_user ),
                                                    'challenge_submit_body_for_user',
                                                    array(
                                                        'media_buttons' => true,
                                                        'textarea_rows' => 20,
                                                        'tabindex' => 4,
                                                        'tinymce' => array(
                                                            'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                        ),
                                                    )
                                                )   
                                                ?>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Subscribe Title For User', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_subscribe_title_for_user = 'Conferma di iscrizione a {challenge_name}';
                                                if ( get_option( 'challenge_subscribe_title_for_user' ) !== false ) {
                                                    $challenge_subscribe_title_for_user = get_option( 'challenge_subscribe_title_for_user');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_subscribe_title_for_user" value="<?php echo $challenge_subscribe_title_for_user; ?>" placeholder="Challenge Subscribe Title For User">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Subscribe Body For User', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_subscribe_body_for_user = '<div class="es-wrapper-color" style="background-color: #f6f6f6;">
                                                <table class="es-wrapper" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; padding: 0; margin: 0; width: 100%; height: 100%; background-repeat: repeat; background-position: center top;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td class="st-br" style="padding: 0; margin: 0;" valign="top">
                                                <table class="es-content" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; background-color: transparent;" align="center" bgcolor="transparent">
                                                <table class="es-content-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0px; background-position: left bottom; padding: 10px 20px; text-align: center;" align="left"><a href="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale.png"><img class=" wp-image-17903 aligncenter" src="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale-1024x373.png" alt="" width="203" height="74" /></a></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0; border-radius: 10px 10px 0px 0px; background-color: #ffffff; background-position: left bottom; padding: 30px 30px 15px 30px;" align="left" bgcolor="#ffffff">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="540">
                                                <table style="border-collapse: collapse; border-spacing: 0px; background-position: left bottom; width: 100%; height: 448px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; font-size: 0px; height: 334px;" align="center"><img class="adapt-img" style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" src="https://fxopkh.stripocdn.email/content/guids/CABINET_d30b682b069734e7d9a20df1b8a47513/images/48821587547954283.png" alt="" width="530" /></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0px; font-size: 0px; height: 25px;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #FFFFFF; background: #FFFFFFnone repeat scroll 0% 0%; height: 1px; width: 100%;"></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 27px;" align="center"><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;"> Thank you! For Subscribe. </span></strong></span><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">{user_name}!</span></strong></span></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px 0px 0px; margin: 0px; height: 62px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 20px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 30px; color: #131313;"><span style="font-size: 14pt; font-family: helvetica, arial, sans-serif;">We hope you find a solution to our problem as soon as possible. Keep in touch with VGen.</span> <span style="font-size: 14pt; font-family: helvetica, arial, sans-serif;">We are really happy to have you! 🚀 </span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 24px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">If you have any problems, please contact us at <span style="color: #3300cc;"><a style="-moz-text-size-adjust: none; font-size: 16px; text-decoration: underline; color: #3300cc;" href="mailto:info@vgen.it" target="_blank" rel="noopener">info@vgen.it</a></span></span></p>
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">
                                                </span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <table class="es-footer" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%; background-color: #f6f6f6; background-repeat: repeat; background-position: center top;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <table class="es-footer-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="left">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="600">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0; font-size: 0;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #CCCCCC; background: none; height: 1px; width: 100%;"></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-bottom: 15px; font-size: 0px;" align="center">
                                                <table class="es-table-not-adapt es-social" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.facebook.com/v0gen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Facebook" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/facebook-circle-gray.png" alt="Fb" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.instagram.com/vgen_ig/?hl=it" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Instagram" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/instagram-circle-gray.png" alt="Ig" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.linkedin.com/company/vgen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Linkedin" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/linkedin-circle-gray.png" alt="In" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.youtube.com/channel/UCoOsUJXt0ZwPGy2JkHHFZ5A" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Youtube" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/youtube-circle-gray.png" alt="Yt" width="32" height="32" /></a></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><span style="color: #a9a9a9;"><strong><span style="font-family: helvetica, arial, sans-serif;">You are receiving this email because you have visited our site or asked us about regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).</span></strong></span></p>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-top: 5px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><strong><span style="font-family: helvetica, arial, sans-serif;"><a style="-moz-text-size-adjust: none; font-size: 13px; text-decoration: underline; color: #a9a9a9;" href="https://www.vgen.it/privacy-policy/" target="_blank" rel="noopener">Privacy</a> </span></strong></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </div>';
                                                if ( get_option( 'challenge_subscribe_body_for_user' ) !== false ) {
                                                    $challenge_subscribe_body_for_user = get_option( 'challenge_subscribe_body_for_user');
                                                    // $challenge_subscribe_body_for_user_r = str_replace( "\&quot;","", $challenge_subscribe_body_for_user );
                                                }
                                                wp_editor(
                                                    stripslashes( $challenge_subscribe_body_for_user ),
                                                    'challenge_subscribe_body_for_user',
                                                    array(
                                                        'media_buttons' => true,
                                                        'textarea_rows' => 20,
                                                        'tabindex' => 4,
                                                        'tinymce' => array(
                                                            'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                        ),
                                                    )
                                                )   
                                                ?>
                                                <div class=""></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Subscribe Title For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_subscribe_title_for_admin = 'Registrazione a {challenge_name}';
                                                if ( get_option( 'challenge_subscribe_title_for_admin' ) !== false ) {
                                                    $challenge_subscribe_title_for_admin = get_option( 'challenge_subscribe_title_for_admin');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_subscribe_title_for_admin" value="<?php echo $challenge_subscribe_title_for_admin; ?>" placeholder="Challenge Subscribe Title For Admin">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Subscribe Body For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_subscribe_body_for_admin = '<div class="es-wrapper-color" style="background-color: #f6f6f6;">
                                                <table class="es-wrapper" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; padding: 0; margin: 0; width: 100%; height: 100%; background-repeat: repeat; background-position: center top;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td class="st-br" style="padding: 0; margin: 0;" valign="top">
                                                <table class="es-content" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; background-color: transparent;" align="center" bgcolor="transparent">
                                                <table class="es-content-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0px; background-position: left bottom; padding: 10px 20px; text-align: center;" align="left"><a href="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale.png"><img class=" wp-image-17903 aligncenter" src="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale-1024x373.png" alt="" width="203" height="74" /></a></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0; border-radius: 10px 10px 0px 0px; background-color: #ffffff; background-position: left bottom; padding: 30px 30px 15px 30px;" align="left" bgcolor="#ffffff">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="540">
                                                <table style="border-collapse: collapse; border-spacing: 0px; background-position: left bottom; width: 100%; height: 448px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; font-size: 0px; height: 334px;" align="center"><img class="adapt-img" style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" src="https://fxopkh.stripocdn.email/content/guids/CABINET_d30b682b069734e7d9a20df1b8a47513/images/48821587547954283.png" alt="" width="530" /></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0px; font-size: 0px; height: 25px;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #FFFFFF; background: #FFFFFFnone repeat scroll 0% 0%; height: 1px; width: 100%;"> </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 27px;" align="center"><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">Congratulation you get a Subscribe. Name is </span></strong></span><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">{user_name}!</span></strong></span></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px 0px 0px; margin: 0px; height: 62px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 20px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 30px; color: #131313;"><span style="font-size: 14pt; font-family: helvetica, arial, sans-serif;">Here is the name of your challenge <strong><span style="font-size: 18pt;">{challenge_name}!</span></strong></span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 24px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">If you have any problems, please contact us at <span style="color: #3300cc;"><a style="-moz-text-size-adjust: none; font-size: 16px; text-decoration: underline; color: #3300cc;" href="mailto:info@vgen.it" target="_blank" rel="noopener">info@vgen.it</a></span></span></p>
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">                             <br /></span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <table class="es-footer" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%; background-color: #f6f6f6; background-repeat: repeat; background-position: center top;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <table class="es-footer-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="left">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="600">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0; font-size: 0;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #CCCCCC; background: none; height: 1px; width: 100%;"> </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-bottom: 15px; font-size: 0px;" align="center">
                                                <table class="es-table-not-adapt es-social" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.facebook.com/v0gen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Facebook" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/facebook-circle-gray.png" alt="Fb" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.instagram.com/vgen_ig/?hl=it" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Instagram" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/instagram-circle-gray.png" alt="Ig" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.linkedin.com/company/vgen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Linkedin" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/linkedin-circle-gray.png" alt="In" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.youtube.com/channel/UCoOsUJXt0ZwPGy2JkHHFZ5A" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Youtube" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/youtube-circle-gray.png" alt="Yt" width="32" height="32" /></a></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><span style="color: #a9a9a9;"><strong><span style="font-family: helvetica, arial, sans-serif;">You are receiving this email because you have visited our site or asked us about regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).</span></strong></span></p>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-top: 5px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><strong><span style="font-family: helvetica, arial, sans-serif;"><a style="-moz-text-size-adjust: none; font-size: 13px; text-decoration: underline; color: #a9a9a9;" href="https://www.vgen.it/privacy-policy/" target="_blank" rel="noopener">Privacy</a> </span></strong></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </div>';
                                                if ( get_option( 'challenge_subscribe_body_for_admin' ) !== false ) {
                                                    $challenge_subscribe_body_for_admin = get_option( 'challenge_subscribe_body_for_admin');
                                                    // $challenge_subscribe_body_for_admin_r = str_replace( "\&quot;","", $challenge_subscribe_body_for_admin );
                                                }
                                                wp_editor(
                                                    stripslashes( $challenge_subscribe_body_for_admin ),
                                                    'challenge_subscribe_body_for_admin',
                                                    array(
                                                        'media_buttons' => true,
                                                        'textarea_rows' => 20,
                                                        'tabindex' => 4,
                                                        'tinymce' => array(
                                                            'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                        ),
                                                    )
                                                )
                                                ?>
                                                <div class=""></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Unsubscribe Title For User', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_unsubscribe_title_for_user = 'La registrazione è stata cancellata {challenge_name}';
                                                if ( get_option( 'challenge_unsubscribe_title_for_user' ) !== false ) {
                                                    $challenge_unsubscribe_title_for_user = get_option( 'challenge_unsubscribe_title_for_user');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_unsubscribe_title_for_user" value="<?php echo $challenge_unsubscribe_title_for_user; ?>" placeholder="Challenge Unsubscribe Title For User">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Unsubscribe Body For User', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_unsubscribe_body_for_user = '<div class="es-wrapper-color" style="background-color: #f6f6f6;">
                                                <table class="es-wrapper" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; padding: 0; margin: 0; width: 100%; height: 100%; background-repeat: repeat; background-position: center top;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td class="st-br" style="padding: 0; margin: 0;" valign="top">
                                                <table class="es-content" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; background-color: transparent;" align="center" bgcolor="transparent">
                                                <table class="es-content-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0px; background-position: left bottom; padding: 10px 20px; text-align: center;" align="left"><a href="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale.png"><img class=" wp-image-17903 aligncenter" src="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale-1024x373.png" alt="" width="203" height="74" /></a></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0; border-radius: 10px 10px 0px 0px; background-color: #ffffff; background-position: left bottom; padding: 30px 30px 15px 30px;" align="left" bgcolor="#ffffff">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="540">
                                                <table style="border-collapse: collapse; border-spacing: 0px; background-position: left bottom; width: 100%; height: 448px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; font-size: 0px; height: 334px;" align="center"><img class="adapt-img" style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" src="https://fxopkh.stripocdn.email/content/guids/CABINET_d30b682b069734e7d9a20df1b8a47513/images/48821587547954283.png" alt="" width="530" /></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0px; font-size: 0px; height: 25px;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #FFFFFF; background: #FFFFFFnone repeat scroll 0% 0%; height: 1px; width: 100%;"></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 27px;" align="center"><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;"> You seem to have mistakenly Unsubscribe. </span></strong></span><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">{user_name}!</span></strong></span></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px 0px 0px; margin: 0px; height: 62px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 20px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 30px; color: #131313;"><span style="font-size: 14pt; font-family: helvetica, arial, sans-serif;">We hope you Will be back as soon as possible. Keep in touch with VGen.</span> <span style="font-size: 14pt; font-family: helvetica, arial, sans-serif;">We are really happy to have you! 🚀 </span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 24px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">If you have any problems, please contact us at <span style="color: #3300cc;"><a style="-moz-text-size-adjust: none; font-size: 16px; text-decoration: underline; color: #3300cc;" href="mailto:info@vgen.it" target="_blank" rel="noopener">info@vgen.it</a></span></span></p>
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">
                                                </span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <table class="es-footer" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%; background-color: #f6f6f6; background-repeat: repeat; background-position: center top;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <table class="es-footer-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="left">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="600">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0; font-size: 0;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #CCCCCC; background: none; height: 1px; width: 100%;"></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-bottom: 15px; font-size: 0px;" align="center">
                                                <table class="es-table-not-adapt es-social" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.facebook.com/v0gen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Facebook" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/facebook-circle-gray.png" alt="Fb" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.instagram.com/vgen_ig/?hl=it" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Instagram" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/instagram-circle-gray.png" alt="Ig" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.linkedin.com/company/vgen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Linkedin" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/linkedin-circle-gray.png" alt="In" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.youtube.com/channel/UCoOsUJXt0ZwPGy2JkHHFZ5A" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Youtube" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/youtube-circle-gray.png" alt="Yt" width="32" height="32" /></a></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><span style="color: #a9a9a9;"><strong><span style="font-family: helvetica, arial, sans-serif;">You are receiving this email because you have visited our site or asked us about regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).</span></strong></span></p>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-top: 5px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><strong><span style="font-family: helvetica, arial, sans-serif;"><a style="-moz-text-size-adjust: none; font-size: 13px; text-decoration: underline; color: #a9a9a9;" href="https://www.vgen.it/privacy-policy/" target="_blank" rel="noopener">Privacy</a> </span></strong></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </div>';
                                                if ( get_option( 'challenge_unsubscribe_body_for_user' ) !== false ) {
                                                    $challenge_unsubscribe_body_for_user = get_option( 'challenge_unsubscribe_body_for_user');
                                                    // $challenge_unsubscribe_body_for_user_r = str_replace( "\&quot;","", $challenge_unsubscribe_body_for_user );
                                                }
                                                wp_editor(
                                                    stripslashes( $challenge_unsubscribe_body_for_user ),
                                                    'challenge_unsubscribe_body_for_user',
                                                    array(
                                                        'media_buttons' => true,
                                                        'textarea_rows' => 20,
                                                        'tabindex' => 4,
                                                        'tinymce' => array(
                                                            'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                        ),
                                                    )
                                                )   
                                                ?>
                                                <div class=""></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Unsubscribe Title For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_unsubscribe_title_for_admin = 'La registrazione è stata cancellata {challenge_name}';
                                                if ( get_option( 'challenge_unsubscribe_title_for_admin' ) !== false ) {
                                                    $challenge_unsubscribe_title_for_admin = get_option( 'challenge_unsubscribe_title_for_admin');
                                                }
                                                ?>
                                                <input type="text" class="form-control" name="challenge_unsubscribe_title_for_admin" value="<?php echo $challenge_unsubscribe_title_for_admin; ?>" placeholder="Challenge Unsubscribe Title For Admin">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Challenge Unsubscribe Body For Admin', 'vgen_challenge' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $challenge_unsubscribe_body_for_admin = '<div class="es-wrapper-color" style="background-color: #f6f6f6;">
                                                <table class="es-wrapper" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; padding: 0; margin: 0; width: 100%; height: 100%; background-repeat: repeat; background-position: center top;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td class="st-br" style="padding: 0; margin: 0;" valign="top">
                                                <table class="es-content" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; background-color: transparent;" align="center" bgcolor="transparent">
                                                <table class="es-content-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0px; background-position: left bottom; padding: 10px 20px; text-align: center;" align="left"><a href="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale.png"><img class=" wp-image-17903 aligncenter" src="https://www.vgen.it/wp-content/uploads/2020/04/logo-VGen-definitivo-V-normale-1024x373.png" alt="" width="203" height="74" /></a></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="margin: 0; border-radius: 10px 10px 0px 0px; background-color: #ffffff; background-position: left bottom; padding: 30px 30px 15px 30px;" align="left" bgcolor="#ffffff">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="540">
                                                <table style="border-collapse: collapse; border-spacing: 0px; background-position: left bottom; width: 100%; height: 448px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; font-size: 0px; height: 334px;" align="center"><img class="adapt-img" style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" src="https://fxopkh.stripocdn.email/content/guids/CABINET_d30b682b069734e7d9a20df1b8a47513/images/48821587547954283.png" alt="" width="530" /></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0px; font-size: 0px; height: 25px;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #FFFFFF; background: #FFFFFFnone repeat scroll 0% 0%; height: 1px; width: 100%;"> </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 27px;" align="center"><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">You get a Unsubscribe. Name is </span></strong></span><span style="color: #000000; font-family: helvetica, arial, sans-serif;"><strong><span style="font-size: 18pt;">{user_name}!</span></strong></span></td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px 0px 0px; margin: 0px; height: 62px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 20px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 30px; color: #131313;"><span style="font-size: 14pt; font-family: helvetica, arial, sans-serif;">Here is the name of your challenge <strong><span style="font-size: 18pt;">{challenge_name}!</span></strong></span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0px; margin: 0px; height: 24px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">If you have any problems, please contact us at <span style="color: #3300cc;"><a style="-moz-text-size-adjust: none; font-size: 16px; text-decoration: underline; color: #3300cc;" href="mailto:info@vgen.it" target="_blank" rel="noopener">info@vgen.it</a></span></span></p>
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 16px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 24px; color: #131313;"><span style="font-family: helvetica, arial, sans-serif;">                             <br /></span></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                <table class="es-footer" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; table-layout: fixed !important; width: 100%; background-color: #f6f6f6; background-repeat: repeat; background-position: center top;" cellspacing="0" cellpadding="0" align="center">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <table class="es-footer-body" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; background-color: transparent;" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="transparent">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="left">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center" valign="top" width="600">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 20px; margin: 0; font-size: 0;" align="center">
                                                <table style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0px; border-bottom: 1px solid #CCCCCC; background: none; height: 1px; width: 100%;"> </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-bottom: 15px; font-size: 0px;" align="center">
                                                <table class="es-table-not-adapt es-social" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px;" role="presentation" cellspacing="0" cellpadding="0">
                                                <tbody>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.facebook.com/v0gen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Facebook" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/facebook-circle-gray.png" alt="Fb" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.instagram.com/vgen_ig/?hl=it" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Instagram" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/instagram-circle-gray.png" alt="Ig" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0; padding-right: 15px;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.linkedin.com/company/vgen/" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Linkedin" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/linkedin-circle-gray.png" alt="In" width="32" height="32" /></a></td>
                                                <td style="padding: 0; margin: 0;" align="center" valign="top"><a style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; font-size: 16px; text-decoration: underline; color: #ffffff;" href="https://www.youtube.com/channel/UCoOsUJXt0ZwPGy2JkHHFZ5A" target="_blank" rel="noopener"><img style="display: block; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" title="Youtube" src="https://fxopkh.stripocdn.email/content/assets/img/social-icons/circle-gray/youtube-circle-gray.png" alt="Yt" width="32" height="32" /></a></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><span style="color: #a9a9a9;"><strong><span style="font-family: helvetica, arial, sans-serif;">You are receiving this email because you have visited our site or asked us about regular newsletter. Make sure our messages get to your Inbox (and not your bulk or junk folders).</span></strong></span></p>
                                                </td>
                                                </tr>
                                                <tr style="border-collapse: collapse;">
                                                <td style="padding: 0; margin: 0; padding-top: 5px;" align="center">
                                                <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 13px; font-family: roboto, "helvetica neue", helvetica, arial, sans-serif; line-height: 20px; color: #131313;"><strong><span style="font-family: helvetica, arial, sans-serif;"><a style="-moz-text-size-adjust: none; font-size: 13px; text-decoration: underline; color: #a9a9a9;" href="https://www.vgen.it/privacy-policy/" target="_blank" rel="noopener">Privacy</a> </span></strong></p>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </td>
                                                </tr>
                                                </tbody>
                                                </table>
                                                </div>';
                                                if ( get_option( 'challenge_unsubscribe_body_for_admin' ) !== false ) {
                                                    $challenge_unsubscribe_body_for_admin = get_option( 'challenge_unsubscribe_body_for_admin');
                                                    // $challenge_subscribe_body_for_admin_r = str_replace( "\&quot;","", $challenge_unsubscribe_body_for_admin );
                                                }
                                                wp_editor(
                                                    stripslashes( $challenge_unsubscribe_body_for_admin ),
                                                    'challenge_unsubscribe_body_for_admin',
                                                    array(
                                                        'media_buttons' => true,
                                                        'textarea_rows' => 20,
                                                        'tabindex' => 4,
                                                        'tinymce' => array(
                                                            'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                        ),
                                                    )
                                                )
                                                ?>
                                                <div class=""></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"></th>
                                            <td class="text-left">
                                                <input type="submit" class="vgen_challenge-submit-btn" name="vgen_challenge_submit_btn" value="Submit" style="float:left">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <?php
        }

        
        //     // ajax
        // function vc_submit_answer_url_filterAjax(){
        //     ob_start();

        //     $file_id = $_POST['file_id'];
         
        //     $output = ob_get_clean();
        //     echo json_encode(
        //         array(
        //             'message' => 'success',
        //             'file_id' => $file_id,
        //         )
        //     );
        //     die(); 
        // }

        function vgen_challenge_participate_list_shortcode($atts){
            ob_start();
            if( $atts['challenge-id'] != '' ){
                $post_id = $atts['challenge-id'];

                $post_deadline = get_post_meta( $post_id, 'post_deadline', true );
                $today_date = date("Y-m-d");
                if( $post_deadline >= $today_date ){
                    echo "<div class='vgen_challenge-submit-title'>Your channel is not finished yet!</div>";
                }else{
                    $marks_first_like_creativity = 'Creativity';
                    if ( get_option( 'marks_first_like_creativity' ) !== false ) {
                        $marks_first_like_creativity = get_option( 'marks_first_like_creativity');
                    }
                    $marks_second_like_innovation = 'Innovation';
                    if ( get_option( 'marks_second_like_innovation' ) !== false ) {
                        $marks_second_like_innovation = get_option( 'marks_second_like_innovation');
                    }
                    $marks_third_like_invention = 'Invention';
                    if ( get_option( 'marks_third_like_invention' ) !== false ) {
                        $marks_third_like_invention = get_option( 'marks_third_like_invention');
                    }

                    $table_name = $this->user_participation_database;
                    $qry = $this->wpdb->get_results( "SELECT * FROM $table_name WHERE `post_id` = $post_id ORDER BY (`user_creativity_marks` + `user_innovation_marks` + `user_invention_marks`) DESC", OBJECT);
                    $all_files = json_decode(json_encode($qry), true);
                    ?>
                    <div class="vgen_challenge-participator-marks-cover">
                        <table class="tablesorter eael-data-table center" id="eael-data-table-8072fed">
                            <thead>
                                <tr class="table-header">
                                    <th class="" id="" colspan=""><span class="data-table-header-text">No.</span></th>
                                    <th class="" id="" colspan=""><span class="data-table-header-text">Participator Name</span></th>
                                    <th class="" id="" colspan=""><span class="data-table-header-text"><?php echo $marks_first_like_creativity; ?></span></th>
                                    <th class="" id="" colspan=""><span class="data-table-header-text"><?php echo $marks_second_like_innovation; ?></span></th>
                                    <th class="" id="" colspan=""><span class="data-table-header-text"><?php echo $marks_third_like_invention; ?></span></th>
                                    <th class="" id="" colspan=""><span class="data-table-header-text">Total</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $j = 1;
                                foreach ($all_files as $single_file){
                                    $challenge_title = get_the_title( $single_file['post_id'] );
                                    $user_id = $single_file['user_id'];
                                    $user_info = get_userdata( $user_id );
                                    $user_name = $user_info->display_name;
                                    $user_creativity_marks = $single_file['user_creativity_marks'];
                                    $user_innovation_marks = $single_file['user_innovation_marks'];
                                    $user_invention_marks = $single_file['user_invention_marks'];
                                    $total_marks = ( $single_file['user_invention_marks'] + $single_file['user_creativity_marks'] + $single_file['user_innovation_marks'] );
                                    ?>

                                    <tr>
                                        <td colspan="" rowspan="" class="" id="">
                                            <div class="td-content-wrapper">
                                                <div class="th-mobile-screen">
                                                    <span class="data-table-header-text"><?php echo $j++; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="" rowspan="" class="" id="">
                                            <div class="td-content-wrapper">
                                                <div class="th-mobile-screen">
                                                    <span class="data-table-header-text"><?php echo $user_name; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="" rowspan="" class="" id="">
                                            <div class="td-content-wrapper">
                                                <div class="th-mobile-screen">
                                                    <span class="data-table-header-text"><?php echo $user_creativity_marks; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="" rowspan="" class="" id="">
                                            <div class="td-content-wrapper">
                                                <div class="th-mobile-screen">
                                                    <span class="data-table-header-text"><?php echo $user_innovation_marks; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="" rowspan="" class="" id="">
                                            <div class="td-content-wrapper">
                                                <div class="th-mobile-screen">
                                                    <span class="data-table-header-text"><?php echo $user_invention_marks; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td colspan="" rowspan="" class="" id="">
                                            <div class="td-content-wrapper">
                                                <div class="th-mobile-screen">
                                                    <span class="data-table-header-text"><?php echo $total_marks; ?></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                            <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                <?php
                }
            }else{
                echo "<div class='vgen_challenge-submit-title'>Your challenge id is not valid!</div>";
            }
            
            ?>
            
            <?php
            $output = ob_get_clean();
            return $output;
            wp_reset_query();
        }

        function vgen_challenge_shortcode($atts){
            ob_start();

            global $post;

            if (isset($_POST['subscribeChallenge']) && isset($_POST['post_id'])) {
                if( !$this->is_user_subscribed() ){
                    $this->subscribe_user_to_post_for_vc($_POST['post_id']);
                }
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    {$link = "https";}
                else
                    {$link = "http";}
                $link .= "://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $link = explode("?", $link);
                $link = $link[0];
                header ("location: $link");
            }
            if (isset($_POST['unsubscribeChallenge']) && isset($_POST['post_id'])) {
                if( $this->is_user_subscribed() ){
                    $this->unsubscribe_user_to_post_for_vc($_POST['post_id']);
                }
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    {$link = "https";}
                else
                    {$link = "http";}
                $link .= "://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $link = explode("?", $link);
                $link = $link[0];
                header ("location: $link");
            }
            
            if( $this->is_user_subscribed() AND !$this->is_user_participate() ){

                if(get_option( 'submit_the_challenge_through_the_link' ) == 1){
                    if (isset($_POST['vgen_challenge_url_submit']) && isset($_POST['vgen_challenge_submit_post_id'])) {

                        $insert_date = date("Y-m-d");
                        $insert_time = date("h:i:sa");
                        $user_id = $_POST['vgen_challenge_submit_user_id'];
                        $post_id = $_POST['vgen_challenge_submit_post_id'];
                        $vgen_challenge_submit_url = $_POST['vgen_challenge_submit_url'];
                        $the_user = get_user_by( 'id', $user_id );
                        $email = $the_user->user_email;
                        $nonce = wp_create_nonce( $email . $post_id . time() );

                        $uploaded_type = 'submit_url';
        
                        $table_name = $this->user_participation_database;
                        $insert_participation = $this->wpdb->insert(
                            $table_name,
                            array(
                                'user_id' => $user_id,
                                'post_id' => $post_id,
                                'uploaded_nonce' => $nonce,
                                'uploaded_url' => $vgen_challenge_submit_url,
                                'uploaded_type' => $uploaded_type,
                                'insert_date' => $insert_date,
                            ),
                            array('%d', '%s', '%s', '%s', '%s', '%s')
                        );
        
                        $user_info = get_userdata( $user_id );
                        $user_name = $user_info->display_name;
                        
                        $post_author_id = get_post_field( 'post_author', $post_id );
                        $post_author_info = get_userdata( $post_author_id );
                        $post_author_email = $post_author_info->user_email;
                        
                        $challenge = get_post($post_id);
                        $challenge_name = $challenge->post_title;

                        // author start
                        $admin_mail_for_getting_answer_with_link = 'iscrizioni@vgen.it';
                        if ( get_option( 'admin_mail_for_getting_answer_with_link' ) !== false ) {
                            $admin_mail_for_getting_answer_with_link = get_option( 'admin_mail_for_getting_answer_with_link');
                        }
                        $challenge_submit_title_for_admin = 'Upload da {challenge_submit_user_email} per {challenge_name}';
                        if ( get_option( 'challenge_submit_title_for_admin' ) !== false ) {
                            $challenge_submit_title_for_admin = get_option( 'challenge_submit_title_for_admin');
                        }
                        $challenge_submit_body_for_admin = '
                        From: {challenge_submit_user_email}</br>
                        Submit date: {challenge_submit_date}</br>
                        Submit time: {challenge_submit_time}</br>
                        Challenge name: {challenge_name}</br>
                        Participant name: {user_name}</br>
                        Challenge answer url: {challenge_submit_drive_link}</br>
                        Provide a mark based on the answer to this project: {challenge_mark_url}
                        ';
                        if ( get_option( 'challenge_submit_body_for_admin' ) !== false ) {
                            $challenge_submit_body_for_admin = get_option( 'challenge_submit_body_for_admin');
                        }
                        $marks_url = admin_url( 'admin.php?page=vgen-challenge-analytics-and-marking-system&marksId=' . $nonce ) . '&user-marks';

                        $challenge_submit_body_for_admin_email = str_replace("{challenge_submit_user_email}", $email, $challenge_submit_body_for_admin );
                        $challenge_submit_body_for_admin_insert_date = str_replace("{challenge_submit_date}", $insert_date, $challenge_submit_body_for_admin_email );
                        $challenge_submit_body_for_admin_insert_time = str_replace("{challenge_submit_time}", $insert_time, $challenge_submit_body_for_admin_insert_date );
                        $challenge_submit_body_for_admin_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_submit_body_for_admin_insert_time );
                        $challenge_submit_body_for_admin_challenge_submit_drive_link = str_replace("{challenge_submit_drive_link}", $vgen_challenge_submit_url, $challenge_submit_body_for_admin_challenge_name );
                        $challenge_submit_body_for_admin_challenge_submit_url = str_replace("{challenge_mark_url}", $marks_url, $challenge_submit_body_for_admin_challenge_submit_drive_link );
                        $challenge_submit_body_for_admin_user_name = str_replace("{user_name}", $user_name, $challenge_submit_body_for_admin_challenge_submit_url );

                        $challenge_submit_title_for_admin_email = str_replace("{challenge_submit_user_email}", $email, $challenge_submit_title_for_admin );
                        $challenge_submit_title_for_admin_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_submit_title_for_admin_email );

                        $subject_for_author = $challenge_submit_title_for_admin_challenge_name;
                        $body_for_author = stripslashes( $challenge_submit_body_for_admin_user_name );
                        $headers_for_author = 'From: ' . $email . "\r\n" .
                            'Reply-To: ' . $email . "\r\n";
                        $headers_for_author .= 'MIME-Version: 1.0' . "\r\n";
                        $headers_for_author .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
                        $sent_for_super_admin = wp_mail($admin_mail_for_getting_answer_with_link, $subject_for_author, $body_for_author, $headers_for_author);
                        $sent_for_challenge_admin = wp_mail($post_author_email, $subject_for_author, $body_for_author, $headers_for_author);
                        // author end
                        // user start
                        $challenge_submit_title_for_user = '{challenge_name} Challenge accepted';
                        if ( get_option( 'challenge_submit_title_for_user' ) !== false ) {
                            $challenge_submit_title_for_user = get_option( 'challenge_submit_title_for_user');
                        }
                        $challenge_submit_title_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_submit_title_for_user );
                        $challenge_submit_title_for_user_us = str_replace("{user_name}", $user_name, $challenge_submit_title_for_user_ch );
        
        
                        $challenge_submit_body_for_user = '';
                        if ( get_option( 'challenge_submit_body_for_user' ) !== false ) {
                            $challenge_submit_body_for_user = get_option( 'challenge_submit_body_for_user');
                        }
                        $challenge_submit_body_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_submit_body_for_user );
                        $challenge_submit_body_for_user_us = str_replace("{user_name}", $user_name, $challenge_submit_body_for_user_ch );
        
                        
                        $admin_mail_for_subscribe = 'iscrizioni@vgen.it';
                        if ( get_option( 'admin_mail_for_subscribe' ) !== false ) {
                            $admin_mail_for_subscribe = get_option( 'admin_mail_for_subscribe');
                        }
        
                        $admin_mail = $admin_mail_for_subscribe;
                        $subject_1 = $challenge_submit_title_for_user_us;
        
                        $body_1 = stripslashes( $challenge_submit_body_for_user_us );
        
                        $headers_1 = 'From: ' . $admin_mail . "\r\n" .
                            'Reply-To: ' . $admin_mail . "\r\n";
                        $headers_1 .= 'MIME-Version: 1.0' . "\r\n";
                        $headers_1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
                        $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1);
                        // user end


                        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                            {$link = "https";}
                        else
                            {$link = "http";}
                        $link .= "://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                        $link = explode("?", $link);
                        $link = $link[0];
                        header ("location: $link");
                    }
                }
            }
            
            $current_user_id = get_current_user_id();
            $current_page_id = get_the_ID();
            
            ?>
            <div class="vgen-challenge-cover <?php echo 'vc_unique_id_' . $current_page_id; ?>">
            <?php

            $vgen_challenge_button_background_color = '#000000';
            if ( get_option( 'vgen_challenge_button_background_color' ) !== false ) {
                $vgen_challenge_button_background_color = get_option( 'vgen_challenge_button_background_color');
            }
            $vgen_challenge_button_text_color = '#ffffff';
            if ( get_option( 'vgen_challenge_button_text_color' ) !== false ) {
                $vgen_challenge_button_text_color = get_option( 'vgen_challenge_button_text_color');
            }
            $subscribe_text = 'SUBSCRIBE';
            if ( get_option( 'subscribe_text' ) !== false ) {
                $subscribe_text = get_option( 'subscribe_text');
            }
            $unsubscribe_text = 'UNSUBSCRIBE';
            if ( get_option( 'unsubscribe_text' ) !== false ) {
                $unsubscribe_text = get_option( 'unsubscribe_text');
            }
            $challenge_text = 'SUBMIT';
            if ( get_option( 'challenge_text' ) !== false ) {
                $challenge_text = get_option( 'challenge_text');
            }
            $challenge_submit_title = 'UPLOAD';
            if ( get_option( 'challenge_submit_title' ) !== false ) {
                $challenge_submit_title = get_option( 'challenge_submit_title');
            }
            if(!is_user_logged_in()){ 
                $subscribe_login_page_url = '#';
                if ( get_option( 'subscribe_login_page_id' ) !== false ) {
                    $subscribe_login_page_id = get_option( 'subscribe_login_page_id');
                    $subscribe_login_page_url = get_permalink($subscribe_login_page_id);
                }
                ?>
                <a class="vgen_challenge_subscribe" href="<?php echo $subscribe_login_page_url; ?>" style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;"><?php echo $subscribe_text; ?></a>
                <?php
            }elseif( $atts['download-url'] != '' ){
                //download
                $download_text = 'DOWNLOAD';
                if ( get_option( 'download_text' ) !== false ) {
                    $download_text = get_option( 'download_text');
                }
                $post_filter_award_coming_soon = get_post_meta( $current_page_id, 'post_filter_award_coming_soon', true );
                if( $post_filter_award_coming_soon == 0 ){

                    $post_deadline = get_post_meta( $current_page_id, 'post_deadline', true );
                    $today_date = date("Y-m-d");
                    if( $post_deadline >= $today_date ){
                        if( !$this->is_user_subscribed() ){
                            ?>
                            <form method="post" id="vgen_subscribe_submit_form">
                                <input type='hidden' name='subscribeChallenge' value='true'>
                                <input type='hidden' name='post_id' value="<?php echo $current_page_id; ?>">
                                <input class='vgen_challenge_subscribe' style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;" type='submit' name='submit' value="<?php echo $subscribe_text; ?>">
                            </form>
                            <?php
                        }elseif( $this->is_user_subscribed() AND !$this->is_user_participate() ){
                        ?>
                            <a class="vgen_challenge_subscribe" href="<?php echo $atts['download-url']; ?>" style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;"><?php echo $download_text; ?></a>
                        <?php
                        }
                    
                    }else{
                        ?>
                        <div class="vgen_challenge-submit-title"><?php _e( 'Challenge over!', 'vgen_challenge' ); ?></div>
                        <?php
                    }
                }else{
                    ?>
                    <div class="vgen_challenge-submit-title"><?php _e( 'Coming Soon!', 'vgen_challenge' ); ?></div>
                    <?php
                }

            }else{
                
                $post_filter_award_coming_soon = get_post_meta( $current_page_id, 'post_filter_award_coming_soon', true );
                if( $post_filter_award_coming_soon == 0 ){

                    $post_deadline = get_post_meta( $current_page_id, 'post_deadline', true );
                    $today_date = date("Y-m-d");
                    if( $post_deadline >= $today_date ){

                        if(!is_user_logged_in()){ 
                            $subscribe_login_page_url = '#';
                            if ( get_option( 'subscribe_login_page_id' ) !== false ) {
                                $subscribe_login_page_id = get_option( 'subscribe_login_page_id');
                                $subscribe_login_page_url = get_permalink($subscribe_login_page_id);
                            }
                            ?>
                            <a class="vgen_challenge_subscribe" href="<?php echo $subscribe_login_page_url; ?>" style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;"><?php echo $subscribe_text; ?></a>
                            <?php
                        }elseif( $atts['unsubscribe'] == 'yes' ){
                            if( !$this->is_user_subscribed() ){
                                ?>
                                <form method="post" id="vgen_subscribe_submit_form">
                                    <input type='hidden' name='subscribeChallenge' value='true'>
                                    <input type='hidden' name='post_id' value="<?php echo $current_page_id; ?>">
                                    <input class='vgen_challenge_subscribe' style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;" type='submit' name='submit' value="<?php echo $subscribe_text; ?>">
                                </form>
                                <?php
                            }else{
                                if( $this->is_user_subscribed() AND $this->is_user_participate() ){
                                    $test_mas = 'yes';
                                    if( $test_mas == 'no' ){
                                    ?>
                                        <div class="vgen_challenge-submit-title"><?php _e( 'You participated in the challenge! Therefore, you cannot cancel the registration.', 'vgen_challenge' ); ?></div>
                                    <?php
                                    }
                                }else{
                                    ?>
                                    <form method="post" id="vgen_unsubscribe_submit_form">
                                        <input type='hidden' name='unsubscribeChallenge' value='true'>
                                        <input type='hidden' name='post_id' value="<?php echo $current_page_id; ?>">
                                        <input class='vgen_challenge_unsubscribe' style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;" type='submit' name='submit' value="<?php echo $unsubscribe_text; ?>">
                                    </form>
                                    <?php
                                }
                            }

                        }elseif(  !$this->is_user_subscribed() ){
                            ?>
                            <form method="post" id="vgen_subscribe_submit_form">
                                <input type='hidden' name='subscribeChallenge' value='true'>
                                <input type='hidden' name='post_id' value="<?php echo $current_page_id; ?>">
                                <input class='vgen_challenge_subscribe' style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;" type='submit' name='submit' value="<?php echo $subscribe_text; ?>">
                            </form>
                            <?php
                        }elseif( $this->is_user_subscribed() AND !$this->is_user_participate() ){
                            // if(get_option( 'submit_the_challenge_through_the_link' ) == 1){
                            $format = $atts['format'];
                            if( $format == 'url' ){
                                

                                
                                $challenge_submit_welcome_page_url = $_SERVER['REQUEST_URI'];
                                if ( get_option( 'challenge_submit_welcome_page' ) !== false ) {
                                    $challenge_submit_welcome_page = get_option( 'challenge_submit_welcome_page');
                                    $challenge_submit_welcome_page_url = get_permalink($challenge_submit_welcome_page);
                                }

                                ?>
                                <div class="vgen_Challenge_submit_cover">
                                    <form method="post" id="vgen_Challenge_submit_form">
                                        <div class="vgen_challenge_submit_form_title"><?php _e( 'Submit your Challenge Answer URL', 'vgen_challenge' ); ?></div>
                                        </br>
                                        <input type='hidden' name='challenge_submit_welcome_page_url' id='challenge_submit_welcome_page_url' value='<?php echo $challenge_submit_welcome_page_url; ?>'>
                                        <input type='hidden' name='vgen_challenge_submit_user_id' value='<?php echo $current_user_id; ?>'>
                                        <input type='hidden' name='vgen_challenge_submit_post_id' value="<?php echo $current_page_id; ?>">
                                        <input type='url' name='vgen_challenge_submit_url' class="vgen_challenge_submit_url" value="" placeholder="https://drive.google.com/drive/folders/example?usp=sharing" pattern="https://.*" size="200" required>
                                        </br>
                                        </br>
                                        <input type='submit' name='vgen_challenge_url_submit' value="Submit" class='vgen_challenge_url_submit_button' style="background-color: <?php echo $vgen_challenge_button_background_color; ?>;color: <?php echo $vgen_challenge_button_text_color; ?>;">
                                    </form>
                                </div>

                                <?php
                            }elseif( $format == 'cf7' ){
                                echo $form = do_shortcode('[contact-form-7 title="vgen_challenge"]');
                            }else{
                                if( $format == 'zip' ){
                                    $accept_file = '.zip';
                                }elseif( $format == 'pdf' ){
                                    $accept_file = '.pdf';
                                }else{
                                    $accept_file = '.zip,.pdf';
                                }
                                ?>
                                <div class="vgen_Challenge_submit_cover">
                                    <div class="vgen_challenge_filter-loder">
                                        <div class="vgen_challenge_filter-gif">
                                            <div class="gifInnter">
                                                <img src="<?php echo $this->plugin_url ?>/asset/css/images/loader1.gif" alt="loding..." />
                                            </div>
                                        </div>
                                    </div>
                                    <form id="vgen_challenge_file_submit" method="post" action="" enctype="multipart/form-data">
                                        <input type="hidden" name="accept_file" class="accept_file" value="<?php echo $accept_file; ?>" />
                                        <div class="vgen_challenge-drag-area">
                                            <div class="vgen_challenge-upload-icon">
                                            </div>
                                            <header class="vgen_challenge-pc" style="display: block;">Drag & Drop to Upload File</header>
                                            <header class="vgen_challenge-mobile" style="display: none;">Take a picture</header>
                                            <span>OR</span>
                                            <div class="vgen_challenge-upload-button">Browse File</div>
                                            <input type="file" id="myfile" accept="<?php echo $accept_file; ?>" class="myfile" name="up_myfile[]" multiple="multiple" hidden>
                                        </div>
                                        <input type="file" id="camera_myfile" accept="<?php echo $accept_file; ?>" class="myfile" name="up_myfile[]" capture="camera" multiple="multiple" hidden>
                                        <div class="vgen_challenge-file_list"></div>
                                        <div class="progress" style="display: none;">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <div id="uploadStatus"></div> 
                                        <input type="hidden" name="user_id" class="user_id" value="<?php echo $current_user_id; ?>" />
                                        <input type="hidden" name="challenge_id" class="challenge_id" value="<?php echo $current_page_id; ?>" />
                                        <input type="submit" class="vgen_challenge_submit_button" name="vgen_challenge_input_submit_btn" value="SUBMIT" >
                                    </form>
                                </div>
                                <?php
                            }
                        }elseif( $this->is_user_subscribed() AND $this->is_user_participate() ){

                            $challenge_after_participation_text = 'Thank you! for your participation.';
                            if ( get_option( 'challenge_after_participation_text' ) !== false ) {
                                $challenge_after_participation_text = get_option( 'challenge_after_participation_text');
                            }
                        ?>
                            <div class="vgen_challenge-submit-title"><?php _e( $challenge_after_participation_text, 'vgen_challenge' ); ?></div>
                        <?php
                        }
                    }else{
                        ?>
                        <div class="vgen_challenge-submit-title"><?php _e( 'Challenge over!', 'vgen_challenge' ); ?></div>
                        <?php
                    }
                }else{
                    ?>
                    <div class="vgen_challenge-submit-title"><?php _e( 'Coming Soon!', 'vgen_challenge' ); ?></div>
                    <?php
                }
            }
                
            ?>
            </div>
            <?php
            $output = ob_get_clean();
            return $output;
            wp_reset_query();
        }

        function vgen_challenge_related_shortcode(){
            ob_start();
            $current_page_id = get_the_ID();
            $current_page_category = get_the_terms( $current_page_id, 'category' );

            $current_page_category_ids = array();
            foreach( $current_page_category as $single_cat ){
                $category_id = $single_cat->term_id;
                array_push( $current_page_category_ids, $category_id );
            }
            
            ?>
            <div class="vgen_challenge-related-show-cover">
                <div class="vgen_challenge-related-show">
                    <div class="vgen_challenge-related-title">Related Challenge</div>
                    <div class="vgen_challenge-related-items">
                        <div class="owl-carousel owl-theme">
                            <?php

                            $args = array(
                                'post_type'      => 'post',
                                'post_status'    => 'publish',
                                'posts_per_page' => -1,
                                'orderby'        => 'ABC',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'category',
                                        'field' => 'id',
                                        'terms' => $current_page_category_ids,
                                    ),
                                )
                            );

                            $all_post = new WP_Query( $args );

                            // echo '<pre>';
                            // print_r($first_category);
                            // echo '</pre>';
                            foreach( $all_post->posts as $single ){
                                $post_id = $single->ID;
                                $author_id = $single->post_author;
                                $thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id($post_id), 'thumbnail' );
                                $get_author_gravatar = get_avatar_url($author_id, array('size' => 450));
                                $category = get_the_terms( $post_id, 'category' );

                                $category_names = array();
                                foreach( $category as $single_cat ){
                                    $category_name = $single_cat->name;
                                    array_push( $category_names, $category_name );
                                }
                                $category_name = implode(", ",$category_names);
                                

                                $post_deadline = get_post_meta( $post_id, 'post_deadline', true );
                                $post_award = get_post_meta( $post_id, 'post_award', true );
                                $post_filter_award_coming_soon = get_post_meta( $post_id, 'post_filter_award_coming_soon', true );

                                $post_deadline = get_post_meta( $post_id, 'post_deadline', true );
                                $today_date = date("Y-m-d");


                                $post_deadline_class = '';
                                $post_deadline_status = '';
                                $post_deadline_status_class = '';
                                $post_date_count = '';
                                $post_stat_number = '';
                                $post_stat_label = '';

                                if( $post_filter_award_coming_soon == 0 ){

                                    if( $post_deadline >= $today_date ){
                                        $post_deadline_class = 'filter-content-active';
                                        $post_deadline_status = 'Active';
                                        $post_deadline_status_class = 'fa fa-flag';

                                        $date1 = new DateTime($today_date);
                                        $date2 = new DateTime($post_deadline);
                                        $interval = $date1->diff($date2);
                                        $post_date_count = $interval->days;

                                        $post_stat_number = $post_date_count;
                                        $post_stat_label = 'days left';
                                    }else{
                                        $post_deadline_class = 'filter-content-closed';
                                        $post_deadline_status = 'Closed';
                                        $post_deadline_status_class = 'fa fa-flag';

                                        $date1 = new DateTime($post_deadline);
                                        $date2 = new DateTime($today_date);
                                        $interval = $date1->diff($date2);
                                        $post_date_count = $interval->days;

                                        $post_stat_number = 'Closed';
                                        $post_stat_label = ' ';
                                    }
                                }else{
                                    $post_deadline_class = 'filter-content-coming-soon';
                                    $post_deadline_status = 'Coming Soon';
                                    $post_deadline_status_class = 'fa fa-flag';

                                    $post_stat_number = '';
                                    $post_stat_label = '';
                                }


                            ?>

                                <div class="wp_post_filter-content">
                                    <a href="<?php echo get_permalink( $post_id ); ?>" class="wp_post_filter-content_link">
                                        <div class="wp_post_filter-content-cover <?php echo $post_deadline_class; ?>">
                                            <table cellspacing="0" cellpadding="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td class="wp_post_filter-content-image">
                                                            <img width="200" height="150" src="<?php echo $thumbnail_url; ?>">
                                                        </td>
                                                        <td class="wp_post_filter-content-body">
                                                            <div class="wp_post_filter-content-title wp_post_filter_hover <?php echo $post_deadline_class; ?>"><?php echo $single->post_title; ?></div>
                                                            <div class="wp_post_filter-content-category wp_post_filter_hover"><?php echo $category_name; ?></div>

                                                            <div class="wp_post_filter-content-status <?php echo $post_deadline_class; ?>">
                                                                <i class="<?php echo $post_deadline_status_class; ?>"></i>
                                                                <?php echo $post_deadline_status; ?>
                                                            </div>
                                                            <?php if( $post_filter_award_coming_soon == 0 ){?>
                                                            <table width="100%" cellspacing="0" cellpadding="0" class="wp_post_filter-number-and-label-table">
                                                                <tbody>
                                                                    <tr class="wp_post_filter-stats-row1">
                                                                        <td>
                                                                            <div class="challenge_stat_number wp_post_filter_hover"><i class="fa fa-clock-o"></i><?php echo $post_stat_number . ' '. $post_stat_label; ?></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="wp_post_filter-stats-row2">
                                                                        <td>
                                                                            <div class="challenge_stat_number challenge-for-trophy wp_post_filter_hover">
                                                                                <i class="fa fa-trophy"></i><?php echo $post_award; ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="wp_post_filter-content-footer">

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </a>
                                </div>


                            <?php
                            }
                            ?>


                        </div>
                    </div>
                </div>
            </div>
            <?php
            $output = ob_get_clean();
            return $output;
            wp_reset_query();
        }

        function custom_vgen_challenge_file_sendajax(){
            global $post;

            $all_value = $_POST;
            $insert_date = date("Y-m-d");
            $insert_time = date("h:i:sa");
            $user_id = $_POST['user_id'];
            $post_id = $_POST['challenge_id'];
            $accept_file = $_POST['accept_file'];
            //if( $this->is_user_subscribed() AND !$this->is_user_participate() ){

                //if(get_option( 'submit_the_challenge_through_the_link' ) == 0){


                    $table_name = $this->user_subscriptions_database;
                    $result_subscriptions = $this->wpdb->get_results( "SELECT * FROM $table_name WHERE post_id = " . $post_id . " AND user_id = " . $user_id . ";", OBJECT);
                    if (count($result_subscriptions) > 0) {

                        $table_name = $this->user_participation_database;
                        $result_participation = $this->wpdb->get_results( "SELECT * FROM $table_name WHERE post_id = " . $post_id . " AND user_id = " . $user_id . ";", OBJECT);
                        if (count($result_participation) > 0) {
                            $mas = '<p style="color:#28A74B;">You have already participated!</p>';
                        }else{

                            $the_user = get_user_by( 'id', $user_id );
                            $email = $the_user->user_email;
                            $nonce = wp_create_nonce( $email . $post_id . time() );

                            $uploaded_url = 'https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox';
                            $uploaded_type = $accept_file;
                            $mas = '<p style="color:#28A74B;">uploaded_type!</p>';
            
            
                            $user_info = get_userdata( $user_id );
                            $user_name = $user_info->display_name;
                            
                            $post_author_id = get_post_field( 'post_author', $post_id );
                            $post_author_info = get_userdata( $post_author_id );
                            $post_author_email = $post_author_info->user_email;
                            
                            $challenge = get_post($post_id);
                            $challenge_name = $challenge->post_title;

                            // author start
                            $admin_mail_for_getting_answer_with_link = 'iscrizioni@vgen.it';
                            if ( get_option( 'admin_mail_for_getting_answer_with_link' ) !== false ) {
                                $admin_mail_for_getting_answer_with_link = get_option( 'admin_mail_for_getting_answer_with_link');
                            }
                            
                            $challenge_submit_by_mail_title_for_admin = 'Upload da {challenge_submit_user_email} per {challenge_name}';
                            if ( get_option( 'challenge_submit_by_mail_title_for_admin' ) !== false ) {
                                $challenge_submit_by_mail_title_for_admin = get_option( 'challenge_submit_by_mail_title_for_admin');
                            }
                            
                            $challenge_submit_by_mail_body_for_admin = '
                            From: {challenge_submit_user_email}</br>
                            Submit date: {challenge_submit_date}</br>
                            Submit time: {challenge_submit_time}</br>
                            Challenge name: {challenge_name}</br>
                            Participant name: {user_name}</br>
                            Provide a mark based on the answer to this project: {challenge_mark_url}
                            ';
                            if ( get_option( 'challenge_submit_by_mail_body_for_admin' ) !== false ) {
                                $challenge_submit_by_mail_body_for_admin = get_option( 'challenge_submit_by_mail_body_for_admin');
                            }

                            $marks_url = admin_url( 'admin.php?page=vgen-challenge-analytics-and-marking-system&marksId=' . $nonce ) . '&user-marks';

                            $challenge_submit_body_for_admin_email = str_replace("{challenge_submit_user_email}", $email, $challenge_submit_by_mail_body_for_admin );
                            $challenge_submit_body_for_admin_insert_date = str_replace("{challenge_submit_date}", $insert_date, $challenge_submit_body_for_admin_email );
                            $challenge_submit_body_for_admin_insert_time = str_replace("{challenge_submit_time}", $insert_time, $challenge_submit_body_for_admin_insert_date );
                            $challenge_submit_body_for_admin_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_submit_body_for_admin_insert_time );
                            $challenge_submit_body_for_admin_challenge_submit_url = str_replace("{challenge_mark_url}", $marks_url, $challenge_submit_body_for_admin_challenge_name );
                            $challenge_submit_body_for_admin_user_name = str_replace("{user_name}", $user_name, $challenge_submit_body_for_admin_challenge_submit_url );

                            // title
                            $challenge_submit_title_for_admin_email = str_replace("{challenge_submit_user_email}", $email, $challenge_submit_by_mail_title_for_admin );
                            $challenge_submit_title_for_admin_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_submit_title_for_admin_email );

                            $subject_for_author = $challenge_submit_title_for_admin_challenge_name;
                            $body_for_author = stripslashes( $challenge_submit_body_for_admin_user_name );
                            $headers_for_author = 'From: ' . $email . "\r\n" .
                                'Reply-To: ' . $email . "\r\n";
                            $headers_for_author .= 'MIME-Version: 1.0' . "\r\n";
                            $headers_for_author .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                            // upload file
                            if ( ! function_exists( 'wp_handle_upload' ) ) {
                                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                            }
                            $attachments = array();
                            $upload_overrides = array( 'test_form' => false );

                            $files = $_FILES["myfile"];
                            foreach ($files['name'] as $key => $value) {
                                if ($files['name'][$key]) {
                                    $filename = $files['name'][$key];
                                    $size = $files['size'][$key];
                                    $file = array(
                                        'name' => $files['name'][$key],
                                        'type' => $files['type'][$key],
                                        'tmp_name' => $files['tmp_name'][$key],
                                        'error' => $files['error'][$key],
                                        'size' => $files['size'][$key]
                                    );
                                    $_FILES = array("upload_file" => $file);
                                    $movefile = wp_handle_upload( $file, $upload_overrides );
                                    $attachments[] = $movefile[ 'file' ];
                                }
                            }
                            
                            // $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1, $attachments);
                            $sent_for_super_admin = wp_mail($admin_mail_for_getting_answer_with_link, $subject_for_author, $body_for_author, $headers_for_author, $attachments);

                            $challenge_owner_get_answers = '1';
                            if ( get_option( 'challenge_owner_get_answers' ) !== false ) {
                                $challenge_owner_get_answers = get_option( 'challenge_owner_get_answers');
                            }
                            if($challenge_owner_get_answers == 1){
                                $sent_for_challenge_admin = wp_mail($post_author_email, $subject_for_author, $body_for_author, $headers_for_author, $attachments);
                            }
                            // author end

                            // user start
                            $challenge_submit_title_for_user = '{challenge_name} Challenge accepted';
                            if ( get_option( 'challenge_submit_title_for_user' ) !== false ) {
                                $challenge_submit_title_for_user = get_option( 'challenge_submit_title_for_user');
                            }
                            $challenge_submit_title_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_submit_title_for_user );
                            $challenge_submit_title_for_user_us = str_replace("{user_name}", $user_name, $challenge_submit_title_for_user_ch );
            
            
                            $challenge_submit_body_for_user = '';
                            if ( get_option( 'challenge_submit_body_for_user' ) !== false ) {
                                $challenge_submit_body_for_user = get_option( 'challenge_submit_body_for_user');
                            }
                            $challenge_submit_body_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_submit_body_for_user );
                            $challenge_submit_body_for_user_us = str_replace("{user_name}", $user_name, $challenge_submit_body_for_user_ch );
            
                            
                            $admin_mail_for_subscribe = 'iscrizioni@vgen.it';
                            if ( get_option( 'admin_mail_for_subscribe' ) !== false ) {
                                $admin_mail_for_subscribe = get_option( 'admin_mail_for_subscribe');
                            }
            
                            $admin_mail = $admin_mail_for_subscribe;
                            $subject_1 = $challenge_submit_title_for_user_us;
            
                            $body_1 = stripslashes( $challenge_submit_body_for_user_us );
            
                            $headers_1 = 'From: ' . $admin_mail . "\r\n" .
                                'Reply-To: ' . $admin_mail . "\r\n";
                            $headers_1 .= 'MIME-Version: 1.0' . "\r\n";
                            $headers_1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            
                            $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1);

                            $table_name = $this->user_participation_database;
                            $insert_participation = $this->wpdb->insert(
                                $table_name,
                                array(
                                    'user_id' => $user_id,
                                    'post_id' => $post_id,
                                    'uploaded_nonce' => $nonce,
                                    'uploaded_url' => $uploaded_url,
                                    'uploaded_type' => $uploaded_type,
                                    'insert_date' => $insert_date,
                                ),
                                array('%d', '%s', '%s', '%s', '%s', '%s')
                            );
                            // user end
                            $mas = '<p style="color:#28A74B;">File has uploaded successfully!</p>';

                            // if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                            //     {$link = "https";}
                            // else
                            //     {$link = "http";}
                            // $link .= "://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                            // $link = explode("?", $link);
                            // $link = $link[0];
                            // header ("location: $link");
                        }
                    }
                //}
            // }else{

            // }


            echo json_encode(
                array(
                    'message' => 'success',
                    'mas' => $mas,
                    'insert_date' => $insert_date,
                    'insert_time' => $insert_time,
                    'user_id' => $user_id,
                    'post_id' => $post_id,
                    'accept_file' => $accept_file
                )
            );
            die();
        }




        function all_usermeta(){
            global $wpdb;

            $all_user_pd = array( 'user_id', 'user_name', 'challenge_name', 'submit_id', 'insert_time', 'user_creativity_marks', 'user_innovation_marks', 'user_invention_marks');

            $sql_users_array = $this->wpdb->get_row( "SELECT * FROM {$wpdb->users} LIMIT 1" );
            $sql_users_array_de = json_decode(json_encode($sql_users_array), true);
            $sql_users = array();
            foreach( $sql_users_array_de as $key=>$value ){
                array_push($sql_users, $key);
            }
            $result_array_1 = array_merge( $all_user_pd, $sql_users );

            $sql_usermeta = $this->wpdb->get_col( "SELECT meta_key FROM {$wpdb->usermeta} GROUP BY meta_key", 0 );
            $result = array_merge( $result_array_1, $sql_usermeta );
            
            return $result;
        }

        function is_user_participate() {
            $current_user_id = get_current_user_id();
            $current_page_id = get_the_ID();
            $table_name = $this->user_participation_database;
            $result = $this->wpdb->get_results( "SELECT * FROM $table_name WHERE post_id = " . $current_page_id . " AND user_id = " . $current_user_id . ";", OBJECT);
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }
        }

        function is_user_subscribed() {
            $current_user_id = get_current_user_id();
            $current_page_id = get_the_ID();
            $table_name = $this->user_subscriptions_database;
            $result = $this->wpdb->get_results( "SELECT * FROM $table_name WHERE post_id = " . $current_page_id . " AND user_id = " . $current_user_id . ";", OBJECT);
            if (count($result) > 0) {
                return true;
            } else {
                return false;
            }
        }

        function unsubscribe_user_to_post_for_vc($post_id) {
            if ($post_id === '') { return; }

            global $wpdb;
            $current_user_id = get_current_user_id();
            $table_name = $this->user_subscriptions_database;
            $query_subscribe_id_array = $this->wpdb->get_row( "SELECT `id` FROM $table_name  WHERE `post_id` = '$post_id' AND `user_id` = '$current_user_id' " );
            $query_subscribe_id = $query_subscribe_id_array->id;
            $delete = $this->wpdb->delete(
                $table_name,
                array('id' => $query_subscribe_id),
                array('%d')        
            );
            $challenge = get_post($post_id);
            $challenge_name = $challenge->post_title;
            global $current_user;
            get_currentuserinfo();
            $email = (string) $current_user->user_email;
            $username = (string) $current_user->user_login;
            $firstname = (string) $current_user->user_firstname;
            $lastname = (string) $current_user->user_lastname;
            
            $post_author_id = get_post_field( 'post_author', $post_id );
            $post_author_info = get_userdata( $post_author_id );
            $post_author_email = $post_author_info->user_email;
            

            $challenge_unsubscribe_title_for_user = 'La registrazione è stata cancellata {challenge_name}';
            if ( get_option( 'challenge_unsubscribe_title_for_user' ) !== false ) {
                $challenge_unsubscribe_title_for_user = get_option( 'challenge_unsubscribe_title_for_user');
            }
            $challenge_unsubscribe_body_for_user = '';
            if ( get_option( 'challenge_unsubscribe_body_for_user' ) !== false ) {
                $challenge_unsubscribe_body_for_user = get_option( 'challenge_unsubscribe_body_for_user');
            }
            $challenge_unsubscribe_title_for_admin = 'La registrazione è stata cancellata {challenge_name}';
            if ( get_option( 'challenge_unsubscribe_title_for_admin' ) !== false ) {
                $challenge_unsubscribe_title_for_admin = get_option( 'challenge_unsubscribe_title_for_admin');
            }
            $challenge_unsubscribe_body_for_admin = '';
            if ( get_option( 'challenge_unsubscribe_body_for_admin' ) !== false ) {
                $challenge_unsubscribe_body_for_admin = get_option( 'challenge_unsubscribe_body_for_admin');
            }

            $challenge_unsubscribe_title_for_user_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_unsubscribe_title_for_user );
            $challenge_unsubscribe_title_for_user_user_name = str_replace("{user_name}", $username, $challenge_unsubscribe_title_for_user_challenge_name );

            $challenge_unsubscribe_body_for_user_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_unsubscribe_body_for_user );
            $challenge_unsubscribe_body_for_user_user_name = str_replace("{user_name}", $username, $challenge_unsubscribe_body_for_user_challenge_name );

            $challenge_unsubscribe_title_for_admin_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_unsubscribe_title_for_admin );
            $challenge_unsubscribe_title_for_admin_challenge_name_user_name = str_replace("{user_name}", $username, $challenge_unsubscribe_title_for_admin_challenge_name );

            $challenge_unsubscribe_body_for_admin_challenge_name = str_replace("{challenge_name}", $challenge_name, $challenge_unsubscribe_body_for_admin );
            $challenge_unsubscribe_body_for_admin_user_name = str_replace("{user_name}", $username, $challenge_unsubscribe_body_for_admin_challenge_name );

            $admin_mail_for_subscribe = 'iscrizioni@vgen.it';
            if ( get_option( 'admin_mail_for_subscribe' ) !== false ) {
                $admin_mail_for_subscribe = get_option( 'admin_mail_for_subscribe');
            }

            $admin_mail = $admin_mail_for_subscribe;
            $subject_1 = $challenge_unsubscribe_title_for_user_user_name;
            $subject_2 = $challenge_unsubscribe_title_for_admin_challenge_name_user_name;
            $body_1 = stripslashes( $challenge_unsubscribe_body_for_user_user_name );
            $body_2 = stripslashes( $challenge_unsubscribe_body_for_admin_user_name );

            $body_3 = 'L\'utente ' . $username . ' ' . $email . ' ' . $firstname . ' ' . $lastname . ' La registrazione è stata cancellata "' .  $challenge_name .'"';

            $headers_1 = 'From: ' . $admin_mail . "\r\n" .
                'Reply-To: ' . $admin_mail . "\r\n";
            $headers_1 .= 'MIME-Version: 1.0' . "\r\n";
            $headers_1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $headers_2 = 'From: ' . $email . "\r\n" .
                'Reply-To: ' . $email . "\r\n";
            $headers_2 .= 'MIME-Version: 1.0' . "\r\n";
            $headers_2 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
            $headers_3 = 'From: '. $email . "\r\n" .
                    'Reply-To: ' . $email . "\r\n";
            $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1);
            $sent_3 = wp_mail($admin_mail, $subject_2, stripslashes($body_3), $headers_3);

            if(get_option( 'challenge_owner_get_answers' ) == 1){
                $sent_2 = wp_mail($post_author_email, $subject_2, $body_2, $headers_2);
            }
        }

        function subscribe_user_to_post_for_vc($post_id) {
            if ($post_id === '') { return; }
            
                $insert_date = date("Y-m-d");

                global $wpdb;
                $current_user_id = get_current_user_id();
                $table_name = $this->user_subscriptions_database;
                $wpdb->insert( $table_name, array(
                    'user_id' => $current_user_id,
                    'post_id' => $post_id,
                    'insert_date' => $insert_date
                ));
                $challenge = get_post($post_id);
                $challenge_name = $challenge->post_title;
                global $current_user;
                get_currentuserinfo();
                $email = (string) $current_user->user_email;
                $username = (string) $current_user->user_login;
                $firstname = (string) $current_user->user_firstname;
                $lastname = (string) $current_user->user_lastname;
                
                $post_author_id = get_post_field( 'post_author', $post_id );
                $post_author_info = get_userdata( $post_author_id );
                $post_author_email = $post_author_info->user_email;
                
                $challenge_subscribe_title_for_user = 'Conferma di iscrizione a {challenge_name}';
                if ( get_option( 'challenge_subscribe_title_for_user' ) !== false ) {
                    $challenge_subscribe_title_for_user = get_option( 'challenge_subscribe_title_for_user');
                }
                $challenge_subscribe_title_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_subscribe_title_for_user );
                $challenge_subscribe_title_for_user_us = str_replace("{user_name}", $username, $challenge_subscribe_title_for_user_ch );

                $challenge_subscribe_body_for_user = '';
                if ( get_option( 'challenge_subscribe_body_for_user' ) !== false ) {
                    $challenge_subscribe_body_for_user = get_option( 'challenge_subscribe_body_for_user');
                    // $challenge_subscribe_body_for_user_r = str_replace( "\&quot;","", $challenge_subscribe_body_for_user );
                }
                $challenge_subscribe_body_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_subscribe_body_for_user );
                $challenge_subscribe_body_for_user_us = str_replace("{user_name}", $username, $challenge_subscribe_body_for_user_ch );


                $challenge_subscribe_title_for_admin = 'Registrazione a {challenge_name}';
                if ( get_option( 'challenge_subscribe_title_for_admin' ) !== false ) {
                    $challenge_subscribe_title_for_admin = get_option( 'challenge_subscribe_title_for_admin');
                }
                $challenge_subscribe_title_for_admin_ch = str_replace("{challenge_name}", $challenge_name, $challenge_subscribe_title_for_admin );
                $challenge_subscribe_title_for_admin_us = str_replace("{user_name}", $username, $challenge_subscribe_title_for_admin_ch );

                $challenge_subscribe_body_for_admin = '';
                if ( get_option( 'challenge_subscribe_body_for_admin' ) !== false ) {
                    $challenge_subscribe_body_for_admin = get_option( 'challenge_subscribe_body_for_admin');
                }
                $challenge_subscribe_body_for_admin_ch = str_replace("{challenge_name}", $challenge_name, $challenge_subscribe_body_for_admin );
                $challenge_subscribe_body_for_admin_us = str_replace("{user_name}", $username, $challenge_subscribe_body_for_admin_ch );


                $admin_mail_for_subscribe = 'iscrizioni@vgen.it';
                if ( get_option( 'admin_mail_for_subscribe' ) !== false ) {
                    $admin_mail_for_subscribe = get_option( 'admin_mail_for_subscribe');
                }

                $admin_mail = $admin_mail_for_subscribe;
                $subject_1 = $challenge_subscribe_title_for_user_us;
                $subject_2 = $challenge_subscribe_title_for_admin_us;
                $body_1 = stripslashes( $challenge_subscribe_body_for_user_us );
                $body_2 = stripslashes( $challenge_subscribe_body_for_admin_us );

                $body_3 = 'L\'utente ' . $username . ' ' . $email . ' ' . $firstname . ' ' . $lastname . ' si è registrato a "' .  $challenge_name .'"';

                $headers_1 = 'From: ' . $admin_mail . "\r\n" .
                    'Reply-To: ' . $admin_mail . "\r\n";
                $headers_1 .= 'MIME-Version: 1.0' . "\r\n";
                $headers_1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $headers_2 = 'From: ' . $email . "\r\n" .
                    'Reply-To: ' . $email . "\r\n";
                $headers_2 .= 'MIME-Version: 1.0' . "\r\n";
                $headers_2 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
                $headers_3 = 'From: '. $email . "\r\n" .
                        'Reply-To: ' . $email . "\r\n";
                $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1);
                $sent_3 = wp_mail($admin_mail, $subject_2, stripslashes($body_3), $headers_3);

                if(get_option( 'challenge_owner_get_answers' ) == 1){
                    $sent_2 = wp_mail($post_author_email, $subject_2, $body_2, $headers_2);
                }
        }

        function wpcf7_add_nonce_to_mail_body($contact_form){
            global $post;
            $insert_date = date("Y-m-d");
            $title = $contact_form->title;
            $submission = WPCF7_Submission::get_instance();
            if ( $submission ) {
                $posted_data = $submission->get_posted_data();
            }
            
            if ( 'vgen_challenge' == $title ) {

                $email = strtolower($posted_data['user_email']);
                $post_id = strtolower($posted_data['cf7_user_id']);
                $user = get_user_by( 'email', $email );
                $user_id = $user->ID;
                $nonce = wp_create_nonce( $email . $post_id . time() );

                $marks_url = 'Provide a mark based on the answer to this project : ' . admin_url( 'admin.php?page=vgen-challenge-analytics-and-marking-system&marksId=' . $nonce ) . '&user-marks';

                // $site_url = get_site_url();
                // $marks_url = 'Provide a mark based on the answer to this project : admin.php?page=vgen_challenge&marksId=' . $nonce;
                $mail = $contact_form->prop( 'mail' );
                $mail['body'] .= $marks_url;
                //$mail['recipient'] .= 'ahjony.bd.test.01@gmail.com';
                $contact_form->set_properties( array( 'mail' => $mail ) );

            }

        }

        function your_wpcf7_mail_sent_function( $contact_form ) {
            global $post;
            $insert_date = date("Y-m-d");
            $title = $contact_form->title;
            $submission = WPCF7_Submission::get_instance();
            if ( $submission ) {
                $posted_data = $submission->get_posted_data();
            }

            if ( 'vgen_challenge' == $title ) {
                $email = strtolower($posted_data['user_email']);
                $post_id = strtolower($posted_data['cf7_user_id']);
                $user = get_user_by( 'email', $email );
                $user_id = $user->ID;

                $mail = $contact_form->prop( 'mail' );
                $mail_body_for_nonce = $mail['body'];
                $mail_body_for_nonce_array = explode("admin.php?page=vgen-challenge-analytics-and-marking-system&marksId=",$mail_body_for_nonce);
                $mail_body_for_nonce_value_array = explode("&user-marks",$mail_body_for_nonce_array[1]);
                $mail_body_for_nonce_array[1];
                $nonce = $mail_body_for_nonce_value_array[0];
                $uploaded_type = 'contact_form_7';
                $uploaded_url = 'https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox';
                //add_option( 'support_desk_user_reply_page', $nonce, 'no' );

                $table_name = $this->user_participation_database;
                $insert = $this->wpdb->insert(
                    $table_name,
                    array(
                        'user_id' => $user_id,
                        'post_id' => $post_id,
                        'uploaded_nonce' => $nonce,
                        'uploaded_url' => $uploaded_url,
                        'uploaded_type' => $uploaded_type,
                        'insert_date' => $insert_date,
                    ),
                    array('%d', '%s', '%s', '%s', '%s', '%s')
                );
                // $table_name = $this->user_participation_database;
                // $insert = $this->wpdb->insert(
                //     $table_name,
                //     array(
                //         'user_id' => $user_id,
                //         'post_id' => $post_id,
                //         'uploaded_nonce' => $email,
                //         'insert_date' => $insert_date,
                //     ),
                //     array('%d', '%s', '%s', '%s')
                // );

                $user_info = get_userdata( $user_id );
                $user_name = $user_info->display_name;
                
                $post_author_id = get_post_field( 'post_author', $post_id );
                $post_author_info = get_userdata( $post_author_id );
                $post_author_email = $post_author_info->user_email;
                
                $challenge = get_post($post_id);
                $challenge_name = $challenge->post_title;

                
                $challenge_submit_title_for_user = '{challenge_name} Challenge accepted';
                if ( get_option( 'challenge_submit_title_for_user' ) !== false ) {
                    $challenge_submit_title_for_user = get_option( 'challenge_submit_title_for_user');
                }
                $challenge_submit_title_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_submit_title_for_user );
                $challenge_submit_title_for_user_us = str_replace("{user_name}", $user_name, $challenge_submit_title_for_user_ch );


                $challenge_submit_body_for_user = '';
                if ( get_option( 'challenge_submit_body_for_user' ) !== false ) {
                    $challenge_submit_body_for_user = get_option( 'challenge_submit_body_for_user');
                }
                $challenge_submit_body_for_user_ch = str_replace("{challenge_name}", $challenge_name, $challenge_submit_body_for_user );
                $challenge_submit_body_for_user_us = str_replace("{user_name}", $user_name, $challenge_submit_body_for_user_ch );

                
                $admin_mail_for_subscribe = 'iscrizioni@vgen.it';
                if ( get_option( 'admin_mail_for_subscribe' ) !== false ) {
                    $admin_mail_for_subscribe = get_option( 'admin_mail_for_subscribe');
                }

                $admin_mail = $admin_mail_for_subscribe;
                $subject_1 = $challenge_submit_title_for_user_us;

                $body_1 = stripslashes( $challenge_submit_body_for_user_us );

                $headers_1 = 'From: ' . $admin_mail . "\r\n" .
                    'Reply-To: ' . $admin_mail . "\r\n";
                $headers_1 .= 'MIME-Version: 1.0' . "\r\n";
                $headers_1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

                $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1);
            }

        }

        function marking_system_function(){
            global $wpdb;
            ?>

            <div class="vgen-challenge-submenu">
                <div class="vgen-challenge-title-csv">
                    <div class="vgen-challenge-submenu-analytics-title">
                        <?php 
                        if(isset($_GET['marksId']) && !empty($_GET['marksId'])){
                        ?>
                        <h1><?php _e('Vgen Marking System', 'vgen_challenge'); ?></h1>
                        <?php 
                        }else{
                        ?>
                        <h1><?php _e('Vgen Challenge Analytics and Marking System', 'vgen_challenge'); ?></h1>
                        <?php 
                        }
                        ?>
                    </div>
                </div>
                <!-- Settings -->
                <div class="vgen-challenge">
                    <?php 
                    if(isset($_GET['marksId']) && !empty($_GET['marksId'])){

                        $marks_first_like_creativity = 'Creativity';
                        if ( get_option( 'marks_first_like_creativity' ) !== false ) {
                            $marks_first_like_creativity = get_option( 'marks_first_like_creativity');
                        }
                        $marks_second_like_innovation = 'Innovation';
                        if ( get_option( 'marks_second_like_innovation' ) !== false ) {
                            $marks_second_like_innovation = get_option( 'marks_second_like_innovation');
                        }
                        $marks_third_like_invention = 'Invention';
                        if ( get_option( 'marks_third_like_invention' ) !== false ) {
                            $marks_third_like_invention = get_option( 'marks_third_like_invention');
                        }
                                
                        $user = wp_get_current_user();
                        $roles = ( array ) $user->roles;
                        $role = $roles[0];
                        if ( in_array( 'administrator', $roles ) ){
                            $role = 'administrator';
                        }

                        $nonce = $_GET['marksId'];
                        $table_database = $this->user_participation_database;
                        $query_database = $this->wpdb->get_row( "SELECT * FROM $table_database WHERE `uploaded_nonce` = '$nonce'" );
                        

                        $challenge_title = get_the_title( $query_database->post_id );
                        $post_id = $query_database->post_id;
                        $post_author_id = get_post_field( 'post_author', $post_id );
                        $post_author_info = get_userdata( $post_author_id );
                        $post_author_name = $post_author_info->display_name;

                        $user_id = $query_database->user_id;
                        $user_info = get_userdata( $user_id );
                        $user_name = $user_info->display_name;
                        $user_email = $user_info->user_email;

                        $insert_time = $query_database->insert_time;
                        $file_id = $query_database->id;
                        $user_creativity_marks = $query_database->user_creativity_marks;
                        $user_innovation_marks = $query_database->user_innovation_marks;
                        $user_invention_marks = $query_database->user_invention_marks;

                        $uploaded_type = $query_database->uploaded_type;
                        $uploaded_type_values = $uploaded_type;
                        // if( $uploaded_type == 'contact_form_7' ){
                        //     $uploaded_type_values = 'CF7 ZIP';
                        // }elseif( $uploaded_type == 'submit_url' ){
                        //     $uploaded_type_values = 'Drive URL';
                        // }
                        $uploaded_url = $query_database->uploaded_url;

                    ?>
                    <div class="tabcontent vgen-challenge-marking-system" style="display:block;">
                        <div class="settingsInner">
                            <div class="vc_marking_update-loder-loder">
                                <div class="vc_marking_update-loder-gif">
                                    <div class="gifInnter">
                                        <img src="<?php echo $this->plugin_url ?>/asset/css/images/loader.gif" alt="loding..." />
                                    </div>
                                </div>
                            </div>
                            <div class="mail-to-update-content">
                                <div class="challenge-submit-id">Challenge Submit Id: <span><?php echo $query_database->uploaded_nonce; ?></span></div>
                                <div class="challenge-name">Challenge Name: <span><?php echo $challenge_title; ?></span></div>
                                <?php
                                if ( $role == 'administrator' OR $role == 'um_company' ){
                                ?>
                                <div class="challenge-author-name">Challenge Author Name: <span><?php echo $post_author_name; ?></span></div>
                                <?php
                                }
                                ?>
                                <div class="challenge-except-user-name">Challenge Except User Name: <span><?php echo $user_name; ?></span></div>
                                <div class="challenge-except-user-name">Challenge Except User Mail: <span><?php echo $user_email; ?></span></div>
                                <div class="challenge-except-time">Challenge Except Time: <span><?php echo $insert_time; ?></span></div>
                                <div class="challenge-author-name">User Answer Format: <span><?php echo $uploaded_type_values; ?></span></div>
                                <div class="challenge-except-user-name">User Answer Link: <span>
                                    <?php 
                                    if( $uploaded_type_values == 'submit_url' ){
                                    ?>
                                    <a class="vgen_uploaded_url" href="<?php echo $uploaded_url; ?>" target="_blank">See the Answer</a>
                                    <?php 
                                    }else{
                                    ?>Your answer has been sent via Mail.<?php
                                    }
                                    ?>
                                    </span></div>
                                <div class="challenge-author-name">Mark:
                                    <div class="vc_show_marks_label">
                                        <p><?php echo $marks_first_like_creativity . ': '; ?><span class="vgen-user-marks-creativity<?php echo $file_id; ?>"><?php echo $user_creativity_marks; ?></span></p>
                                    </div>
                                    <div class="vc_show_marks_label">
                                        <p><?php echo $marks_second_like_innovation . ': '; ?><span class="vgen-user-marks-innovation<?php echo $file_id; ?>"><?php echo $user_innovation_marks; ?></span></p>
                                    </div>
                                    <div class="vc_show_marks_label">
                                        <p><?php echo $marks_third_like_invention . ': '; ?><span class="vgen-user-marks-invention<?php echo $file_id; ?>"><?php echo $user_invention_marks; ?></span></p>
                                    </div>
                                </div>
                                <div class="vc_add_marks_cover">
                                    <input type="hidden" name="file_id" class="file_id" value="<?php echo $file_id; ?>" />
                                    <label class="vc_add_marks_label"><?php echo $marks_first_like_creativity . ': '; ?><input type="number" min="1" max="10" name="vc_add_marks_creativity" class="vc_add_marks_creativity" value="<?php echo $user_creativity_marks; ?>"/></label>
                                    <label class="vc_add_marks_label"><?php echo $marks_second_like_innovation . ': '; ?><input type="number" min="1" max="10" name="vc_add_marks_innovation" class="vc_add_marks_innovation" value="<?php echo $user_innovation_marks; ?>"/></label>
                                    <label class="vc_add_marks_label"><?php echo $marks_third_like_invention . ': '; ?><input type="number" min="1" max="10" name="vc_add_marks_invention" class="vc_add_marks_invention" value="<?php echo $user_invention_marks; ?>"/></label>
                                    <div class="update-marks" value="update" name="status">Update</div>
                                </div>
                                <div class="vagn-go-back">
                                    <a href="<?php echo admin_url( 'admin.php?page=vgen-challenge-analytics-and-marking-system' ); ?>">Go Back Analytics and Marking Page</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }else{
                    ?>
                    <div class="tabcontent vgen-challenge-marking-system" style="display:block;">
                        <div class="settingsInner">
                            <div class="vc_marking_update-loder-loder">
                                <div class="vc_marking_update-loder-gif">
                                    <div class="gifInnter">
                                        <img src="<?php echo $this->plugin_url ?>/asset/css/images/loader.gif" alt="loding..." />
                                    </div>
                                </div>
                            </div>

                            <div class="challenge-subscribe-and-participation-rate-cover-header">
                                <?php $jony = 0; if( $jony == 1 ){ ?>
                                <!-- challenge subscriber and participation rate start -->
                                <div class="challenge-subscribe-and-participation-rate-cover-all">
                                    <h2><?php _e('Top 5 Challenge Subscriptions and Participation Rate', 'vgen_challenge'); ?></h2>
                                    <div id="challenge_subscribe_and_participation_rate"></div>
                                </div>
                                <!-- challenge subscriber and participation rate end -->
                                <?php } ?>
                                <!-- challenge subscriber and participation rate start -->
                                <div class="challenge-subscribe-and-participation-rate-cover-all">
                                    <h2><?php _e('Active Challenge Subscriptions and Participation Rate', 'vgen_challenge'); ?></h2>
                                    <div id="active_challenge_subscribe_and_participation_rate"></div>
                                </div>
                                <!-- challenge subscriber and participation rate end -->
                            </div>

                            <div class="accordion" id="accordionExample">
                                <h2 class="accordion-header" id="heading_main">
                                    <button class="accordion-button vgen_single_challenge_data" " type="button" data-bs-toggle="collapse" data-bs-target="#collapse_main" aria-controls="collapse_main">
                                        Test
                                    </button>
                                </h2>
                                <div id="collapse_main" class="accordion-collapse collapse" aria-labelledby="heading_main" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                
                            <?php

                                $wp_post_filter_choose_first_category = array();
                                if ( get_option( 'wp_post_filter_choose_first_category' ) !== false ) {
                                    $wp_post_filter_choose_first_category = get_option( 'wp_post_filter_choose_first_category');
                                }
                                $wp_post_filter_choose_second_category = array();
                                if ( get_option( 'wp_post_filter_choose_second_category' ) !== false ) {
                                    $wp_post_filter_choose_second_category = get_option( 'wp_post_filter_choose_second_category');
                                }
                                $wp_post_filter_choose_all_category = array_merge( $wp_post_filter_choose_first_category, $wp_post_filter_choose_second_category );

                                $who_user_roles_can_access_vgen_challenge = array();
                                if ( get_option( 'who_user_roles_can_access_vgen_challenge' ) !== false ) {
                                    $who_user_roles_can_access_vgen_challenge = get_option( 'who_user_roles_can_access_vgen_challenge');
                                }
                                
                                $user = wp_get_current_user();
                                $roles = ( array ) $user->roles;
                                $role = $roles[0];
                                if ( in_array( 'administrator', $roles ) ){
                                    $role = 'administrator';
                                }
                                if ( $role == 'administrator' ){
                                    $current_user_id = get_current_user_id();
                                    $args = array(
                                        'post_type'      => 'post',
                                        'post_status'    => 'publish',
                                        'posts_per_page' => -1,
                                        'orderby'        => 'ABC',
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'category',
                                                'field' => 'id',
                                                'terms' => $wp_post_filter_choose_all_category,
                                            ),
                                        )
                                    );
                                }elseif( $role == 'um_company' ){
                                    $current_user_id = get_current_user_id();
                                    $args = array(
                                        'post_type'      => 'post',
                                        'post_status'    => 'publish',
                                        'posts_per_page' => -1,
                                        'orderby'        => 'ABC',
                                        'meta_query' => array(
                                            array(
                                                'key' => 'post_filter_post_author_company',
                                                'value' => $current_user_id,
                                                'compare' => 'LIKE',
                                            ),
                                        )
                                    );
                                }elseif( in_array( $role, $who_user_roles_can_access_vgen_challenge ) ){
                                    $current_user_id = get_current_user_id();
                                    $args = array(
                                        'post_type'      => 'post',
                                        'author'         => $current_user_id,
                                        'post_status'    => 'publish',
                                        'posts_per_page' => -1,
                                        'orderby'        => 'ABC',
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'category',
                                                'field' => 'id',
                                                'terms' => $wp_post_filter_choose_all_category,
                                            ),
                                        )
                                    );
                                }
                                $all_post = new WP_Query( $args );

                                $all_vgen_challenge_ids = array();
                                foreach( $all_post->posts as $single ){
                                    $post_id = $single->ID;
                                    if( !empty( get_post_meta( $post_id, 'post_filter_post_child', true ) ) ){
                                        $post_filter_post_child = get_post_meta( $post_id, 'post_filter_post_child', true );
                                        array_push( $all_vgen_challenge_ids, $post_filter_post_child );
                                    }
                                    array_push( $all_vgen_challenge_ids, $post_id );
                                }
                                $i = 0;
                                
                                foreach( $all_vgen_challenge_ids as $single ){


                                    $post_id = $single;

                                    $post_author_id = get_post_field( 'post_author', $post_id );
                                    $post_author_info = get_userdata( $post_author_id );
                                    $post_author_name = $post_author_info->display_name;

                                    $post_filter_award_coming_soon = get_post_meta( $post_id, 'post_filter_award_coming_soon', true );
                                    //echo 'post_filter_award_coming_soon : ' . $post_filter_award_coming_soon;
                                    $post_deadline = get_post_meta( $post_id, 'post_deadline', true );
                                    $today_date = date("Y-m-d");
                                    $challenge_active_or_deactive = '';
                                    $post_date_count = '';
                                    $post_stat_number = '';
                                    $post_stat_label = '';

                                    if( $post_filter_award_coming_soon == 0 ){
                                        if( $post_deadline >= $today_date ){
                                            $challenge_active_or_deactive = 'active';

                                            $date1 = new DateTime($today_date);
                                            $date2 = new DateTime($post_deadline);
                                            $interval = $date1->diff($date2);
                                            $post_date_count = $interval->days;
                    
                                            $post_stat_number = $post_date_count;
                                            $post_stat_label = 'days left';
                                        }else{
                                            
                                            $post_stat_number = 'Closed';
                                            $post_stat_label = '';
                                        }
                                    }else{
                                        $challenge_active_or_deactive = 'coming-soon';
                                        $post_stat_number = 'Coming Soon';
                                        $post_stat_label = '';
                                    }

                                    // $aria_expanded = ( $i == 0 ) ? 'true' : 'false';
                                    // $class_collapsed = ( $i == 0 ) ? '' : 'collapsed';
                                    // $class_collapse_show = ( $i == 0 ) ? 'show' : '';

                                    
                                    $aria_expanded = 'false';
                                    $class_collapsed = 'collapsed';
                                    $class_collapse_show = '';
                                ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $i; ?>">
                                            <button class="accordion-button <?php echo $class_collapsed; ?> vgen_single_challenge_data" data-post_id="<?php echo $post_id; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i; ?>" aria-expanded="<?php echo $aria_expanded; ?>" aria-controls="collapse<?php echo $i; ?>">
                                                <div class="challenge-active-or-deactive <?php echo $challenge_active_or_deactive; ?>"></div>
                                                <div class="challenge-name">Challenge Name: <span><?php echo get_the_title($single); ?></span></div>
                                                <?php
                                                if ( $role == 'administrator' OR $role == 'um_company' ){
                                                ?>
                                                <div class="challenge-author-name">Challenge Author Name: <span><?php echo $post_author_name; ?></span></div>
                                                <?php
                                                }
                                                ?>
                                                <div class="challenge_stat_number wp_post_filter_hover"><img class="challenge-clock-icon" src="<?php echo $this->plugin_url ?>/asset/css/images/clock.svg" alt="clock" id="clock"><?php echo $post_stat_number . ' '. $post_stat_label; ?></div>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $i; ?>" class="accordion-collapse collapse <?php echo $class_collapse_show; ?>" aria-labelledby="heading<?php echo $i; ?>" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="vgen_challenge-data-andsubscriptions-cover">
                                                    <div class="vgen_challenge-data-andsubscriptions">
                                                        <h1 id="challenge_subscriptions_button" class="challenge_subscriptions_button">Subscriptions Mail</h1>
                                                        <div class="modal-overlay">
                                                            <div class="modal modal-download-challenge">
                                                                <div class="vgen_challenge_filter-loder">
                                                                    <div class="vgen_challenge_filter-gif">
                                                                        <div class="gifInnter">
                                                                            <img src="<?php echo $this->plugin_url ?>/asset/css/images/loader1.gif" alt="loding..." />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <a class="close-modal close-modal-download-challenge">
                                                                    <svg viewBox="0 0 20 20">
                                                                        <path fill="#000000" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                                                                    </svg>
                                                                </a>
                                                                <div class="modal-content-title">
                                                                    <p>All Subscriptions Mail(Send mail or Download)</p>
                                                                </div>
                                                                <div class="modal-content modal-content-download-challenge">
                                                                    <div class="modal-download-challenge-cover for-user-mail-send-cover">
                                                                        <form class="user_subscriptions_mail_submit" method="post" action="" enctype="multipart/form-data">
                                                                            <input type="hidden" name="post_id" class="post_id" value="<?php echo $post_id; ?>" />
                                                                            <div class="user_subscriptions_search"></div>
                                                                            <div class="user_subscriptions_mail_cover">
                                                                                <ul class="vc-cboxtags">
                                                                                    <li>
                                                                                        <input type="checkbox" name="user_subscriptions_mail_vgen_challenge[]" id="vgen_challenge_checkbox_subscriptions_all_<?php echo $post_id; ?>" value="checkbox_all"/>
                                                                                        <label for="vgen_challenge_checkbox_subscriptions_all_<?php echo $post_id; ?>">Select All Mails</label>
                                                                                    </li>
                                                                                    <?php
                                                                                    $table_subscriptions = $this->user_subscriptions_database;
                                                                                    $query_subscribe_id = $this->wpdb->get_results( "SELECT `user_id` FROM $table_subscriptions  WHERE `post_id` = '$post_id'" );
                                                                                    foreach( $query_subscribe_id as $single_subscribe_id ){
                                                                                        $single_user_id = $single_subscribe_id->user_id;
                                                                                        $user_info = get_userdata( $single_user_id );
                                                                                        $user_email = $user_info->user_email;
                                                                                        if( !empty($user_email) ){
                                                                                    ?>
                                                                                        <li>
                                                                                            <input type="checkbox" class="vgen_challenge_checkbox_subscription_checkSingle" name="user_subscriptions_mail_vgen_challenge[]" id="checkbox<?php echo $single_user_id ?>" value="<?php echo $user_email ?>"/>
                                                                                            <label for="checkbox<?php echo $single_user_id ?>"><?php echo $user_email ?></label>
                                                                                        </li>
                                                                                    <?php } } ?>
                                                                                </ul>
                                                                            </div>
                                                                            <label class="vgen_challenge_subscriptions_label" for="vgen_challenge_checkbox_subscription_title">Mail Title</label>
                                                                            <input type="text" class="jspc_input-text" id="vgen_challenge_checkbox_subscription_title" name="vgen_challenge_checkbox_subscription_title" placeholder="Mail Title" value="" required>
                                                                            
                                                                            <label class="vgen_challenge_subscriptions_label" for="vgen_challenge_checkbox_subscription_body_"<?php echo $post_id; ?>>Mail Body</label>
                                                                            <?php
                                                                            $vgen_challenge_checkbox_subscription_body = '';
                                                                            wp_editor(
                                                                                stripslashes( $vgen_challenge_checkbox_subscription_body ),
                                                                                'vgen_challenge_checkbox_subscription_body_' . $post_id,
                                                                                array(
                                                                                    'media_buttons' => true,
                                                                                    'textarea_rows' => 12,
                                                                                    'tabindex' => 4,
                                                                                    'tinymce' => array(
                                                                                        'theme_advanced_buttons1' => 'bold, italic, ul, pH, temp, sub, sup',
                                                                                    ),
                                                                                )
                                                                            ) 
                                                                            ?>
                                                                            <div class="vgen_challenge_note">User email = {user_email}, User name = {user_name}, Challenge Name = {challenge_name}.</div>
                                                                            <input type="submit" class="challenge-send-mail-button" name="challenge_subscription_mail_send" value="send" >
                                                                            <label class="vgen_challenge_subscriptions_label" for="vgen_challenge_mail_sending_status_error">Mail Sending Status</label>
                                                                            <div class="vgen_challenge_mail_sending_status_cover" id="vgen_challenge_mail_sending_status_error">
                                                                                <div class="vgen_challenge_mail_sending_status_error">
                                                                                    <ul class="vgen_challenge_mail_sending_status_error_ul">
                                                                                    <?php
                                                                                    $email_sending_note = get_post_meta( $post_id, 'email_sending_status', true );
                                                                                    foreach($email_sending_note as $key => $value) {
                                                                                        ?>
                                                                                            <li><?php echo $value ?></li>
                                                                                        <?php } 
                                                                                    ?>
                                                                                    </ul>
                                                                                </div> 
                                                                            </div>
                                                                            <div id="test_uploadStatus"></div>
                                                                        </form> 
                                                                    </div>
                                                                </div>
                                                                <div class="challenge-download-cover">
                                                                    <form class="download-mail-list_submit" method="post" action="" enctype="multipart/form-data">
                                                                        <input type="hidden" name="user_id" class="user_id" value="<?php echo get_current_user_id(); ?>" />
                                                                        <input type="hidden" name="challenge_id" class="challenge_id" value="<?php echo $post_id; ?>" />
                                                                        <div class="challenge-download-user-email-button">Download Mail List (CSV)</div>
                                                                    </form> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vgen_challenge-data-andsubscriptions">
                                                        <form class="submit-user-mata-value" action="">
                                                            <div class="vgen_challenge-data-admin-icon">
                                                                <h1 id="vgen_challenge_data_export" class="vgen_challenge_data_export">Download Challenge (CSV)</h1>
                                                                <div class="modal-overlay">
                                                                    <div class="modal modal-download-challenge">
                                                                        <a class="close-modal close-modal-download-challenge">
                                                                            <svg viewBox="0 0 20 20">
                                                                                <path fill="#000000" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                                                                            </svg>
                                                                        </a>
                                                                        <div class="modal-content-title">
                                                                            <p>Download Challenge User Data (CSV)</p>
                                                                        </div>
                                                                        <div class="modal-content modal-content-download-challenge">
                                                                            <div class="download-challenge-user-subscriptions-and-participation-button-cover">
                                                                                <div class="download-challenge-user-subscriptions-button modal_download_challenge_tablinks active" data-values="subscriptions">Subscriptions Data</div>
                                                                                <div class="download-challenge-user-participation-button modal_download_challenge_tablinks" data-values="participation">Participation Data</div>
                                                                            </div>
                                                                            <div class="modal-download-challenge-cover">
                                                                                <div class="modal-download-challenge-vc-ac-cb-div modal_download_challenge_user_subscriptions_data active">
                                                                                    <ul class="vc-cboxtags">
                                                                                        <?php
                                                                                        if ( get_option( 'user_mata_access_vgen_subscriptions_challenge_by_admin' ) !== false ) {
                                                                                            $result = get_option( 'user_mata_access_vgen_subscriptions_challenge_by_admin');
                                                                                        }else{
                                                                                            $result = $this->all_usermeta();
                                                                                        }

                                                                                        $user_mata_access_vgen_subscriptions_challenges = array();
                                                                                        if ( get_option( 'user_mata_access_vgen_subscriptions_challenge' ) !== false ) {
                                                                                            $user_mata_access_vgen_subscriptions_challenges = get_option( 'user_mata_access_vgen_subscriptions_challenge');
                                                                                        }
                                                                                        $user_mata_access_vgen_subscriptions_challenge = explode(',', $user_mata_access_vgen_subscriptions_challenges);

                                                                                        foreach($result as $key => $value) {
                                                                                            $checked = ( in_array( $value ,$user_mata_access_vgen_subscriptions_challenge ) ) ? 'checked' : '';
                                                                                        ?>
                                                                                            <li>
                                                                                                <input type="checkbox" name="user_mata_access_vgen_subscriptions_challenge_by_admin[]" id="checkbox_subscriptions<?php echo $value . '_' . $post_id ?>" value="<?php echo $value ?>" <?php echo $checked; ?>/>
                                                                                                <label for="checkbox_subscriptions<?php echo $value . '_' . $post_id ?>"><?php echo $value ?></label>
                                                                                            </li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </div>

                                                                                <div class="modal-download-challenge-vc-ac-cb-div modal_download_challenge_user_participation_data">
                                                                                    <ul class="vc-cboxtags">
                                                                                        <?php 
                                                                                        $user_mata_access_vgen_challenges_by_admin = array();
                                                                                        if ( get_option( 'user_mata_access_vgen_challenge_by_admin' ) !== false ) {
                                                                                            $result = get_option( 'user_mata_access_vgen_challenge_by_admin');
                                                                                        }else{
                                                                                            $result = $this->all_usermeta();
                                                                                        }

                                                                                        $user_mata_access_vgen_challenges = array();
                                                                                        if ( get_option( 'user_mata_access_vgen_challenge' ) !== false ) {
                                                                                            $user_mata_access_vgen_challenges = get_option( 'user_mata_access_vgen_challenge');
                                                                                        }
                                                                                        $user_mata_access_vgen_challenge = explode(',', $user_mata_access_vgen_challenges);
                                                                                        foreach($result as $key => $value) {
                                                                                            $checked = ( in_array( $value ,$user_mata_access_vgen_challenge ) ) ? 'checked' : '';
                                                                                        ?>
                                                                                            <li>
                                                                                                <input type="checkbox" name="user_mata_access_vgen_challenge[]" id="checkbox_participation_<?php echo $value . '_' . $post_id ?>" value="<?php echo $value ?>" <?php echo $checked; ?>/>
                                                                                                <label for="checkbox_participation_<?php echo $value . '_' . $post_id ?>"><?php echo $value ?></label>
                                                                                            </li>
                                                                                        <?php } ?>
                                                                                    </ul>
                                                                                </div>

                                                                            </div>
                                                                        </div>

                                                                        <div class="challenge-download-cover">
                                                                            <input type="hidden" name="user_id" class="user_id" value="<?php echo get_current_user_id(); ?>" />
                                                                            <input type="hidden" name="challenge_id" class="challenge_id" value="<?php echo $post_id; ?>" />
                                                                            <input type="hidden" name="download_type" class="download_type" value="subscriptions" />
                                                                            <!-- <input class="challenge-download-button" type="submit" value="Save & Download (CSV)"> -->
                                                                            <div class="challenge-download-button">Save & Download (CSV)</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <?php 
                                                
                                                    $particular_page_views = 0;
                                                    $unique_page_views = 0;
                                                    //$time_on_that_page = 0;
                                                    if( !empty( get_post_meta( $post_id, 'particular_page_views', true ) ) ){
                                                        $particular_page_views = get_post_meta( $post_id, 'particular_page_views', true );
                                                    }
                                                    if( !empty( get_post_meta( $post_id, 'unique_page_views', true ) ) ){
                                                        $unique_page_views = get_post_meta( $post_id, 'unique_page_views', true );
                                                    }
                                                    // if( !empty( get_post_meta( $post_id, 'time_on_that_page', true ) ) ){
                                                    //     $time_on_that_page = get_post_meta( $post_id, 'time_on_that_page', true );
                                                    // }

                                                    // $time_on_that_pages_min = $time_on_that_page/60;
                                                    // $time_on_that_pages_hou = $time_on_that_pages_min/60;
                                                    // $time_on_that_pages = number_format($time_on_that_pages_hou, 2);

                                                    // $average_time_on_that_page = $time_on_that_pages_min / $particular_page_views;
                                                    // $average_time_on_that_pages = number_format($average_time_on_that_page, 2);

                                                    $table_subscriptions = $this->user_subscriptions_database;
                                                    $table_database = $this->user_participation_database;
                                                    $query_subscriptions_active = $this->wpdb->get_row( "SELECT COUNT(*) AS `challenge_subscribe` FROM $table_subscriptions  WHERE `post_id` = '$post_id'" );
                                                    $query_participation_active = $this->wpdb->get_row( "SELECT COUNT(*) AS `challenge_participation` FROM $table_database WHERE `post_id` = '$post_id'" );

                                                ?>
                                                <div class="analytics-cover">

                                                    <!-- single challenge particular and unique page views rate start -->
                                                    <div class="challenge-subscribe-and-participation-rate-cover challenge-subscribe-and-participation-rate-cover_particular_and_unique">
                                                        <h2 class="challenge-subscribe-and-participation-title<?php echo $i; ?>"><?php _e( 'Analytics Particular and Unique Page Views', 'vgen_challenge'); ?></h2>
                                                        <div class="challenge_particular_and_unique_page_views_par_challenge" id="challenge_particular_and_unique_page_views_par_challenge<?php echo $post_id; ?>"></div>
                                                        <div class="challenge_particular_and_unique_page_views_cover">
                                                            <div class="challenge_particular_page_views"><p>Particular Page: <span class="challenge_analytics_countdown_for_view"><?php echo $particular_page_views; ?></span> Views</p></div>
                                                            <div class="challenge_unique_page_views"><p>Unique Page: <span class="challenge_analytics_countdown_for_view"><?php echo $unique_page_views; ?></span> Views</p></div>
                                                        </div>
                                                        <?php
                                                            $stop = 'off';
                                                            if( $stop == 'no' ){ 
                                                        ?>
                                                        <div class="challenge_average_and_time_on_that_page_cover">
                                                            <div class="challenge_time_on_that_page_views"><p>Viewing Time: <span class="challenge_analytics_countdown"><?php echo $time_on_that_pages; ?></span> Hours</p></div>
                                                            <div class="challenge_average_time_on_that_page_views"><p>Average Viewing Time: <span class="challenge_analytics_countdown"><?php echo $average_time_on_that_pages; ?></span> Minute</p></div>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    <!-- single challenge particular and unique page views rate end -->
                                                    
                                                    <?php  $off = 0;
                                                    if( $off == 1 ){ ?>
                                                    <!-- single challenge time on that page start -->
                                                    <div class="challenge-subscribe-and-participation-rate-cover">
                                                        <h2 class="challenge-subscribe-and-participation-title<?php echo $i; ?>"><?php _e( 'Analytics Time On that Page', 'vgen_challenge'); ?></h2>
                                                        <div class="challenge_time_on_that_page_par_challenge" id="challenge_time_on_that_page_par_challenge<?php echo $post_id; ?>"></div>
                                                    </div>
                                                    <!-- single challenge time on that page end -->
                                                    <?php } ?>
                                                    <!-- single challenge subscriber and participation rate start -->
                                                    <div class="challenge-subscribe-and-participation-rate-cover">
                                                        <h2 class="challenge-subscribe-and-participation-title<?php echo $i; ?>"><?php _e( 'Analytics Subscriptions and Participation Rate', 'vgen_challenge'); ?></h2>
                                                        <div class="challenge_subscribe_and_participation_rate_par_challenge" id="challenge_subscribe_and_participation_rate_par_challenge<?php echo $post_id; ?>"></div>
                                                        <div class="challenge_particular_and_unique_page_views_cover">
                                                            <div class="challenge_total_subscriptions"><p>Total Subscriptions: <span class="challenge_analytics_countdown_for_view"><?php echo $query_subscriptions_active->challenge_subscribe; ?></span></p></div>
                                                            <div class="challenge_total_participation"><p>Total Participation: <span class="challenge_analytics_countdown_for_view"><?php echo $query_participation_active->challenge_participation; ?></span></p></div>
                                                        </div>
                                                    </div>
                                                    <!-- single challenge subscriber and participation rate end -->
                                                    
                                                    <!-- single challenge top 10 user start -->
                                                    <div class="challenge-subscribe-and-participation-rate-cover">
                                                        <h2><?php _e('Top 5 Users', 'vgen_challenge'); ?></h2>
                                                        <div class="challenge_top_10_users" id="challenge_top_10_users<?php echo $post_id; ?>"></div>
                                                        <div class="challenge_particular_and_unique_page_views_cover">
                                                        </div>
                                                    </div>
                                                    <!-- single challenge top 10 user rate end -->
                                                </div>
                                                
                                                <!-- vgen challenge marking system start -->
                                                <div class="table-responsive">

                                                    <table class="table vgen_challenge-table jquerydatatable">
                                                        <thead>
                                                            <tr class="vgen_challenge-heading-wrapper">
                                                                <th><?php _e('Number', 'vgen_challenge'); ?></th>
                                                                <th><?php _e('User Name', 'vgen_challenge'); ?></th>
                                                                <th><?php _e('Submit Id', 'vgen_challenge'); ?></th>
                                                                <th><?php _e('Insert Time', 'vgen_challenge'); ?></th>
                                                                <th><?php _e('Answer Format', 'vgen_challenge'); ?></th>
                                                                <th><?php _e('Answer Link', 'vgen_challenge'); ?></th>
                                                                <th><?php _e('Marks', 'vgen_challenge'); ?></th>
                                                                <th><?php _e('Action', 'vgen_challenge'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        
                                                            $marks_first_like_creativity = 'Creativity';
                                                            if ( get_option( 'marks_first_like_creativity' ) !== false ) {
                                                                $marks_first_like_creativity = get_option( 'marks_first_like_creativity');
                                                            }
                                                            $marks_second_like_innovation = 'Innovation';
                                                            if ( get_option( 'marks_second_like_innovation' ) !== false ) {
                                                                $marks_second_like_innovation = get_option( 'marks_second_like_innovation');
                                                            }
                                                            $marks_third_like_invention = 'Invention';
                                                            if ( get_option( 'marks_third_like_invention' ) !== false ) {
                                                                $marks_third_like_invention = get_option( 'marks_third_like_invention');
                                                            }

                                                            $table_name = $this->user_participation_database;
                                                            $qry = $this->wpdb->get_results( "SELECT * FROM $table_name ssn WHERE `post_id` = $post_id ORDER BY ssn.`insert_time` DESC", OBJECT);
                                                            $all_files = json_decode(json_encode($qry), true);
                                                        ?>
                                                        <tbody>
                                                            <?php
                                                            $j = 1;
                                                            foreach ($all_files as $single_file){
                                                                $challenge_title = get_the_title( $single_file['post_id'] );
                                                                $user_id = $single_file['user_id'];
                                                                $user_info = get_userdata( $user_id );
                                                                $user_name = $user_info->display_name;
                                                                $user_email = $user_info->user_email;

                                                                $uploaded_type = $single_file['uploaded_type'];
                                                                $uploaded_type_values = $uploaded_type;
                                                                // if( $uploaded_type == 'contact_form_7' ){
                                                                //     $uploaded_type_values = 'CF7 ZIP';
                                                                // }elseif( $uploaded_type == 'submit_url' ){
                                                                //     $uploaded_type_values = 'Drive URL';
                                                                // }elseif( $uploaded_type == '.zip' ){
                                                                //     $uploaded_type_values = 'ZIP';
                                                                // }elseif( $uploaded_type == '.pdf' ){
                                                                //     $uploaded_type_values = 'PDF';
                                                                // }
                                                                $uploaded_url = $single_file['uploaded_url'];
                                                                ?>
                                                                <tr class="tr-vgen-user-marks">
                                                                    <td><?php echo $j++; ?></td>
                                                                    <td><?php echo $user_name; ?></td>
                                                                    <td><?php echo $single_file['uploaded_nonce']; ?></td>
                                                                    <td class="vgen-date"><?php echo $single_file['insert_time']; ?></td>
                                                                    <td><?php echo $uploaded_type_values; ?></td>
                                                                    <?php 
                                                                    if( $uploaded_type_values == 'submit_url' ){
                                                                    ?>
                                                                    <td><a class="vgen_uploaded_url" href="<?php echo $uploaded_url; ?>" target="_blank">See the Answer</a></td>
                                                                    <?php 
                                                                    }else{
                                                                    ?>
                                                                    <td>Your answer has been sent via Mail.</td>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <td>
                                                                        <div class="vc_show_marks_label">
                                                                            <p><?php echo $marks_first_like_creativity . ': '; ?><span class="vgen-user-marks-creativity<?php echo $single_file['id']; ?>"><?php echo $single_file['user_creativity_marks']; ?></span></p>
                                                                        </div>
                                                                        <div class="vc_show_marks_label">
                                                                            <p><?php echo $marks_second_like_innovation . ': '; ?><span class="vgen-user-marks-innovation<?php echo $single_file['id']; ?>"><?php echo $single_file['user_innovation_marks']; ?></span></p>
                                                                        </div>
                                                                        <div class="vc_show_marks_label">
                                                                            <p><?php echo $marks_third_like_invention . ': '; ?><span class="vgen-user-marks-invention<?php echo $single_file['id']; ?>"><?php echo $single_file['user_invention_marks']; ?></span></p>
                                                                        </div>
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <div class="np-edit add_marks" ><?php _e('Add Marks', 'vgen_challenge'); ?></div>
                                                                        <div class="modal-overlay">
                                                                            <div class="modal">
                                                                                <a class="close-modal">
                                                                                <svg viewBox="0 0 20 20">
                                                                                    <path fill="#000000" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                                                                                </svg>
                                                                                </a>
                                                                                <div class="modal-content">
                                                                                    <div class="challenge-submit-id">Challenge Submit Id: <span><?php echo $single_file['uploaded_nonce']; ?></span></div>
                                                                                    <div class="challenge-name">Challenge Name: <span><?php echo get_the_title($single); ?></span></div>
                                                                                    <?php
                                                                                    if ( $role == 'administrator' OR $role == 'um_company' ){
                                                                                    ?>
                                                                                    <div class="challenge-author-name">Challenge Author Name: <span><?php echo $post_author_name; ?></span></div>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                    <div class="challenge-except-user-name">Challenge Except User Name: <span><?php echo $user_name; ?></span></div>
                                                                                    <div class="challenge-except-user-name">Challenge Except User Mail: <span><?php echo $user_email; ?></span></div>
                                                                                    <div class="challenge-except-time">Challenge Except Time: <span><?php echo $single_file['insert_time']; ?></span></div>
                                                                                    <div class="challenge-except-user-name">User Answer Format: <span><?php echo $uploaded_type_values; ?></span></div>
                                                                                    <div class="challenge-except-user-name">User Answer Link: <span>
                                                                                        <?php 
                                                                                        if( $uploaded_type_values == 'submit_url' ){
                                                                                        ?>
                                                                                        <a class="vgen_uploaded_url" href="<?php echo $uploaded_url; ?>" target="_blank">See the Answer</a>
                                                                                        <?php 
                                                                                        }else{
                                                                                        ?>Your answer has been sent via Mail.<?php
                                                                                        }
                                                                                        ?>
                                                                                        </span></div>
                                                                                    <div class="vc_add_marks_cover">
                                                                                        <input type="hidden" name="file_id" class="file_id" value="<?php echo $single_file['id']; ?>" />
                                                                                        <label class="vc_add_marks_label"><?php echo $marks_first_like_creativity . ': '; ?><input type="number" min="1" max="10" name="vc_add_marks_creativity" class="vc_add_marks_creativity" value="<?php echo $single_file['user_creativity_marks']; ?>"/></label>
                                                                                        <label class="vc_add_marks_label"><?php echo $marks_second_like_innovation . ': '; ?><input type="number" min="1" max="10" name="vc_add_marks_innovation" class="vc_add_marks_innovation" value="<?php echo $single_file['user_innovation_marks']; ?>"/></label>
                                                                                        <label class="vc_add_marks_label"><?php echo $marks_third_like_invention . ': '; ?><input type="number" min="1" max="10" name="vc_add_marks_invention" class="vc_add_marks_invention" value="<?php echo $single_file['user_invention_marks']; ?>"/></label>
                                                                                        <div class="update-marks" value="update" name="status">Update</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                </div>
                                                <!-- vgen challenge marking system end -->
                                                
                                                <div class="analytics-cover">
                                                    <div class="vgen_challenge-participator-marks-shortcode">Participator Marks Shortcode: <span><?php echo '[vgen-challenge-participate-list challenge-id="' . $post_id . '"]'; ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                <?php
                                $i++;
                                }
                            ?>
                                </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php
                    }
                    ?>

                </div>
            </div>
            <?php
        }

        function mail_subscriptions_send_Ajax(){
            ob_start();
            global $post;
            $post_id = $_POST['post_id'];
            $user_subscriptions_mail = $_POST['user_subscriptions_mail_vgen_challenge'];
            $mail_title = $_POST['vgen_challenge_checkbox_subscription_title'];
            $mail_body = '';
            if(isset($_POST['vgen_challenge_checkbox_subscription_body_' . $post_id])  ){
                $mail_body = $_POST['vgen_challenge_checkbox_subscription_body_' . $post_id];
            }
            $admin_mail = 'iscrizioni@vgen.it';
            if ( get_option( 'admin_mail_for_getting_answer_with_link' ) !== false ) {
                $admin_mail = get_option( 'admin_mail_for_getting_answer_with_link');
            }

            $email_sending_note = array();
            foreach( $user_subscriptions_mail as $key => $single_email ){
                if( $single_email != 'checkbox_all' ){
                    $email = $single_email;
                    $subject = $mail_title;

                    $user = get_user_by("email", $email);;
                    $username = $user->user_login;

                    $challenge = get_post($post_id);
                    $challenge_name = $challenge->post_title;
                    
                    $mail_body_with_email = str_replace("{user_email}", $email, $mail_body );
                    $mail_body_with_name = str_replace("{user_name}", $username, $mail_body_with_email );
                    $mail_body_content = str_replace("{challenge_name}", $challenge_name, $mail_body_with_name );

                    $body = stripslashes( $mail_body_content );
                    $headers = 'From: ' . $admin_mail . "\r\n" .
                    'Reply-To: ' . $admin_mail . "\r\n";
                    $headers .= 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $sent_1 = wp_mail($email, $subject, $body, $headers);

                    if( $sent_1 == '1' ){
                        $email_sending_note_mas = 'Mail sending is complete to this ' . $email . '.';
                    }else{
                        $email_sending_note_mas = 'Mail sending is not complete to this ' . $email . '.';
                    }
                    array_push( $email_sending_note, $email_sending_note_mas );
                }
            }
            update_post_meta( $post_id, 'email_sending_status', $email_sending_note );
            $output = ob_get_clean();

            ob_start();
            ?>
            <ul class="vgen_challenge_mail_sending_status_error_ul">
            <?php
            foreach($email_sending_note as $key => $value) {
                ?>
                    <li><?php echo $value ?></li>
                <?php } 
            ?>
            </ul>
            <?php
            $email_sending_notes = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'email_sending_notes' => $email_sending_notes,
                )
            );
            die(); 
        }
        
        function vc_single_challenge_data_filterAjax(){
            ob_start();
            $post_id = $_POST['post_id'];
            global $wpdb;
            $table_subscriptions = $this->user_subscriptions_database;
            $table_database = $this->user_participation_database;

            $query_single_challenge_participation = $this->wpdb->get_results( "SELECT `insert_date`, COUNT(*) AS `challenge_participation` FROM $table_database WHERE `post_id` = '$post_id' GROUP BY `insert_date`" );
            $query_single_challenge_participation_array = json_decode(json_encode($query_single_challenge_participation), true);
            $single_challenge_participation_time_value = array();
            foreach( $query_single_challenge_participation_array as $single_time_value ){
                $single_challenge_participation_time_value[$single_time_value['insert_date']] = $single_time_value['challenge_participation'];
            }
            $query_single_challenge_subscriptions = $this->wpdb->get_results( "SELECT `insert_date`, COUNT(*) AS `challenge_subscriptions` FROM $table_subscriptions WHERE `post_id` = '$post_id' GROUP BY `insert_date`" );
            $query_single_challenge_subscriptions_array = json_decode(json_encode($query_single_challenge_subscriptions), true);
            $single_challenge_subscriptions_time_value = array();
            foreach( $query_single_challenge_subscriptions_array as $single_time_value ){
                $single_challenge_subscriptions_time_value[$single_time_value['insert_date']] = $single_time_value['challenge_subscriptions'];
            }

            $table_particular_page_views = $this->user_particular_page_views_database;
            $query_particular_page_views_date = $this->wpdb->get_results( "SELECT `insert_date`, `particular_page_views` FROM $table_particular_page_views WHERE `post_id` = '$post_id' GROUP BY `insert_date`" );
            $query_particular_page_views_date_array = json_decode(json_encode($query_particular_page_views_date), true);

            $single_challenge_particular_page_views_value = array();
            foreach( $query_particular_page_views_date_array as $single_page_views_date_value ){
                $single_challenge_particular_page_views_value[$single_page_views_date_value['insert_date']] = $single_page_views_date_value['particular_page_views'];
            }

            //user_unique_page_views_database
            $table_unique_page_views = $this->user_unique_page_views_database;
            $query_unique_page_views_date = $this->wpdb->get_results( "SELECT `insert_date`, `unique_page_views` FROM $table_unique_page_views WHERE `post_id` = '$post_id' GROUP BY `insert_date`" );
            $query_unique_page_views_date_array = json_decode(json_encode($query_unique_page_views_date), true);

            $single_challenge_unique_page_views_value = array();
            foreach( $query_unique_page_views_date_array as $single_unique_page_views_value ){
                $single_challenge_unique_page_views_value[$single_unique_page_views_value['insert_date']] = $single_unique_page_views_value['unique_page_views'];
            }
            
            //user_time_on_that_page_database
            $table_time_on_that_page = $this->user_time_on_that_page_database;
            $query_time_on_that_page_date = $this->wpdb->get_results( "SELECT `insert_date`, `time_on_that_page` FROM $table_time_on_that_page WHERE `post_id` = '$post_id' GROUP BY `insert_date`" );
            $query_time_on_that_page_date_array = json_decode(json_encode($query_time_on_that_page_date), true);

            $single_challenge_time_on_that_page_value = array();
            $single_average_time_on_that_page_value = array();
            foreach( $query_time_on_that_page_date_array as $single_time_on_that_page_value ){
                $single_time_on_that_page = $single_time_on_that_page_value['time_on_that_page'];
                $single_minute_time_on_that_page = $single_time_on_that_page/60;
                $single_challenge_time_on_that_page_value[$single_time_on_that_page_value['insert_date']] = number_format($single_minute_time_on_that_page, 2);

                $single_challenge_time_on_that_page_for = get_post_meta( $post_id, 'time_on_that_page', true );
                $single_challenge_time_on_that_page_fors = $single_challenge_time_on_that_page_for/60;
                $single_challenge_particular_page_views_for = get_post_meta( $post_id, 'particular_page_views', true );

                $single_average_time_on_that_page = $single_challenge_time_on_that_page_fors / $single_challenge_particular_page_views_for;
                $single_average_time_on_that_page_value[$single_time_on_that_page_value['insert_date']] = number_format($single_average_time_on_that_page, 2);
            }

            $query_top_users_participation = $this->wpdb->get_results( "SELECT `post_id`, `user_id`, `user_creativity_marks`, `user_innovation_marks`, `user_invention_marks`, ( `user_creativity_marks` + `user_innovation_marks` + `user_invention_marks` ) as `user_total_marks` FROM $table_database WHERE `post_id` = '$post_id' ORDER BY `user_total_marks` DESC LIMIT 5" );
            $query_top_users_participation_array = json_decode(json_encode($query_top_users_participation), true);
            $challenge_top_users_name = array();
            $challenge_top_users_user_creativity_marks = array();
            $challenge_top_users_user_innovation_marks = array();
            $challenge_top_users_user_invention_marks = array();
            foreach( $query_top_users_participation_array as $single_top_user ){
                $singlee_top_user_id = $single_top_user['user_id'];
                $single_top_user_info = get_userdata( $singlee_top_user_id );
                $single_top_user_name = $single_top_user_info->display_name;
                $challenge_top_users_name[$singlee_top_user_id] = $single_top_user_name;
                $challenge_top_users_user_creativity_marks[$singlee_top_user_id] = $single_top_user['user_creativity_marks'];
                $challenge_top_users_user_innovation_marks[$singlee_top_user_id] = $single_top_user['user_innovation_marks'];
                $challenge_top_users_user_invention_marks[$singlee_top_user_id] = $single_top_user['user_invention_marks'];
            }

            $output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'post_id' => $post_id,
                    'challenge_participation' => $single_challenge_participation_time_value,
                    'challenge_subscriptions' => $single_challenge_subscriptions_time_value,
                    'particular_page_views_value' => $single_challenge_particular_page_views_value,
                    'unique_page_views_value' => $single_challenge_unique_page_views_value,
                    'time_on_that_page_value' => $single_challenge_time_on_that_page_value,
                    'average_time_on_that_page_value' => $single_average_time_on_that_page_value,
                    'challenge_top_users_name' => $challenge_top_users_name,
                    'challenge_top_users_user_creativity_marks' => $challenge_top_users_user_creativity_marks,
                    'challenge_top_users_user_innovation_marks' => $challenge_top_users_user_innovation_marks,
                    'challenge_top_users_user_invention_marks' => $challenge_top_users_user_invention_marks,
                )
            );
            die(); 
        }

        // ajax
        function vc_download_challenge_filterAjax(){
            //ob_start();
            global $wpdb;
            $user_id = $_POST['user_id'];
            $challenge_id = $_POST['challenge_id'];
            $download_type = $_POST['download_type'];
            $user_mata_access_vgen_challenge_value = $_POST['user_mata_access_vgen_challenge_value'];
            $user_mata_access_vgen_subscriptions_challenge_by_admin_value = $_POST['user_mata_access_vgen_subscriptions_challenge_by_admin_value'];
            
            $subscribe_user_mail = 'no';
            if(isset($_POST['subscribe_user_mail'])  ){
                $subscribe_user_mail = $_POST['subscribe_user_mail'];
            }

            if( $subscribe_user_mail == 'no' ){

                update_option( 'user_mata_access_vgen_subscriptions_challenge', $user_mata_access_vgen_subscriptions_challenge_by_admin_value );
                update_option( 'user_mata_access_vgen_challenge', $user_mata_access_vgen_challenge_value );

                if( $download_type == 'participation' ){
                    $user_mata_access_vgen_challenge = explode(',', $user_mata_access_vgen_challenge_value);
                }else{
                    $user_mata_access_vgen_challenge = explode(',', $user_mata_access_vgen_subscriptions_challenge_by_admin_value);
                }

                $sql_users_array = $this->wpdb->get_row( "SELECT * FROM {$wpdb->users} LIMIT 1" );
                $sql_users_array_de = json_decode(json_encode($sql_users_array), true);
                $sql_users = array();
                foreach( $sql_users_array_de as $key=>$value ){
                    array_push($sql_users, $key);
                }
                $sql_usermeta = $this->wpdb->get_col( "SELECT meta_key FROM {$wpdb->usermeta} GROUP BY meta_key", 0 );
                
                $table_users = $this->users;

                $file_url = '';
                
                if( $download_type == 'participation' ){
                    $table_name = $this->user_participation_database;
                    $query = $this->wpdb->get_col( "SELECT `user_id` FROM $table_name WHERE `post_id` = $challenge_id" );
                }else{
                    $table_name_subscriptions = $this->user_subscriptions_database;
                    $query = $this->wpdb->get_col( "SELECT `user_id` FROM $table_name_subscriptions WHERE `post_id` = $challenge_id" );
                }

                if(count($query) > 0){
                    $delimiter = ",";
                    $filename = $this->plugin_dir . "csv/vgen_challenge_user_data.csv";

                    $f = fopen($filename, 'w');

                    $fields = $user_mata_access_vgen_challenge;
                    fputcsv($f, $fields);
                    
                    foreach ($query as $user_id){

                        $user_lineData = array();
                        if( in_array( 'user_id', $user_mata_access_vgen_challenge ) ){
                            $meta_values = $user_id;
                            array_push( $user_lineData, $meta_values );
                        }
                        if( in_array( 'user_name', $user_mata_access_vgen_challenge ) ){
                            $user_info = get_userdata( $user_id );
                            $meta_values = $user_info->display_name;
                            array_push( $user_lineData, $meta_values );
                        }
                        if( in_array( 'challenge_name', $user_mata_access_vgen_challenge ) ){
                            $meta_values = get_the_title( $challenge_id );
                            array_push( $user_lineData, $meta_values );
                        }
                        if( in_array( 'submit_id', $user_mata_access_vgen_challenge ) ){
                            $uploaded_nonce = $this->wpdb->get_col( "SELECT `uploaded_nonce` FROM $table_name WHERE `post_id` = $challenge_id AND `user_id` = $user_id ");
                            $meta_values = $uploaded_nonce[0];
                            array_push( $user_lineData, $meta_values );
                        }
                        if( in_array( 'insert_time', $user_mata_access_vgen_challenge ) ){
                            $insert_time = $this->wpdb->get_col( "SELECT `insert_time` FROM $table_name WHERE `post_id` = $challenge_id AND `user_id` = $user_id ");
                            $meta_values = $insert_time[0];
                            array_push( $user_lineData, $meta_values );
                        }
                        if( in_array( 'user_creativity_marks', $user_mata_access_vgen_challenge ) ){
                            $user_creativity_marks = $this->wpdb->get_col( "SELECT `user_creativity_marks` FROM $table_name WHERE `post_id` = $challenge_id AND `user_id` = $user_id ");
                            $meta_values = $user_creativity_marks[0];
                            array_push( $user_lineData, $meta_values );
                        }
                        if( in_array( 'user_innovation_marks', $user_mata_access_vgen_challenge ) ){
                            $user_innovation_marks = $this->wpdb->get_col( "SELECT `user_innovation_marks` FROM $table_name WHERE `post_id` = $challenge_id AND `user_id` = $user_id ");
                            $meta_values = $user_innovation_marks[0];
                            array_push( $user_lineData, $meta_values );
                        }
                        if( in_array( 'user_invention_marks', $user_mata_access_vgen_challenge ) ){
                            $user_invention_marks = $this->wpdb->get_col( "SELECT `user_invention_marks` FROM $table_name WHERE `post_id` = $challenge_id AND `user_id` = $user_id ");
                            $meta_values = $user_invention_marks[0];
                            array_push( $user_lineData, $meta_values );
                        }

                        $user_data = $this->wpdb->get_row( "SELECT * FROM $table_users WHERE `ID` = $user_id");
                        foreach( $user_data as $key => $value ){
                            if( in_array( $key, $user_mata_access_vgen_challenge ) ){
                                $meta_values = $value;
                                if( $meta_values == '' ){
                                    $meta_values = 'empty';
                                }
                                array_push( $user_lineData, $meta_values );
                            }
                        }
                        foreach( $sql_usermeta as $single_usermeta ){
                            if( in_array( $single_usermeta, $user_mata_access_vgen_challenge ) ){
                                $meta_values = get_user_meta( $user_id, $single_usermeta, true );
                                if( $meta_values == '' ){
                                    $meta_values = 'empty';
                                }
                                // $data = @unserialize($meta_value);
                                // if($data !== false) {
                                //     $test = unserialize($data);
                                //     $meta_values = implode("|",$test);
                                // }else{
                                //     $meta_values = $meta_value;
                                // }

                                // if( is_array($meta_values) == 1 ){
                                //     $meta_values = implode("|",$meta_values);
                                // }else{
                                //     $meta_values = $meta_values;
                                // }
                                array_push( $user_lineData, $meta_values );
                            }
                        }
                        
                        $lineData = $user_lineData;
                        fputcsv($f, $lineData, $delimiter);
                    }
                    fclose($f);
                    
                    $file_url = $this->plugin_url . "csv/vgen_challenge_user_data.csv";
                }

            }else{

                $file_url = '';

                $table_subscriptions = $this->user_subscriptions_database;
                $query = $this->wpdb->get_col( "SELECT `user_id` FROM $table_subscriptions WHERE `post_id` = $challenge_id" );

                if(count($query) > 0){
                    $delimiter = ",";
                    $filename = $this->plugin_dir . "csv/vgen_challenge_user_data.csv";

                    $f = fopen($filename, 'w');

                    $fields = array('Subscriber Mail List');
                    $user_mata_access_vgen_challenge = $fields;
                    fputcsv($f, $fields);
                    
                    foreach ($query as $user_id){
                        $user_lineData = array();
                        $user_info = get_userdata( $user_id );
                        $user_email = $user_info->user_email;
                        if( !empty($user_email) ){
                            $user_mail_lineData = $user_email;
                        }else{
                            $user_mail_lineData = 'empty!';
                        }
                        array_push( $user_lineData, $user_mail_lineData );

                        $lineData = $user_lineData;
                        fputcsv($f, $lineData, $delimiter);
                    }
                    fclose($f);
                    
                    $file_url = $this->plugin_url . "csv/vgen_challenge_user_data.csv";
                }

            }
            
            //$output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'user_id' => $user_id,
                    'challenge_id' => $challenge_id,
                    'user_lineData' => $user_lineData,
                    'download_url'  => $file_url,
                    'user_mata_access_vgen_challenge' => $user_mata_access_vgen_challenge,
                    'query' => $query,
                    'download_type' => $download_type,
                )
            );
            die(); 
        }
        

        // ajax
        function vc_marking_update_filterAjax(){
            ob_start();

            $file_id = $_POST['file_id'];
            $vc_add_marks_creativity = $_POST['vc_add_marks_creativity'];
            $vc_add_marks_innovation = $_POST['vc_add_marks_innovation'];
            $vc_add_marks_invention = $_POST['vc_add_marks_invention'];

            global $wpdb;
            $table_database = $this->user_participation_database;
            $wpdb->update( $table_database,
                array(
                        'user_creativity_marks' => $vc_add_marks_creativity,
                        'user_innovation_marks' => $vc_add_marks_innovation,
                        'user_invention_marks' => $vc_add_marks_invention,
                    ),
                array(
                    'id'=> $file_id
                ),
                array( '%d', '%d', '%d'),
                array( '%d' )
            );
         
            $output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'file_id' => $file_id,
                    'vc_add_marks_creativity' => $vc_add_marks_creativity,
                    'vc_add_marks_innovation' => $vc_add_marks_innovation,
                    'vc_add_marks_invention' => $vc_add_marks_invention,
                )
            );
            die(); 
        }

        
        function vgen_challenge_page_view_counter(){



        }

        
        function vc_submit_remove_cache_filterAjax(){
            ob_start();
            $post_id = $_POST['post_id'];
            // if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            //     {$link = "https";}
            // else
            //     {$link = "http";}
            // $link .= "://" .  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            // $link = explode("?", $link);
            // $link = $link[0];
            // header ("location: $link");

            $output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'post_id'=> $post_id
                )
            );
            die(); 
        }

        
        

    } // End Class
} // End Class check if exist / not