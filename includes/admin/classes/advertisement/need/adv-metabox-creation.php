<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSMADVMetaboxCreationClass
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

		add_action( 'add_meta_boxes', array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_boxes' ) );

		// save regions and cities
		add_action( 'save_post_mxzsm_adv_need', array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_boxes_save_regions' ) );

		// save social
		add_action( 'save_post_mxzsm_adv_need', array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_boxes_save_social' ) );

		// phone
		add_action( 'save_post_mxzsm_adv_need', array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_boxes_phone_save' ) );
	}


		public static function mxzsm_meta_boxes()
		{ 

			add_meta_box(
				'mxzsm_meta_regions_cities_adv_need',
				'Обрати область та населений п-т',
				array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_box_regions_callback' ),
				array( 'mxzsm_adv_need' ),
				'normal'
			);

			add_meta_box(
				'mxzsm_meta_social_profile',
				'Соціальний профіль',
				array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_box_social_profile_callback' ),
				array( 'mxzsm_adv_need' ),
				'normal'
			);

			add_meta_box(
				'mxzsm_meta_show_info',
				'Додаткова інформація',
				array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_box_show_info_callback' ),
				array( 'mxzsm_adv_need' ),
				'normal'
			);

			// phone
			add_meta_box(
				'mxzsm_meta_phone_of_obj_need',
				'Телефон:',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_phone_of_obj_callback' ),
				array( 'mxzsm_adv_need' ),
				'normal'
			);

		}

		// social field
		public static function mxzsm_meta_box_social_profile_callback( $post, $meta )
		{

			// check nonce
			wp_nonce_field( 'mxzsm_meta_box_social_profile_action', 'mxzsm_meta_box_social_profile_nonce' );

			$social = get_post_meta( $post->ID, '_mxzsm_user_social', true );

			echo '<p>
				<label for="#"></label>
				<input type="url" name="mxzsm_social_profile" id="mxzsm_social_profile" value="' . $social . '" />
			</p>';


		}

			// save social
			public static function mxzsm_meta_boxes_save_social( $post_id )
			{

				if ( ! isset( $_POST['mxzsm_meta_box_social_profile_nonce'] ) ) 
					return;

				if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_social_profile_nonce'], 'mxzsm_meta_box_social_profile_action') )
					return;

				if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
					return;

				if( ! current_user_can( 'edit_post', $post_id ) )
					return;

				// mxzsm_regions
				$social = esc_url_raw( $_POST['mxzsm_social_profile'] );

				update_post_meta( $post_id, '_mxzsm_user_social', $social );

			}

		// add info
		public static function mxzsm_meta_box_show_info_callback( $post, $meta )
		{

			$cat = get_post_meta( $post->ID, '_mxzsm_add_obj_categories', true );

			$cat_name = $cat == 'food' ? 'Продукти' : '';

			$cat_name = $cat == 'household_chemicals' ? 'Побутова хімія' : $cat_name;

			$cat_name = $cat == 'household_goods' ? 'Господарські товари' : $cat_name;

			$cat_name = $cat == 'building' ? 'Будівельні матеріали' : $cat_name;

			$cat_name = $cat == 'spare_parts' ? 'Запчастини' : $cat_name;

			$cat_name = $cat == 'agriculture' ? 'С/Г товари' : $cat_name;

			$cat_name = $cat == 'other' ? 'Інше' : $cat_name; 

			echo '<p>
				<label for="#"><b>Категорія</b></label>
				<br>
				<span>' . $cat_name . '</span>
			</p>';

		}

	// regions / cities metadata
	public static function mxzsm_meta_box_regions_callback( $post, $meta )
	{

		// check nonce
		wp_nonce_field( 'mxzsm_meta_box_region_action', 'mxzsm_meta_box_region_nonce' );

		$region_id = get_post_meta( $post->ID, '_mxzsm_region_id_adv_need', true );

		$city_id = get_post_meta( $post->ID, '_mxzsm_city_id_adv_need', true );

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
		public static function mxzsm_meta_boxes_save_regions( $post_id )
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

			update_post_meta( $post_id, '_mxzsm_region_id_adv_need', $region  );

			// mxzsm_cities
			$city = sanitize_text_field( $_POST['mxzsm_cities'] );			

			update_post_meta( $post_id, '_mxzsm_city_id_adv_need', $city  );

		}

	// phone
	public static function mxzsm_meta_phone_of_obj_callback( $post, $meta )
	{

		// check nonce
		wp_nonce_field( 'mxzsm_meta_box_phone_action', 'mxzsm_meta_box_phone_nonce' );

		$phone = get_post_meta( $post->ID, '_mxzsm_obj_phone', true );

		echo '<p>
			<label for="#"></label>
			<input type="text" name="mxzsm_obj_phone" id="mxzsm_obj_phone" value="' . $phone . '" />
		</p>';

	}

		public static function mxzsm_meta_boxes_phone_save( $post_id )
		{

			if ( ! isset( $_POST['mxzsm_meta_box_phone_nonce'] ) ) 
				return;

			if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_phone_nonce'], 'mxzsm_meta_box_phone_action') )
				return;

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

			if( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// 
			$phone = sanitize_text_field( $_POST['mxzsm_obj_phone'] );

			update_post_meta( $post_id, '_mxzsm_obj_phone', $phone  );

		}

}