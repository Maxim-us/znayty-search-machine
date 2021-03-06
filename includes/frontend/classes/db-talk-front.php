<?php

class MXZSM_Database_Talk_Front
{

	public static function db_ajax() {

		/*
		* get cities
		*/
		add_action( 'wp_ajax_mxzsm_get_cities_front', array( 'MXZSM_Database_Talk_Front', 'get_cities' ) );

			add_action( 'wp_ajax_nopriv_mxzsm_get_cities_front', array( 'MXZSM_Database_Talk_Front', 'get_cities' ) );

		/*
		* Add post
		*/
		add_action( 'wp_ajax_mxzsm_add_obj_front', array( 'MXZSM_Database_Talk_Front', 'add_new_obj' ) );

			add_action( 'wp_ajax_nopriv_mxzsm_add_obj_front', array( 'MXZSM_Database_Talk_Front', 'add_new_obj' ) );

		/*
		* Count of views of object
		*/
		add_action( 'wp_ajax_mxzsm_count_of_views_of_obj', array( 'MXZSM_Database_Talk_Front', 'count_of_views_of_obj' ) );

			add_action( 'wp_ajax_nopriv_mxzsm_count_of_views_of_obj', array( 'MXZSM_Database_Talk_Front', 'count_of_views_of_obj' ) );


	}

		public static function get_cities() {

			if( empty( $_POST['nonce'] ) ) wp_die();

			if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_nonce_request_front' ) ) {

				global $wpdb;

				$cities_table = $wpdb->prefix . 'cities';

				$results = $wpdb->get_results( "SELECT id, city FROM $cities_table WHERE region_id = '" . $_POST['region_id'] . "'" );

				if( $_POST['get_all_cities'] !== 'true' ) {

					// Get available cities
					$available_city_ids = mxzsm_get_available_cities();

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

	// add new object
	public static function add_new_obj()
	{

		if( empty( $_POST['nonce'] ) ) wp_die();

		if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_add_obj_nonce_request' ) ) {

			$post_ID = wp_insert_post( 

				array(

					'post_title' 	=> sanitize_text_field( $_POST['title'] ),
					'post_content'	=> wp_kses_post( $_POST['content'] ),
					'post_type' 	=> 'mxzsm_objects',
					'post_status' 	=> 'verification'

				)

			);

			if( gettype( $post_ID ) == 'integer' ) {

				// set region id
				update_post_meta( $post_ID, '_mxzsm_region_id', sanitize_text_field( $_POST['region_id'] ) );

				// set city id
				update_post_meta( $post_ID, '_mxzsm_city_id', sanitize_text_field( $_POST['city_id'] ) );

				// set categories
				update_post_meta( $post_ID, '_mxzsm_add_obj_categories', sanitize_text_field( $_POST['categories'] ) );

				// set keywords
				update_post_meta( $post_ID, '_mxzsm_add_obj_keywords', sanitize_text_field( $_POST['keywords'] ) );

				// set address
				update_post_meta( $post_ID, '_mxzsm_address_of_obj', sanitize_text_field( $_POST['address'] ) );

				// google map
				// set latitude
				update_post_meta( $post_ID, '_mxzsm_obj_latitude', sanitize_text_field( $_POST['obj_latitude'] ) );

				// set longitude
				update_post_meta( $post_ID, '_mxzsm_obj_longitude', sanitize_text_field( $_POST['obj_longitude'] ) );

				// website
				update_post_meta( $post_ID, '_mxzsm_obj_website', esc_url_raw( $_POST['obj_website'] ) );

				// phone
				update_post_meta( $post_ID, '_mxzsm_obj_phone', sanitize_text_field( $_POST['obj_phone'] ) );

				// email
				update_post_meta( $post_ID, '_mxzsm_obj_email', sanitize_email( $_POST['obj_email'] ) );

				// against covid
				update_post_meta( $post_ID, '_mxzsm_obj_against_covid', sanitize_text_field( $_POST['obj_against_covid'] ) );

				// service type
					update_post_meta( $post_ID, '_mxzsm_obj_service_type_normal_mode', sanitize_text_field( $_POST['normal_mode'] ) );

					update_post_meta( $post_ID, '_mxzsm_obj_service_type_takeaway', sanitize_text_field( $_POST['takeaway'] ) );

					update_post_meta( $post_ID, '_mxzsm_obj_service_type_delivery', sanitize_text_field( $_POST['delivery'] ) );

				// video from youtube
				update_post_meta( $post_ID, '_mxzsm_obj_video_youtube', esc_url_raw( $_POST['obj_video_youtube'] ) );

				
				// insert thumbnail
				if( $_POST['img_id'] !== '' ) {

					set_post_thumbnail( $post_ID, sanitize_text_field( $_POST['img_id'] ) );

				}				

				// send email to admin
				$email = get_user_by( 'ID', 1 )->user_email;

				$subject = 'Додано новий об\'єкт!';

				$message = 'Щойно користувач додав новий об\'єкт. Необхідна модерація.';

				$headers = 'From: Знайти робот <robot@znayty.com.ua>' . "\r\n";

				wp_mail( $email, $subject, $message, $headers );

			}

			echo gettype( $post_ID );

		}

		wp_die();

	}

	// count of views
	public static function count_of_views_of_obj()
	{

		if( ! current_user_can('administrator') ) {

			if( empty( $_POST['nonce'] ) ) wp_die();

			if( wp_verify_nonce( $_POST['nonce'], 'count_of_views_of_obj_action' ) ) {

				$post_id = $_POST['post_id'];

				$coutn_views_current = get_post_meta( $post_id, '_count_of_views_of_obj', true );

				if( $coutn_views_current == '' ) {

					$coutn_views_current = 1;

				} else {

					$coutn_views_current = $coutn_views_current + 1;

				}

				update_post_meta( $post_id, '_count_of_views_of_obj', $coutn_views_current  );

				echo $coutn_views_current;

			}			

		}

		wp_die();

	}

}