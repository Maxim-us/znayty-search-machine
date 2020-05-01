<?php

class MXZSM_Shortcode_Add_New_Obj 
{

	public static function add_shorcode() {

		// search result
		add_shortcode( 'mxzsm_add_new_obj', array( 'MXZSM_Shortcode_Add_New_Obj', 'add_new_obj' ) );

		// show images for particular user
		add_filter( 'ajax_query_attachments_args', array( 'MXZSM_Shortcode_Add_New_Obj', 'mx_function_current_user' ) );

	}

		// search result
		public static function add_new_obj() {

			ob_start();

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

			// obj on verification
			$result_obj_v = new WP_Query(

				array(
					'post_type' 		=> 'mxzsm_objects',
					'post_status'		=> 'verification',
					'order' 			=> 'DESC',
					'author'			=> get_current_user_id()
				)

			);

			// public obj
			global $wpdb;

			$table_posts = $wpdb->prefix . 'posts';

			$user_id = get_current_user_id();

			$results_posts_p = $wpdb->get_results(

				"SELECT ID, post_title, guid
					FROM
						$table_posts
					WHERE
						post_author = $user_id
						AND post_status = 'publish'
						AND post_type = 'mxzsm_objects'
					ORDER BY post_title"

			); ?>

			<!-- tabs -->
			<div class="mxzsm_users_obj_tabs">
				
				<ul class="mxzsm_users_obj_tabs_header">
					<li>
						<a href="#" class="mxzsm_users_obj_tab_item mxzsm_active" data-active-tab="add_form">Додати об'єкт</a>
					</li>
					<li>
						<a href="#" class="mxzsm_users_obj_tab_item verification" data-active-tab="verification">На модерації (<?php echo count( $result_obj_v->posts ); ?>)</a>
					</li>
					<li>
						<a href="#" class="mxzsm_users_obj_tab_item my-public-obj" data-active-tab="my_objs">Мої опубліковані об'єкти (<?php echo count( $results_posts_p ); ?>)</a>
					</li>
				</ul>

				<div class="mxzsm_users_obj_tabs_body">

					<!-- add obj -->
					<div class="mxzsm_users_obj_tabs_body_add_form">

						<?php

							$count_of_v_posts = 2;

							$available_management = true;

						?>

						<?php if( count( $result_obj_v->posts ) < $count_of_v_posts ) : ?>
							
							<?php							

								MXZSM_Shortcode_Add_New_Obj::add_obj_form();
							?>

						<?php else : ?>

							<?php $available_management = false; ?>

							<h4 style="text-align: center;">Ви відправили <?php echo $count_of_v_posts; ?> об'єкта на модерацію. Після затвердження, Ви зможете надістати ще. <br> Дякуємо Вам!</h4>

						<?php endif; ?>

					</div>

					<!-- obj verification -->
					<div class="mxzsm_users_obj_tabs_body_verification" style="display: none;"><?php
						MXZSM_Shortcode_Add_New_Obj::obj_verification( $result_obj_v );
					?></div>

					<!-- my objs -->
					<div class="mxzsm_users_obj_tabs_body_my_objs" style="display: none;"><?php
						MXZSM_Shortcode_Add_New_Obj::obj_public( $results_posts_p, $available_management );
					?></div>
					
				</div>

			</div>

			<?php
			return ob_get_clean();

		}

		// show images for particular user
		public static function mx_function_current_user( $query ) {

			if( current_user_can( 'administrator' ) ) return $query;

			$user_id = get_current_user_id();

			$query['author'] = $user_id;			

			return $query;
				
		}

