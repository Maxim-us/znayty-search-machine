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

		/*
		* Shortcodes
		*/
			// search form
			mxzsm_require_class_file_frontend( 'shortcode-search-form.php' );

				MXZSM_Shortcodes_Search_Form::add_shorcode();

			// search result
			mxzsm_require_class_file_frontend( 'shortcode-search-result.php' );

				MXZSM_Shortcode_Search_Result::add_shorcode();

				// add actions
				MXZSM_Shortcode_Search_Result::add_actions();

			// add new obj (for logged user)
			mxzsm_require_class_file_frontend( 'shortcode-add-new-obj.php' );

				MXZSM_Shortcode_Add_New_Obj::add_shorcode();


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