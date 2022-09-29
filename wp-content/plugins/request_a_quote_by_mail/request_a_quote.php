<?php
/**
 * Plugin Name: Request a Quote by mail
 * Plugin URI: https://www.facebook.com/abdul.h.jony1/
 * Description: Request a Quote by mail. 
 * Version: 1.0.0
 * Author: Abdul Hakim
 * Author URI: https://www.facebook.com/abdul.h.jony1/
 * Text Domain: request_a_quote
 * Domain Path: /languages
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * @package     request_a_quote
 * @category 	Core
 * @author 		Abdul Hakim
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('request_a_quoteDIR', plugin_dir_path( __FILE__ ));
define('request_a_quoteURL', plugin_dir_url( __FILE__ ));

require_once(request_a_quoteDIR . 'inc/class.php');

new request_a_quoteClass;
