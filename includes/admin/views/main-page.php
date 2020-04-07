<h2>Shortcodes:</h2>
<p>
	<span>Search Form:</span><br>
	[mxzsm_search_form search_result_slug='search-page'] (search_result_slug='search-page' - optional)
</p>

<p>
	<span>Search Result:</span><br>
	[mxzsm_search_result]
</p>

<p>
	<span>Add new object:</span><br>
	[mxzsm_add_new_obj]
</p>

<h2>Add new city</h2>
<form id="mx_add_new_city_to_list">

	<div class="mx-form-group">

		<label for="mx_list_of_regions">
			Сптсок областей
		</label><br>

		<?php

			global $wpdb;

			$table_regions = $wpdb->prefix . 'regions';

			$results_regions = $wpdb->get_results(

				"SELECT id, region FROM $table_regions ORDER BY region"

			);

		?>

		<select name="mxzsm_region_list" class="mxzsm_region_list" id="mxzsm_region_list" required="required">

			<option value=""></option>

			<?php foreach ( $results_regions as $key => $value ) : ?>

				<option value="<?php echo $value->id; ?>"><?php echo $value->region; ?></option>

			<?php endforeach; ?>

		</select>

	</div>
	
	<div class="mx-form-group">

		<label for="mx_list_of_regions">
			Назва населеного п-т в обраній області
		</label><br>

		<input type="text" id="mx_new_city_of_region" required="required">

	</div>

	<input type="hidden" id="mxzsm_add_city_nonce" value="<?php echo wp_create_nonce( 'mxzsm_add_city_nonce_request' ); ?>" />

	<br>

	<input type="submit" value="Зберегти" />


</form>