<?php

class MXZSM_Shortcode_Edit_Obj 
{ 

	public static function add_shorcode() {

		// search result
		add_shortcode( 'mxzsm_edit_obj', array( 'MXZSM_Shortcode_Edit_Obj', 'edit_obj' ) );

	}

	/*
	* Edit post
	*/
	public static function mx_edit_post()
	{

		/*
		* Add post
		*/
		add_action( 'wp_ajax_mxzsm_edit_obj_front', array( 'MXZSM_Shortcode_Edit_Obj', 'edit_post_action' ) );

			add_action( 'wp_ajax_nopriv_mxzsm_edit_obj_front', array( 'MXZSM_Shortcode_Edit_Obj', 'edit_post_action' ) );

		/*
		* Delete post
		*/
		add_action( 'wp_ajax_mxzsm_delete_obj_front', array( 'MXZSM_Shortcode_Edit_Obj', 'delete_post_action' ) );

			add_action( 'wp_ajax_nopriv_mxzsm_delete_obj_front', array( 'MXZSM_Shortcode_Edit_Obj', 'delete_post_action' ) );
		
	}

		// search result
		public static function edit_obj() {

			ob_start();

			// check post exists
			$_get_ = mxzsm_check_get_set_get( $_GET );

			$_post = mxzsm_get_post_by_id( $_get_['mx_post_id'] );

			if( $_post === NULL ) {

				mxzsm_alert( 'Сталася помилка!' );

				return;

			}

			// check author
			$current_author_id = intval( get_current_user_id() );

			$post_author_id = intval( $_post->post_author );

			if( $current_author_id !== $post_author_id ) {

				mxzsm_alert( 'Ви не маєте права редагувати цей пост!' );

				return;

			}

			// if user logged in
			if( ! is_user_logged_in() ) {

				mxzsm_alert( 'Вам потрібно <a href="/my-account/">увійти в систему</a> щоб додати новий об\'єкт.' );

				return;
			}

			// if user has "mxzsm_contr_obj" role
			$user_role = wp_get_current_user()->roles[0];

			if( $user_role !== 'mxzsm_contr_obj' AND $user_role !== 'administrator' ) {

				mxzsm_alert( 'Вам потрібно підтвердити свою електронну пошту.<br>Також, Ви можете написати нам через нашу електронну адресу: support@znayty.in.ua' );

				return;
			} 

			// check publish post
			if( $_post->post_status !== 'publish' ) {

				mxzsm_alert( 'Пост недоступний!' );

				return;

			}
			
			?>

			<form id="mxzsm_edit_obj" class="mxzsm_form_editable">
				

				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_title">Назва: <span class="mxzsm_required">*</span></label>
					<input type="text" id="mxzsm_add_obj_title" value="<?php echo esc_html( $_post->post_title ); ?>" required="required">
				</div>

				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_editor">Опис: <span class="mxzsm_required">*</span></label>

					<textarea name="mxzsm_add_obj_editor" id="mxzsm_add_obj_editor" cols="30" rows="10"><?php echo esc_html( $_post->post_content ); ?></textarea>

				</div>

				<?php
					$address = get_post_meta( $_post->ID, '_mxzsm_address_of_obj', true );
				?>				

				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_address">Адреса:</label>
					<input type="text" value="<?php echo $address; ?>" id="mxzsm_add_obj_address">
					<small>Введіть адресу. Наприклад: <em>м. Київ, вул. Головна, буд. 120</em></small>
				</div>

				<?php

					$website = get_post_meta( $_post->ID, '_mxzsm_obj_website', true );

				?>	
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_website">Вебсайт:</label>
					<input type="url" value="<?php echo $website; ?>" id="mxzsm_add_obj_website">
					<small>Вебсайт (якщо він є). Наприклад: <em>https://domain.com.ua</em></small>
				</div>

				<?php

					$email = get_post_meta( $_post->ID, '_mxzsm_obj_email', true );

				?>
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_email">Електронна адреса:</label>
					<input type="email" value="<?php echo $email; ?>" id="mxzsm_add_obj_email">
					<small>Електронна адреса (якщо вона є). Наприклад: <em>my_magazyn@gmail.com</em></small>
				</div>

				<?php

					$phone = get_post_meta( $_post->ID, '_mxzsm_obj_phone', true );

				?>
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_phone">Телефон:</label>
					<input type="tel" value="<?php echo $phone; ?>" id="mxzsm_add_obj_phone">
					<small>Телефон (якщо він є). Наприклад: <em>097 00 000 00</em></small>
				</div>

				<?php

					$normal_mode = get_post_meta( $_post->ID, '_mxzsm_obj_service_type_normal_mode', true );

					$takeaway = get_post_meta( $_post->ID, '_mxzsm_obj_service_type_takeaway', true );

					$delivery = get_post_meta( $_post->ID, '_mxzsm_obj_service_type_delivery', true );

				?>
				<div class="mx_add_obj_fields mx_checkbox_area">

					<h6>Режим роботи:</h6>

					<div>
						<input type="checkbox" id="mxzsm_add_obj_service_type_normal_mode" <?php echo $normal_mode == 1 ? 'checked' : ''; ?> /> <label for="mxzsm_add_obj_service_type_normal_mode">Звичайний режим</label>
					</div>

					<div>
						<input type="checkbox" id="mxzsm_add_obj_service_type_takeaway" <?php echo $takeaway == 1 ? 'checked' : ''; ?>> <label for="mxzsm_add_obj_service_type_takeaway">Торгівля на виніс</label>
					</div>

					<div>
						<input type="checkbox" id="mxzsm_add_obj_service_type_delivery" <?php echo $delivery == 1 ? 'checked' : ''; ?>> <label for="mxzsm_add_obj_service_type_delivery">Є доставка додому</label>
					</div>
					
				</div>

				<?php

					$video = get_post_meta( $_post->ID, '_mxzsm_obj_video_youtube', true );

				?>

				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_video_youtube">Відео з YouTube:</label>
					<input type="url" id="mxzsm_add_obj_video_youtube" value="<?php echo $video; ?>" />
					<small>Відео з YouTube (якщо воно є). Наприклад: <em>https://www.youtube.com/watch?v=96kmU6xM6iY</em></small>
				</div>

				<?php

					$img_id = '';
					$img = '';

					if( get_post_thumbnail_id( $_post->ID ) !== 0 ) {

						$img_id = get_post_thumbnail_id( $_post->ID );

						$img = get_the_post_thumbnail( $_post->ID, 'thumbnail' );

					}
				?>
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_image">Зображення:</label>
					<a href="#" id="mxzsm_add_obj_image">Обрати зображення</a>
					<!-- <a href="#" id="mx_delete_image" style="display: none;">Замінити зображення</a> -->
					<input type="hidden" id="mxzsm_add_obj_image_id" value="<?php echo $img_id; ?>" />
					<div class="mxzsm_add_obj_image_preview">
						<?php echo $img; ?>
					</div>
				</div>

				<?php

					$latitude = get_post_meta( $_post->ID, '_mxzsm_obj_latitude', true );

					$longitude = get_post_meta( $_post->ID, '_mxzsm_obj_longitude', true );

					$lat = 49.577;
					$lng = 31.458;
					$zoom = 7;

					if( $latitude !== '' ) {

						$lat = $latitude;
						$lng = $longitude;
						$zoom = 17;

					}

				?>

				<br>
				<!-- map -->
				<div class="mx_add_obj_fields">
					<label for="#">Вкажіть координати:</label>
					<div class="form-group row">
						<div class="col-6">
							<input type="text" id="mx_obj_latitude" value="<?php echo $latitude; ?>"  />
						</div>
						<div class="col-6">
							<input type="text" id="mx_obj_longitude" value="<?php echo $longitude; ?>"  />
						</div>
					</div>
					<small>Вам потрібно просто клацнути по карті в місці, де розміщений Ваш об'єкт.</small>
				</div>

				<style>
			      #map {
			        height: 700px;
			      }
			    </style>
				<script async defer
			    src="https://maps.googleapis.com/maps/api/js?key=----&callback=initMap">
			    </script>
				<div id="map" class="mx_add_obj_fields"></div>
			    <script>
			    	var markers = [];

