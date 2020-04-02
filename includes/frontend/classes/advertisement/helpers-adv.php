<?php

// hide phone
function mx_hide_phone( $phone ) {

	if( ! is_user_logged_in() ) {

		global $post;

		echo '<div class="mx-phone-hidden" title="Щоб побачити номер - авторизуйтесь!">+3 80 ... <a href="/my-account/?adv_parrent=mxzsm_adv_need&adv_slug=' . $post->post_name . '">Увійти</a></div>';

	} else {

		echo '<a href="tel:' . $phone . '" target="_blank">' . $phone . '</a>';

	}	

}

// hide social
function mx_hide_social( $url ) {

	if( ! is_user_logged_in() ) {

		global $post;

		echo '<div class="mx-phone-social" title="Щоб побачити лінк - авторизуйтесь!"><a href="/my-account/?adv_parrent=mxzsm_adv_need&adv_slug=' . $post->post_name . '">Увійти</a></div>';

	} else {

		echo '<div class="mx-phone-social" title="Щоб побачити лінк - авторизуйтесь!"><a href="' . $url . '" target="_blank" rel="ugc nofollow">Профіль продавця</a></div>';

	}	

}

// display avatar
function mx_display_avatar() {

	global $post;

	$author_id = $post->post_author;	

	$avatar = get_avatar( $author_id, 96 );

	echo $avatar;

	$author = get_user_meta( $author_id, 'nickname', true );

	$author_first_name = get_user_meta( $author_id, 'first_name', true );

	$author_last_name = get_user_meta( $author_id, 'last_name', true );

	if( $author_first_name !== '' ) {

		$author = $author_first_name . ' ' . $author_last_name;

	}

	echo '<span class="mx_user_name_adv">' . $author . '</span>';

}

/*
* Get available regions
*/
function mxzsm_get_available_regions_adv_need() {

	global $wpdb;

	$posts_table = $wpdb->prefix . 'posts';

	$posts_id_results = $wpdb->get_results(

		"SELECT ID FROM $posts_table WHERE post_status = 'publish'"

	);

	$regions_array = array();

	$postmeta_table = $wpdb->prefix . 'postmeta';

	foreach ( $posts_id_results as $key => $value ) {

		$region_id_row = $wpdb->get_row(

			"SELECT meta_value FROM $postmeta_table
				WHERE
					post_id = $value->ID
				AND
					meta_key = '_mxzsm_region_id_adv_need'"
		);			

		if( $region_id_row !== NULL ) {

			if( ! in_array( $region_id_row->meta_value, $regions_array ) ) {

				array_push( $regions_array, $region_id_row->meta_value );

			}

		}

	}

	return $regions_array;

}

/*
* Get available regions
*/
function mxzsm_get_available_regions_adv_prop() {

	global $wpdb;

	$posts_table = $wpdb->prefix . 'posts';

	$posts_id_results = $wpdb->get_results(

		"SELECT ID FROM $posts_table WHERE post_status = 'publish'"

	);

	$regions_array = array();

	$postmeta_table = $wpdb->prefix . 'postmeta';

	foreach ( $posts_id_results as $key => $value ) {

		$region_id_row = $wpdb->get_row(

			"SELECT meta_value FROM $postmeta_table
				WHERE
					post_id = $value->ID
				AND
					meta_key = '_mxzsm_region_id_adv_prop'"
		);			

		if( $region_id_row !== NULL ) {

			if( ! in_array( $region_id_row->meta_value, $regions_array ) ) {

				array_push( $regions_array, $region_id_row->meta_value );

			}

		}

	}

	return $regions_array;

}

/*
* Get available cities
*/
function mxzsm_get_available_cities_adv_need() {

	global $wpdb;

	$posts_table = $wpdb->prefix . 'posts';

	$posts_id_results = $wpdb->get_results(

		"SELECT ID FROM $posts_table WHERE post_status = 'publish'"

	);

	$cities_array = array();

	$postmeta_table = $wpdb->prefix . 'postmeta';

	foreach ( $posts_id_results as $key => $value ) {

		$city_id_row = $wpdb->get_row(

			"SELECT meta_value FROM $postmeta_table
				WHERE
					post_id = $value->ID
				AND
					meta_key = '_mxzsm_city_id_adv_need'"
		);			

		if( $city_id_row !== NULL ) {

			if( ! in_array( $city_id_row->meta_value, $cities_array ) ) {

				array_push( $cities_array, $city_id_row->meta_value );

			}

		}

	}

	return $cities_array;

}

