<?php
/*
Plugin Name: znayty search machine
Plugin URI: https://github.com/Maxim-us/wp-plugin-skeleton
Description: Brief description
Author: Marko Maksym
Version: 1.0
Author URI: https://github.com/Maxim-us
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Unique string - MXZSM
*/

/*
* Define MXZSM_PLUGIN_PATH
*
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\znayty-search-machine\znayty-search-machine.php
*/
if ( ! defined( 'MXZSM_PLUGIN_PATH' ) ) {

	define( 'MXZSM_PLUGIN_PATH', __FILE__ );

}

/*
* Define MXZSM_PLUGIN_URL
*
* Return http://my-domain.com/wp-content/plugins/znayty-search-machine/
*/
if ( ! defined( 'MXZSM_PLUGIN_URL' ) ) {

	define( 'MXZSM_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

}

/*
* Define MXZSM_PLUGN_BASE_NAME
*
* 	Return znayty-search-machine/znayty-search-machine.php
*/
if ( ! defined( 'MXZSM_PLUGN_BASE_NAME' ) ) {

	define( 'MXZSM_PLUGN_BASE_NAME', plugin_basename( __FILE__ ) );

}

/*
* Define MXZSM_TABLE_SLUG
*/
if ( ! defined( 'MXZSM_TABLE_SLUG' ) ) {

	define( 'MXZSM_TABLE_SLUG', 'mxzsm_table_slug' );

}

/*
* Define MXZSM_PLUGIN_ABS_PATH
* 
* E:\OpenServer\domains\my-domain.com\wp-content\plugins\znayty-search-machine/
*/
if ( ! defined( 'MXZSM_PLUGIN_ABS_PATH' ) ) {

	define( 'MXZSM_PLUGIN_ABS_PATH', dirname( MXZSM_PLUGIN_PATH ) . '/' );

}

/*
* Define MXZSM_PLUGIN_VERSION
*/
if ( ! defined( 'MXZSM_PLUGIN_VERSION' ) ) {

	// version
	define( 'MXZSM_PLUGIN_VERSION', '28.04.20' ); // Must be replaced before production on for example '1.0'

}

/*
* Define MXZSM_MAIN_MENU_SLUG
*/
if ( ! defined( 'MXZSM_MAIN_MENU_SLUG' ) ) {

	// version
	define( 'MXZSM_MAIN_MENU_SLUG', 'mxzsm-znayty-search-machine-menu' );

}

/**
 * activation|deactivation
 */
require_once plugin_dir_path( __FILE__ ) . 'install.php';

/*
* Registration hooks
*/
// Activation
register_activation_hook( __FILE__, array( 'MXZSM_Basis_Plugin_Class', 'activate' ) );

// Deactivation
register_deactivation_hook( __FILE__, array( 'MXZSM_Basis_Plugin_Class', 'deactivate' ) );


/*
* Include the main MXZSMZnaytySearchMachine class
*/
if ( ! class_exists( 'MXZSMZnaytySearchMachine' ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'includes/final-class.php';

	/*
	* Translate plugin
	*/
	add_action( 'plugins_loaded', 'mxzsm_translate' );

	function mxzsm_translate()
	{

		load_plugin_textdomain( 'mxzsm-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

}