		// add obj form
		public static function add_obj_form()
		{ ?>

			<form id="mxzsm_add_obj">

				<!-- title -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_title">Назва: <span class="mxzsm_required">*</span></label>
					<input type="text" id="mxzsm_add_obj_title" required="required" />
					<small>Введіть назву об'єкта. Наприклад: <em>Кафе "Роксолана"</em></small>
				</div>

				<!-- editor -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_editor">Опис: <span class="mxzsm_required">*</span></label>

					<textarea name="mxzsm_add_obj_editor" id="mxzsm_add_obj_editor" cols="30" rows="10"></textarea>

					<?php 

						// wp_editor( 'Опис об\'єкта', 'mxzsm_add_obj_editor', array(
						// 	'media_buttons' => false,
						// 	'textarea_name' => 'mxzsm_add_obj_textarea',
						// 	'editor_class' 	=> 'mxzsm_add_obj_content_editor',
						// 	'quicktags'		=> false
						// ) );

						
					?>
			</div>

				<!-- regions / cities -->
				<?php

					global $wpdb;

					$table_regions = $wpdb->prefix . 'regions';

					$results_regions = $wpdb->get_results(

						"SELECT id, region FROM $table_regions ORDER BY region"

					);

				?>
				<div class="mx_add_obj_fields">
					<!-- regions -->
					<div class="mxzsm_regions">

						<label for="mxzsm_regions">Оберіть область: <span class="mxzsm_required">*</span></label>

						<select name="mxzsm_regions" class="mxzsm_add_obj_regions" id="mxzsm_regions" required="required">

							<option value=""></option>

							<?php foreach ( $results_regions as $key => $value ) : ?>

								<option value="<?php echo $value->id; ?>"><?php echo $value->region; ?></option>

							<?php endforeach; ?>

						</select>
					</div>

					<!-- cities -->
					<div class="mxzsm_cities" style="display: none;">

						<label for="mxzsm_cities">Оберіть місто: <span class="mxzsm_required">*</span></label>

						<select name="mxzsm_cities" id="mxzsm_cities" required="required"></select>

						<div class="mx-loading-panel" style="display: none;">
							<img src="<?php echo MXZSM_PLUGIN_URL . 'includes/frontend/assets/img/loading.gif'; ?>" alt="">
						</div>

					</div>
				</div>

				<!-- categories of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_categories">Категорія: <span class="mxzsm_required">*</span></label>
					<input type="text" id="mxzsm_add_obj_categories" required="required" />
					<small>Введіть через кому категорії. Наприклад: <em>Кафе, Їдальня</em></small>
				</div>

				<!-- keywords of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_keywords">Мітки: <span class="mxzsm_required">*</span></label>
					<input type="text" id="mxzsm_add_obj_keywords" required="required" />
					<small>Введіть через кому мітки. Наприклад: <em>їжа, корпоратив, вареники</em></small>
				</div>

				<!-- address of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_address">Адреса:</label>
					<input type="text" id="mxzsm_add_obj_address"/>
					<small>Введіть адресу об'єкта. Наприклад: <em>м. Київ, вул. Головна, буд. 120</em></small>
				</div>

				<!-- website of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_website">Вебсайт:</label>
					<input type="url" id="mxzsm_add_obj_website" />
					<small>Вебсайт (якщо він є). Наприклад: <em>https://domain.com.ua</em></small>
				</div>

				<!-- email of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_email">Електронна адреса:</label>
					<input type="email" id="mxzsm_add_obj_email" />
					<small>Електронна адреса об'єкта (якщо вона є). Наприклад: <em>my_magazyn@gmail.com</em></small>
				</div>

				<!-- phone of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_phone">Телефон:</label>
					<input type="tel" id="mxzsm_add_obj_phone" />
					<small>Телефон об'єкта (якщо він є). Наприклад: <em>+380970000000</em></small>
				</div>

				<!-- against covid -->
				<div class="mx_add_obj_fields mx_checkbox_area">
					<h6>Чи допомагає цей об'єкт подолати Коронавірус (COVID-19)?:</h6>
					<input type="checkbox" id="mxzsm_add_obj_against_covid" /> <label for="mxzsm_add_obj_against_covid">Так, допомагає</label>
					<input type="text" id="mx_against_covid_details" style="display: none;" placeholder="Як саме?" />
				</div>

				<!-- service type -->
				<div class="mx_add_obj_fields mx_checkbox_area">

					<h6>Режим роботи:</h6>

					<div>
						<input type="checkbox" id="mxzsm_add_obj_service_type_normal_mode" checked/> <label for="mxzsm_add_obj_service_type_normal_mode">Звичайний режим</label>
					</div>

					<div>
						<input type="checkbox" id="mxzsm_add_obj_service_type_takeaway" /> <label for="mxzsm_add_obj_service_type_takeaway">Торгівля на виніс</label>
					</div>

					<div>
						<input type="checkbox" id="mxzsm_add_obj_service_type_delivery" /> <label for="mxzsm_add_obj_service_type_delivery">Є доставка додому</label>
					</div>
					
				</div>

				<!-- video from youtube -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_video_youtube">Відео з YouTube:</label>
					<input type="url" id="mxzsm_add_obj_video_youtube" />
					<small>Відео з YouTube (якщо воно є). Наприклад: <em>https://www.youtube.com/watch?v=96kmU6xM6iY</em></small>
				</div>

				<!-- object image -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_image">Зображення:</label>
					<a href="#" id="mxzsm_add_obj_image">Обрати зображення</a>
					<!-- <a href="#" id="mx_delete_image" style="display: none;">Замінити зображення</a> -->
					<input type="hidden" id="mxzsm_add_obj_image_id" />
					<div class="mxzsm_add_obj_image_preview"></div>
				</div>	

				<br>
				<!-- map -->
				<div class="mx_add_obj_fields">
					<label for="#">Вкажіть координати об'єкта:</label>
					<div class="form-group row">
						<div class="col-6">
							<input type="text" id="mx_obj_latitude" value=""  />
						</div>
						<div class="col-6">
							<input type="text" id="mx_obj_longitude" value=""  />
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

				        var mxLatlng = {lat: 49.577, lng: 31.458};

				        var map = new google.maps.Map(document.getElementById('map'), {
				        	zoom: 7,
				        	center: mxLatlng
				        });

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


				<input type="hidden" id="mxzsm_add_obj_nonce" value="<?php echo wp_create_nonce( 'mxzsm_add_obj_nonce_request' ); ?>" />

				<div>
					<input type="submit" value="Додати" />
				</div>
				
			</form>

		<?php }

