<?php
/*
* wp_post_filter Class 
*/

if (!class_exists('wp_post_filterClass')) {
    class wp_post_filterClass{
        public $plugin_url;
        public $plugin_dir;
        public $wpdb;
        public $option_tbl; 
        
        /**Plugin init action**/ 
        public function __construct() {
            global $wpdb;
            $this->plugin_url 				= wp_post_filterURL;
            $this->plugin_dir 				= wp_post_filterDIR;
            $this->wpdb 					= $wpdb;	
            $this->option_tbl               = $this->wpdb->prefix . 'options';
         
            $this->init();
        }

        private function init(){

            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'wp_post_filter_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'wp_post_filter_frontend_script') );

            //Add Menu Options
            add_action('admin_menu', array($this, 'wp_post_filter_admin_menu_function'));
            
            //Shortcode
            add_shortcode( 'wp-post-filter', array($this, 'wp_post_filterShortcodeCallback') );

            //Add Meta Boxes 
            add_action('add_meta_boxes', array($this, 'wp_post_filter_add_custom_meta_box') );
            add_action( 'save_post', array($this, 'wp_post_filter_save_meta_box_data') );

            //Settins hook to wp admin via js
            add_action( 'admin_head', array($this, 'wp_post_filter_admin_area') );

            /* Send data ajax */ 
            add_action('wp_ajax_nopriv_wp_post_filterAjax', array($this, 'wp_post_filterAjax'));
            add_action( 'wp_ajax_wp_post_filterAjax', array($this, 'wp_post_filterAjax') );
        }


        /*
        * Appointment backend Script
        */
        function wp_post_filter_backend_script($hook){
            if($hook != 'toplevel_page_wp-post-filter') return false;
            
            wp_enqueue_style( 'b_wp_post_filterCSS', $this->plugin_url . 'asset/css/wp_post_filter_backend.css', array(), true, 'all' );
            wp_enqueue_script( 'b_wp_post_filterJS', $this->plugin_url . 'asset/js/wp_post_filter_backend.js', array(), true );

            wp_enqueue_style( 'fontawesomeCSS', 'https://use.fontawesome.com/releases/v5.4.1/css/all.css', array(), true, 'all' );
        }

        /*
        * Appointment frontend Script
        */
        function wp_post_filter_frontend_script(){

            $wp_post_filter_checkbox_option = (get_option( 'wp_post_filter_checkbox_option' ) == 1) ? 1 : 0 ;

            wp_enqueue_style( 'f_wp_post_filterCSS', $this->plugin_url . 'asset/css/wp_post_filter_frontend.css', array(), true, 'all' );
            wp_enqueue_script('f_wp_post_filterJS', $this->plugin_url . 'asset/js/wp_post_filter_frontend.js', array('jquery'), time(), true);

            wp_enqueue_style( 'f_font_awesomeCSS','https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), true, 'all' );

            
            //ajax
            wp_localize_script( 'f_wp_post_filterJS', 'wp_filterAjax', 
            array(
                'ajax' => admin_url( 'admin-ajax.php' ),
                'wp_post_filter_checkbox_option' => $wp_post_filter_checkbox_option,
                )
            );
        }

        function wp_post_filter_admin_menu_function(){
            add_menu_page( 'Wp Post Filter', 'Wp Post Filter', 'manage_options', 'wp-post-filter', array($this, 'submenufunction'), $this->plugin_url . 'asset/css/images/filter.png', 50 );
        }


        // update Settings
        public function updateSettings($data){
            foreach($data as $k => $sd) update_option( $k, $sd );
        }
        function submenufunction(){
            if(isset($_POST['wp_post_filter_submit_btn'])) $this->updateSettings($_POST);

            $all_taxonomies = get_terms( array(
                'taxonomy'   => 'category',
                'hide_empty' => false
            ) );
            ?>

            <div class="wp-post-filter-submenu">
                <div class="wp-post-filter-title-csv">
                    <div class="wp-post-filter-submenu-title">
                        <h1><?php _e('Wp Post Filter Settings', 'wp_post_filter'); ?></h1>
                    </div>
                </div>
                <!-- Settings -->
                <div class="wp-post-filter">
                    <div class="tab">
                        <button class="tablinks active" onclick="openTab(event, 'create_wp_post_filter')"><?php _e('Settings', 'wp_post_filter'); ?></button>
                    </div>

                    <div id="create_wp_post_filter" class="tabcontent" style="display:block;">
                        <div class="settingsInner">
                            <form id="wp_post_filter_submit" method="post" action="">
                                <table class="wp-post-filter-data-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left"><?php _e('Filter frame heading', 'wp_post_filter' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $filter_frame_heading = '';
                                                if ( get_option( 'filter_frame_heading' ) !== false ) {
                                                    $filter_frame_heading = get_option( 'filter_frame_heading');
                                                }
                                                ?>
                                                <input type="text" class="category_title" name="filter_frame_heading" value="<?php echo $filter_frame_heading; ?>" placeholder="Type your filter frame heading">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Your first category title', 'wp_post_filter' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $first_category_title = '';
                                                if ( get_option( 'first_category_title' ) !== false ) {
                                                    $first_category_title = get_option( 'first_category_title');
                                                }
                                                ?>
                                                <input type="text" class="category_title" name="first_category_title" value="<?php echo $first_category_title; ?>" placeholder="Type your first category title">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Choose your first category', 'wp_post_filter' ); ?></th>
                                            <td class="text-left">
                                                <div class="wssn-ks-cb-div">
                                                    <ul class="ks-cboxtags">
                                                        <li>
                                                            <input type="checkbox" class="wssn-hidden" name="wp_post_filter_choose_first_category[]" id="checkbox_wp_post_filter_choose_first_category" value="checkbox" checked/>
                                                        </li>
                                                        <?php
                                                        $wp_post_filter_choose_first_category = array();
                                                        if ( get_option( 'wp_post_filter_choose_first_category' ) !== false ) {
                                                            $wp_post_filter_choose_first_category = get_option( 'wp_post_filter_choose_first_category');
                                                        }
                                                        foreach( $all_taxonomies as $single ) {
                                                            $category_id = $single->term_id;
                                                            $category_name = $single->name;
                                                            $checked = ( in_array( $category_id ,$wp_post_filter_choose_first_category ) ) ? 'checked' : '';
                                                            
                                                        ?>
                                                            <li>
                                                                <input type="checkbox" name="wp_post_filter_choose_first_category[]" id="checkbox_wp_post_filter_choose_first_category_<?php echo str_replace(' ', '_', $category_name) ?>" value="<?php echo $category_id ?>" <?php echo $checked; ?> />
                                                                <label for="checkbox_wp_post_filter_choose_first_category_<?php echo str_replace(' ', '_', $category_name) ?>"><?php echo $category_name ?></label>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Your second category title', 'wp_post_filter' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $second_category_title = '';
                                                if ( get_option( 'second_category_title' ) !== false ) {
                                                    $second_category_title = get_option( 'second_category_title');
                                                }
                                                ?>
                                                <input type="text" class="category_title" name="second_category_title" value="<?php echo $second_category_title; ?>" placeholder="Type your second category title">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Choose your second category', 'wp_post_filter' ); ?></th>
                                            <td class="text-left">
                                                <div class="wssn-ks-cb-div">
                                                    <ul class="ks-cboxtags">
                                                        <li>
                                                            <input type="checkbox" class="wssn-hidden" name="wp_post_filter_choose_second_category[]" id="checkbox_wp_post_filter_choose_second_category" value="checkbox" checked/>
                                                        </li>
                                                        <?php
                                                        $wp_post_filter_choose_second_category = array();
                                                        if ( get_option( 'wp_post_filter_choose_second_category' ) !== false ) {
                                                            $wp_post_filter_choose_second_category = get_option( 'wp_post_filter_choose_second_category');
                                                        }
                                                        foreach( $all_taxonomies as $single ) {
                                                            $category_id = $single->term_id;
                                                            $category_name = $single->name;
                                                            $checked = ( in_array( $category_id ,$wp_post_filter_choose_second_category ) ) ? 'checked' : '';
                                                            
                                                        ?>
                                                            <li>
                                                                <input type="checkbox" name="wp_post_filter_choose_second_category[]" id="checkbox_wp_post_filter_choose_second_category_<?php echo str_replace(' ', '_', $category_name) ?>" value="<?php echo $category_id ?>" <?php echo $checked; ?> />
                                                                <label for="checkbox_wp_post_filter_choose_second_category_<?php echo str_replace(' ', '_', $category_name) ?>"><?php echo $category_name ?></label>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"><?php _e('Do you want to single selector?', 'wp_post_filter'); ?></th>
                                            <td class="text-left">
                                                <div class='checkbox' id='hideSearch'>
                                                    <label class='checkbox__container'>
                                                    <input type="hidden" name="wp_post_filter_checkbox_option" value="0">
                                                    <input class='checkbox__toggle' type='checkbox' value="1" name='wp_post_filter_checkbox_option' <?php echo $checked = (get_option( 'wp_post_filter_checkbox_option' ) == 1) ? 'checked' : '' ; ?>/>
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
                                            <th class="text-left"></th>
                                            <td class="text-left">
                                                <input type="submit" class="wp_post_filter-submit-btn" name="wp_post_filter_submit_btn" value="Submit" style="float:left">
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

        function wp_post_filterShortcodeCallback(){
            ob_start();

                        
            $filter_frame_heading = '';
            if ( get_option( 'filter_frame_heading' ) !== false ) {
                $filter_frame_heading = get_option( 'filter_frame_heading');
            }
            $first_category_title = '';
            if ( get_option( 'first_category_title' ) !== false ) {
                $first_category_title = get_option( 'first_category_title');
            }
            $second_category_title = '';
            if ( get_option( 'second_category_title' ) !== false ) {
                $second_category_title = get_option( 'second_category_title');
            }
            $wp_post_filter_choose_first_category = array();
            if ( get_option( 'wp_post_filter_choose_first_category' ) !== false ) {
                $wp_post_filter_choose_first_category = get_option( 'wp_post_filter_choose_first_category');
            }
            $wp_post_filter_choose_second_category = array();
            if ( get_option( 'wp_post_filter_choose_second_category' ) !== false ) {
                $wp_post_filter_choose_second_category = get_option( 'wp_post_filter_choose_second_category');
            }

            $first_category = array();
            foreach( $wp_post_filter_choose_first_category as $single ){
                $args = get_term_by('id', $single, 'category');
                array_push( $first_category, $args );
            }
            $first_category = array_filter(json_decode(json_encode($first_category), true));


            $second_category = array();
            foreach( $wp_post_filter_choose_second_category as $single ){
                $args = get_term_by('id', $single, 'category');
                array_push( $second_category, $args );
            }
            $second_category = array_filter(json_decode(json_encode($second_category), true));
            ?>
            <!-- Page Content -->
            <div class="wp_post_filter-container-fluid">
                <div class="wp_post_filter-frame-heading-cover">
                    <div class="wp_post_filter-frame-heading"><?php echo $filter_frame_heading; ?></div>
                </div>
                </br>
                </br>
                <div class="wp_post_filter-row">
                    <div class="wp_post_filter-md-3">				
                        <div class="wp_post_filter-list-group">
                            <div class="wp_post_filter-category-title"><?php echo $first_category_title; ?></div>
                            <div style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                            <?php foreach( $first_category as $single): ?>
                                <div class="wp_post_filter-list-group-item checkbox">
                                    <input type="checkbox" class="wp_post_filter-common_selector first_category" value="<?php echo $single['term_id']; ?>" id="checkbox_wp_post_filter_choose_first_category_<?php echo str_replace(' ', '_', $single['name']); ?>" >
                                    <label for="checkbox_wp_post_filter_choose_first_category_<?php echo str_replace(' ', '_', $single['name']); ?>"><?php echo $single['name']; ?></label>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                        </br>
                        </br>		
                        <div class="wp_post_filter-list-group">
                            <div class="wp_post_filter-category-title"><?php echo $second_category_title; ?></div>
                            <div style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                            <?php foreach( $second_category as $single): ?>
                                <div class="wp_post_filter-list-group-item checkbox">
                                    <input type="checkbox" class="wp_post_filter-common_selector second_category" value="<?php echo $single['term_id']; ?>" id="checkbox_wp_post_filter_choose_second_category_<?php echo str_replace(' ', '_', $single['name']); ?>">
                                    <label for="checkbox_wp_post_filter_choose_second_category_<?php echo str_replace(' ', '_', $single['name']); ?>"><?php echo $single['name']; ?></label>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                        </br>
                        </br>

                    </div>

                    <div class="wp_post_filter-md-9">
                        <div class="wp_post_filter-row wp_post_filter_data" ></div>
                    </div>
                </div>

            </div>
            

            <?php
            $output = ob_get_clean();
            return $output;
            wp_reset_query();
        }

        function wp_post_filter_admin_area(){
            echo '<style>
                    .post_filter_award_activity_deadline {
                        width: 100%;
                    }
            </style>';
            }

        function wp_post_filter_add_custom_meta_box() {
            
            add_meta_box(
                'smw_add_sale_price',
                esc_html__( 'Post Filter Settings', 'wp_post_filter' ),
                array($this, 'post_filter_settings_callback'),
                'post',
                'side'
            );
        }

        function post_filter_settings_callback( $post ){

            // $wp_post_filter_choose_first_category = array();
            // if ( get_option( 'wp_post_filter_choose_first_category' ) !== false ) {
            //     $wp_post_filter_choose_first_category = get_option( 'wp_post_filter_choose_first_category');
            // }
            // $wp_post_filter_choose_second_category = array();
            // if ( get_option( 'wp_post_filter_choose_second_category' ) !== false ) {
            //     $wp_post_filter_choose_second_category = get_option( 'wp_post_filter_choose_second_category');
            // }
            // $wp_post_filter_choose_all_category = array_merge( $wp_post_filter_choose_first_category, $wp_post_filter_choose_second_category );
            $args = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'ABC'
            );
            $all_post = new WP_Query( $args );

            $post_deadline   = esc_attr(get_post_meta( $post->ID, 'post_deadline', true ));
            $post_award   = esc_attr(get_post_meta( $post->ID, 'post_award', true ));

            $post_filter_award_coming_soon   = esc_attr(get_post_meta( $post->ID, 'post_filter_award_coming_soon', true ));
            $post_filter_post_child   = esc_attr(get_post_meta( $post->ID, 'post_filter_post_child', true ));
            $post_filter_post_author_company   = esc_attr(get_post_meta( $post->ID, 'post_filter_post_author_company', true ));
            $checked = ( $post_filter_award_coming_soon == 1 ) ? 'checked' : '';


            //Post Coming soon
            echo '<br/>';
            echo '<input type="hidden" name="post_filter_award_coming_soon" value="0">';
            echo '<input type="checkbox" name="post_filter_award_coming_soon" id="post_filter_award_coming_soon" value="1" ' . $checked . '>';
            echo '<label for="post_filter_award_coming_soon">Coming soon</label>';
            echo '<br/>';
            echo '<br/>';

            //Post Deadline
            echo '<label for="post_deadline">Post Deadline</label>';
            echo '<input class="post_filter_award_activity_deadline" type="date" name="post_deadline" value="' . $post_deadline . '"></input>';
            echo '<br/>';
            echo '<br/>';

            //Post Award
            echo '<label for="post_award">Post Award</label>';
            echo '<input class="post_filter_award_activity_deadline" id="post_award" type="text" name="post_award" placeholder="Post Award" value="' . $post_award . '"></input>';
            echo '<br/>';
            echo '<br/>';

            //Post Child
            echo '<label for="post_child">Post Child</label>';
            echo '<select name="post_filter_post_child" class="post_filter_post_child" id="post_child">';
            echo '<option value="" >Select the Child</option>';
            foreach( $all_post->posts as $sp){
                $post_id = $sp->ID;
                if( $post->ID != $post_id ){
                    $selected = ( $post_filter_post_child == $post_id ) ? 'selected' : '';
                    echo '<option ' . $selected . ' value="' . $post_id . '">' . $sp->post_title . '</option>';
                }
            }
            echo '</select>';
            echo '<br/>';
            echo '<br/>';
            //Post Author for Company
            echo '<label for="post_author_company">Post Author for Company</label>';
            echo '<select name="post_filter_post_author_company" class="post_filter_post_child" id="post_author_company">';
            echo '<option value="" >Select the Child</option>';
            $args1 = array(
                'role' => 'um_company',
                'orderby' => 'user_nicename',
                'order' => 'ASC'
            );
            $um_company = get_users($args1);
            foreach ($um_company as $user) {
                    $selected = ( $post_filter_post_author_company == $user->ID ) ? 'selected' : '';
                    echo '<option ' . $selected . ' value="' . $user->ID . '">' . $user->display_name . '</option>';
            }
            echo '</select>';
            echo '<br/>';
        }


        /**
         * When the post is saved, saves our custom data.
         *
         * @param int $post_id The ID of the post being saved.
         */
        function wp_post_filter_save_meta_box_data( $post_id ) {
            
            if ( get_post_type( $post_id ) == 'post' ) {
                if ( get_post_status ( $post_id ) == 'publish' ) {
                    
                    // Sanitize user input.
                    //$my_data_text_post_deadline = '';
                    if(isset($_POST['post_deadline'])  ){
                        $my_data_text_post_deadline = sanitize_text_field( $_POST['post_deadline'] );
                        update_post_meta( $post_id, 'post_deadline', $my_data_text_post_deadline );
                    }
                    //$my_data_text_post_award = '';
                    if(isset($_POST['post_award'])  ){
                        $my_data_text_post_award 	= sanitize_text_field( $_POST['post_award'] );
                        update_post_meta( $post_id, 'post_award', $my_data_text_post_award );
                    }
                    //$my_data_text_post_filter_award_coming_soon = '';
                    if(isset($_POST['post_filter_award_coming_soon'])  ){
                        $my_data_text_post_filter_award_coming_soon 	= sanitize_text_field( $_POST['post_filter_award_coming_soon'] );
                        update_post_meta( $post_id, 'post_filter_award_coming_soon', $my_data_text_post_filter_award_coming_soon );
                    }

                    if(isset($_POST['post_filter_post_child'])  ){
                        $my_data_text_post_filter_post_parent 	= sanitize_text_field( $_POST['post_filter_post_child'] );
                        update_post_meta( $post_id, 'post_filter_post_child', $my_data_text_post_filter_post_parent );
                    }

                    if(isset($_POST['post_filter_post_author_company'])  ){
                        $my_data_text_post_filter_post_filter_post_author_company 	= sanitize_text_field( $_POST['post_filter_post_author_company'] );
                        update_post_meta( $post_id, 'post_filter_post_author_company', $my_data_text_post_filter_post_filter_post_author_company );
                    }

                    // Update the meta field in the database.
                }
            }
        }
        
        // ajax
        function wp_post_filterAjax(){
            ob_start();

            
            $wp_post_filter_choose_first_category = array();
            if ( get_option( 'wp_post_filter_choose_first_category' ) !== false ) {
                $wp_post_filter_choose_first_category = get_option( 'wp_post_filter_choose_first_category');
            }
            $wp_post_filter_choose_second_category = array();
            if ( get_option( 'wp_post_filter_choose_second_category' ) !== false ) {
                $wp_post_filter_choose_second_category = get_option( 'wp_post_filter_choose_second_category');
            }
            $wp_post_filter_choose_all_category = array_merge( $wp_post_filter_choose_first_category, $wp_post_filter_choose_second_category );

            $first_category = ( empty($_POST['first_category']) ) ? array() : $_POST['first_category'];
            $second_category = ( empty($_POST['second_category']) ) ? array() : $_POST['second_category'];
            $pagination_page_id = ( empty($_POST['pagination_page_id']) ) ? 1 : $_POST['pagination_page_id'];
            // $all_category = array_merge( $first_category, $second_category );

            if( empty( $first_category ) AND empty( $second_category ) ){
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
            }elseif( !empty( $first_category ) AND !empty( $second_category ) ){
                $args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'ABC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $first_category,
                        ),
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $second_category,
                        ),
                    )
                );
            }elseif( !empty( $first_category ) AND empty( $second_category ) ){
                $args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'ABC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $first_category,
                        ),
                    )
                );
            }elseif( empty( $first_category ) AND !empty( $second_category ) ){
                $args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'ABC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'id',
                            'terms' => $second_category,
                        ),
                    )
                );
            }

            $all_post = new WP_Query( $args );

            // echo '<pre>';
            // print_r($first_category);
            // echo '</pre>';
            $pagination = 0;
            foreach( $all_post->posts as $single ){
                $pagination++;
                $pagination_id = ceil($pagination / 4);
                $pagination_style = ( $pagination_page_id == $pagination_id ) ? '' : 'style="display: none;"';

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

                <div class="wp_post_filter-content" <?php echo $pagination_style; ?>>
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

            $paged = $pagination_page_id;
            $pages = ceil($pagination / 4);
            // $pages = 1000;
            $range = 3;
            $showitems = ($range * 2)+1;  
        
            if(1 != $pages)
            {
                ?>
                <div class="pagination-cover"><div class="pagination"><p class="pagination_page_and_pages">Page <span class="pagination_paged"><?php echo $paged; ?></span> of <span class="pagination_pages"><?php echo $pages; ?></span></p>

                <?php
                if($paged > 2 && $paged > $range+1 ){
                    ?>
                    <div class="pagination_page_id" data-pagination_page_id="<?php echo 1; ?>">&laquo; First</div>
                    <?php
                }
                if($paged > 1 ){
                    ?>
                    <div class="pagination_page_id" data-pagination_page_id="<?php echo $paged - 1; ?>">&lsaquo; Previous</div>
                    <?php
                }
        
                for ($i=1; $i <= $pages; $i++)
                {
                    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                    {
                        if( $paged == $i ){
                            ?>
                            <div class="pagination_page_id current" data-pagination_page_id="<?php echo $i; ?>"><?php echo $i; ?></div>
                            <?php
                        }else{
                            ?>
                            <div class="pagination_page_id" data-pagination_page_id="<?php echo $i; ?>" class="inactive"><?php echo $i; ?></div>
                            <?php
                        }
                    }
                }
        
                if($paged < $pages){
                    ?>
                    <div class="pagination_page_id" data-pagination_page_id="<?php echo $paged + 1; ?>">Next &rsaquo;</div>
                    <?php
                } 
                if($paged < $pages-1 &&  $paged+$range-1 < $pages ){
                    ?>
                    <div class="pagination_page_id" data-pagination_page_id="<?php echo $pages; ?>">Last &raquo;</div>
                    <?php
                }
                ?>
                </div></div>
                <?php
            }


            // echo '<pre>';
            // print_r($all_post->posts);
            // echo '</pre>';

            
            $output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'output' => $output
                )
            );
            die(); 
        }






    } // End Class
} // End Class check if exist / not