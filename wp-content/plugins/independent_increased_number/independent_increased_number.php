<?php
/**
 * Plugin Name: Independent Increased Number
 * Plugin URI: https://www.facebook.com/abdul.h.jony1/
 * Description: Independent Increased Number. 
 * Version: 1.0.0
 * Author: Abdul Hakim
 * Author URI: https://www.facebook.com/abdul.h.jony1/
 * Text Domain: independent_increased_number
 * Domain Path: /languages
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * @package     independent_increased_number
 * @category 	Core
 * @author 		Abdul Hakim
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('independent_increased_numberDIR', plugin_dir_path( __FILE__ ));
define('independent_increased_numberURL', plugin_dir_url( __FILE__ ));

require_once(independent_increased_numberDIR . 'inc/class.php');

new independent_increased_numberClass;
