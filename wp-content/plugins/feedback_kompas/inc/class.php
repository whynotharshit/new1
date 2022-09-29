<?php
/*
* feedback_kompas Class 
*/

if (!class_exists('feedback_kompasClass')) {
    class feedback_kompasClass{
        public $plugin_url;
        public $plugin_dir;
        public $wpdb;
        public $option_tbl; 
        
        /**Plugin init action**/ 
        public function __construct() {
            global $wpdb;
            $this->plugin_url 				= feedback_kompasURL;
            $this->plugin_dir 				= feedback_kompasDIR;
            $this->wpdb 					= $wpdb;	
            $this->option_tbl               = $this->wpdb->prefix . 'options';
         
            $this->init();
        }

        private function init(){

            //Backend Script
            add_action( 'admin_enqueue_scripts', array($this, 'feedback_kompas_backend_script') );
            //Frontend Script
            add_action( 'wp_enqueue_scripts', array($this, 'feedback_kompas_frontend_script') );

            //Font Option
            add_action('wp_footer', array($this, 'feedback_kompas_fontoption') );

            //Add Menu Options
            add_action('admin_menu', array($this, 'feedback_kompas_admin_menu_function'));
            
            add_action('wp_ajax_nopriv_feedback_kompas_sendajax', array($this, 'feedback_kompas_sendajax') );
            add_action( 'wp_ajax_feedback_kompas_sendajax', array($this, 'feedback_kompas_sendajax') );
            
        }

        /*
        * Appointment backend Script
        */
        function feedback_kompas_backend_script(){
            wp_enqueue_style( 'b_feedback_kompasCSS', $this->plugin_url . 'asset/css/feedback_kompas_backend.css', array(), true, 'all' );
            wp_enqueue_script( 'b_feedback_kompasJS', $this->plugin_url . 'asset/js/feedback_kompas_backend.js', array(), true );
        }

        /*
        * Appointment frontend Script
        */
        function feedback_kompas_frontend_script(){
            wp_enqueue_style( 'f_feedback_kompasCSS', $this->plugin_url . 'asset/css/feedback_kompas_frontend.css', array(), true, 'all' );
            wp_enqueue_script('f_feedback_kompasJS', $this->plugin_url . 'asset/js/feedback_kompas_frontend.js', array('jquery'), time(), true);
            wp_localize_script( 'f_feedback_kompasJS', 'feedback_kompas_Ajax', 
            array(
                'ajax' => admin_url( 'admin-ajax.php' ),
                )
            );
        }

        function feedback_kompas_admin_menu_function(){
            add_menu_page( 'Feedback Kompas 360', 'Feedback Kompas 360', 'manage_options', 'feedback-kompas', array($this, 'settingsfunction'), $this->plugin_url . 'asset/css/images/filter.png', 50 );
        }

        // update Settings
        public function updateSettings($data){
            foreach($data as $k => $sd) update_option( $k, $sd );
        }
        function settingsfunction(){
            if(isset($_POST['feedback_kompas_submit_btn'])) $this->updateSettings($_POST);
            ?>
            <div class="feedback_kompas-submenu">
                <div class="feedback_kompas-title-csv">
                    <div class="feedback_kompas-submenu-title">
                        <h1><?php _e('Feedback Kompas 360 Settings', 'feedback_kompas'); ?></h1>
                    </div>
                </div>
                <div class="feedback_kompas">
                    <div id="create_feedback_kompas" class="tabcontent" style="display:block;">
                        <div class="settingsInner">
                            <form id="feedback_kompas_submit" method="post" action="">
                                <table class="feedback_kompas-data-table">
                                    <tbody>
                                        <tr>
                                            <th class="text-left"><?php _e('Type your email where you get this request', 'feedback_kompas' ); ?></th>
                                            <td class="text-left">
                                                <?php
                                                $feedback_kompas_receive_mail = '';
                                                if ( get_option( 'feedback_kompas_receive_mail' ) !== false ) {
                                                    $feedback_kompas_receive_mail = get_option( 'feedback_kompas_receive_mail');
                                                }
                                                ?>
                                                <input type="email" class="feedback_kompas_receive_mail" name="feedback_kompas_receive_mail" value="<?php echo $feedback_kompas_receive_mail; ?>" placeholder="Type your email">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-left"></th>
                                            <td class="text-left">
                                                <input type="submit" class="feedback_kompas-submit-btn" name="feedback_kompas_submit_btn" value="Submit" style="float:left">
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
        
        function feedback_kompas_fontoption(){
        // if(isset($_COOKIE['your_feedback_kompas_done']))
        //     {
            ?>
            <div class="feedback_kompas_start">
                <div class="modal-overlay" id="feedback_kompas_modal_happy_overlay">
                    <div class="modal">
                        <a class="close-modal">
                        <svg viewBox="0 0 20 20">
                            <path fill="#ED7D31" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                        </svg>
                        </a>
                        <div class="modal-content">
                            <div class="feedback_kompas_massage_ocver">
                                <div class="feedback_kompas_massage_happy_icon"></div>
                                <div class="feedback_kompas_massage_text">TAK – vi er glade for, at du er tilfreds Del gerne din oplevelse på</div>
                                <div class="feedback_kompas_massage">
                                    <form id="feedback_kompas_submit" method="post">
                                        <input type="email" class="feedback_kompas_Message_input" id="feedback_kompas_email" name="feedback_kompas_email" placeholder="Email" required="">
                                        <textarea name="feedback_kompas_textarea" type="textarea" class="feedback_kompas_Message_textarea" placeholder="Fortæl os, hvordan vi kan forbedre os..." spellcheck="false"></textarea>
                                        <input type="hidden" id="feedback_kompas_hidden_mas" name="feedback_kompas_hidden_mas" value="happy">
                                        <input type="submit" class="feedback_kompas_submit-btn" name="feedback_kompas_submit_btn" value="Send">
                                        <div class="progress" style="display: none;">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <div class="uploadStatus"></div>
                                    </form>
                                </div>
                                <div class="feedback_kompas_massage_icon_list">
                                    <div class="feedback_kompas_massage_trustpilot_icon"></div>
                                    <div class="feedback_kompas_massage_facbook_icon"></div>
                                    <div class="feedback_kompas_massage_google_icon"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-overlay" id="feedback_kompas_modal_unhappy_overlay">
                    <div class="modal">
                        <a class="close-modal">
                        <svg viewBox="0 0 20 20">
                            <path fill="#ED7D31" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                        </svg>
                        </a>
                        <div class="modal-content">
                            <div class="feedback_kompas_massage_ocver">
                                <div class="feedback_kompas_massage_unhappy_icon"></div>
                                <div class="feedback_kompas_massage_text">Du er ikke tilfreds, og det skal vi have gjort noget ved med det samme. Skriv venligst til os, og vi vil kontakte dig inden for 24 timer, så vi kan få løst dit problem</div>
                                <div class="feedback_kompas_massage">
                                    <form id="feedback_kompas_submit" method="post">
                                        <input type="email" class="feedback_kompas_Message_input" id="feedback_kompas_email" name="feedback_kompas_email" placeholder="Email" required="">
                                        <textarea name="feedback_kompas_textarea" type="textarea" class="feedback_kompas_Message_textarea" placeholder="Jeg er ikke tilfreds fordi…" required spellcheck="false"></textarea>
                                        <input type="hidden" id="feedback_kompas_hidden_mas" name="feedback_kompas_hidden_mas" value="unhappy">
                                        <input type="submit" class="feedback_kompas_submit-btn" name="feedback_kompas_submit_btn" value="Send">
                                        <div class="progress" style="display: none;">
                                            <div class="progress-bar"></div>
                                        </div>
                                        <div class="uploadStatus"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feedback_kompas_open_button">
                    <div class="feedback_kompas_open_icon_cover">
                        <div class="feedback_kompas_open_icon"></div>
                    </div>
                </div>
                <div class="feedback_kompas_cover">
                    <div class="feedback_kompas">
                        <div class="feedback_kompas_head">
                            <div class="feedback_kompas_company_name">Beregnet af <a href="#" class="feedback_kompas_company_link" target="_blank">Yu<span>huu</span>.dk</a></div>
                            <div class="feedback_kompas_company_feedback_percentage">97,2% tilfreds</div>
                        </div>
                        <div class="feedback_kompas_body">
                            <div class="feedback_kompas_happy">
                                <div class="feedback_kompas_title">TILFREDS</div>
                                <div class="feedback_kompas_happy_icon"></div>
                            </div>
                            <div class="feedback_kompas_unhappy">
                                <div class="feedback_kompas_title">IKKE TILFREDS</div>
                                <div class="feedback_kompas_unhappy_icon"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
            // }
        }

        function feedback_kompas_sendajax(){
            $feedback_kompas_email = 'test@gmail.com';
            if( !empty($_POST['feedback_kompas_email']) ){
                $feedback_kompas_email = $_POST['feedback_kompas_email'];
            }
            $feedback_kompas_textarea = 'empty!';
            if( !empty($_POST['feedback_kompas_textarea']) ){
                $feedback_kompas_textarea = $_POST['feedback_kompas_textarea'];
            }
            $feedback_kompas_hidden_mas = $_POST['feedback_kompas_hidden_mas'];
            if( $feedback_kompas_hidden_mas == 'happy' ){
                $clint = 'Happy Customer';
            }else{
                $clint = 'Unhappy Customer';
            }

            $mail_body = '<table class="es-wrapper" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-collapse: collapse; border-spacing: 0px; padding: 0; margin: 0; width: 100%; height: 100%; background-repeat: repeat; background-position: left top;" width="100%" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr style="border-collapse: collapse;">
                        <td style="padding: 0px; margin: 0px; height: 24px;" align="left">
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 22px;">' . $clint . '</span></p>
                        <p style="margin: 5px 0px; text-align: left; mso-line-height-alt: 24px;"><span style="font-size: 18px;">Feedback: ' . $feedback_kompas_textarea . '</span></p>
                    </tr>
                </tbody>
            </table>';

            $user_mail = $feedback_kompas_email;
            
            $feedback_kompas_receive_mail = 'ahjony.bd@gmail.com';
            if ( get_option( 'feedback_kompas_receive_mail' ) !== false ) {
                $feedback_kompas_receive_mail = get_option( 'feedback_kompas_receive_mail');
            }
            $email = $feedback_kompas_receive_mail;
            $subject_1 = 'Feedback Kompas 360 ' . $clint;


            $body_1 = stripslashes( $mail_body );
            $headers_1 = 'From: ' . $user_mail . "\r\n" .
            'Reply-To: ' . $user_mail . "\r\n";
            $headers_1 .= 'MIME-Version: 1.0' . "\r\n";
            $headers_1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

            $sent_1 = wp_mail($email, $subject_1, $body_1, $headers_1 );
            echo json_encode(
                array(
                    'message' => 'success',
                )
            );
            die();
        }

    } // End Class
} // End Class check if exist / not

