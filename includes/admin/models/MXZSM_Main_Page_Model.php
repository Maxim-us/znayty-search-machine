<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Main page Model
*/
class MXZSM_Main_Page_Model extends MXZSM_Model
{

	/*
	* Observe function
	*/
	public static function mxzsm_wp_ajax()
	{

		add_action( 'wp_ajax_mxzsm_add_new_city_to_db', array( 'MXZSM_Main_Page_Model', 'add_new_city' ), 10, 1 );

	}

	public static function add_new_city()
	{

		if( empty( $_POST['nonce'] ) ) wp_die();

		if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_add_city_nonce_request' ) ) {

			if( ! isset( $_POST['region'] ) ) return;

			if( ! isset( $_POST['city'] ) ) return;

			$cities = mxzsm_get_cities_by_region_id( $_POST['region'] );

			$city = $_POST['city'];

			$insert_city = 1;

			foreach ( $cities as $key => $value ) {

				$enter_city = mb_strtolower( $city );

				$db_city = mb_strtolower( $value->city );

				if( $enter_city == $db_city ) {

					$insert_city = 0;

					break;
				}

			}

			// insert new city
			if( $insert_city == 1 ) {

				global $wpdb;

				$table_name_cities = $wpdb->prefix . 'cities';

				// insert cities
        		$insert_city_to_db = $wpdb->insert(
					$table_name_cities,
					array(
						'city' 			=> sanitize_text_field( $city ),
						'region_id'		=> sanitize_text_field( $_POST['region'] )
					),
					array( '%s', '%d' )
				);

				echo $insert_city_to_db;

			} else {

				echo $insert_city;

			}			

		}

		wp_die();

	}

	
}