<?php
/*
* independent_increased_number Class 
*/

if (!class_exists('independent_increased_numberClass')) {
    class independent_increased_numberClass{
        public $plugin_url;
        public $plugin_dir;
        public $wpdb;
        public $option_tbl; 
        
        /**Plugin init action**/ 
        public function __construct() {
            global $wpdb;
            $this->plugin_url 				   = independent_increased_numberURL;
            $this->plugin_dir 				   = independent_increased_numberDIR;
            $this->wpdb 					   = $wpdb;	
            $this->option_tbl                  = $this->wpdb->prefix . 'options';
            $this->independent_increased_data  = $this->wpdb->prefix . 'independent_increased_data';
         
            $this->init();
        }

        private function init(){

            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'independent_increased_number_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'independent_increased_number_frontend_script') );
            
            add_action('admin_init', array($this, 'independent_increased_number_database') );

            //Shortcode
            add_shortcode( 'independent-increased-number', array($this, 'independent_increased_numberShortcodeCallback') );

            /* Send data ajax */ 
            add_action('wp_ajax_nopriv_independent_increased_number_filterAjax', array($this, 'independent_increased_number_filterAjax'));
            add_action( 'wp_ajax_independent_increased_number_filterAjax', array($this, 'independent_increased_number_filterAjax') );

            //add_action( 'template_redirect', array($this, 'apicall') );
            add_action( 'rest_api_init', array($this, 'rest_api_function') );
        }
        
        function independent_increased_number_database(){
            global $wpdb;
            $charset_collate = $this->wpdb->get_charset_collate();

            $table_name = $this->independent_increased_data;

            //$this->wpdb->query("DROP TABLE $table_name");
            $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 
                id INT(20) NOT NULL AUTO_INCREMENT,
                iid_title VARCHAR(255) NOT NULL,
                iid_description VARCHAR(255) NOT NULL,
                iid_value_one VARCHAR(255) NOT NULL,
                iid_value_two VARCHAR(255) NOT NULL,
                insert_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY id (id)
                ) $charset_collate;";
                
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        function rest_api_function(){
            register_rest_route( 'v1', '/independent-increment', array(
                'methods' => 'GET',
                'callback' => array($this, 'my_awesome_func'),
              ) );
        }
        function my_awesome_func() {
            global $wpdb;
            $table_name = $this->independent_increased_data;
            $insert = $this->wpdb->insert(
                $table_name,
                array(
                    'iid_title' => '',
                    'iid_description' => '',
                    'iid_value_one' => '',
                    'iid_value_two' => '',
                ),
                array('%s', '%s', '%s', '%s')
            );
            $lastid = $this->wpdb->insert_id;
            $increment = ( 100000 + $lastid );
            echo $increment;
            // $independent_increased_number_increment = get_option( 'independent_increased_number_increment' );
            
            // if( empty($independent_increased_number_increment) ){
            //     $increment = 100000;
            // }else{
            //     $increment = $independent_increased_number_increment + 1;
            // }
            // update_option( 'independent_increased_number_increment', $increment );
        }

        function apicall() {

            $callapi = $_GET['value'];

            //https://prova.vgen.it/?API=jony
            
            if($callapi=='independent-increment'){
                require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
                //header('Content-Type: text/javascript; charset=utf8');
                header("Access-Control-Allow-Origin: *"); 
                header('Access-Control-Max-Age: 3628800');
                header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
                header("content-type: JSON");
                $independent_increased_number_increment = get_option( 'independent_increased_number_increment' );
                // echo $jony = 'jony!';
                echo json_encode($independent_increased_number_increment, JSON_PRETTY_PRINT);
                exit;
            }   
        }

        /*
        * Appointment backend Script
        */
        function independent_increased_number_backend_script(){
            wp_enqueue_style( 'b_independent_increased_numberCSS', $this->plugin_url . 'asset/css/independent_increased_number_backend.css', array(), true, 'all' );
            wp_enqueue_script( 'b_independent_increased_numberJS', $this->plugin_url . 'asset/js/independent_increased_number_backend.js', array(), true );
        }

        /*
        * Appointment frontend Script
        */
        function independent_increased_number_frontend_script(){
 
            wp_enqueue_style( 'f_independent_increased_numberCSS', $this->plugin_url . 'asset/css/independent_increased_number_frontend.css', array(), true, 'all' );
            wp_enqueue_script('f_independent_increased_numberJS', $this->plugin_url . 'asset/js/independent_increased_number_frontend.js', array('jquery'), time(), true);
            //ajax
            wp_localize_script( 'f_independent_increased_numberJS', 'independent_increased_numberAjax', 
            array(
                'ajax' => admin_url( 'admin-ajax.php' ),
            )
        );
        }


        function independent_increased_numberShortcodeCallback(){
            ob_start();
            //$independent_increased_number_increment = get_option( 'independent_increased_number_increment' );
            //$independent_increased_number_nonce = get_option( 'independent_increased_number_nonce' );
            global $wpdb;
            $table_name = $this->independent_increased_data;
            $qrys = $this->wpdb->get_results( "SELECT `id` FROM $table_name" );
            $all_donation = json_decode(json_encode($qrys), true);

            $qrys_last_id = $this->wpdb->get_row( "SELECT `id` FROM $table_name ORDER BY `id` DESC LIMIT 1" );
            $last_id = $qrys_last_id->id;
            echo '<pre>';
            print_r($all_donation);
            echo '</pre>';
            // $all_donation_count = count($all_donation);

            $independent_increased_number_increment = ( 100000 + $last_id );
            ?>
            <div class="independent_increased_number-cover">
                <!-- <div class="independent_increased_number-button">Click Here!</div> -->
                <div class="independent_increased_number-increment">Total Increment: <span><?php echo number_format($independent_increased_number_increment); ?></span></div>
                <!-- <div class="independent_increased_number-nonce">Complex Increment: <span><?php //echo $independent_increased_number_nonce; ?></span></div> -->
            </div>
            <?php
            $output = ob_get_clean();
            return $output;
            wp_reset_query();
        }

        // ajax
        function independent_increased_number_filterAjax(){
            ob_start();

            $independent_increased_number_increment = get_option( 'independent_increased_number_increment' );
            //$independent_increased_number_nonce = get_option( 'independent_increased_number_nonce' );

            if( empty($independent_increased_number_increment) ){
                $increment = 100000;
            }else{
                $increment = $independent_increased_number_increment + 1;
            }
            
            $nonce = wp_create_nonce( 'independent_increased_number'. date('y/m/d') . time() );
            update_option( 'independent_increased_number_increment', $increment );
            update_option( 'independent_increased_number_nonce', $nonce );
            
            $output = ob_get_clean();
            echo json_encode(
                array(
                    'message' => 'success',
                    'increment' => number_format($increment),
                    'nonce' => $nonce,
                )
            );
            die(); 
        }


    } // End Class
} // End Class check if exist / not
