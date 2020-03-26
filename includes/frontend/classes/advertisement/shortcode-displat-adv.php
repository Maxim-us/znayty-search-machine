<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSM_Shortcodes_Display_Adv
{

	static public function add_adv_shorcode()
	{

		// 
		add_shortcode( 'mxzsm_advertisement_display', array( 'MXZSM_Shortcodes_Display_Adv', 'mxzsm_advertisement_display' ) );

	}

		static public function mxzsm_advertisement_display()
		{

			ob_start();

				if( count( $_GET ) == 0 ) return;
			
				// check $_GET			
				$_get_ = mxzsm_check_get_set_get( $_GET );

				if( $_get_['region_id'] == '0' ) return;

				if( $_get_['region_id'] > 27 ) return;

				$posts_per_page = 16;

				// get region by id
				$row_region = mxzsm_get_region_row_by_id( $_get_['region_id'] );

				// get cities by region id
				$results_cities = mxzsm_get_cities_by_region_id( $_get_['region_id'] );
				
				// get city row by id
				$row_city = mxzsm_get_city_row_by_id( $_get_['city_id'] );

				// 

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

				$tax_query = array();

				$result_adv = new WP_Query( 

					array(
						'post_type' 		=> 'mxzsm_adv_need',
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

			<div class="mx-advertisement-wrap">
				
				<?php 

					// var_dump( $result_adv->have_posts() );

				if( $result_adv->have_posts() ) : ?>

					<!-- result -->
					<div class="mx-search-result-wrap_add">

						<!-- system results ... -->
						<?php MXZSM_Shortcodes_Display_Adv::adv_search_system_info( array(

							'row_region'	=> $row_region,
							'row_city' 		=> $row_city,
							'result_obj'	=> $result_adv,
							'region_id' 	=> $_get_['region_id'],
							'city_id'		=> $_get_['city_id'],
							'cat_id'		=> $_get_['cat_id']

						) ); ?>
						<!-- ... system results -->

						<div class="adv_search_result_items_wrap">			
						
							<!-- search result loop ... -->
							<?php while( $result_adv->have_posts() ) : $result_adv->the_post(); ?>

								<?php MXZSM_Shortcodes_Display_Adv::adv_search_result_item(); ?>

							<?php endwhile; ?> 
							<!-- ... search result loop -->

						</div>

					</div>

					<!-- pagination ... -->
					<?php mxzsm_navigation( 'mxzsm_adv_need', $posts_per_page, $meta_query, $tax_query ); ?>
					<!-- ... pagination -->
 
				<?php endif; ?>

			</div>

			<?php
			return ob_get_clean();

		}


	/**
	* Elements
	*/
		/*
		* Display system info
		*/
		public static function adv_search_system_info( $args ) { ?>

			<div class="alert alert-secondary mx-search-system-info-wrap" id="mx_adv_search_system_info">

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

				</ul>

			</div>

		<?php }



		/*
		*
		*/
		public static function adv_search_result_item()
		{ ?>

			<div class="mx-adv_search_result_item">

				<div>

					<div class="mx-adv_search_result_item_cat">

						<?php $categories = get_the_terms( get_the_ID(), 'mxzsm_adv_category' ); ?>

						<ul>
							
							<?php foreach ( $categories as $key => $value ) {

								echo  '<li><a href="#">' . $value->name . '</a></li>';

							} ?>


						</ul>

					</div>

					<div class="mx-avatar_adv">
						<?php 
							
							mx_display_avatar();

						?>
					</div>

					<div class="mx-adv_search_result_item_title">
						
						<a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a>
						
					</div>

					<div class="mx-adv_search_result_item_text">
						<?php the_excerpt(); ?>
					</div>

					<div class="mx-adv_search_result_item_contact">

						<?php

						$phone = get_post_meta( get_the_ID(), '_mxzsm_obj_phone', true );

						?>

						<i class="fa fa-phone"></i> <?php mx_hide_phone( $phone ); ?>
						
					</div>

					<div class="mx-adv_search_result_item_date">
						25.03.20
					</div>

				</div>
				
			</div>

		<?php }


}