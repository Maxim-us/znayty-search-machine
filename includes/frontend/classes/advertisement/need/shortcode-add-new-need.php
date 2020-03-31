<?php

class MXZSM_Shortcode_Add_New_Need
{

	public static function add_shorcode() {

		// search result
		add_shortcode( 'mxzsm_add_new_need', array( 'MXZSM_Shortcode_Add_New_Need', 'add_new_need' ) );

	}

		// search result
		public static function add_new_need() {

			ob_start();

			// if user logged in
			if( ! is_user_logged_in() ) {

				mxzsm_alert( 'Вам потрібно <a href="/my-account/">увійти в систему</a> щоб додати нове оголошення.' );

				return;
			}

			// if user has "mxzsm_contr_obj" role
			$user_role = wp_get_current_user()->roles[0];

			if( $user_role !== 'mxzsm_contr_obj' AND $user_role !== 'administrator' ) {

				mxzsm_alert( 'Вам потрібно підтвердити свою електронну пошту.<br>Також, Ви можете написати нам через нашу електронну адресу: support@znayty.in.ua' );

				return;
			}

			// obj on verification
			$result_need_v = new WP_Query(

				array(
					'post_type' 		=> 'mxzsm_adv_need',
					'post_status'		=> 'verification_need',
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
						AND post_type = 'mxzsm_adv_need'
					ORDER BY post_title"

			); ?>

			<!-- tabs -->
			<div class="mxzsm_users_obj_tabs">
				
				<ul class="mxzsm_users_obj_tabs_header">
					<li>
						<a href="#" class="mxzsm_users_obj_tab_item mxzsm_active" data-active-tab="add_form">Додати оголошення</a>
					</li>
					<li>
						<a href="#" class="mxzsm_users_obj_tab_item verification" data-active-tab="verification">На модерації (<?php echo count( $result_need_v->posts ); ?>)</a>
					</li>
					<li>
						<a href="#" class="mxzsm_users_obj_tab_item my-public-obj" data-active-tab="my_objs">Мої опубліковані оголошення (<?php echo count( $results_posts_p ); ?>)</a>
					</li>
				</ul>

				<div class="mxzsm_users_obj_tabs_body">

					<!-- add obj -->
					<div class="mxzsm_users_obj_tabs_body_add_form">

						<?php $count_of_v_posts = 4; ?>

						<?php if( count( $result_need_v->posts ) < $count_of_v_posts ) : ?>
							
							<?php
								MXZSM_Shortcode_Add_New_Need::add_obj_form();
							?>

						<?php else : ?>

							<h4 style="text-align: center;">Ви відправили <?php echo $count_of_v_posts; ?> оголошень на модерацію. Після затвердження, Ви зможете надістати ще. <br> Дякуємо Вам!</h4>

						<?php endif; ?>

					</div>

					<!-- obj verification -->
					<div class="mxzsm_users_obj_tabs_body_verification" style="display: none;"><?php
						MXZSM_Shortcode_Add_New_Need::need_verification( $result_need_v );
					?></div>

					<!-- my objs -->
					<div class="mxzsm_users_obj_tabs_body_my_objs" style="display: none;"><?php
						MXZSM_Shortcode_Add_New_Need::need_public( $results_posts_p );
					?></div>
					
				</div>

			</div>

			<?php
			return ob_get_clean();

		}

		// add obj form
		public static function add_obj_form()
		{ ?>

			<form id="mxzsm_add_need">

				<!-- title -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_title">Назва: <span class="mxzsm_required">*</span></label>
					<input type="text" id="mxzsm_add_obj_title" required="required" />
					<small>Введіть назву. Наприклад: <em>Хочу купити 5 мішків цементу</em></small>
				</div>

				<!-- editor -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_editor">Опис: <span class="mxzsm_required">*</span></label>

					<textarea name="mxzsm_add_obj_editor" id="mxzsm_add_obj_editor" cols="30" rows="10"></textarea>

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

						<label for="mxzsm_regions_adv">Оберіть область: <span class="mxzsm_required">*</span></label>

						<select name="mxzsm_regions" class="mxzsm_add_obj_regions" id="mxzsm_regions_adv_need" required="required">

							<option value=""></option>

							<?php foreach ( $results_regions as $key => $value ) : ?>

								<option value="<?php echo $value->id; ?>"><?php echo $value->region; ?></option>

							<?php endforeach; ?>

						</select>
					</div>

					<!-- cities -->
					<div class="mxzsm_cities" style="display: none;">

						<label for="mxzsm_cities_adv">Оберіть місто: <span class="mxzsm_required">*</span></label>

						<select name="mxzsm_cities" id="mxzsm_cities_adv_need" required="required"></select>

						<div class="mx-loading-panel" style="display: none;">
							<img src="<?php echo MXZSM_PLUGIN_URL . 'includes/frontend/assets/img/loading.gif'; ?>" alt="">
						</div>

					</div>
				</div>

				<!-- categories of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_categories">Категорія: <span class="mxzsm_required">*</span></label>

					<select name="mxzsm_add_obj_categories" id="mxzsm_add_obj_categories" required="required">
						<option value=""></option>

						<option value="food">Продукти</option>
						<option value="household_chemicals">Побутова хімія</option>
						<option value="household_goods">Господарські товари</option>
						<option value="building">Будівельні матеріали</option>
						<option value="spare_parts">Запчастини</option>
						<option value="agriculture">С/Г товари</option>
						<option value="other">Інше</option>
					</select>

					<small>Оберіть категорію. Наприклад: <em>Побутова хімія</em></small>
				</div>

				<!-- phone of obj -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_obj_phone">Ваш телефон: <span class="mxzsm_required">*</span></label>
					<input type="tel" id="mxzsm_add_obj_phone"  required="required"/>
					<small>Ваш телефон. Наприклад: <em>097 00 000 00</em></small>
					
					<div class="mx-hide-phone-button" style="display: none;">
						<input type="checkbox" id="mxzsm_hide_phone_number" />
						<label for="mxzsm_hide_phone_number">Не показувати номер телефону.</label>
					</div>
				</div>

				<!-- need social -->
				<div class="mx_add_obj_fields">
					<label for="mxzsm_add_need_social">Профіль в соціальный мережі:</label>
					<input type="tel" id="mxzsm_add_need_social" />
					<small>Ви можете вказати посилання на Ваш профіль в соціальній мережі.</small>					
				</div>

				<input type="hidden" id="mxzsm_add_obj_nonce" value="<?php echo wp_create_nonce( 'mxzsm_add_obj_nonce_request' ); ?>" />

				<div>
					<input type="submit" value="Додати Оголошення" />
				</div>
				
			</form>

		<?php }

		// objects on verification
		public static function need_verification( $result_need_v )
		{ ?>

			<table>
				<thead>
					<tr>
						<th>Назва</th>
					</tr>
				</thead>
				<tbody>

					<?php if( $result_need_v->have_posts() ) : ?>

						<?php while( $result_need_v->have_posts() ) : $result_need_v->the_post(); ?>

							<?php MXZSM_Shortcode_Add_New_Need::verification_meed_item(); ?>							

						<?php endwhile; ?> 

					<?php else : ?>
						<tr>
							<td>
								Жодного опублікованого оголошення.
								<a href="/dodaty-ogoloshennya-meni-potribno/" class="mxzsm__add_obj_empty_link">Додати оголошення</a>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>

		<?php }

		// verification obj item
		public static function verification_meed_item()
		{ ?>

			<tr>
				<td><?php the_title(); ?></td>
			</tr>

		<?php }

		// objects on public
		public static function need_public( $results_posts_p )
		{ ?>

			<table>
				<thead>
					<tr>
						<th>Назва</th>
					</tr>
				</thead>
				<tbody>

					<?php if( count( $results_posts_p ) !== 0 ) : ?>

						<?php foreach ( $results_posts_p as $key => $value ) : ?>

							<?php MXZSM_Shortcode_Add_New_Need::public_need_item( $value->post_title, $value->guid ); ?>							

						<?php endforeach; ?> 

					<?php else : ?>
						<tr>
							<td>
								Жодного опублікованого оголошення.
								<a href="/dodaty-ogoloshennya-meni-potribno/" class="mxzsm__add_obj_empty_link">Додати оголошення</a>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>

		<?php }

		// verification obj item
		public static function public_need_item( $post_title, $permalink )
		{ ?>

			<tr>
				<td>
					<a href="<?php echo $permalink; ?>" target="_blank"><?php echo $post_title; ?></a>
				</td>
			</tr>

		<?php }
}