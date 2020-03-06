<?php

class MXZSM_Shortcode_Search_Result
{

	public static function add_shorcode() {

		// search result
		add_shortcode( 'mxzsm_search_result', array( 'MXZSM_Shortcode_Search_Result', 'search_result' ) );

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
		public static function search_result_item() {

			$the_thumbnail = get_the_post_thumbnail_url( get_the_ID(), 'znayty-thumbnail' ) == false ? MXZSM_PLUGIN_URL . 'includes/frontend/assets/img/empty-thum.png' : get_the_post_thumbnail_url( get_the_ID(), 'znayty-thumbnail' );

			?>

			<div class="mx-search-result-wrap">

				<div class="mx-search-result-item">
				
					<div class="mx-search-result-item-thumb">

						<a href="<?php echo get_post_permalink(); ?>">
							<img src="<?php echo $the_thumbnail; ?>" class="" alt="" />
						</a>

						<!-- autor -->
						<div class="mx-search-result-item-meta-data-autor">
							
							<span>Автор:</span>
							<?php echo get_the_author(); ?>

						</div>

					</div>

					<div class="mx-search-result-item-desc">
						<div class="mx-search-result-item-title">
							<a href="<?php echo get_post_permalink(); ?>"><?php echo get_the_title(); ?></a>
						</div>

						<div class="mx-search-result-item-excerp">
							<?php the_excerpt(); ?>
						</div>						

					</div>

					<!-- meta data -->
					<!-- keywords -->
					<div class="mx-meta-keywords-data">

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

					</div>
					<!--  -->

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

				</div>

			</div>

		<?php }

}