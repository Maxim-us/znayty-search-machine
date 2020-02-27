<?php

class MXZSM_Database_Talk_Front
{

	public static function db_ajax() {

		add_action( 'wp_ajax_mxzsm_get_cities_front', array( 'MXZSM_Database_Talk_Front', 'get_cities' ) );

		add_action( 'wp_ajax_nopriv_mxzsm_get_cities_front', array( 'MXZSM_Database_Talk_Front', 'get_cities' ) );

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

}