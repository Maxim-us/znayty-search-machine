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


		/**
		* Advertisement
		*/

			// enqueue_scripts
			mxzsm_require_class_file_frontend( 'advertisement/enqueue-scripts-adv.php' );

				MXZSM_Enqueue_Scripts_Frontend_Adv::mxzsm_register();

			// helpers
			mxzsm_require_class_file_frontend( 'advertisement/helpers-adv.php' );

			// ajax
			mxzsm_require_class_file_frontend( 'advertisement/db-talk-front_adv.php' );

				// get cities
				MXZSM_Database_Talk_Front_Adv::db_ajax();
		
			/*** Need ***/
				// display
				mxzsm_require_class_file_frontend( 'advertisement/need/shortcode-display-adv-need.php' );

					MXZSM_Shortcodes_Display_Adv_Need::add_adv_shorcode();

				// add new need
				mxzsm_require_class_file_frontend( 'advertisement/need/shortcode-add-new-need.php' );

					MXZSM_Shortcode_Add_New_Need::add_shorcode();

				// search form
				mxzsm_require_class_file_frontend( 'advertisement/need/shortcode-search-form-adv-need.php' );

					MXZSM_Shortcodes_Search_Form_Adv::add_shorcode();

			/*** Proposal ***/
				// display
				mxzsm_require_class_file_frontend( 'advertisement/proposal/shortcode-display-adv-prop.php' );

					MXZSM_Shortcodes_Display_Adv_Prop::add_adv_shorcode();

				// add new prop
				mxzsm_require_class_file_frontend( 'advertisement/proposal/shortcode-add-new-prop.php' );

					MXZSM_Shortcode_Add_New_Prop::add_shorcode();

				// search form
				mxzsm_require_class_file_frontend( 'advertisement/proposal/shortcode-search-form-adv-prop.php' );

					MXZSM_Shortcodes_Search_Form_Adv_Prop::add_shorcode();


				


	}

}

// Initialize
$initialize_admin_class = new MXZSM_FrontEnd_Main();

// include classes
$initialize_admin_class->mxzsm_additional_classes();