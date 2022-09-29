<?php
/*
* request_a_quote Class 
*/

if (!class_exists('request_a_quoteClass')) {
    class request_a_quoteClass{
        public $plugin_url;
        public $plugin_dir;
        public $wpdb;
        public $option_tbl; 
        
        /**Plugin init action**/ 
        public function __construct() {
            global $wpdb;
            $this->plugin_url 				= request_a_quoteURL;
            $this->plugin_dir 				= request_a_quoteDIR;
            $this->wpdb 					= $wpdb;	
            $this->option_tbl               = $this->wpdb->prefix . 'options';
            $this->request_a_quote_tbl      = $this->wpdb->prefix . 'request_a_quote_tbl';
         
            $this->init();
        }

        private function init(){

            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'request_a_quote_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'request_a_quote_frontend_script') );

            //Add Menu Options
            add_action('admin_menu', array($this, 'request_a_quote_admin_menu_function'));

            //Font Option
            add_action('wp_footer', array($this, 'request_a_quote_fontoption') );
            
            add_action('wp_ajax_nopriv_request_a_quote_sendajax', array($this, 'request_a_quote_sendajax') );
            add_action( 'wp_ajax_request_a_quote_sendajax', array($this, 'request_a_quote_sendajax') );

            //data save 
            add_action('admin_init', array($this, 'request_a_quote_save_create_db'));
        }

        function request_a_quote_save_create_db() {
            global $wpdb;
            $charset_collate = $this->wpdb->get_charset_collate();

            $table_name = $this->request_a_quote_tbl;

            $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 
                id INT(20) NOT NULL AUTO_INCREMENT,
                names VARCHAR(255) NOT NULL,
                title VARCHAR(255) NOT NULL,
                project_status VARCHAR(255) NOT NULL,
                phone_number VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                services VARCHAR(255) NOT NULL,
                budget VARCHAR(255) NOT NULL,
                messages VARCHAR(255) NOT NULL,
                file_ids VARCHAR(255) NOT NULL,
                insert_time DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY id (id)
                ) $charset_collate;";
                
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            // DROP TABLE `wp_all_pdf_files`
        }


        /*
        * Appointment backend Script
        */
        function request_a_quote_backend_script(){
            wp_enqueue_style( 'b_request_a_quoteCSS', $this->plugin_url . 'asset/css/request_a_quote_backend.css', array(), true, 'all' );
            wp_enqueue_script( 'b_request_a_quoteJS', $this->plugin_url . 'asset/js/request_a_quote_backend.js', array(), true );
            
            wp_enqueue_script( 'dataTableJS', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js', array(), time(), true);
            wp_enqueue_style( 'dataTableCSS', 'https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css', array(), true, 'all' );
        }

        /*
        * Appointment frontend Script
        */
        function request_a_quote_frontend_script(){
            wp_enqueue_style( 'f_request_a_quoteCSS', $this->plugin_url . 'asset/css/request_a_quote_frontend.css', array(), true, 'all' );
            wp_enqueue_script('f_jqueryJS', $this->plugin_url . 'asset/js/jquery-3.4.1.min.js', array('jquery'), time(), true);
            wp_enqueue_script('f_request_a_quoteJS', $this->plugin_url . 'asset/js/request_a_quote_frontend.js', array('jquery'), time(), true);
            wp_localize_script( 'f_request_a_quoteJS', 'raquoteAjax', 
            array(
                'ajax' => admin_url( 'admin-ajax.php' ),
                //'job_search_by_postal_code_checkbox_option' => $job_search_by_postal_code_checkbox_option,
                )
            );
        }

        function request_a_quote_admin_menu_function(){
            add_menu_page( 'Request a Quote', 'Request a Quote', 'read', 'request-a-quote', array($this, 'submenufunction'), $this->plugin_url . 'asset/css/images/filter.png', 50 );
            add_submenu_page( 'request-a-quote', 'Settings', 'Settings', 'read', 'request-a-quote-settings', array( $this, 'request_a_quote_settingsfunction' ) );
        }

        function submenufunction(){
            global $wpdb;
            if (isset($_POST['status'])) {
                if( $_POST['status'] == 'delete' ){
                    $id = $_POST['file_id'];
                    $table_name = $this->request_a_quote_tbl;
                    $file_ids_value = $this->wpdb->get_row( "SELECT `file_ids` FROM $table_name WHERE `id` = $id" );
                    $file_ids_values = $file_ids_value->file_ids;
                    $file_id_array = explode("|||",$file_ids_values);
                    foreach ($file_id_array as $single_file_id){
                        wp_delete_attachment( $single_file_id, true );
                    }
                    $delete = $this->wpdb->delete(
                        $table_name,
                        array('id' => $id),
                        array('%d')        
                    );
                }
            }
            ?>

            <div class="request_a_quote-submenu">
                <div class="request_a_quote-title-csv">
                    <div class="request_a_quote-menu-title">
                        <h1><?php _e('Request a Quote', 'request_a_quote'); ?></h1>
                    </div>
                </div>
                <!-- Settings -->
                <div class="request_a_quote">

                    <div id="create_request_a_quote" class="tabcontent" style="display:block;">
                        <div class="settingsInner">

                            <table class="table request_a_quote-table jquerydatatable">
                            
                                <thead>
                                    <tr class="request_a_quote-heading-wrapper">
                                        <th><?php _e('No', 'request_a_quote'); ?></th>
                                        <th><?php _e('Project Description', 'request_a_quote'); ?></th>
                                        <th><?php _e('Contact', 'request_a_quote'); ?></th>
                                        <th><?php _e('File Link', 'request_a_quote'); ?></th>
                                        <th><?php _e('Action', 'request_a_quote'); ?></th>
                                    </tr>
                                </thead>
                                <?php
                                    $table_name = $this->request_a_quote_tbl;
                                    $qry = $this->wpdb->get_results( "SELECT * FROM $table_name ssn ORDER BY ssn.`insert_time` DESC", OBJECT);
                                    $all_files = json_decode(json_encode($qry), true);
                                ?>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($all_files as $single_file){
                                        $insert_time = $single_file['insert_time'];
                                        $insert_time_timestamp = strtotime($insert_time);
                                        $date = date('F j, Y, g:i a', $insert_time_timestamp);

                                        $services = $single_file['services'];
                                        $services_array = explode("|||",$services);

                                        $file_ids = $single_file['file_ids'];
                                        $file_id_array = explode("|||",$file_ids);

                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td class="request_a_quote_project_description_td">
                                                <div class="request_a_quote_project_description"><span>Title: </span><?php echo $single_file['title']; ?></div>
                                                <div class="request_a_quote_project_description"><span>Status: </span><?php echo $single_file['project_status']; ?></div>
                                                <div class="request_a_quote_project_description">
                                                    <span>Services: </span>
                                                    <ul>
                                                        <?php
                                                        $j = 1;
                                                        foreach ($services_array as $single_service){ 
                                                        ?>
                                                        <li><?php echo $j . '. ' . $single_service; ?></li>
                                                        <?php
                                                        $j++;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div class="request_a_quote_project_description"><span>Message: </span><?php echo $single_file['messages']; ?></div>
                                            </td>
                                            <td class="request_a_quote_project_contact_td">
                                                <div class="request_a_quote_project_contact"><span>Submit Time: </span><?php echo $date; ?></div>
                                                <div class="request_a_quote_project_contact request_a_quote_project_budget"><span>Budget: </span><?php echo $single_file['budget']; ?></div>
                                                <div class="request_a_quote_project_contact"><span>Name: </span><?php echo $single_file['names']; ?></div>
                                                <div class="request_a_quote_project_contact"><span>Number: </span><?php echo $single_file['phone_number']; ?></div>
                                                <div class="request_a_quote_project_contact"><span>Mail: </span><?php echo $single_file['email']; ?></div>
                                            </td>
                                            <td>
                                                <div class="request_a_quote_project_description">
                                                    <span>Upload Files: </span>
                                                    <ul>
                                                        <?php
                                                        $f = 1;
                                                        foreach ($file_id_array as $single_file_id){
                                                            $single_file_url = wp_get_attachment_url( $single_file_id );
                                                            $single_file_title = get_the_title( $single_file_id );
                                                        ?>
                                                        <li><?php echo $f . '.  <a target="_blank" href="' . $single_file_url . '">' . $single_file_title; ?></a></li>
                                                        <?php
                                                        $f++;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td>
                                                <form method="POST">
                                                    <input type="hidden" name="file_id" value="<?php echo $single_file['id']; ?>" />
                                                    <button class="request_a_quote_project_delete" value="delete" name="status"><?php _e('Delete', 'request_a_quote'); ?></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <?php
        }

        // update Settings
        public function updateSettings($data){
            foreach($data as $k => $sd) update_option( $k, $sd );
        }
        function request_a_quote_settingsfunction(){
            if(isset($_POST['request_a_quote_submit_btn'])) $this->updateSettings($_POST);
            $all_taxonomies = get_terms( array(
                'taxonomy'   => 'category',
                'hide_empty' => false
            ) );
            ?>
            <div class="request_a_quote-submenu">
                <div class="request_a_quote-title-csv">
                    <div class="request_a_quote-submenu-title">
                        <h1><?php _e('Request a Quote Settings', 'request_a_quote'); ?></h1>
                    </div>
                </div>
                <div class="request_a_quote">
                    <div id="create_request_a_quote" class="tabcontent" style="display:block;">
                        <div class="settingsInner">
                            <form id="request_a_quote_submit" method="post" action="">
                                <table class="request_a_quote-data-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left"><?php _e('Type your email where you get this request', 'request_a_quote' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $raq_receive_mail = '';
                                                if ( get_option( 'raq_receive_mail' ) !== false ) {
                                                    $raq_receive_mail = get_option( 'raq_receive_mail');
                                                }
                                                ?>
                                                <input type="email" class="raq_receive_mail" name="raq_receive_mail" value="<?php echo $raq_receive_mail; ?>" placeholder="Type your email">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"></th>
                                            <td class="text-left">
                                                <input type="submit" class="request_a_quote-submit-btn" name="request_a_quote_submit_btn" value="Submit" style="float:left">
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
        
        function request_a_quote_fontoption(){
        ?>
        <div class="request_a_quote_cover">
            <div class="raq_open-icon"></div>
            <div class="raq_open-button">REQUEST A QUOTE</div>
            <div class="raq_open">
                <div class="raq_open_filter-loder">
                    <div class="raq_open_filter-gif">
                        <div class="gifInnter">
                            <img src="<?php echo $this->plugin_url ?>/asset/css/images/loader.gif" alt="loding..." />
                        </div>
                    </div>
                </div>
                <div class="raq_open_cover">
                <div class="raq_open-title">REQUEST A QUOTE</div>
                <form id="raq_open_submit" method="post" action="" enctype="multipart/form-data">
                    <label class="raq_open-label" for="raq_open_input_name"><?php _e('Name', 'request_a_quote' ); ?></label>
                    <input type="text" class="raq_open-input" id="raq_open_input_name" name="raq_open_input_name" placeholder="Name" required>
                    <label class="raq_open-label" for="raq_open_input_title"><?php _e('Title', 'request_a_quote' ); ?></label>
                    <input type="text" class="raq_open-input" id="raq_open_input_title" name="raq_open_input_title" placeholder="Title" required>
                    <label class="raq_open-label" for="raq_open_input_project_status"><?php _e('Project Status', 'request_a_quote' ); ?></label>
                    <select name="raq_open_input_project_status" class="raq_open-input-selector" id="raq_open_input_project_status">
                        <option class="raq_open_input_option_project_status" value="Bidding on project with combined services">Bidding on project with combined services</option>
                        <option class="raq_open_input_option_project_status" value="Project has been awarded">Project has been awarded</option>
                        <option class="raq_open_input_option_project_status" value="Require services now">Require services now</option>
                        <option class="raq_open_input_option_project_status" value="Other">Other</option>
                    </select>
                    <label class="raq_open-label" for="raq_open_input_phone_number"><?php _e('Phone Number', 'request_a_quote' ); ?></label>
                    <input type="tel" class="raq_open-input" id="raq_open_input_phone_number" name="raq_open_input_phone_number" placeholder="Phone Number" required>
                    <label class="raq_open-label" for="raq_open_input_email"><?php _e('Email', 'request_a_quote' ); ?></label>
                    <input type="email" class="raq_open-input" id="raq_open_input_email" name="raq_open_input_email" placeholder="Email" required>
                    <label class="raq_open-label" for="raq_open_input_services"><?php _e('Choose Your Service(s)', 'request_a_quote' ); ?></label>
                    <div class="raq_open_input_services_cover">
                    <?php 
                        $raq_open_input_checkbox_values = array('3D Laser Scanning', 'Reverse Engineering', 'Design and Modeling', 'Equipment Upgrades', 'Turn-key Design to lnstall', 'Spatial Programming', '3D Human Scanning', 'Augmented Reality');
                        foreach($raq_open_input_checkbox_values as $value){
                            echo '<input type="checkbox" class="raq_open_input_checkbox" name="raq_open_input_checkbox[]" id="' . str_replace(" ","_",strtolower($value)) . '" value="' . $value . '">';
                            echo '<label class="raq_open_input_checkbox_label" for="' . str_replace(" ","_",strtolower($value)) . '"> ' . $value . '</label><br>';
                        }
                    ?>
                    </div>
                    <label class="raq_open-label" for="raq_open_input_budget"><?php _e('Expected Budget', 'request_a_quote' ); ?></label>
                    <select name="raq_open_input_budget" class="raq_open-input-selector" id="raq_open_input_budget">
                        <option class="raq_open_input_option" value="$0 - $1,000">$0 - $1,000</option>
                        <option class="raq_open_input_option" value="$1,000 - $5,000">$1,000 - $5,000</option>
                        <option class="raq_open_input_option" value="$5,000 - $10,000">$5,000 - $10,000</option>
                        <option class="raq_open_input_option" value="$10,000 - $20,000">$10,000 - $20,000</option>
                        <option class="raq_open_input_option" value="$20,000 - $100,000">$20,000 - $100,000</option>
                        <option class="raq_open_input_option" value="$100,000 - $500,000">$100,000 - $500,000</option>
                        <option class="raq_open_input_option" value="$500,000 - $5,000,000">$500,000 - $5,000,000</option>
                        <option class="raq_open_input_option" value="$5,000,000 plus">$5,000,000 plus</option>
                    </select>
                    <label class="raq_open-label" for="raq_open_input_Message"><?php _e('Message', 'request_a_quote' ); ?></label>
                    <textarea maxlength="10000" name="raq_open_input_Message" type="textarea" class="raq_open_input_Message" placeholder="Message" required></textarea>
                    <div class="raq_open-drag-area">
                        <div class="raq_open-upload-icon">
                        </div>
                        <header class="raq_open-pc" style="display: block;">Drag & Drop to Upload File</header>
                        <header class="raq_open-mobile" style="display: none;">Take a picture</header>
                        <span>OR</span>
                        <div class="raq_open-upload-button">Browse File</div>
                        <input type="file" id="myfile" class="myfile" name="up_myfile[]" accept="image/*;capture=camera" multiple="multiple" hidden>
                    </div>
                    <input type="file" id="camera_myfile" class="myfile" name="up_myfile[]" accept="image/*;capture=camera" capture="camera" multiple="multiple" hidden>
                    <div class="raq_open-file_list"></div>
                    <div class="progress" style="display: none;">
                        <div class="progress-bar"></div>
                    </div>
                    <div id="uploadStatus"></div>
                    <input type="submit" class="raq_open_input_submit-btn" name="raq_open_input_submit_btn" value="Send" >
                </form>
            </div>
            </div>
        </div>
        <?php
        }

        function request_a_quote_sendajax(){
            // $raq_open_input_name                     = $_POST['raq_open_input_name'];
            // $raq_open_input_title                    = $_POST['raq_open_input_title'];
            // $raq_open_input_project_status           = $_POST['raq_open_input_project_status'];
            // $raq_open_input_phone_number             = $_POST['raq_open_input_phone_number'];
            // $raq_open_input_email                    = $_POST['raq_open_input_email'];
            // $raq_open_input_checkbox                 = $_POST['raq_open_input_checkbox'];
            // $raq_open_input_budget                   = $_POST['raq_open_input_budget'];
            // $raq_open_input_Message                  = $_POST['raq_open_input_Message'];
            $raq_open_input_name = 'empty!';
            if( !empty($_POST['raq_open_input_name']) ){
                $raq_open_input_name                     = $_POST['raq_open_input_name'];
            }
            $raq_open_input_title = 'empty!';
            if( !empty($_POST['raq_open_input_title']) ){
                $raq_open_input_title                = $_POST['raq_open_input_title'];
            }
            $raq_open_input_project_status           = $_POST['raq_open_input_project_status'];
            $raq_open_input_phone_number = 'empty!';
            if( !empty($_POST['raq_open_input_phone_number']) ){
                $raq_open_input_phone_number         = $_POST['raq_open_input_phone_number'];
            }
            $raq_open_input_email = 'empty!';
            if( !empty($_POST['raq_open_input_email']) ){
                $raq_open_input_email                = $_POST['raq_open_input_email'];
            }
            $raq_open_input_checkbox                 = $_POST['raq_open_input_checkbox'];
            $raq_open_input_budget                   = $_POST['raq_open_input_budget'];
            $raq_open_input_Message = 'empty!';
            if( !empty($_POST['raq_open_input_Message']) ){
                $raq_open_input_Message              = $_POST['raq_open_input_Message'];
            }
            $raq_open_input_checkbox_array = array();
            $keys = 1;
            foreach ($raq_open_input_checkbox as $key => $single_file){
                $email_list_single = '
                <li style="list-style: none;">
                    <p style="margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly; font-size: 17px; font-family: roboto,;">
                        <span style="font-family: helvetica, arial, sans-serif;">' . $keys . '. ' . $single_file . '</span>
                    </p>
                </li>';
                array_push( $raq_open_input_checkbox_array, $email_list_single );
                $keys++;
            }
            $raq_open_input_checkbox_implode = implode( " ", $raq_open_input_checkbox_array );
            $challenge_submit_body_for_user_us = '<table class="es-wrapper" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; padding: 0; margin: 0; width: 100%; height: 100%; background-repeat: repeat; background-position: left top;" width="100%" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr style="border-collapse: collapse;">
                        <td style="padding: 0px; margin: 0px; height: 24px;" align="left">
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Name: ' . $raq_open_input_name . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Title: ' . $raq_open_input_title . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Project Status: ' . $raq_open_input_project_status . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Phone Number: ' . $raq_open_input_phone_number . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Email: ' . $raq_open_input_email . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Service(s):</span></p>
                        <ul style="margin: 0px;">' . $raq_open_input_checkbox_implode . '
                        </ul>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Expected Budget: ' . $raq_open_input_budget . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Message: ' . $raq_open_input_Message . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Upload File:</span></p>
                        </td>
                    </tr>
                </tbody>
            </table>';


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
            $admin_mail = $raq_open_input_email;
            
            $raq_receive_mail = 'ahjony.bd@gmail.com';
            if ( get_option( 'raq_receive_mail' ) !== false ) {
                $raq_receive_mail = get_option( 'raq_receive_mail');
            }
            $email = $raq_receive_mail;
            $subject_1 = 'REQUEST A QUOTE By ' . $raq_open_input_title;


            $body_1 = stripslashes( $challenge_submit_body_for_user_us );
            $headers_1 = 'From: ' . $admin_mail . "\r\n" .
            'Reply-To: ' . $admin_mail . "\r\n";
            $headers_1 .= 'MIME-Version: 1.0' . "\r\n";
            $headers_1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1, $attachments);
            echo json_encode(
                array(
                    'message' => 'success',
                )
            );
            die();
        }


    } // End Class
} // End Class check if exist / not

