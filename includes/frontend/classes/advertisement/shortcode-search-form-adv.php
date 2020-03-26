<?php

class MXZSM_Shortcodes_Search_Form_Adv
{

	public static function add_shorcode() {

		// search form
		add_shortcode( 'mxzsm_search_form_adv', array( 'MXZSM_Shortcodes_Search_Form_Adv', 'search_form' ) );


	}

		public static function search_form( $atts ) {

			ob_start();

				$_get_ = mxzsm_check_get_set_get( $_GET );
				
				// get regions
				$results_regions = mxzsm_get_regions();

				// array of available region ids
				$available_region_ids = mxzsm_get_available_regions();

				// Get available cities
				$available_city_ids = mxzsm_get_available_cities();

			?>

				<div class="mx-search-by-cities bg-light p-5">
					
					<!-- <h3>Пошук Об'єктів</h3> -->

					<!-- regions -->
					<div class="mxzsm_regions">

						<h4>Оберіть область:</h4>

						<select name="mxzsm_regions" id="mxzsm_regions" required="required">

							<option value="">Область не вибрана</option>

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

						<h4>Оберіть населений пункт:</h4>

						<select name="mxzsm_cities" id="mxzsm_cities">
							
							<?php if( count( $cities ) !== 0 ) : ?>

								<option value="">Результат по всіх н-п області</option>

								<?php foreach ( $cities as $key => $value ) : ?>

									<?php if( ! in_array( $value->id, $available_city_ids ) ) continue; ?>

									<option value="<?php echo $value->id; ?>" <?php echo $value->id == $_get_['city_id'] ? 'selected' : ''; ?>><?php echo $value->city; ?></option>

								<?php endforeach; ?>

							<?php endif; ?>

						</select>

					</div>

					<?php

						$region = mxzsm_get_region_row_by_id( $_get_['region_id'] );

					?>

					<div class="mx-znayty-submit-button-wrap" <?php echo $region == NULL ? 'style="display: none;"' : ''; ?>>

						<?php if( isset( $atts['search_result_slug'] ) ) : ?>
						
							<button id="mx_znayty_submit_button" data-search-result-slug="<?php echo $atts['search_result_slug']; ?>">Знайти</button>

						<?php else : ?>

							<button id="mx_znayty_submit_button">Знайти</button>

						<?php endif; ?>

					</div>

						

				</div>

			<?php return ob_get_clean();
			
		}

}