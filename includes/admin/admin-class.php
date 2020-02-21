<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSM_Admin_Main
{

	// list of model names used in the plugin
	public $models_collection = [
		'MXZSM_Main_Page_Model'
	];

	/*
	* MXZSM_Admin_Main constructor
	*/
	public function __construct()
	{

	}

	/*
	* Additional classes
	*/
	public function mxzsm_additional_classes()
	{

		// enqueue_scripts class
		mxzsm_require_class_file_admin( 'enqueue-scripts.php' );

		MXZSM_Enqueue_Scripts::mxzsm_register();


		// CPT class
		mxzsm_require_class_file_admin( 'cpt.php' );

		MXZSMCPTclass::createCPT();

		// database creation
		mxzsm_require_class_file_admin( 'db-creation.php' );

			MXZSM_Database_Creation::cerate_tbs();

			// insert data
			MXZSM_Database_Creation::insert_data();

		// create metabox
		mxzsm_require_class_file_admin( 'metabox-creation.php' );

		MXZSMMetaboxCreationClass::createMetaBox();

		// ajax
		mxzsm_require_class_file_admin( 'db-talk.php' );

			// get cities
			MXZSM_Database_Talk::db_ajax();

	}

	/*
	* Models Connection
	*/
	public function mxzsm_models_collection()
	{

		// require model file
		foreach ( $this->models_collection as $model ) {
			
			mxzsm_use_model( $model );

		}		

	}

	/**
	* registration ajax actions
	*/
	public function mxzsm_registration_ajax_actions()
	{

		// ajax requests to main page
		MXZSM_Main_Page_Model::mxzsm_wp_ajax();

	}

	/*
	* Routes collection
	*/
	public function mxzsm_routes_collection()
	{

		// main menu item
		MXZSM_Route::mxzsm_get( 'MXZSM_Main_Page_Controller', 'index', '', [
			'page_title' => 'City Lists',
			'menu_title' => 'City Lists'
		] );

		// // sub menu item
		// MXZSM_Route::mxzsm_get( 'MXZSM_Main_Page_Controller', 'submenu', '', [
		// 	'page_title' => 'Sub Menu title',
		// 	'menu_title' => 'Sub menu'
		// ], 'sub_menu' );

		// // hide menu item
		// MXZSM_Route::mxzsm_get( 'MXZSM_Main_Page_Controller', 'hidemenu', 'NULL', [
		// 	'page_title' => 'Hidden Menu title',
		// ], 'hide_menu' );

		// // sub settings menu item
		// MXZSM_Route::mxzsm_get( 'MXZSM_Main_Page_Controller', 'settings_menu_item_action', 'NULL', [
		// 	'menu_title' => 'Settings Item',
		// 	'page_title' => 'Title of settings page'
		// ], 'settings_menu_item', true );

	}

}

// Initialize
$initialize_admin_class = new MXZSM_Admin_Main();

// include classes
$initialize_admin_class->mxzsm_additional_classes();

// include models
$initialize_admin_class->mxzsm_models_collection();

// ajax requests
$initialize_admin_class->mxzsm_registration_ajax_actions();

// include controllers
$initialize_admin_class->mxzsm_routes_collection();