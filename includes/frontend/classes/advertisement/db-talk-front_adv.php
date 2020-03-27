<?php

class MXZSM_Database_Talk_Front_Adv
{

	public static function db_ajax() {

		/*** Need ***/

			/*
			* get cities
			*/
			add_action( 'wp_ajax_mxzsm_get_cities_front_adv_need', array( 'MXZSM_Database_Talk_Front_Adv', 'get_cities_need' ) );

				add_action( 'wp_ajax_nopriv_mxzsm_get_cities_front_adv_need', array( 'MXZSM_Database_Talk_Front_Adv', 'get_cities_need' ) );

			/*
			* Add post
			*/
			add_action( 'wp_ajax_mxzsm_add_obj_front_adv_need', array( 'MXZSM_Database_Talk_Front_Adv', 'add_new_adv_need' ) );

				add_action( 'wp_ajax_nopriv_mxzsm_add_obj_front_adv_need', array( 'MXZSM_Database_Talk_Front_Adv', 'add_new_adv_need' ) );
				

		/*** Proposal ***/

			/*
			* get cities
			*/
			add_action( 'wp_ajax_mxzsm_get_cities_front_adv_prop', array( 'MXZSM_Database_Talk_Front_Adv', 'get_cities_prop' ) );

				add_action( 'wp_ajax_nopriv_mxzsm_get_cities_front_adv_prop', array( 'MXZSM_Database_Talk_Front_Adv', 'get_cities_prop' ) );

			/*
			* Add post
			*/
			add_action( 'wp_ajax_mxzsm_add_obj_front_adv_prop', array( 'MXZSM_Database_Talk_Front_Adv', 'add_new_adv_prop' ) );

				add_action( 'wp_ajax_nopriv_mxzsm_add_obj_front_adv_prop', array( 'MXZSM_Database_Talk_Front_Adv', 'add_new_adv_prop' ) );
			


	}

		// get cities need
		public static function get_cities_need() {

			if( empty( $_POST['nonce'] ) ) wp_die();

			if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_nonce_request_front' ) ) {

				global $wpdb;

				$cities_table = $wpdb->prefix . 'cities';

				$results = $wpdb->get_results( "SELECT id, city FROM $cities_table WHERE region_id = '" . $_POST['region_id'] . "'" );

				if( $_POST['get_all_cities'] !== 'true' ) {

					// Get available cities
					$available_city_ids = mxzsm_get_available_cities_adv_need();

					// cleaned results
					$cleaned_results = array();

					// clean result
					foreach ( $results as $key => $value ) {

						if( ! in_array( $value->id, $available_city_ids ) ) continue;

						// set city id to the array
						array_push( $cleaned_results, $value );

					}

					$json_code = json_encode( $cleaned_results );

				} else {

					$json_code = json_encode( $results );

				}

				echo $json_code;

			}		

			wp_die();
			
		}

	// add new need
	public static function add_new_adv_need()
	{

		if( empty( $_POST['nonce'] ) ) wp_die();

		if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_add_obj_nonce_request' ) ) {

			$post_ID = wp_insert_post( 

				array(

					'post_title' 	=> sanitize_text_field( $_POST['title'] ),
					'post_content'	=> wp_kses_post( $_POST['content'] ),
					'post_type' 	=> 'mxzsm_adv_need',
					'post_status' 	=> 'verification_need'

				)

			);

			if( gettype( $post_ID ) == 'integer' ) { 

				// set region id
				update_post_meta( $post_ID, '_mxzsm_region_id_adv_need', sanitize_text_field( $_POST['region_id'] ) );

				// set city id
				update_post_meta( $post_ID, '_mxzsm_city_id_adv_need', sanitize_text_field( $_POST['city_id'] ) );

				// set categories
				update_post_meta( $post_ID, '_mxzsm_add_obj_categories', sanitize_text_field( $_POST['categories'] ) );

				// phone
				update_post_meta( $post_ID, '_mxzsm_obj_phone', sanitize_text_field( $_POST['obj_phone'] ) );

				// social
				update_post_meta( $post_ID, '_mxzsm_user_social', esc_url_raw( $_POST['obj_social'] ) );
		

				// send email to admin
				$email = get_user_by( 'ID', 1 )->user_email;

				$type_of_adv = '"Мені потрібно"';

				$subject = 'Додано нове оголошення ' . $type_of_adv;

				$message = 'Щойно користувач додав нове оголошення. Необхідна модерація.';

				$headers = 'From: Знайти робот <robot@znayty.com.ua>' . "\r\n";

				wp_mail( $email, $subject, $message, $headers );

			}

			echo gettype( $post_ID );

		}

		wp_die();

	}

	// get cities prop
	public static function get_cities_prop() {

		if( empty( $_POST['nonce'] ) ) wp_die();

		if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_nonce_request_front' ) ) {

			global $wpdb;

			$cities_table = $wpdb->prefix . 'cities';

			$results = $wpdb->get_results( "SELECT id, city FROM $cities_table WHERE region_id = '" . $_POST['region_id'] . "'" );

			if( $_POST['get_all_cities'] !== 'true' ) {

				// Get available cities
				$available_city_ids = mxzsm_get_available_cities_adv_prop();

				// cleaned results
				$cleaned_results = array();

				// clean result
				foreach ( $results as $key => $value ) {

					if( ! in_array( $value->id, $available_city_ids ) ) continue;

					// set city id to the array
					array_push( $cleaned_results, $value );

				}

				$json_code = json_encode( $cleaned_results );

			} else {

				$json_code = json_encode( $results );

			}

			echo $json_code;

		}		

		wp_die();
		
	}

	// add prop
	public static function add_new_adv_prop()
	{

		if( empty( $_POST['nonce'] ) ) wp_die();

		if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_add_obj_nonce_request' ) ) {

			$post_ID = wp_insert_post( 

				array(

					'post_title' 	=> sanitize_text_field( $_POST['title'] ),
					'post_content'	=> wp_kses_post( $_POST['content'] ),
					'post_type' 	=> 'mxzsm_adv_prop',
					'post_status' 	=> 'verification_prop'

				)

			);

			if( gettype( $post_ID ) == 'integer' ) { 

				// set region id
				update_post_meta( $post_ID, '_mxzsm_region_id_adv_prop', sanitize_text_field( $_POST['region_id'] ) );

				// set city id
				update_post_meta( $post_ID, '_mxzsm_city_id_adv_prop', sanitize_text_field( $_POST['city_id'] ) );

				// set categories
				update_post_meta( $post_ID, '_mxzsm_add_obj_categories', sanitize_text_field( $_POST['categories'] ) );

				// phone
				update_post_meta( $post_ID, '_mxzsm_obj_phone', sanitize_text_field( $_POST['obj_phone'] ) );

				// social
				update_post_meta( $post_ID, '_mxzsm_user_social', esc_url_raw( $_POST['obj_social'] ) );

				// insert thumbnail
				if( $_POST['img_id'] !== '' ) {

					set_post_thumbnail( $post_ID, sanitize_text_field( $_POST['img_id'] ) );

				}
		

				// send email to admin
				$email = get_user_by( 'ID', 1 )->user_email;

				$type_of_adv = '"Пропозиція"';

				$subject = 'Додано нове оголошення ' . $type_of_adv;

				$message = 'Щойно користувач додав нове оголошення. Необхідна модерація.';

				$headers = 'From: Знайти робот <robot@znayty.com.ua>' . "\r\n";

				wp_mail( $email, $subject, $message, $headers );

			}

			echo gettype( $post_ID );

		}

		wp_die();

	}

}