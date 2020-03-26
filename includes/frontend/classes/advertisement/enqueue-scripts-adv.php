<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSM_Enqueue_Scripts_Frontend_Adv
{

	/*
	* Registration of styles and scripts
	*/
	public static function mxzsm_register()
	{

		// register scripts and styles
		add_action( 'wp_enqueue_scripts', array( 'MXZSM_Enqueue_Scripts_Frontend_Adv', 'mxzsm_enqueue' ) );

	}

		public static function mxzsm_enqueue()
		{
			
			wp_enqueue_script( 'mxzsm_script_adv', MXZSM_PLUGIN_URL . 'includes/frontend/assets/js/script_adv.js', array( 'mxzsm_script' ), MXZSM_PLUGIN_VERSION, false );
					
		}

}