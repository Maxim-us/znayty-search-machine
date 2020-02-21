<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSMMetaboxCreationClass
{

	/*
	* MXZSMMetaboxCreationClass constructor
	*/
	public function __construct()
	{		

	}

	/*
	* create metabox function
	*/
	public static function createMetaBox()
	{

		add_action( 'add_meta_boxes', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes' ) );

		// 
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_save' ) );

	}


		public static function mxzsm_meta_boxes()
		{

			add_meta_box(
				'mxzsm_meta_regions_cities',
				'Обрати область та населений п-т',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_box_regions_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);

		}

		public static function mxzsm_meta_box_regions_callback( $post, $meta )
		{

			// check nonce
			wp_nonce_field( 'mxzsm_meta_box_region_action', 'mxzsm_meta_box_region_nonce' );

			$region_id = get_post_meta( $post->ID, '_mxzsm_region_id', true );

			$city_id = get_post_meta( $post->ID, '_mxzsm_city_id', true );

			global $wpdb;

			$table_regions = $wpdb->prefix . 'regions';

			$results_regions = $wpdb->get_results(
				"SELECT id, region FROM $table_regions ORDER BY region"
			);

			$table_cities = $wpdb->prefix . 'cities';

			$results_cities = $wpdb->get_results(

				"SELECT id, city FROM $table_cities WHERE region_id = '" . $region_id . "'"
				
			);

			?>
				<!-- regions -->
				<div class="mxzsm_regions">

					<p>Оберіть область:</p>

					<select name="mxzsm_regions" id="mxzsm_regions" required="required">

						<option value=""></option>

						<?php foreach ( $results_regions as $key => $value ) : ?>

							<?php if( $region_id !== NULL ) : ?>

								<option value="<?php echo $value->id; ?>" <?php echo $region_id == $value->id ? 'selected' : ''; ?>><?php echo $value->region; ?></option>

							<?php else : ?>

								<option value="<?php echo $value->id; ?>"><?php echo $value->region; ?></option>

							<?php endif; ?>

						<?php endforeach; ?>

					</select>
				</div>

				<!-- cities -->
				<div class="mxzsm_cities" <?php echo $city_id == NULL ? 'style="display: none;"' : ''; ?>>

					<p>Оберіть місто:</p>

					<select name="mxzsm_cities" id="mxzsm_cities" required="required">
						
						<?php if( $results_cities !== NULL ) : ?>

							<?php foreach ( $results_cities as $key => $value ) : ?>

								<?php if( $city_id !== NULL ) : ?>

									<option value="<?php echo $value->id; ?>" <?php echo $city_id == $value->id ? 'selected' : ''; ?>><?php echo $value->city; ?></option>

								<?php else : ?>

									<option value="<?php echo $value->id; ?>"><?php echo $value->city; ?></option>

								<?php endif; ?>				

							<?php endforeach; ?>

						<?php endif; ?>

					</select>

				</div>
			<?php

		}

		// 
		public static function mxzsm_meta_boxes_save( $post_id )
		{

			if ( ! isset( $_POST['mxzsm_meta_box_region_nonce'] ) ) 
				return;

			if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_region_nonce'], 'mxzsm_meta_box_region_action') )
				return;

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

			if( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// mxzsm_regions
			$region = sanitize_text_field( $_POST['mxzsm_regions'] );

			update_post_meta( $post_id, '_mxzsm_region_id', $region  );

			// mxzsm_cities
			$city = sanitize_text_field( $_POST['mxzsm_cities'] );			

			update_post_meta( $post_id, '_mxzsm_city_id', $city  );

		}
}