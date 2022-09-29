<?php
/**
 * Plugin Name: Vgen Challenge
 * Plugin URI: https://www.facebook.com/abdul.h.jony1/
 * Description: Connects "Wp Post Filter" Challenge and Analytics functionality. 
 * Version: 1.0.0
 * Author: Abdul Hakim
 * Author URI: https://www.facebook.com/abdul.h.jony1/
 * Text Domain: vgen_challenge
 * Domain Path: /languages
 * Requires at least: 4.0
 * Tested up to: 4.8
 *
 * @package     vgen_challenge
 * @category 	Core
 * @author 		Abdul Hakim
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
include_once(ABSPATH.'wp-admin/includes/plugin.php');
define('vgen_challengeDIR', plugin_dir_path( __FILE__ ));
define('vgen_challengeURL', plugin_dir_url( __FILE__ ));

/*
* Show notice if Wp Post Filter aren't installed
*/
function vgen_challenge_admin_notice(){
    echo sprintf('<div class="notice notice-warning is-dismissible">
        <p>%s</p>
    </div>', __('"Wp Post Filter" is required for "Vgen Challenge" Plugin.', 'vgen_challenge'));
}

if ( is_plugin_active( 'wp_post_filter/wp_post_filter.php' ) ) {
    require_once(vgen_challengeDIR . 'inc/class.php');
    new vgen_challengeClass;
}else{
    add_action('admin_notices', 'vgen_challenge_admin_notice');
}