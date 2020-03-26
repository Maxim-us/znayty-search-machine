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

		// save regions and cities
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_save' ) );

		// save address
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_address_save' ) );

		// save coordinates
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_coordinates_save' ) );

		// save website
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_website_save' ) );

		// save email
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_email_save' ) );

		// against covid
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_against_covid_save' ) );
		
		// service type
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_service_type_normal_mode_save' ) );

		// phone
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_phone_save' ) );

		// video
		add_action( 'save_post_mxzsm_objects', array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_boxes_youtube_save' ) );	

	}


		public static function mxzsm_meta_boxes()
		{			

			add_meta_box(
				'mxzsm_meta_regions_cities',
				'Обрати область та населений п-т',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_box_regions_callback' ),
				array( 'mxzsm_objects', 'mxzsm_adv_need' ),
				'normal'
			);

			add_meta_box(
				'mxzsm_meta_address_of_obj',
				'Адреса обєкта',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_address_of_obj_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);

			// global $post;

			// if( $post->post_status == 'verification' ) {

				// add obj data
				add_meta_box(
					'mxzsm_meta_add_obj_data',
					'Дані введені користувачем',
					array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_box_add_obj_data_callback' ),
					array( 'mxzsm_objects' ),
					'normal'
				);

			// }

			// coordinates
			add_meta_box(
				'mxzsm_meta_coordinates_of_obj',
				'Координати об\'єкта',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_coordinates_of_obj_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);

			// website
			add_meta_box(
				'mxzsm_meta_website_of_obj',
				'Вебсайт об\'єкта',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_website_of_obj_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);

			// email
			add_meta_box(
				'mxzsm_meta_email_of_obj',
				'Електронна пошта об\'єкта',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_email_of_obj_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);

			// against covid
			add_meta_box(
				'mxzsm_meta_against_covid_of_obj',
				'Чи допомагає цей об\'єкт подолати Коронавірус (COVID-19)?:',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_against_covid_of_obj_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);
			
			// service type
			add_meta_box(
				'mxzsm_meta_service_type_of_obj',
				'Режим роботи:',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_service_type_of_obj_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);

			// phone
			add_meta_box(
				'mxzsm_meta_phone_of_obj',
				'Телефон:',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_phone_of_obj_callback' ),
				array( 'mxzsm_objects', 'mxzsm_adv_need' ),
				'normal'
			);

			// youtube
			add_meta_box(
				'mxzsm_meta_youtube',
				'Відео:',
				array( 'MXZSMMetaboxCreationClass', 'mxzsm_meta_youtube_of_obj_callback' ),
				array( 'mxzsm_objects' ),
				'normal'
			);

		}

		// coordinated
		public static function mxzsm_meta_coordinates_of_obj_callback( $post, $meta )
		{

			$latitude = get_post_meta( $post->ID, '_mxzsm_obj_latitude', true );

			$longitude = get_post_meta( $post->ID, '_mxzsm_obj_longitude', true );

			echo '<p>
				<label for="#">Latitude</label><br>
				<input type="text" name="mxzsm_latitude_of_obj" id="mxzsm_latitude_of_obj" value="' . $latitude . '" />
			</p>';

			echo '<p>
				<label for="#">Longitude</label><br>
				<input type="text" name="mxzsm_longitude_of_obj" id="mxzsm_longitude_of_obj" value="' . $longitude . '" />
			</p>';

		}

		// address
		public static function mxzsm_meta_address_of_obj_callback( $post, $meta )
		{

			// check nonce
			wp_nonce_field( 'mxzsm_meta_box_address_action', 'mxzsm_meta_box_address_nonce' );

			$address = get_post_meta( $post->ID, '_mxzsm_address_of_obj', true );

			echo '<p>
				<label for="#"></label><br>
				<input type="text" name="mxzsm_address_of_obj" id="mxzsm_address_of_obj" value="' . $address . '" />
			</p>';

		}

		// regions / cities metadata
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

	// add obj from users
	public static function mxzsm_meta_box_add_obj_data_callback( $post, $meta )
	{

		$users_categories = get_post_meta( $post->ID, '_mxzsm_add_obj_categories', true );

		$users_keywords = get_post_meta( $post->ID, '_mxzsm_add_obj_keywords', true );

		$address = get_post_meta( $post->ID, '_mxzsm_address_of_obj', true );

		$latitude = get_post_meta( $post->ID, '_mxzsm_obj_latitude', true );

		$longitude = get_post_meta( $post->ID, '_mxzsm_obj_longitude', true );

		?>

		<p>
			<label for="#"><b>Категорії об'єкта:</b></label><br>
			<span><?php echo $users_categories; ?></span>
		</p>

		<p>
			<label for="#"><b>Ключові слова об'єкта:</b></label><br>
			<span><?php echo $users_keywords; ?></span>
		</p>

		<!-- <p>
			<label for="#"><b>Адреса об'єкта:</b></label><br>
			<span><?php echo $address; ?></span>
		</p>

		<p>
			<label for="#"><b>Координати об'єкта:</b></label><br>
			<span>Latitude: <?php echo $latitude; ?></span><br>
			<span>Longitude: <?php echo $longitude; ?></span>
		</p> -->

		<?php 

	}

	// save address
	public static function mxzsm_meta_boxes_address_save( $post_id )
	{

		if ( ! isset( $_POST['mxzsm_meta_box_address_nonce'] ) ) 
				return;

		if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_address_nonce'], 'mxzsm_meta_box_address_action') )
			return;

		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return;

		if( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// mxzsm_address_of_obj
		$address = sanitize_text_field( $_POST['mxzsm_address_of_obj'] );

		update_post_meta( $post_id, '_mxzsm_address_of_obj', $address  );

	}

	public static function mxzsm_meta_boxes_coordinates_save( $post_id )
	{

		if ( ! isset( $_POST['mxzsm_meta_box_address_nonce'] ) ) 
				return;

		if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_address_nonce'], 'mxzsm_meta_box_address_action') )
			return;

		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return;

		if( ! current_user_can( 'edit_post', $post_id ) )
			return;

		$latitude = sanitize_text_field( $_POST['mxzsm_latitude_of_obj'] );

			update_post_meta( $post_id, '_mxzsm_obj_latitude', $latitude  );

		$longitude = sanitize_text_field( $_POST['mxzsm_longitude_of_obj'] );

			update_post_meta( $post_id, '_mxzsm_obj_longitude', $longitude  );		

	}

	// website
	public static function mxzsm_meta_website_of_obj_callback( $post, $meta )
	{

		// check nonce
		wp_nonce_field( 'mxzsm_meta_box_website_action', 'mxzsm_meta_box_website_nonce' );

		$website = get_post_meta( $post->ID, '_mxzsm_obj_website', true );

		echo '<p>
			<label for="#"></label>
			<input type="url" name="mxzsm_obj_website" id="mxzsm_obj_website" value="' . $website . '" />
		</p>';

	}

		// save website meta
		public static function mxzsm_meta_boxes_website_save( $post_id )
		{

			if ( ! isset( $_POST['mxzsm_meta_box_website_nonce'] ) ) 
				return;

			if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_website_nonce'], 'mxzsm_meta_box_website_action') )
				return;

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

			if( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// mxzsm_regions
			$website = esc_url_raw( $_POST['mxzsm_obj_website'] );

			update_post_meta( $post_id, '_mxzsm_obj_website', $website  );

		}

	// email
	public static function mxzsm_meta_email_of_obj_callback( $post, $meta )
	{

		// check nonce
		wp_nonce_field( 'mxzsm_meta_box_email_action', 'mxzsm_meta_box_email_nonce' );

		$email = get_post_meta( $post->ID, '_mxzsm_obj_email', true );

		echo '<p>
			<label for="#"></label>
			<input type="email" name="mxzsm_obj_email" id="mxzsm_obj_email" value="' . $email . '" />
		</p>';

	}

		public static function mxzsm_meta_boxes_email_save( $post_id )
		{

			if ( ! isset( $_POST['mxzsm_meta_box_email_nonce'] ) ) 
				return;

			if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_email_nonce'], 'mxzsm_meta_box_email_action') )
				return;

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

			if( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// mxzsm_regions
			$email = sanitize_email( $_POST['mxzsm_obj_email'] );

			update_post_meta( $post_id, '_mxzsm_obj_email', $email  );

		}

	// against covid
	public static function mxzsm_meta_against_covid_of_obj_callback( $post, $meta )
	{

		// check nonce
		wp_nonce_field( 'mxzsm_meta_box_against_covid_action', 'mxzsm_meta_box_against_covid_nonce' );

		$against_covid = get_post_meta( $post->ID, '_mxzsm_obj_against_covid', true );

		echo '<p>
			<label for="#"></label>
			<input type="text" name="mxzsm_obj_against_covid" id="mxzsm_obj_against_covid" value="' . $against_covid . '" />
		</p>';

	}

		public static function mxzsm_meta_boxes_against_covid_save( $post_id )
		{

			if ( ! isset( $_POST['mxzsm_meta_box_against_covid_nonce'] ) ) 
				return;

			if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_against_covid_nonce'], 'mxzsm_meta_box_against_covid_action') )
				return;

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

			if( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// 
			$against_covid = sanitize_text_field( $_POST['mxzsm_obj_against_covid'] );

			update_post_meta( $post_id, '_mxzsm_obj_against_covid', $against_covid  );

		}

	// service type
	public static function mxzsm_meta_service_type_of_obj_callback( $post, $meta )
	{

		// check nonce
		wp_nonce_field( 'mxzsm_meta_box_service_type_action', 'mxzsm_meta_box_service_type_nonce' );

		$normal_mode = get_post_meta( $post->ID, '_mxzsm_obj_service_type_normal_mode', true );

		$takeaway = get_post_meta( $post->ID, '_mxzsm_obj_service_type_takeaway', true );

		$delivery = get_post_meta( $post->ID, '_mxzsm_obj_service_type_delivery', true );

		?>
		<div>
			<input type="checkbox" id="mxzsm_add_obj_service_type_normal_mode" name="mxzsm_add_obj_service_type_normal_mode" value="<?php echo $normal_mode == 1 ? '1' : ''; ?>" <?php echo $normal_mode == 1 ? 'checked' : ''; ?> /> <label for="mxzsm_add_obj_service_type_normal_mode">Звичайний режим</label>
		</div>

		<div>
			<input type="checkbox" id="mxzsm_add_obj_service_type_takeaway" name="mxzsm_add_obj_service_type_takeaway" value="<?php echo $takeaway == 1 ? '1' : ''; ?>" <?php echo $takeaway == 1 ? 'checked' : ''; ?> /> <label for="mxzsm_add_obj_service_type_takeaway">Торгівля на виніс</label>
		</div>

		<div>
			<input type="checkbox" id="mxzsm_add_obj_service_type_delivery" name="mxzsm_add_obj_service_type_delivery" value="<?php echo $delivery == 1 ? '1' : ''; ?>" <?php echo $delivery == 1 ? 'checked' : ''; ?> /> <label for="mxzsm_add_obj_service_type_delivery">Є доставка додому</label>
		</div>

		<?php 

	}

		public static function mxzsm_meta_boxes_service_type_normal_mode_save( $post_id )
		{

			if ( ! isset( $_POST['mxzsm_meta_box_service_type_nonce'] ) ) 
				return;

			if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_service_type_nonce'], 'mxzsm_meta_box_service_type_action') )
				return;

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

			if( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// 
			$normal_mode = '';			

				if( isset( $_POST['mxzsm_add_obj_service_type_normal_mode'] ) ) {

					$normal_mode = 1;

				}

				update_post_meta( $post_id, '_mxzsm_obj_service_type_normal_mode', $normal_mode  );

			// 
			$takeaway = '';			

				if( isset( $_POST['mxzsm_add_obj_service_type_takeaway'] ) ) {

					$takeaway = 1;

				}

				update_post_meta( $post_id, '_mxzsm_obj_service_type_takeaway', $takeaway  );

			// 
			$delivery = '';			

				if( isset( $_POST['mxzsm_add_obj_service_type_delivery'] ) ) {

					$delivery = 1;

				}

				update_post_meta( $post_id, '_mxzsm_obj_service_type_delivery', $delivery  );			

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

	// youtube
	public static function mxzsm_meta_youtube_of_obj_callback( $post, $meta )
	{

		// check nonce
		wp_nonce_field( 'mxzsm_meta_box_youtube_action', 'mxzsm_meta_box_youtube_nonce' );

		$video = get_post_meta( $post->ID, '_mxzsm_obj_video_youtube', true );

		preg_match( '/.*\?v=(.*)&?/', $video, $matches ); 

		echo '<p>
			<label for="#"></label>
			<input type="url" name="mxzsm_obj_youtube" id="mxzsm_obj_youtube" value="' . $video . '" />
		</p>';

		echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $matches[1] . '?controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

	}

		public static function mxzsm_meta_boxes_youtube_save( $post_id )
		{

			if ( ! isset( $_POST['mxzsm_meta_box_youtube_nonce'] ) ) 
				return;

			if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_youtube_nonce'], 'mxzsm_meta_box_youtube_action') )
				return;

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				return;

			if( ! current_user_can( 'edit_post', $post_id ) )
				return;

			// 
			$video = sanitize_text_field( $_POST['mxzsm_obj_youtube'] );

			update_post_meta( $post_id, '_mxzsm_obj_video_youtube', $video  );

		}

}