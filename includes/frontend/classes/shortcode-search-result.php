<?php

class MXZSM_Shortcode_Search_Result
{

	public static function add_shorcode() {

		// search result
		add_shortcode( 'mxzsm_search_result', array( 'MXZSM_Shortcode_Search_Result', 'search_result' ) );

	}

	public static function add_actions()
	{

		// 'mx_search_result_item_thumb_area'
			// add thumbnail
			add_action( 'mx_search_result_item_thumb_area', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_thumbnail' ), 10 );

			// add thumbnail
			// add_action( 'mx_search_result_item_thumb_area', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_author' ), 20 );

			// add date
			// add_action( 'mx_search_result_item_thumb_area', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_date' ), 30 );

		// 'mx_search_result_item_desc_area'
			// add title
			add_action( 'mx_search_result_item_desc_area', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_title' ), 10 );

			// add excerpt
			// add_action( 'mx_search_result_item_desc_area', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_excerpt' ), 20 );

		// 'mx_list_of_obj_meta_data'
			// add phone number
			add_action( 'mx_list_of_obj_meta_data', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_phone_number' ), 20 );

			// add keywords
			add_action( 'mx_list_of_obj_meta_data', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_keywords' ), 20 );

			// add categories
			add_action( 'mx_list_of_obj_meta_data', array( 'MXZSM_Shortcode_Search_Result', 'mx_add_categories' ), 30 );

		// 'mx_search_result_item_footer'

			// add list of icons
			add_action( 'mx_search_result_item_footer', array( 'MXZSM_Shortcode_Search_Result', 'mx_list_of_icons' ), 10 );

			// add region and city
			add_action( 'mx_search_result_item_footer', array( 'MXZSM_Shortcode_Search_Result', 'mx_region_and_city' ), 10 );
			

	}

		// search result
		public static function search_result() {

			if( count( $_GET ) == 0 ) return;
			
			// check $_GET			
			$_get_ = mxzsm_check_get_set_get( $_GET );					

			if( $_get_['region_id'] == '0' ) return;

			if( $_get_['region_id'] > 27 ) return;

			// $_get_ - use for query

			// posts_per_page
			$posts_per_page = 10;

			ob_start();

				// get region by id
				$row_region = mxzsm_get_region_row_by_id( $_get_['region_id'] );

				// get cities by region id
				$results_cities = mxzsm_get_cities_by_region_id( $_get_['region_id'] );
				
				// get city row by id
				$row_city = mxzsm_get_city_row_by_id( $_get_['city_id'] );

			?>

			<!-- result -->
			<div class="mx-search-result">
				
				<?php

					// search by metadata
					$meta_query = array();

					if( $row_region == NULL ) {

						// mxzsm_nothing_found( 'Область не знайдено!' );
						// show all results
						$meta_query = array();

					} else if( $row_city == NULL ) {

						$meta_query = array(
							array(
								'key' 		=> '_mxzsm_region_id',
                                'value' 	=> $row_region->id
                            )
						);
					} else {

						$meta_query = array(
							'relation' => 'BETWEEN',
							array(
								'key' 		=> '_mxzsm_region_id',
                                'value' 	=> $row_region->id
                            ),
                            array(
								'key' 		=> '_mxzsm_city_id',
                                'value' 	=> $row_city->id
                            )
						);

					}

				?>
				
				<?php

				// search by terms
				$tax_query = array();				

				if( $_get_['cat_id'] !== 0 ) {

					// key work setted check
					if( $_get_['key_word_id'] == 0 ) {

						$tax_query = array(

							array(

								'taxonomy' => 'mxzsm_objects_category',
								'field'    => 'id',
								'terms'    => $_get_['cat_id']

							)

						);

					} else {

						$tax_query = array(

							'relation' => 'BETWEEN',

							array(

								'taxonomy' => 'mxzsm_objects_category',
								'field'    => 'id',
								'terms'    => $_get_['cat_id']

							),

							array(

								'taxonomy' => 'mxzsm_objects_keywords',
								'field'    => 'id',
								'terms'    => $_get_['key_word_id']

							)

						);

					}

				} else {

					if( $_get_['key_word_id'] !== 0 ) {

						$tax_query = array(

							array(

								'taxonomy' => 'mxzsm_objects_keywords',
								'field'    => 'id',
								'terms'    => $_get_['key_word_id']

							)

						);

					}

				}			

				// get results
				$result_obj = new WP_Query(

					array(
						'post_type' 		=> 'mxzsm_objects',
						'post_status'		=> 'publish',
						'posts_per_page' 	=> $posts_per_page,
						'order' 			=> 'DESC',
						'meta_query'		=> $meta_query,
						'paged' 			=> $_get_['res_page'],

						// terms
						'tax_query' 		=> $tax_query
					)

				);

				?>

					<?php if( $result_obj->have_posts() ) : ?>

						<!-- result -->
						<div class="mx-search-result-wrap">

							<!-- system results ... -->
							<?php MXZSM_Shortcode_Search_Result::search_system_info( array(

								'row_region'	=> $row_region,
								'row_city' 		=> $row_city,
								'result_obj'	=> $result_obj,
								'region_id' 	=> $_get_['region_id'],
								'city_id'		=> $_get_['city_id'],
								'cat_id'		=> $_get_['cat_id'],
								'key_word_id'	=> $_get_['key_word_id']

							) ); ?>
							<!-- ... system results -->

							<!-- search result loop ... -->
							<?php while( $result_obj->have_posts() ) : $result_obj->the_post(); ?>

								<?php MXZSM_Shortcode_Search_Result::search_result_item(); ?>

							<?php endwhile; ?> 
							<!-- ... search result loop -->

						</div>

						<!-- pagination ... -->
						<?php mxzsm_navigation( 'mxzsm_objects', $posts_per_page, $meta_query, $tax_query ); ?>
						<!-- ... pagination -->

					<?php endif; ?>
				
			</div>

			<?php return ob_get_clean();
			
		}

	/**
	* COMPONENTS
	*/
		/*
		* Display system info
		*/
		public static function search_system_info( $args ) { ?>

			<div class="alert alert-secondary mx-search-system-info-wrap" id="mx_search_system_info">

			<h5>Результат пошуку:</h5>

				<ul class="mx-search-system-info">

					<!-- region -->
					<?php if( $args['row_region'] !== NULL ) : ?>

						<li>
							<!-- <span>Область:</span> -->
							<span><?php echo mxzsm_get_region_row_by_id( $args['region_id'] )->region; ?></span>
						</li>

					<?php else : ?>

						<li>
							<span>Вся Україна</span>
						</li>

					<?php endif ?>

					<!-- cities -->
					<?php if( $args['row_city'] == NULL ) : ?>

						<li>
							<span>Результати по всіх населених пунктах</span>
						</li>

					<?php else : ?>

						<li>
							<span>Населений п-т:</span>
							<span><?php echo mxzsm_get_city_row_by_id( $args['city_id'] )->city; ?></span>
						</li>

					<?php endif; ?>

					<!-- categoty -->
					<?php if( $args['cat_id'] !== 0 ) : ?>

						<?php if( count( $args['result_obj']->posts ) !== 0 ) : ?>

							<li>
								<span>Категорія об'єкту:</span>
								<span><?php echo mxzsm_get_term_by_term_id( $args['cat_id'] ); ?></span>
							</li>

						<?php endif; ?>

					<?php endif; ?>

					<!-- keyword -->
					<?php if( $args['key_word_id'] !== 0 ) : ?>

						<?php if( count( $args['result_obj']->posts ) !== 0 ) : ?>

							<li>
								<span>Ключове слово:</span>
								<span><?php echo mxzsm_get_term_by_term_id( $args['key_word_id'] ); ?></span>
							</li>

						<?php endif; ?>

					<?php endif; ?>

				</ul>

			</div>

		<?php }

		/*
		* Search result item
		*/
		public static function search_result_item()
		{ ?>

			<div class="mx-search-result-item">

				<div>				
			
					<div class="mx-search-result-item-thumb">

						<?php do_action( 'mx_search_result_item_thumb_area' ); ?>

					</div>

					<div class="mx-search-result-item-desc">

						<?php do_action( 'mx_search_result_item_desc_area' ); ?>		

					</div>

					<!-- meta data -->
					<!-- keywords -->
					<div class="mx-meta-keywords-data">

						<!-- add extra items -->
						<?php do_action( 'mx_list_of_obj_meta_data' ); ?>

					</div>
					<!--  -->

					<!-- result footer -->
					<div class="mx-result-footer">

						<?php do_action( 'mx_search_result_item_footer' ); ?>

					</div>

				</div>

			</div>

		<?php }

	/**
	*	Add actions
	*/

	/*
	* 'mx_search_result_item_thumb_area'
	*/
		// thumbnail
		public static function mx_add_thumbnail()
		{ 

			$the_thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'znayty-thumbnail' ) == false ? MXZSM_PLUGIN_URL . 'includes/frontend/assets/img/empty-thum_list.jpg' : get_the_post_thumbnail_url( get_the_ID(), 'znayty-thumbnail' );

			?>
			<a href="<?php echo get_post_permalink(); ?>">
				<img src="<?php echo $the_thumbnail; ?>" class="" alt="" />
			</a>

		<?php } 

		// author
		public static function mx_add_author()
		{ ?>

			<!-- autor -->
			<div class="mx-search-result-item-meta-data-autor">
				
				<span>Автор:</span>

				<?php

					$author_first_name = get_the_author_meta( 'first_name' );

					$author_last_name = get_the_author_meta( 'last_name' );

					if( $author_first_name !== '' ) {

						echo $author_first_name . ' ' . $author_last_name;

					} else {

						echo get_the_author();

					}

				?>

			</div>

		<?php }

		// add date
		public static function mx_add_date()
		{ ?>

			<!-- date -->
			<div class="mx-search-result-item-meta-data-date">
				
				<?php $time_string = esc_attr( get_the_date() );

				$posted_on = sprintf(
					/* translators: %s: post date. */
					esc_html_x( 'Опубліковано: %s', 'post date', 'znayty-search-machine' ),
					$time_string
				);

				echo '<span class="posted-on">' . $posted_on . '</span>'; ?>
				
			</div>

		<?php }

	/*
	* 'mx_search_result_item_desc_area'
	*/
		// add title
		public static function mx_add_title()
		{ ?>

			<div class="mx-search-result-item-title">
				<a href="<?php echo get_post_permalink(); ?>"><?php echo get_the_title(); ?></a>
			</div>

		<?php }

		// add excerpt
		public static function mx_add_excerpt()
		{ ?>

			<div class="mx-search-result-item-excerp">
				<?php the_excerpt(); ?>
			</div>	

		<?php }

	/*
	* 'mx_list_of_obj_meta_data'
	*/
		// add phone number
		public static function mx_add_phone_number()
		{ 

			$phone = get_post_meta( get_the_ID(), '_mxzsm_obj_phone', true );

			if( $phone == '' ) return;

			?>

			<div class="mx_add_phone_number">
				<i class="fa fa-phone"></i> - <?php echo $phone; ?>
			</div>

		<?php }
		// add keywords
		public static function mx_add_keywords()
		{ ?>

			<?php $keywords = get_the_terms( get_the_ID(), 'mxzsm_objects_keywords' ); ?>

			<?php if( $keywords ) : ?>

				<div class="mx-search-result-item-meta-data-keywords">
					<span>Мітки:</span>

						<ul>

						<?php foreach ( $keywords as $key => $value ) { ?>

							<li><a href="<?php echo mxzsm_create_url_for_terms( 'key_word_id', $value->term_id ); ?>"><?php echo $value->name; ?></a></li>

						<?php } ?>

					</ul>
				</div>

			<?php endif; ?>

		<?php }

		// add keywords
		public static function mx_add_categories()
		{ ?>

			<!-- categories -->
			<?php $categories = get_the_terms( get_the_ID(), 'mxzsm_objects_category' ); ?>

			<?php if( $categories ) : ?>

				<div class="mx-search-result-item-meta-data-categories">

					<span>Категорії:</span>

						<ul>

						<?php foreach ( $categories as $key => $value ) { ?>

							<li>
								<a href="<?php echo mxzsm_create_url_for_terms( 'cat_id', $value->term_id ); ?>"><?php echo $value->name; ?></a>
							</li>

						<?php } ?>

					</ul>
					
				</div>

			<?php endif; ?>	

		<?php }

	/*
	* 'mx_search_result_item_footer'
	*/

		// add list of icons
		public static function mx_list_of_icons()
		{ ?>

			<div class="mx_list_of_icons">

				<?php $url = get_post_permalink(); ?>
				
				<a href="<?php echo $url; ?>" title="Відео"><i class="fa fa-youtube"></i></a>
				<a href="<?php echo $url; ?>#map" title="Точка на мапі"><i class="fa fa-map-marker"></i></a>
				<a href="<?php echo $url; ?>" title="Електронна адреса"><i class="fa fa-envelope-o"></i></a>
				<a href="<?php echo $url; ?>" title="Вебсайт"><i class="fa fa-globe"></i></a>
				<a href="<?php echo $url; ?>" title="Телефон"><i class="fa fa-phone"></i></a>
				<a href="<?php echo $url; ?>" title="Є доставка"><i class="fa fa-truck"></i></a>
				<a href="<?php echo $url; ?>" title="Цей об'єкт допомагає подолати Коронавірус (COVID-19)"><i class="fa fa-bug"></i></a>
							
	
			</div>

		<?php }

		// add region and city
		public static function mx_region_and_city()
		{ ?>

			<!-- region / city -->
			<?php
				$region_data = mxzsm_get_region_by_post_id( get_the_ID() );	

				$city_data = mxzsm_get_city_by_post_id( get_the_ID() );
			?>
			<div class="mx-result-region-city-wrap">

				<div class="mx-result-region">
					<a href="<?php echo mxzsm_create_url_for_regions( $region_data['region_id'] ); ?>"><?php echo $region_data['region_name']; ?></a>
				</div>

				<div class="mx-result-city">
					<a href="<?php echo mxzsm_create_url_for_city( $city_data['city_id'], $region_data['region_id'] ); ?>"><?php echo $city_data['city_name']; ?></a>
				</div>
				
			</div>

		<?php }
}