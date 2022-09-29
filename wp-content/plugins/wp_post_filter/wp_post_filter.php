<?php
/**
 * Plugin Name: Wp Post Filter
 * Plugin URI: https://www.facebook.com/abdul.h.jony1/
 * Description: Wp Post Filter. Shortcode Used it: [wp-post-filter]
 * Version: 1.0.0
 * Author: Abdul Hakim
 * Author URI: https://www.facebook.com/abdul.h.jony1/
 * Text Domain: wp_post_filter
 * Domain Path: /languages
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * @package     wp_post_filter
 * @category 	Core
 * @author 		Abdul Hakim
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define('wp_post_filterDIR', plugin_dir_path( __FILE__ ));
define('wp_post_filterURL', plugin_dir_url( __FILE__ ));

require_once(wp_post_filterDIR . 'inc/class.php');

new wp_post_filterClass;
