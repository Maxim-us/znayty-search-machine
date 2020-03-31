jQuery( document ).ready( function( $ ) {

	/*
	*	APP VARS
	*/
	var mxzsm_app = {

		// regions
		'regions_select' 	: 	$( '#mxzsm_regions_adv_need' ),

		// cities
		'cities_select_wrap': 	$( '.mxzsm_cities' ),
		'cities_select' 	: 	$( '#mxzsm_cities_adv_need' ),

		// regions_data
		'regions_data'		:  mxzsm_data_obj_front.regions,

		// nonce
		'nonce'				: mxzsm_data_obj_front.nonce,

		// obj built
		'interval_obj_built': null,
		'obj_built' 		: false,

		// ajax
		'ajaxurl' 			: mxzsm_data_obj_front.ajax_url,

		// search button wrap
		'search_button_wrap': $( '.mx-znayty-submit-button-wrap' ),

		'search_button'		: $( '#mx_znayty_submit_button_adv_need' )
	}; 
	
	/*
	*	Region select change
	*/
	mxzsm_app.regions_select.on( 'change', function() {

		var region_id = $( this ).val();

		$( '.mx-loading-panel' ).show();

		mxzsm_get_cities_by_region_adv( region_id );

		// search button show
		mxzsm_show_search_button_adv( region_id );

	} );

	/*
	* Search machine
	*/
	mxzsm_app.search_button.on( 'click', function( e ) {

		e.preventDefault();

		var search_res_slug = $( this ).attr( 'data-search-result-slug' );

		var search_slug = '';

		if( search_res_slug !== undefined ) {

			search_slug = '/' + search_res_slug + '/';

		}

		var region_id = mxzsm_app.regions_select.val();

		var city_id = mxzsm_app.cities_select.val();

		window.location.href = search_slug + '?region_id=' + region_id + '&city_id=' + city_id + '#mx_search_system_info';

	} );

	/*
	* functions
	*/

	// search button
	function mxzsm_show_search_button_adv( region_id ) {

		if( region_id === '' ) {

			mxzsm_app.search_button_wrap.hide();

		} else {

			mxzsm_app.search_button_wrap.show();

		}		

	}

	// get cities by region
	function mxzsm_get_cities_by_region_adv( region_id ) {

		mxzsm_app.cities_select.empty();

		if( region_id === '' ) {			

			mxzsm_app.cities_select_wrap.hide();			

		} else {

			mxzsm_app.cities_select_wrap.show();

			// check if region exists in JS obj
			if( mxzsm_check_js_obj_of_cities_adv( region_id ) === undefined ) {

				// get from DB
				mxzsm_get_obj_of_cities_database_adv( region_id );

				// wait when the object will built
				mxzsm_app.interval_obj_built = setInterval( function() {

					if( mxzsm_app.obj_built ) {

						clearInterval( mxzsm_app.interval_obj_built );

						// build select element
						mxzsm_build_select_element( region_id );

						// pull the interval's trigger
						mxzsm_app.obj_built = false;

					}

					// console.log( 'interval' );

				}, 100 );

			} else {

				// build select element
				mxzsm_build_select_element( region_id );

			}			

		}

	}

	// check obj of cities
	function mxzsm_check_js_obj_of_cities_adv( region_id ) {

		// check if the region exists in global obj
		return mxzsm_app.regions_data['region_' + region_id];

	}

		// get cities from DB
		function mxzsm_get_obj_of_cities_database_adv( region_id ) {

			var add_obj = mxzsm_app.regions_select.hasClass( 'mxzsm_add_obj_regions' );

			var data = {

				'action'			:  'mxzsm_get_cities_front_adv_need',
				'nonce'				: 	mxzsm_app.nonce,
				'region_id'			: 	region_id,
				'get_all_cities'	: 	add_obj

			};

			// $.ajax
			jQuery.post( mxzsm_app.ajaxurl, data, function( response ) {

				var cities_json = JSON.parse( response );

				mxzsm_set_to_blobal_js_obj( region_id, cities_json );

				// clear interval
				mxzsm_app.obj_built = true;

			} );

		}

		// set to the blobal JS obj
		function mxzsm_set_to_blobal_js_obj( region_id, cities_array ) {

			mxzsm_app.regions_data['region_' + region_id] = {};

			mxzsm_app.regions_data['region_' + region_id].cities = cities_array;

		}

		// build select element
		function mxzsm_build_select_element( region_id ) {

			$( '.mx-loading-panel' ).hide( 'fast' );

			mxzsm_app.cities_select.append( '<option value=""></option>' );

			$.each( mxzsm_app.regions_data['region_' + region_id].cities, function( i, v ) {

				// console.log( i, v );
				mxzsm_app.cities_select.append( '<option value="' + v.id + '">' + v.city + '</option>' );

			} );

		}

	/*
	* Add object 
	*/

		// change social
		$( '#mxzsm_add_need_social' ).on( 'change', function() {

			var _val = $( this ).val();

			if( _val === '' ) {

				$( '.mx-hide-phone-button' ).hide();

			} else {

				$( '.mx-hide-phone-button' ).show();

			}

		} );

		// submit form
		$( '#mxzsm_add_need' ).on( 'submit', function( e ) {

			e.preventDefault();

			var content = $( '#mxzsm_add_obj_editor' ).val();

			// hide phone
			var hide_phone = 0;

			if( $( '#mxzsm_hide_phone_number' ).prop( 'checked' ) ) {

				hide_phone = 1;

			}

			var form_data = {
				action: 	'mxzsm_add_obj_front_adv_need',

				title: 		$( '#mxzsm_add_obj_title' ).val(),
				content: 	content,
				region_id: 	$( '#mxzsm_regions_adv_need' ).val(),
				city_id: 	$( '#mxzsm_cities_adv_need' ).val(),
				categories: $( '#mxzsm_add_obj_categories' ).val(),

				nonce: 		$( '#mxzsm_add_obj_nonce' ).val(),

				// phone
				obj_phone: $( '#mxzsm_add_obj_phone' ).val(),
				hide_phone: hide_phone,

				// social
				obj_social: $( '#mxzsm_add_need_social' ).val(),

			};

			jQuery.post( mxzsm_app.ajaxurl, form_data, function( response ) {

				// console.log( response );

				if( response === 'integer' ) {

					alert( 'Відправлено на модерацію. Дякуємо Вам!' );

					document.location.reload();


				} else {

					alert( 'Виникла помилка! Зв\'яжіться з нами.' );

				}
				

			} );

		} );

	// excerpt
	$( '.mx-adv_search_result_item_text p' ).each( function() {

		var _text = $( this ).text();

		if( _text.length >= 60 ) {

			_text = _text.slice( 0, 60 );

			$( this ).text( _text + ' ...' );

		}

	} );

} );