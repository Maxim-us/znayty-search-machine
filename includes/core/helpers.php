<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Require class for admin panel
*/
function mxzsm_require_class_file_admin( $file ) {

	require_once MXZSM_PLUGIN_ABS_PATH . 'includes/admin/classes/' . $file;

}


/*
* Require class for frontend panel
*/
function mxzsm_require_class_file_frontend( $file ) {

	require_once MXZSM_PLUGIN_ABS_PATH . 'includes/frontend/classes/' . $file;

}

/*
* Require a Model
*/
function mxzsm_use_model( $model ) {

	require_once MXZSM_PLUGIN_ABS_PATH . 'includes/admin/models/' . $model . '.php';

}

/*
* nothing found
*/
function mxzsm_nothing_found( $message ) {

	echo '<div class="mxzsm_nothing_found">' . $message . '</div>';

}

/*
* Get regions result
*/
function mxzsm_get_regions() {

	global $wpdb;

	$table_regions = $wpdb->prefix . 'regions';

	$results_regions = $wpdb->get_results(

		"SELECT id, region FROM $table_regions ORDER BY region"

	);

	return $results_regions;
}

/*
* Check $_GET and return it's clone $_get_
*/
function mxzsm_check_get_set_get( $_get ) {

	$_get_ = array(
		'region_id' => 0,
		'city_id'	=> 0
	);

	foreach ( $_get as $key => $value ) {

		$key = sanitize_text_field( $key );

		$value = sanitize_text_field( $value );

		$_get_[$key] = $value;

		// val to int
		if( $value !== '' ) {

			$val = intval( $value );

			$_get_[$key] = $val;

		}

	}

	return $_get_;

}

/*
* Get region row by id
*/
function mxzsm_get_region_row_by_id( $region_id ) {

	global $wpdb;

	$table_regions = $wpdb->prefix . 'regions';

	// get region by id
	$row_region = $wpdb->get_row(

		"SELECT id, region FROM $table_regions WHERE id = '" . $region_id . "'"

	);

	return $row_region;

}

/*
* Get cities by region id
*/
function mxzsm_get_cities_by_region_id( $region_id ) {

	global $wpdb;

	$table_cities = $wpdb->prefix . 'cities';

	// get cities by region id
	$cities_results = $wpdb->get_results(

		"SELECT id, city, region_id FROM $table_cities WHERE region_id = '" . $region_id . "'"

	);

	return $cities_results;

}

/*
* Get city's row by id
*/
function mxzsm_get_city_row_by_id( $city_id ) {

	global $wpdb;

	$table_cities = $wpdb->prefix . 'cities';

	$row_city = $wpdb->get_row(

		"SELECT id, city, region_id FROM $table_cities WHERE id = '" . $city_id . "'"

	);

	return $row_city;

}

/*
* Get available regions
*/
function mxzsm_get_available_regions() {

	global $wpdb;

	$regions_array = array();

	$postmeta_table = $wpdb->prefix . 'postmeta';

	$region_id_results = $wpdb->get_results(

		"SELECT meta_value FROM $postmeta_table WHERE meta_key = '_mxzsm_region_id'"

	);

	// if no regions - return
	if( $region_id_results == NULL )
		return $regions_array;

	// each row
	foreach ( $region_id_results as $key => $value ) {

		if( in_array( $value->meta_value, $regions_array ) )
			continue;

		// set region id to the array
		array_push( $regions_array, $value->meta_value );

	}

	return $regions_array;

}

/*
* Get available cities
*/
function mxzsm_get_available_cities() {

	global $wpdb;

	$cities_array = array();

	$postmeta_table = $wpdb->prefix . 'postmeta';

	$cities_id_results = $wpdb->get_results(

		"SELECT meta_value FROM $postmeta_table WHERE meta_key = '_mxzsm_city_id'"

	);

	// if no cities - return
	if( $cities_id_results == NULL )
		return $cities_array;

	// each row
	foreach ( $cities_id_results as $key => $value ) {

		if( in_array( $value->meta_value, $cities_array ) )
			continue;

		// set city id to the array
		array_push( $cities_array, $value->meta_value );

	}

	return $cities_array;

}