		// objects on verification
		public static function obj_verification( $result_obj_v )
		{ ?>

			<table>
				<thead>
					<tr>
						<th>Назва об'єкта</th>						
					</tr>
				</thead>
				<tbody>

					<?php if( $result_obj_v->have_posts() ) : ?>

						<?php while( $result_obj_v->have_posts() ) : $result_obj_v->the_post(); ?>

							<?php MXZSM_Shortcode_Add_New_Obj::verification_obj_item(); ?>							

						<?php endwhile; ?> 

					<?php else : ?>
						<tr>
							<td>
								Жодного об'єкта на модерації.
								<a href="/add-new-object/" class="mxzsm__add_obj_empty_link">Додати об'єкт</a>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>

		<?php }

		// verification obj item
		public static function verification_obj_item()
		{ ?>

			<tr>
				<td><?php the_title(); ?></td>
			</tr>

		<?php }

		// objects on public
		public static function obj_public( $results_posts_p, $available_management )
		{ ?>

			<table>
				<thead>
					<tr>
						<th>Назва об'єкта</th>
						<th>Дії</th>
					</tr>
				</thead>
				<tbody>

					<?php if( count( $results_posts_p ) !== 0 ) : ?>

						<?php foreach ( $results_posts_p as $key => $value ) : ?>

							<?php MXZSM_Shortcode_Add_New_Obj::public_obj_item( $value->ID, $value->post_title, $value->guid, $available_management ); ?>							

						<?php endforeach; ?> 

					<?php else : ?>
						<tr>
							<td>
								Жодного опублікованого об'єкта.
								<a href="/add-new-object/" class="mxzsm__add_obj_empty_link">Додати об'єкт</a>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>

		<?php }

		// verification obj item
		public static function public_obj_item( $post_id, $post_title, $permalink, $available_management )
		{ ?>

			<tr>
				<td>
					<a href="<?php echo $permalink; ?>" target="_blank"><?php echo $post_title; ?></a>
				</td>				
				<td>

					<?php if( $available_management ) : ?>

						<a href="/redaguvaty-obyekt/?mx_post_id=<?php echo $post_id; ?>">Редагувати</a>
						
					<?php endif; ?>
				</td>
			</tr>

		<?php }
}