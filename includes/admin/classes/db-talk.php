<?php

class MXZSM_Database_Talk
{

	public static function db_ajax() {

		add_action( 'wp_ajax_mxzsm_get_cities', array( 'MXZSM_Database_Talk', 'get_cities' ) );

	}

		public static function get_cities() {			

			if( empty( $_POST['nonce'] ) ) wp_die();

			if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_nonce_request' ) ) {

				global $wpdb;

				$cities_table = $wpdb->prefix . 'cities';

				$results = $wpdb->get_results( "SELECT id, city FROM $cities_table WHERE region_id = '" . $_POST['region_id'] . "'" );

				$json_code = json_encode( $results );

				echo $json_code;

			}		

			wp_die();
			
		}

}