/*
* Get available cities
*/
function mxzsm_get_available_cities_adv_prop() {

	global $wpdb;

	$posts_table = $wpdb->prefix . 'posts';

	$posts_id_results = $wpdb->get_results(

		"SELECT ID FROM $posts_table WHERE post_status = 'publish'"

	);

	$cities_array = array();

	$postmeta_table = $wpdb->prefix . 'postmeta';

	foreach ( $posts_id_results as $key => $value ) {

		$city_id_row = $wpdb->get_row(

			"SELECT meta_value FROM $postmeta_table
				WHERE
					post_id = $value->ID
				AND
					meta_key = '_mxzsm_city_id_adv_prop'"
		);			

		if( $city_id_row !== NULL ) {

			if( ! in_array( $city_id_row->meta_value, $cities_array ) ) {

				array_push( $cities_array, $city_id_row->meta_value );

			}

		}

	}

	return $cities_array;

}

/*
* Get region by post ID
*/
function mxzsm_get_region_by_post_id_adv_need( $post_id ) {

	$region_data = array(

		'region_id' 	=> 0,
		'region_name'	=> ''

	);

	global $wpdb;

	$postmeta_table = $wpdb->prefix . 'postmeta';

	$region_id_row = $wpdb->get_row(

		"SELECT meta_value FROM $postmeta_table
			WHERE
				post_id = $post_id
			AND
				meta_key = '_mxzsm_region_id_adv_need'"
	);

	$region_id = $region_id_row->meta_value;

	$region_row = mxzsm_get_region_row_by_id_adv( $region_id );

	$region_data['region_id'] = $region_row->id;

	$region_data['region_name'] = $region_row->region;

	return $region_data;

}

/*
* Get city by post ID
*/
function mxzsm_get_city_by_post_id_adv_need( $post_id ) {

	$city_data = array(

		'city_id' 	=> 0,
		'city_name'	=> ''

	);

	global $wpdb;

	$postmeta_table = $wpdb->prefix . 'postmeta';

	$city_id_row = $wpdb->get_row(

		"SELECT meta_value FROM $postmeta_table
			WHERE
				post_id = $post_id
			AND
				meta_key = '_mxzsm_city_id_adv_need'"
	);

	$city_id = $city_id_row->meta_value;

	$city_row = mxzsm_get_city_row_by_id_adv( $city_id );

	$city_data['city_id'] = $city_row->id;

	$city_data['city_name'] = $city_row->city;

	return $city_data;

}

/*
* Get region by post ID
*/
function mxzsm_get_region_by_post_id_adv_prop( $post_id ) {

	$region_data = array(

		'region_id' 	=> 0,
		'region_name'	=> ''

	);

	global $wpdb;

	$postmeta_table = $wpdb->prefix . 'postmeta';

	$region_id_row = $wpdb->get_row(

		"SELECT meta_value FROM $postmeta_table
			WHERE
				post_id = $post_id
			AND
				meta_key = '_mxzsm_region_id_adv_prop'"
	);

	$region_id = $region_id_row->meta_value;

	$region_row = mxzsm_get_region_row_by_id_adv( $region_id );

	$region_data['region_id'] = $region_row->id;

	$region_data['region_name'] = $region_row->region;

	return $region_data;

}

/*
* Get city by post ID
*/
function mxzsm_get_city_by_post_id_adv_prop( $post_id ) {

	$city_data = array(

		'city_id' 	=> 0,
		'city_name'	=> ''

	);

	global $wpdb;

	$postmeta_table = $wpdb->prefix . 'postmeta';

	$city_id_row = $wpdb->get_row(

		"SELECT meta_value FROM $postmeta_table
			WHERE
				post_id = $post_id
			AND
				meta_key = '_mxzsm_city_id_adv_prop'"
	);

	$city_id = $city_id_row->meta_value;

	$city_row = mxzsm_get_city_row_by_id_adv( $city_id );

	$city_data['city_id'] = $city_row->id;

	$city_data['city_name'] = $city_row->city;

	return $city_data;

}






/*
* Get city's row by id
*/
function mxzsm_get_city_row_by_id_adv( $city_id ) {

	global $wpdb;

	$table_cities = $wpdb->prefix . 'cities';

	$row_city = $wpdb->get_row(

		"SELECT id, city, region_id FROM $table_cities WHERE id = '" . $city_id . "'"

	);

	// return format: $row_region->city
	return $row_city;

}

/*
* Get region row by id
*/
function mxzsm_get_region_row_by_id_adv( $region_id ) {

	global $wpdb;

	$table_regions = $wpdb->prefix . 'regions';

	// get region by id
	$row_region = $wpdb->get_row(

		"SELECT id, region FROM $table_regions WHERE id = '" . $region_id . "'"

	);

	// return format: $row_region->region
	return $row_region;

}