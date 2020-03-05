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

				mxzsm_alert( 'Вам потрібно підтвердити свою електронну пошту.' );

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

				"SELECT post_title, guid
					FROM
						$table_posts
					WHERE
						post_author = $user_id
						AND post_status = 'publish'
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
					<div class="mxzsm_users_obj_tabs_body_add_form"><?php
						MXZSM_Shortcode_Add_New_Obj::add_obj_form();
					?></div>

					<!-- obj verification -->
					<div class="mxzsm_users_obj_tabs_body_verification" style="display: none;"><?php
						MXZSM_Shortcode_Add_New_Obj::obj_verification( $result_obj_v );
					?></div>

					<!-- my objs -->
					<div class="mxzsm_users_obj_tabs_body_my_objs" style="display: none;"><?php
						MXZSM_Shortcode_Add_New_Obj::obj_public( $results_posts_p );
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
				<div>
					<label for="mxzsm_add_obj_title">Назва об'єкта:</label>
					<input type="text" id="mxzsm_add_obj_title" required="required" />
					<small>Введіть назву об'єкта. Наприклад: <em>Кафе "Роксолана"</em></small>
				</div>

				<!-- editor -->
				<div>
					<label for="mxzsm_add_obj_editor">Опис об'єкта:</label><?php

					wp_editor( 'Опис об\'єкта', 'mxzsm_add_obj_editor', array(
						'media_buttons' => false,
						'textarea_name' => 'mxzsm_add_obj_textarea',
						'editor_class' 	=> 'mxzsm_add_obj_content_editor',
						'quicktags'		=> false
					) );

					
				?></div>

				<!-- regions / cities -->
				<?php

					global $wpdb;

					$table_regions = $wpdb->prefix . 'regions';

					$results_regions = $wpdb->get_results(

						"SELECT id, region FROM $table_regions ORDER BY region"

					);

				?>
				<div>
					<!-- regions -->
					<div class="mxzsm_regions">

						<label for="mxzsm_regions">Оберіть область:</label>

						<select name="mxzsm_regions" class="mxzsm_add_obj_regions" id="mxzsm_regions" required="required">

							<option value=""></option>

							<?php foreach ( $results_regions as $key => $value ) : ?>

								<option value="<?php echo $value->id; ?>"><?php echo $value->region; ?></option>

							<?php endforeach; ?>

						</select>
					</div>

					<!-- cities -->
					<div class="mxzsm_cities" style="display: none;">

						<label for="mxzsm_cities">Оберіть місто:</label>

						<select name="mxzsm_cities" id="mxzsm_cities" required="required"></select>

					</div>
				</div>

				<!-- categories of obj -->
				<div>
					<label for="mxzsm_add_obj_categories">Категорія об'єкта:</label>
					<input type="text" id="mxzsm_add_obj_categories" required="required" />
					<small>Введіть через кому категорії. Наприклад: <em>Кафе, Їдальня</em></small>
				</div>

				<!-- keywords of obj -->
				<div>
					<label for="mxzsm_add_obj_keywords">Мітки об'єкта:</label>
					<input type="text" id="mxzsm_add_obj_keywords" required="required" />
					<small>Введіть через кому мітки. Наприклад: <em>їжа, корпоратив, вареники</em></small>
				</div>

				<!-- address of obj -->
				<div>
					<label for="mxzsm_add_obj_address">Адреса об'єкта:</label>
					<input type="text" id="mxzsm_add_obj_address" required="required" />
					<small>Введіть адресу об'єкта. Наприклад: <em>Місто Київ, вул. Головна, буд. 120</em></small>
				</div>

				<!-- object image -->
				<div>
					<label for="mxzsm_add_obj_image">Зображення об'єкта:</label>
					<a href="#" id="mxzsm_add_obj_image">Обрати зображення</a>
					<input type="hidden" id="mxzsm_add_obj_image_id" />
					<div class="mxzsm_add_obj_image_preview"></div>
				</div>
				<br>

				<input type="hidden" id="mxzsm_add_obj_nonce" value="<?php echo wp_create_nonce( 'mxzsm_add_obj_nonce_request' ); ?>" />

				<div>
					<input type="submit" value="Додати Об'єкт" />
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
		public static function obj_public( $results_posts_p )
		{ ?>

			<table>
				<thead>
					<tr>
						<th>Назва об'єкта</th>
					</tr>
				</thead>
				<tbody>

					<?php if( count( $results_posts_p ) !== 0 ) : ?>

						<?php foreach ( $results_posts_p as $key => $value ) : ?>

							<?php MXZSM_Shortcode_Add_New_Obj::public_obj_item( $value->post_title, $value->guid ); ?>							

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
		public static function public_obj_item( $post_title, $permalink )
		{ ?>

			<tr>
				<td>
					<a href="<?php echo $permalink; ?>" target="_blank"><?php echo $post_title; ?></a>
				</td>
			</tr>

		<?php }
}