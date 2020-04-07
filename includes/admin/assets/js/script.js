jQuery( document ).ready( function( $ ) {

	/*
	*	APP VARS
	*/
	var mxzsm_app = {

		// regions
		'regions_select' 	: 	$( '#mxzsm_regions' ),

		// cities
		'cities_select_wrap': 	$( '.mxzsm_cities' ),
		'cities_select' 	: 	$( '#mxzsm_cities' ),

		// regions_data
		'regions_data'		:  mxzsm_data_obj.regions,

		// nonce
		'nonce'				: mxzsm_data_obj.nonce,

		// obj built
		'interval_obj_built': null,
		'obj_built' 		: false
	};

	/*
	*	Region select change
	*/
	mxzsm_app.regions_select.on( 'change', function() {

		var region_id = $( this ).val();

		mxzsm_get_cities_by_region( region_id );

	} );

	/*
	* functions
	*/

	// get cities by region
	function mxzsm_get_cities_by_region( region_id ) {

		mxzsm_app.cities_select.empty();

		if( region_id === '' ) {			

			mxzsm_app.cities_select_wrap.hide();			

		} else {

			mxzsm_app.cities_select_wrap.show();

			// check if region exists in JS obj
			if( mxzsm_check_js_obj_of_cities( region_id ) === undefined ) {

				// get from DB
				mxzsm_get_obj_of_cities_database( region_id );

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
	function mxzsm_check_js_obj_of_cities( region_id ) {

		// check if the region exists in global obj
		return mxzsm_app.regions_data['region_' + region_id];

	}

		// get cities from DB
		function mxzsm_get_obj_of_cities_database( region_id ) {

			var data = {

				'action'	:  'mxzsm_get_cities',
				'nonce'		: 	mxzsm_app.nonce,
				'region_id'	: 	region_id

			};

			// $.ajax
			jQuery.post( ajaxurl, data, function( response ) {

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

			mxzsm_app.cities_select.append( '<option value=""></option>' );

			$.each( mxzsm_app.regions_data['region_' + region_id].cities, function( i, v ) {

				// console.log( i, v );
				mxzsm_app.cities_select.append( '<option value="' + v.id + '">' + v.city + '</option>' );

			} );

		}

	// add new city
	$( '#mx_add_new_city_to_list' ).on( 'submit', function( e ) {

		e.preventDefault();

		var data = {

			action: 	'mxzsm_add_new_city_to_db',

			nonce: 		$( '#mxzsm_add_city_nonce' ).val(),

			region: 	$( '#mxzsm_region_list' ).val(),

			city: 		$( '#mx_new_city_of_region' ).val()

		};

		jQuery.post( ajaxurl, data, function( response ) {

			console.log( response  );

			if( response === '0' ) {

				alert( 'Таке місто вже існує!' );

			} else {

				alert( 'Місто додано!' );

				document.location.reload();

			}

			// if( response === 'integer' ) {

			// 	alert( 'Відправлено на модерацію. Дякуємо Вам!' );

			// 	document.location.reload();


			// } else {

			// 	alert( 'Виникла помилка! Зв\'яжіться з нами.' );

			// }
			

		} );

	} );

} );