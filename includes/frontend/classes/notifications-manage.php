<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSM_Notifications_Manage
{

	/*
	* Registration of styles and scripts
	*/
	public static function mxzsm_notifications_ajax()
	{

		add_action( 'wp_ajax_mxzsm_got_this_notification', array( 'MXZSM_Notifications_Manage', 'got_this_notification' ) );

			add_action( 'wp_ajax_nopriv_mxzsm_got_this_notification', array( 'MXZSM_Notifications_Manage', 'got_this_notification' ) );

	}

		public static function got_this_notification()
		{

			if( empty( $_POST['nonce'] ) ) wp_die();

			if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_nonce_request_front' ) ) {

				$option = $_POST['option'];

				$current_user_id = get_current_user_id();

				$update_user_meta = update_user_meta( $current_user_id, $option, 1 );

				echo gettype( $update_user_meta );

			}

			wp_die();

		}

}