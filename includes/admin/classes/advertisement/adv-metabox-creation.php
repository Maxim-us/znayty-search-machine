<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSMADVMetaboxCreationClass
{

	/*
	* MXZSMMetaboxCreationClass constructor
	*/
	public function __construct()
	{		

	}

	/*
	* create metabox function
	*/
	public static function createMetaBox()
	{

		add_action( 'add_meta_boxes', array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_boxes' ) );

		// save regions and cities
		add_action( 'save_post_mxzsm_adv_need', array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_boxes_save_social' ) );
	}


		public static function mxzsm_meta_boxes()
		{			

			add_meta_box(
				'mxzsm_meta_social_profile',
				'Соціальний профіль',
				array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_box_social_profile_callback' ),
				array( 'mxzsm_adv_need' ),
				'normal'
			);

			add_meta_box(
				'mxzsm_meta_show_info',
				'Додаткова інформація',
				array( 'MXZSMADVMetaboxCreationClass', 'mxzsm_meta_box_show_info_callback' ),
				array( 'mxzsm_adv_need' ),
				'normal'
			);

		}

		// social field
		public static function mxzsm_meta_box_social_profile_callback( $post, $meta )
		{

			// check nonce
			wp_nonce_field( 'mxzsm_meta_box_social_profile_action', 'mxzsm_meta_box_social_profile_nonce' );

			$social = get_post_meta( $post->ID, '_mxzsm_user_social', true );

			echo '<p>
				<label for="#"></label>
				<input type="url" name="mxzsm_social_profile" id="mxzsm_social_profile" value="' . $social . '" />
			</p>';


		}

			// save social
			public static function mxzsm_meta_boxes_save_social( $post_id )
			{

				if ( ! isset( $_POST['mxzsm_meta_box_social_profile_nonce'] ) ) 
					return;

				if ( ! wp_verify_nonce( $_POST['mxzsm_meta_box_social_profile_nonce'], 'mxzsm_meta_box_social_profile_action') )
					return;

				if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
					return;

				if( ! current_user_can( 'edit_post', $post_id ) )
					return;

				// mxzsm_regions
				$social = esc_url_raw( $_POST['mxzsm_social_profile'] );

				update_post_meta( $post_id, '_mxzsm_user_social', $social );

			}

		// add info
		public static function mxzsm_meta_box_show_info_callback( $post, $meta )
		{

			$cat = get_post_meta( $post->ID, '_mxzsm_add_obj_categories', true );

			$cat_name = $cat == 'food' ? 'Продукти' : '';

			$cat_name = $cat == 'household_chemicals' ? 'Побутова хімія' : $cat_name;

			$cat_name = $cat == 'household_goods' ? 'Господарські товари' : $cat_name;

			$cat_name = $cat == 'building' ? 'Будівельні матеріали' : $cat_name;

			$cat_name = $cat == 'spare_parts' ? 'Запчастини' : $cat_name;

			$cat_name = $cat == 'agriculture' ? 'С/Г товари' : $cat_name;

			$cat_name = $cat == 'other' ? 'Інше' : $cat_name; 

			echo '<p>
				<label for="#"><b>Категорія</b></label>
				<br>
				<span>' . $cat_name . '</span>
			</p>';

		}

}