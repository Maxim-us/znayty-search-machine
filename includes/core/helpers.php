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
		'region_id' 	=> 0,
		'city_id'		=> 0,
		'res_page'		=> 1,
		'cat_id' 		=> 0,
		'key_word_id'	=> 0
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

/*
* Тavigation
*/
function mxzsm_navigation( $custom_post, $count_posts_in_page, $meta_query, $tax_query ){

	//get this page
	$this_page = ! isset( $_GET['res_page'] ) ? 0 : $_GET['res_page'];

	$this_page = (int) $this_page;

	if( $this_page === 0 ) $this_page = 1;

	//get count publish posts
	// $count_posts = wp_count_posts( $type = $custom_post, $perm = '' )->publish;

	$post_type_res = new WP_Query(

		array(
			'post_type' 		=> 'mxzsm_objects',
			'meta_query'		=> $meta_query,

			// terms
			'tax_query' 		=> $tax_query
		)

	);

	$count_posts = count( $post_type_res->posts ); 

	//set count page
	$count_page = $count_posts / $count_posts_in_page;

	$count_page = ceil( $count_page );

	//get url
	$host = $_SERVER['HTTP_HOST'];

	$path = $_SERVER['REQUEST_URI'];

	$url = $host . $path;

	//loop links
	if( $count_posts > $count_posts_in_page ){ ?>

		<!-- pagination -->
		<nav class="mx-pagination">
			<ul class="pagination">

				<?php if( $this_page > 1 ): 
					$prev_page = $this_page - 1;
				?>

					<li><a href="<?php echo $url . '&res_page=' . $prev_page; ?>" aria-label="Previous"><span aria-hidden="true" id="mx-Previous">«</span></a></li>				
				<?php endif; ?>

				<?php for ( $i = 1; $i <= $count_page; $i++ ) { 
				if( $i === $this_page ){ ?>
					<li class="active">
						<a href="#" onclick="return false;"><?php echo $i; ?></a>
					</li>
				<?php }
				else{ ?>					
					<li>
						<a href="<?php echo $url . '&res_page=' . $i; ?>"><?php echo $i; ?></a>
					</li>

				<?php }
				} ?>

				<?php if( $this_page < $count_page ): 
					$next_page = $this_page + 1;
				?>

					<li><a href="<?php echo $url . '&res_page=' . $next_page; ?>" aria-label="Next"><span aria-hidden="true" id="mx-Next">»</span></a></li>
				<?php endif;?>
			</ul>
		</nav>
		<!-- pagination -->

	<?php }	
}

/*
* Get Term by term id
*/
function mxzsm_get_term_by_term_id( $term_id ) {

	global $wpdb;

	$terms_table = $wpdb->prefix . 'terms';

	$term_row = $wpdb->get_row(

		"SELECT term_id, name FROM $terms_table WHERE term_id = '" . $term_id . "'"

	);

	return $term_row->name;

}

/*
* Get current url
*/
function mxzsm_get_current_url() {

	$host = $_SERVER['HTTP_HOST'];

	$path = $_SERVER['REQUEST_URI'];

	$url = isset( $_SERVER["HTTPS"] ) ? 'https://' : 'http://' . $host . $path;

	return $url;
}

/*
* Create url for terms
*/
function mxzsm_create_url_for_terms( $get_key, $term_id ) {

	$full_url = mxzsm_get_current_url();

	// check terms key
	preg_match( '/(&' . $get_key . '=\d+)/', $full_url, $matches_terms );

	$clean_url = $full_url;

	if( count( $matches_terms ) !== 0 ) {

		$clean_url = str_replace( $matches_terms[0], '', $clean_url );

	}

	// check pagination key
	preg_match( '/(&res_page=\d+)/', $full_url, $matches_pag );

	if( count( $matches_pag ) !== 0 ) {

		$clean_url = str_replace( $matches_pag[0], '', $clean_url );

	}

	$url = $clean_url . '&' . $get_key . '=' . $term_id . '&res_page=1';

	return $url;

}