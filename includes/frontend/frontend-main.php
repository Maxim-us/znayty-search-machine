<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSM_FrontEnd_Main
{

	/*
	* MXZSM_FrontEnd_Main constructor
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
		mxzsm_require_class_file_frontend( 'enqueue-scripts.php' );

			MXZSM_Enqueue_Scripts_Frontend::mxzsm_register();

		// shortcodes
		mxzsm_require_class_file_frontend( 'shortcodes.php' );

			MXZSM_shortcodes::add_shorcodes();

		// ajax
		mxzsm_require_class_file_frontend( 'db-talk-front.php' );

			// get cities
			MXZSM_Database_Talk_Front::db_ajax();
		

	}

}

// Initialize
$initialize_admin_class = new MXZSM_FrontEnd_Main();

// include classes
$initialize_admin_class->mxzsm_additional_classes();