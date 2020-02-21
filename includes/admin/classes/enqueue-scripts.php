<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSM_Enqueue_Scripts
{

	/*
	* MXZSM_Enqueue_Scripts
	*/
	public function __construct()
	{

	}

	/*
	* Registration of styles and scripts
	*/
	public static function mxzsm_register()
	{

		// register scripts and styles
		add_action( 'admin_enqueue_scripts', array( 'MXZSM_Enqueue_Scripts', 'mxzsm_enqueue' ) );

	}

		public static function mxzsm_enqueue()
		{

			wp_enqueue_style( 'mxzsm_font_awesome', MXZSM_PLUGIN_URL . 'assets/font-awesome-4.6.3/css/font-awesome.min.css' );

			wp_enqueue_style( 'mxzsm_admin_style', MXZSM_PLUGIN_URL . 'includes/admin/assets/css/style.css', array( 'mxzsm_font_awesome' ), MXZSM_PLUGIN_VERSION, 'all' );

			wp_enqueue_script( 'mxzsm_admin_script', MXZSM_PLUGIN_URL . 'includes/admin/assets/js/script.js', array( 'jquery' ), MXZSM_PLUGIN_VERSION, false );

			wp_localize_script( 'mxzsm_admin_script', 'mxzsm_data_obj', array(

				'regions' => array(

					'region_0' => array(

						'region_name'	=> 'Null region',
						'cities'		=> array(
							// { {id: "0", city: "Some city"} }
						)

					)

				),

				'nonce' => wp_create_nonce( 'mxzsm_nonce_request' )

			) );

		}

}