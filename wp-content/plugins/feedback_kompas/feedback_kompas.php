<?php
/**
 * Plugin Name: Feedback Kompas 360
 * Plugin URI: https://www.facebook.com/abdul.h.jony1/
 * Description: Feedback Kompas 360. 
 * Version: 1.0.0
 * Author: Abdul Hakim
 * Author URI: https://www.facebook.com/abdul.h.jony1/
 * Text Domain: feedback_kompas
 * Domain Path: /languages
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * @package     feedback_kompas
 * @category 	Core
 * @author 		Abdul Hakim
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('feedback_kompasDIR', plugin_dir_path( __FILE__ ));
define('feedback_kompasURL', plugin_dir_url( __FILE__ ));

require_once(feedback_kompasDIR . 'inc/class.php');

new feedback_kompasClass;
