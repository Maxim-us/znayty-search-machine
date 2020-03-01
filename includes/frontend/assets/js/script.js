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

		mxzsm_get_cities_by_region( region_id );

		// search button show
		mxzsm_show_search_button( region_id );

	} );

	/*
	* Search machine
	*/
	mxzsm_app.search_button.on( 'click', function( e ) {

		e.preventDefault();

		var region_id = mxzsm_app.regions_select.val();

		var city_id = mxzsm_app.cities_select.val();

		window.location.href = '?region_id=' + region_id + '&city_id=' + city_id;

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

	        } ).open();

		} );

		// submit form
		$( '#mxzsm_add_obj' ).on( 'submit', function( e ) {

			e.preventDefault();

			var content = tinymce.get( 'mxzsm_add_obj_editor' ).getContent();

			var js_script = content.match( /(<script>.+<\/script>)/gi );

			if( js_script !== null ) {

				content = content.replace( js_script[0], '' );

			}

			js_script = content.match( /(&lt;script&gt;.+&lt;\/script&gt;)/gi );

			if( js_script !== null ) {

				content = content.replace( js_script[0], '' );

			}

			// content = mxzsm_builder_encode_html( content );

			var form_data = {
				action: 	'mxzsm_add_obj_front',

				title: 		$( '#mxzsm_add_obj_title' ).val(),
				content: 	content,
				region_id: 	$( '#mxzsm_regions' ).val(),
				city_id: 	$( '#mxzsm_cities' ).val(),
				categories: $( '#mxzsm_add_obj_categories' ).val(),
				keywords: 	$( '#mxzsm_add_obj_keywords' ).val(),
				img_url: 	$( '#mxzsm_add_obj_img' ).attr( 'src' ),
				img_id: 	$( '#mxzsm_add_obj_image_id' ).val(),
				nonce: 		$( '#mxzsm_add_obj_nonce' ).val()
			};

			jQuery.post( mxzsm_app.ajaxurl, form_data, function( response ) {

				console.log( response );

			} );

		} );

} );

// function
function mxzsm_builder_encode_html( str ) {

    return String( str )
    .replace( /&/g, '&amp;' )
    .replace( /</g, '&lt;' )
    .replace( />/g, '&gt;' )
    .replace( /\"/g, '&quot;' )
    .replace( /\'/g, '&apos;' );

}