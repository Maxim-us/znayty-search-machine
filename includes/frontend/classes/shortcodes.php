<?php

class MXZSM_shortcodes
{

	public static function add_shorcodes() {

		// search form
		add_shortcode( 'mxzsm_search_form', array( 'MXZSM_shortcodes', 'search_form' ) );

		// search result
		add_shortcode( 'mxzsm_search_result', array( 'MXZSM_shortcodes', 'search_result' ) );

	}

		public static function search_form() {

			ob_start();

				$_get_ = mxzsm_check_get_set_get( $_GET );
				
				// get regions
				$results_regions = mxzsm_get_regions();

				// array of available region ids
				$available_region_ids = mxzsm_get_available_regions();

				// Get available cities
				$available_city_ids = mxzsm_get_available_cities();

			?>

				<div class="mx-search-by-cities">
					
					<h3>Пошук по містам</h3>

					<!-- regions -->
					<div class="mxzsm_regions">

						<h4>Оберіть область:</h4>

						<select name="mxzsm_regions" id="mxzsm_regions" required="required">

							<option value=""></option>

							<?php foreach ( $results_regions as $key => $value ) : ?>

								<?php if( ! in_array( $value->id, $available_region_ids ) ) continue; ?>
								
								<option value="<?php echo $value->id; ?>" <?php echo $_get_['region_id'] == $value->id ? 'selected' : '';  ?>><?php echo $value->region; ?></option>

							<?php endforeach; ?>

						</select>
					</div>

					<!-- cities -->
					<?php 

						$cities = mxzsm_get_cities_by_region_id( $_get_['region_id'] );

					?>
					<div class="mxzsm_cities" <?php echo count( $cities ) == 0 ? 'style="display: none;"' : ''; ?>>

						<h4>Оберіть місто:</h4>

						<select name="mxzsm_cities" id="mxzsm_cities">
							
							<?php if( count( $cities ) !== 0 ) : ?>

								<option value=""></option>

								<?php foreach ( $cities as $key => $value ) : ?>

									<?php if( ! in_array( $value->id, $available_city_ids ) ) continue; ?>

									<option value="<?php echo $value->id; ?>" <?php echo $value->id == $_get_['city_id'] ? 'selected' : ''; ?>><?php echo $value->city; ?></option>

								<?php endforeach; ?>

							<?php endif; ?>

						</select>

					</div>

					<br>

					<?php

						$region = mxzsm_get_region_row_by_id( $_get_['region_id'] );

					?>

					<div class="mx-znayty-submit-button-wrap" <?php echo $region == NULL ? 'style="display: none;"' : ''; ?>>
						
						<button id="mx_znayty_submit_button">Знайти</button>

					</div>

						

				</div>

			<?php return ob_get_clean();
			
		}

		// search result
		public static function search_result() {

			if( count( $_GET ) == 0 ) return;
			
			// check $_GET			
			$_get_ = mxzsm_check_get_set_get( $_GET );

			if( $_get_['region_id'] == 0 ) return;			

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

					$meta_query = array();

					if( $row_region == NULL ) {

						mxzsm_nothing_found( 'Область не знайдено!' );						

					} else if( $row_city == NULL ) {

						mxzsm_nothing_found( 'Місто не вказано!' );

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

				$result_obj = new WP_Query(

					array(
						'post_type' 		=> 'mxzsm_objects', 
						'posts_per_page' 	=> $posts_per_page,
						'order' 			=> 'DESC',
						'meta_query'		=> $meta_query,
						'paged' 			=> $_get_['res_page']
					)

				);				

				?>

					<? if( $result_obj->have_posts() ) : ?>

						<!-- result -->
						<div class="mx-search-result-wrap">

						<? while( $result_obj->have_posts() ) : $result_obj->the_post(); ?>

							<div class="mx-search-result-item">
							
								<div class="mx-search-result-item-thumb">
									<img src="<?php echo get_the_post_thumbnail_url( 'medium' ); ?>" alt="">
								</div>

								<div class="mx-search-result-item-desc">
									<div class="mx-search-result-item-title">
										<a href="<?php get_post_permalink(); ?>"><?php echo get_the_title(); ?></a>
									</div>

									<div class="mx-search-result-item-excerp">
										<?php the_excerpt(); ?>
									</div>
								</div>

							</div>							

						<?php endwhile; ?> 

						</div>

						<?php mxzsm_navigation( 'mxzsm_objects', $posts_per_page, $meta_query ); ?>

					<?php endif;?>

				
					
					

				
			</div>

			<?php return ob_get_clean();
			
		}

}