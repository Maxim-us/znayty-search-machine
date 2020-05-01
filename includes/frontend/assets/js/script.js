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

		'search_button'		: $( '#mx_znayty_submit_button' )
	}; 
	
	/*
	*	Region select change
	*/
	mxzsm_app.regions_select.on( 'change', function() {

		var region_id = $( this ).val();

		$( '.mx-loading-panel' ).show();

		mxzsm_get_cities_by_region( region_id );

		// search button show
		mxzsm_show_search_button( region_id );

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
	function mxzsm_show_search_button( region_id ) {

		if( region_id === '' ) {

			mxzsm_app.search_button_wrap.hide();

		} else {

			mxzsm_app.search_button_wrap.show();

		}		

	}

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

			var add_obj = mxzsm_app.regions_select.hasClass( 'mxzsm_add_obj_regions' );

			var data = {

				'action'			:  'mxzsm_get_cities_front',
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
		// media
		$( '#mxzsm_add_obj_image' ).on( 'click', function( e ) {

			e.preventDefault();

	        var upload = wp.media( {

		        title: 'Обрати зображення з комп\'ютера.',
		        
			    library: {
			    	type: 'image'
			    },

		        multiple: false

	        } ).on( 'select', function(){

	            var select = upload.state().get( 'selection' );

	            var attach = select.first().toJSON();

	            $( '.mxzsm_add_obj_image_preview' ).empty();

	            $( '.mxzsm_add_obj_image_preview' ).append( '<img src="' + attach.url + '" id="mxzsm_add_obj_img" style="width: 150px;" />' );

	            $( '#mxzsm_add_obj_image_id' ).val( attach.id );

	         //    $( '#mxzsm_add_obj_image' ).hide();

	         //    $( '#mx_delete_image' ).show();

	        	// console.log( attach.id );

	        } ).open();

		} );

		// submit form
		$( '#mxzsm_add_obj' ).on( 'submit', function( e ) {

			e.preventDefault();

			$( this ).find( 'input[type="submit"]' ).attr( 'disabled', 'disabled' );

			// var content = tinymce.get( 'mxzsm_add_obj_editor' ).getContent();

			// var js_script = content.match( /(<script>.+<\/script>)/gi );

			// if( js_script !== null ) {

			// 	content = content.replace( js_script[0], '' );

			// }

			// js_script = content.match( /(&lt;script&gt;.+&lt;\/script&gt;)/gi );

			// if( js_script !== null ) {

			// 	content = content.replace( js_script[0], '' );

			// }

			var content = $( '#mxzsm_add_obj_editor' ).val();

			// against covid
			var against_covid = '';
			if( $( '#mxzsm_add_obj_against_covid' ).prop( 'checked' ) ) {

				against_covid = $( '#mx_against_covid_details' ).val();

			}

			// service type
			var service_type_normal_mode = '';
			if( $( '#mxzsm_add_obj_service_type_normal_mode' ).prop( 'checked' ) ) {

				service_type_normal_mode = 1;

			}

			var service_type_takeaway = '';
			if( $( '#mxzsm_add_obj_service_type_takeaway' ).prop( 'checked' ) ) {

				service_type_takeaway = 1;

			}

			var service_type_delivery = '';
			if( $( '#mxzsm_add_obj_service_type_delivery' ).prop( 'checked' ) ) {

				service_type_delivery = 1;

			}

			// keywords
			var keywords_string = $( '#mxzsm_add_obj_keywords' ).val();
			keywords_string = keywords_string.toLowerCase();

			var form_data = {
				action: 	'mxzsm_add_obj_front',

				title: 		$( '#mxzsm_add_obj_title' ).val(),
				content: 	content,
				region_id: 	$( '#mxzsm_regions' ).val(),
				city_id: 	$( '#mxzsm_cities' ).val(),
				address: 	$( '#mxzsm_add_obj_address' ).val(),
				categories: $( '#mxzsm_add_obj_categories' ).val(),
				keywords: 	keywords_string,
				img_url: 	$( '#mxzsm_add_obj_img' ).attr( 'src' ),
				img_id: 	$( '#mxzsm_add_obj_image_id' ).val(),
				nonce: 		$( '#mxzsm_add_obj_nonce' ).val(),

				// map data
				obj_latitude: $( '#mx_obj_latitude' ).val(),
				obj_longitude: $( '#mx_obj_longitude' ).val(),

				// website
				obj_website: $( '#mxzsm_add_obj_website' ).val(),

				// email
				obj_email: $( '#mxzsm_add_obj_email' ).val(),

				// against covid
				obj_against_covid: against_covid,

				// service type
				normal_mode: service_type_normal_mode,
				takeaway: service_type_takeaway,
				delivery: service_type_delivery,

				// phone
				obj_phone: $( '#mxzsm_add_obj_phone' ).val(),

				// video youtube
				obj_video_youtube: $( '#mxzsm_add_obj_video_youtube' ).val(),

			};

			jQuery.post( mxzsm_app.ajaxurl, form_data, function( response ) {

				console.log( response );

				if( response === 'integer' ) {

					alert( 'Відправлено на модерацію. Дякуємо Вам!' );

					window.location.href = '/';


				} else {

					alert( 'Виникла помилка! Зв\'яжіться з нами.' );

				}
				

			} );

		} );

	// tabs
	// activate public posts tab
	if( mxGetURLParameter( 'active_item' ) ) {

		$( '.mxzsm_users_obj_tab_item' ).removeClass( 'mxzsm_active' );
		$( '.mxzsm_users_obj_tab_item.my-public-obj' ).addClass( 'mxzsm_active' );

		$( '.mxzsm_users_obj_tabs_body' ).children( 'div' ).css( 'display', 'none' );
		$( '.mxzsm_users_obj_tabs_body_my_objs' ).css( 'display', 'block' );

	}

	$( '.mxzsm_users_obj_tabs_header' ).find( 'a' ).each( function() {

		$( this ).on( 'click', function( e ) {

			e.preventDefault();

			$( '.mxzsm_users_obj_tabs_header' ).find( 'a' ).removeClass( 'mxzsm_active' );

			var item_name = $( this ).attr( 'data-active-tab' );

			$( this ).addClass( 'mxzsm_active' );

			$( '.mxzsm_users_obj_tabs_body' ).children( 'div' ).css( 'display', 'none' );

			$( '.mxzsm_users_obj_tabs_body_' + item_name ).css( 'display', 'block' );

		} );
		
	} );

	// covid area
	$( '#mxzsm_add_obj_against_covid' ).on( 'change', function() {

		if( $( this ).prop( 'checked' ) ) {

			$( '#mx_against_covid_details' ).show( 'fast' );

		} else {

			$( '#mx_against_covid_details' ).hide( 'fast' );

		}

	} );

	/*
	* 	OBJECT EDITABLE
	*/
	// submit form
		$( '#mxzsm_edit_obj' ).on( 'submit', function( e ) {

			e.preventDefault();

			$( this ).find( 'input[type="submit"]' ).attr( 'disabled', 'disabled' );

			var content = $( '#mxzsm_add_obj_editor' ).val();
			
			// service type
			var service_type_normal_mode = '';
			if( $( '#mxzsm_add_obj_service_type_normal_mode' ).prop( 'checked' ) ) {

				service_type_normal_mode = 1;

			}

			var service_type_takeaway = '';
			if( $( '#mxzsm_add_obj_service_type_takeaway' ).prop( 'checked' ) ) {

				service_type_takeaway = 1;

			}

			var service_type_delivery = '';
			if( $( '#mxzsm_add_obj_service_type_delivery' ).prop( 'checked' ) ) {

				service_type_delivery = 1;

			}

			// keywords
			var form_data = {
				action: 	'mxzsm_edit_obj_front',

				post_id: 	$( '#mxzsm_post_id' ).val(),

				current_user: $( '#mxzsm_current_user' ).val(),
				current_user_id : $( '#current_user_id' ).val(),

				title: 		$( '#mxzsm_add_obj_title' ).val(),
				content: 	content,
				address: 	$( '#mxzsm_add_obj_address' ).val(),
				img_url: 	$( '.mxzsm_add_obj_image_preview' ).find( 'img' ).attr( 'src' ),
				img_id: 	$( '#mxzsm_add_obj_image_id' ).val(),
				nonce: 		$( '#mxzsm_edit_obj_nonce' ).val(),

				// map data
				obj_latitude: $( '#mx_obj_latitude' ).val(),
				obj_longitude: $( '#mx_obj_longitude' ).val(),

				// website
				obj_website: $( '#mxzsm_add_obj_website' ).val(),

				// email
				obj_email: $( '#mxzsm_add_obj_email' ).val(),

				// service type
				normal_mode: service_type_normal_mode,
				takeaway: service_type_takeaway,
				delivery: service_type_delivery,

				// phone
				obj_phone: $( '#mxzsm_add_obj_phone' ).val(),

				// video youtube
				obj_video_youtube: $( '#mxzsm_add_obj_video_youtube' ).val(),

			};

			jQuery.post( mxzsm_app.ajaxurl, form_data, function( response ) {

				if( response === 'error' ) {

					window.location.href = '/';

				}

				if( response === 'edit' ) {

					alert( 'Відправлено на модерацію. Дякуємо!' );

					window.location.href = '/add-new-object/?active_item=my-public-obj';

				}

			} );

		} );

		// remove obj
		$( '#mx_remove_obj' ).on( 'click', function( e ) {

			e.preventDefault();

			if ( !confirm( 'Ви впевнені, що хочете видалити цей об\'єкт?' ) ) return false;

			var data = {

				action: 	'mxzsm_delete_obj_front',

				post_id: 	$( '#mxzsm_post_id' ).val(),

				title: 		$( '#mxzsm_add_obj_title' ).val(),

				current_user: $( '#mxzsm_current_user' ).val(),

				current_user_id : $( '#current_user_id' ).val(),

				nonce: 		$( '#mxzsm_edit_obj_nonce' ).val()

			};

			jQuery.post( mxzsm_app.ajaxurl, data, function( response ) {

				console.log( response );

				if( response === 'error' ) {

					window.location.href = '/';

				}

				if( response === 'removed' ) {

					alert( 'Видалено!' );

					window.location.href = '/add-new-object/?active_item=my-public-obj';

				}

			} );

		} );

	// notifications
	$( '.mx_close_notification_button' ).on( 'click', function( e ) {

		e.preventDefault();
		
		$( this ).attr( 'disabled', 'disabled' );

		var option = $( this ).attr( 'data-close-notification' );

		var data = {

			action: 	'mxzsm_got_this_notification',

			nonce: 		mxzsm_data_obj_front.nonce,

			option: 	option

		};

		var _this = $( this );

		jQuery.post( mxzsm_app.ajaxurl, data, function( response ) {

			if( response === 'integer' ) {

				_this.parent().parent().hide( 'fast' );

				setTimeout( function() {

					_this.parent().parent().remove();

				}, 1000 );

			}

		} );

	} )

} );

// functions
function mxGetURLParameter( sParam ) {

    var sPageURL = window.location.search.substring(1);

    var sURLVariables = sPageURL.split('&');

    for (var i = 0; i < sURLVariables.length; i++) {

        var sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] == sParam) {

            return sParameterName[1];

        }
    }
}