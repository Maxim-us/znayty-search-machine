<?php

class MXZSM_Shortcode_Add_New_Obj
{

	public static function add_shorcode() {

		// search result
		add_shortcode( 'mxzsm_add_new_obj', array( 'MXZSM_Shortcode_Add_New_Obj', 'add_new_obj' ) );

		// 
		add_filter( 'ajax_query_attachments_args', array( 'MXZSM_Shortcode_Add_New_Obj', 'mx_function_current_user' ) );

	}

		// search result
		public static function add_new_obj() {

			ob_start();

			// if user logged in
			if( ! is_user_logged_in() ) {

				mxzsm_alert( 'Вам потрібно увійти в систему щоб додати новий об\'єкт.' );

				return;
			}

			// if user has "mxzsm_contr_obj" role
			$user_role = wp_get_current_user()->roles[0];			

			if( $user_role !== 'mxzsm_contr_obj' AND $user_role !== 'administrator' ) {

				mxzsm_alert( 'Вам потрібно підтвердити свою електронну пошту.' );

				return;
			} ?>

			<form id="mxzsm_add_obj">

				<!-- title -->
				<div>
					<label for="mxzsm_add_obj_title">Назва об'єкта:</label>
					<input type="text" id="mxzsm_add_obj_title" />
				</div>

				<!-- editor -->
				<div>
					<label for="mxzsm_add_obj_editor">Опис об'єкта:</label><?php

					wp_editor( 'content', 'mxzsm_add_obj_editor', array(
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
					<input type="text" id="mxzsm_add_obj_categories" />
					<small>Введіть через кому категорії. Наприклад: <em>Кафе, Їдальня</em></small>
				</div>

				<!-- keywords of obj -->
				<div>
					<label for="mxzsm_add_obj_keywords">Мітки об'єкта:</label>
					<input type="text" id="mxzsm_add_obj_keywords" />
					<small>Введіть через кому мітки. Наприклад: <em>їжа, корпоратив, вареники</em></small>
				</div>

				<!-- object image -->
				<div>
					<label for="mxzsm_add_obj_image">Зображення об'єкта:</label>
					<a href="#" id="mxzsm_add_obj_image">Обрати зображення</a>
				</div>
				
			</form>


			<?php 

			// remove_role( 'mxzsm_contr_obj' );


			// image

			return ob_get_clean();

		}


		public static function mx_function_current_user( $query ) {

			if( current_user_can( 'administrator' ) ) return $query;

			$user_id = get_current_user_id();

			$query['author'] = $user_id;			

			return $query;
				
		}

}