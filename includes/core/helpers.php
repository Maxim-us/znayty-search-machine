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
* Alert
*/
function mxzsm_alert( $message ) {

	echo '<div class="mxzsm_alert alert alert-primary" role="alert">' . $message . '</div>';

}
	function mxzsm_alert_success( $message ) {

		echo '<div class="mxzsm_alert alert alert-success" role="alert">' . $message . '</div>';

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
		if( $value !== '' AND $value !== 'full' ) {

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

	// return format: $row_region->region
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

	// return format: $row_region->city
	return $row_city;

}

/*
* Get available regions
*/
function mxzsm_get_available_regions() {

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
					meta_key = '_mxzsm_region_id'"
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
function mxzsm_get_available_cities() {

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
					meta_key = '_mxzsm_city_id'"
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
* Pavigation
*/
function mxzsm_navigation( $custom_post, $count_posts_in_page, $meta_query, $tax_query ){

	//get this page
	$this_page = ! isset( $_GET['res_page'] ) ? 0 : $_GET['res_page'];

	$this_page = (int) $this_page;

	if( $this_page === 0 ) $this_page = 1;	

	//get count publish posts
	// $count_posts = wp_count_posts( $type = $custom_post, $perm = '' )->publish;

	wp_reset_postdata();

	$post_type_res = new WP_Query(

		array(
			'post_type' 		=> $custom_post,
			'meta_query'		=> $meta_query,
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> -1,
			// 'paged' 			=> $this_page,

			// terms
			'tax_query' 		=> $tax_query
		)

	);	

	$count_posts = count( $post_type_res->posts );

	// var_dump($count_posts);

	//set count page
	$count_page = $count_posts / $count_posts_in_page;

	$count_page = ceil( $count_page );

	//get url
	$_http = isset( $_SERVER["HTTPS"] ) ? 'https://' : 'http://';

	$host = $_SERVER['HTTP_HOST'];

	$path = $_SERVER['REQUEST_URI'];

	preg_match( '/(&res_page=\d+)/', $path, $matches_pag );

	if( count( $matches_pag ) > 0 ) {

		$path = str_replace( $matches_pag[0], '', $path );

	}

	// var_dump( $path );

	$url = $_http . $host . $path;	

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

	return $url . $host . $path;
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

/*
* Create url for regions
*/
function mxzsm_create_url_for_regions( $region_id ) {

	$full_url = mxzsm_get_current_url();

	// check terms key
	preg_match( '/(\?.*)/', $full_url, $matches_terms );

	$clean_url = $full_url;

	$clean_url = str_replace( $matches_terms[0], '', $clean_url );	

	$url = $clean_url . '?region_id=' . $region_id;

	return $url;

}

/*
* Create url for city
*/
function mxzsm_create_url_for_city( $city_id, $region_id ) {

	$full_url = mxzsm_get_current_url();

	// check terms key
	preg_match( '/(\?.*)/', $full_url, $matches_terms );

	$clean_url = $full_url;

	$clean_url = str_replace( $matches_terms[0], '', $clean_url );	

	$url = $clean_url . '?region_id=' . $region_id . '&city_id=' . $city_id;

	return $url;

}

/*
* Get region by post ID
*/
function mxzsm_get_region_by_post_id( $post_id ) {

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
				meta_key = '_mxzsm_region_id'"
	);

	$region_id = $region_id_row->meta_value;

	$region_row = mxzsm_get_region_row_by_id( $region_id );

	$region_data['region_id'] = $region_row->id;

	$region_data['region_name'] = $region_row->region;

	return $region_data;

}

/*
* Get city by post ID
*/
function mxzsm_get_city_by_post_id( $post_id ) {

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
				meta_key = '_mxzsm_city_id'"
	);

	$city_id = $city_id_row->meta_value;

	$city_row = mxzsm_get_city_row_by_id( $city_id );

	$city_data['city_id'] = $city_row->id;

	$city_data['city_name'] = $city_row->city;

	return $city_data;

}

// count of views of obj (8 sec)
function mxzsm_count_of_views_of_obj( $post_id ) {

	wp_nonce_field( 'count_of_views_of_obj_action', 'count_of_views_of_obj_nonce' );

	?>
		<script>

			jQuery( document ).ready( function( $ ) {

				setTimeout( function() {

					var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';

					var data = {

						'action'		:  'mxzsm_count_of_views_of_obj',
						'nonce'			: 	$( '#count_of_views_of_obj_nonce' ).val(),
						'post_id' 		: '<?php echo $post_id; ?>'

					};

					// $.ajax
					jQuery.post( ajaxurl, data, function( response ) {

						if( response !== '' ) {

							$( '.mx_count_of_views' ).find( 'span' ).text( response );

						}						

					} );

				}, 6000 );				

			} );

		</script>

	<?php	

}

// show last items of publications
function mxzsm_show_last_items_of_publications( $post_type, $count_of_posts, $category, $search_page, $add_page ) {

	$meta_query = array();

	$tax_query = array();

	wp_reset_postdata();

	$post_type_res = new WP_Query(

		array(
			'post_type' 		=> $post_type,
			'meta_query'		=> $meta_query,
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> $count_of_posts,
			// 'paged' 			=> $this_page,

			// terms
			'tax_query' 		=> $tax_query
		)

	); 

	if( $post_type_res->have_posts() ) : ?>

		<div class="mxzsm_show_last_items_of_publications">
			
			<?php while( $post_type_res->have_posts() ) : $post_type_res->the_post(); ?>				

				<?php 
				$thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'znayty-thumbnail' ) == false ? MXZSM_PLUGIN_URL . 'includes/frontend/assets/img/empty-thum.png' : get_the_post_thumbnail_url( get_the_ID(), 'znayty-thumbnail-b' );
				?>

				<div class="mxzsm_show_last_item">

					<div>
					
						<div class="mxzsm_show_last_item_thumbnail">

							<?php if( $thumbnail ) : ?>

								<a href="<?php echo get_the_permalink(); ?>">						
									<img src="<?php echo $thumbnail; ?>" alt="" />
								</a>

							<?php endif; ?>

						</div>

						<div class="mxzsm_show_last_item_title">
							<a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a>
						</div>

						<div class="mxzsm_show_last_item_meta">

							<!-- categories -->
							<?php $categories = get_the_terms( get_the_ID(), $category ); 

								// var_dump( $categories );

									if( $categories ) : ?>

									<ul>

										<?php foreach ( $categories as $key => $value ) { ?>

											<li>
												<a href="/<?php echo $search_page; ?>/?region_id=full&cat_id=<?php echo $value->term_id; ?>#mx_search_system_info"><?php echo $value->name; ?></a>
											</li>

										<?php } ?>

									</ul>

								<?php endif; ?>

							<div>
								
								<!-- views -->
								<?php $count_of_views = mx_get_count_of_views( get_the_ID() ) == '' ? 0 : mx_get_count_of_views( get_the_ID() ); ?>

								<div class="mx_count_of_views">
									<i class="fa fa-eye"></i> (<span><?php echo $count_of_views; ?></span>)
									
								</div>

								<!-- comments -->
								<div class="mx_comment_count">
									<i class="fa fa-comments"></i> (<?php echo mx_count_of_comments_by_post_id( get_the_ID() ); ?>)
								</div>

							</div>

						</div>

					</div>

				</div>			


			<?php endwhile; ?>

			<div class="mxzsm_show_last_item_footer">
				<a href="/<?php echo $search_page; ?>/?region_id=full#mx_search_system_info">Переглянути всі <i class="fa fa-arrow-right"></i></a>
				<a href="/<?php echo $add_page; ?>/"><i class="fa fa-plus"></i> Додати</a>
			</div>


		</div>


	<?php endif;


	// var_dump($post_type_res->posts);

}

?>