				    function initMap() {

				        var mxLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};

				        var map = new google.maps.Map(document.getElementById('map'), {
				        	zoom: <?php echo $zoom; ?>,
				        	center: mxLatlng
				        });

				        var marker = new google.maps.Marker({
							position: mxLatlng,
							map: map
						});

						markers.push( marker );

				        map.addListener('click', function( e ) {
			        	
				        	for( i=0; i<markers.length; i++ ){

						        markers[i].setMap(null);

						    }

						    markers = [];

				        	var latitude = e.latLng.lat();

						    var longitude = e.latLng.lng();

						    var markerLatlng = {
						    	lat: latitude,
						    	lng: longitude
						    };

						    var marker = new google.maps.Marker({
					          position: markerLatlng,
					          map: map
					        });					  

					        markers.push( marker );

					        document.getElementById( 'mx_obj_latitude' ).setAttribute( 'value', latitude );

					        document.getElementById( 'mx_obj_longitude' ).setAttribute( 'value', longitude )	         

				        } );

				    }
			    </script>

				<input type="hidden" id="mxzsm_edit_obj_nonce" value="<?php echo wp_create_nonce( 'mxzsm_edit_obj_nonce_request' ); ?>" />

				<input type="hidden" id="mxzsm_post_id" value="<?php echo $_post->ID; ?>" />			

				<?php 

					$c_user = get_user_by( 'ID', $_post->post_author );

				?>

				<input type="hidden" id="mxzsm_current_user" value="<?php echo $c_user->display_name; ?>" />

				<input type="hidden" id="current_user_id" value="<?php echo $_post->post_author; ?>" />

				<div>

				<div>
					<input type="submit" value="Зберегти">
				</div>

			</form>

			<div class="text-right">

				<button type="button" id="mx_remove_obj" class="btn-lg btn btn-outline-danger">Видалити підприємство</button>
				
			</div>			

			<?php return ob_get_clean();

		}

	// edit
	public static function edit_post_action()
	{

		if( empty( $_POST['nonce'] ) ) wp_die();

		if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_edit_obj_nonce_request' ) ) {

			$post_ID = $_POST['post_id'];

			global $wpdb;

			$table = $wpdb->prefix . 'posts';

			$author = $wpdb->get_row(
				"SELECT post_author FROM $table
				WHERE ID = $post_ID"
			);

			$post_author = intval( $author->post_author );

			$current_author = intval( $_POST['current_user_id'] );

			if( $post_author !== $current_author ) {

				echo 'error';

				// send email to admin
				$email = get_user_by( 'ID', 1 )->user_email;

				$subject = 'Небезпека - б\'єкти!';

				$message = 'Щойно користувач "' . $_POST['current_user'] . '" з ID = "' . $current_author . '" намагався редагувати чужий об\'єкт. Необхідно відреагувати.';

				$headers = 'From: Знайти. Небезпека - об\'єкти <robot@znayty.com.ua>' . "\r\n";

				wp_mail( $email, $subject, $message, $headers );

				wp_die();

			}

			// var_dump( $author->post_author );

			// set address
			update_post_meta( $post_ID, '_mxzsm_address_of_obj', sanitize_text_field( $_POST['address'] ) );

			// google map
			// set latitude
			update_post_meta( $post_ID, '_mxzsm_obj_latitude', sanitize_text_field( $_POST['obj_latitude'] ) );

			// set longitude
			update_post_meta( $post_ID, '_mxzsm_obj_longitude', sanitize_text_field( $_POST['obj_longitude'] ) );

			// website
			update_post_meta( $post_ID, '_mxzsm_obj_website', esc_url_raw( $_POST['obj_website'] ) );

			// phone
			update_post_meta( $post_ID, '_mxzsm_obj_phone', sanitize_text_field( $_POST['obj_phone'] ) );

			// email
			update_post_meta( $post_ID, '_mxzsm_obj_email', sanitize_email( $_POST['obj_email'] ) );

			// service type
				update_post_meta( $post_ID, '_mxzsm_obj_service_type_normal_mode', sanitize_text_field( $_POST['normal_mode'] ) );

				update_post_meta( $post_ID, '_mxzsm_obj_service_type_takeaway', sanitize_text_field( $_POST['takeaway'] ) );

				update_post_meta( $post_ID, '_mxzsm_obj_service_type_delivery', sanitize_text_field( $_POST['delivery'] ) );

			// video from youtube
			update_post_meta( $post_ID, '_mxzsm_obj_video_youtube', esc_url_raw( $_POST['obj_video_youtube'] ) );

			// insert thumbnail
			if( $_POST['img_id'] !== '' ) {

				set_post_thumbnail( $post_ID, sanitize_text_field( $_POST['img_id'] ) );

			}

			// update data
			$title = sanitize_text_field( $_POST['title'] );

			$content = sanitize_textarea_field( $_POST['content'] );

			$wpdb->update( $table,
				array(
					'post_title' 	=> $title,
					'post_content' 	=> $content,
					'post_status' 	=> 'verification'
				),
				array( 'ID' => $post_ID )
			);

			// send email to admin
			$email = get_user_by( 'ID', 1 )->user_email;

			$subject = 'Об\'єкт редаговано!';

			$message = 'Щойно користувач "' . $_POST['current_user'] . '" відредагував об\'єкт "' . $title . '". Необхідна модерація.';

			$headers = 'From: Знайти. Редагування об\'єкта <robot@znayty.com.ua>' . "\r\n";

			wp_mail( $email, $subject, $message, $headers );

			echo 'edit';

		}

		wp_die();

	}
	
	public static function delete_post_action()
	{

		if( empty( $_POST['nonce'] ) ) wp_die();

		if( wp_verify_nonce( $_POST['nonce'], 'mxzsm_edit_obj_nonce_request' ) ) {

			$post_ID = $_POST['post_id'];

			global $wpdb;

			$table = $wpdb->prefix . 'posts';

			$author = $wpdb->get_row(
				"SELECT post_author FROM $table
				WHERE ID = $post_ID"
			);

			$post_author = intval( $author->post_author );

			$current_author = intval( get_current_user_id() );

			if( $post_author !== $current_author ) {

				echo 'error';

				// send email to admin
				$email = get_user_by( 'ID', 1 )->user_email;

				$subject = 'Небезпека - б\'єкти!';

				$message = 'Щойно користувач "' . $_POST['current_user'] . '" з ID = "' . $current_author . '" намагався видалити чужий об\'єкт. Необхідно відреагувати.';

				$headers = 'From: Знайти. Небезпека - об\'єкти <robot@znayty.com.ua>' . "\r\n";

				wp_mail( $email, $subject, $message, $headers );

				wp_die();

			}

			$wpdb->update( $table,
				array(
					'post_status' 	=> 'trash'
				),
				array( 'ID' => $post_ID )
			);

			echo 'removed';

			// send email to admin
			$title = sanitize_text_field( $_POST['title'] );

			$email = get_user_by( 'ID', 1 )->user_email;

			$subject = 'Видалено б\'єкти!';

			$message = 'Щойно користувач "' . $_POST['current_user'] . '" з ID = "' . $current_author . '" видалив об\'єкт "' . $title . '".';

			$headers = 'From: Знайти. Видалено об\'єкт <robot@znayty.com.ua>' . "\r\n";

			wp_mail( $email, $subject, $message, $headers );

			wp_die();

		}

	}

}