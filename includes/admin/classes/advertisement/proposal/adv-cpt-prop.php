<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSMADVCPTclassProp
{

	static public function createAdvCPT()
	{
		
		add_action( 'init', array( 'MXZSMADVCPTclassProp', 'mxzsm_adv_custom_init' ) );

	}

		static public function mxzsm_adv_custom_init()
		{

			$_menu_title = 'Пропозиції';

			$count_v_posts = self::mxzsm_count_of_verification_posts();

			if( $count_v_posts !== false ) {

				$_menu_title =  'Пропозиції (' . $count_v_posts . ')';

			}

			register_post_type( 'mxzsm_adv_prop', array(

				'labels'             => array(
					'name'               => 'Пропозиції',
					'singular_name'      => 'Пропозиції',
					'add_new'            => 'Додати нове',
					'add_new_item'       => 'Додати Оголошення',
					'edit_item'          => 'Редагувати Оголошення',
					'new_item'           => 'Нове Оголошення',
					'view_item'          => 'Дивитися Оголошення',
					'search_items'       => 'Шукати Оголошення',
					'not_found'          =>  'Оголошення не знайдено',
					'not_found_in_trash' => 'Оголошення не знайдено в кошику',
					'parent_item_colon'  => '',
					'menu_name'          => $_menu_title

				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => true,
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => true,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
				'menu_icon'			=> 'dashicons-forms'

			) );


			// new post status
			register_post_status( 'verification_prop', array(
	            'label'                     => _x( 'На підтвердження ', 'post status label', 'bznrd' ),
	            'public'                    => true,
	            'label_count'               => _n_noop( 'На підтвердження <span class="count">(%s)</span>', 'На підтвердження <span class="count">(%s)</span>', 'plugin-domain' ),
	            'post_type'                 => array( 'mxzsm_adv_prop' ),
	            'show_in_admin_all_list'    => true,
	            'show_in_admin_status_list' => true,
	            'show_in_metabox_dropdown'  => true,
	            'show_in_inline_dropdown'   => true,
	            'dashicon'                  => 'dashicons-businessman',
	        ) );

	        // category
			register_taxonomy( 'mxzsm_adv_category_prop', [ 'mxzsm_adv_prop' ], [ 
				'label'                 => '', // определяется параметром $labels->name
				'labels'                => [
					'name'              => 'Категорія Оголошення',
					'singular_name'     => 'Категорія',
					'search_items'      => 'Шукати Категорію',
					'all_items'         => 'Всі Категорії',
					'view_item '        => 'Переглянути',
					'parent_item'       => 'Батьківська категорія',
					'parent_item_colon' => 'Батьківська категорія:',
					'edit_item'         => 'Редагувати категорію',
					'update_item'       => 'Оновити категорію',
					'add_new_item'      => 'Додати категорію',
					'new_item_name'     => 'Ім\'я категорії',
					'menu_name'         => 'Категорія',
				],
				'description'           => 'Наприклад: Буд. матеріали',
				'public'                => true,
				'hierarchical'          => true,
				'rewrite'               => true,
				'capabilities'          => array(),
				'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
			] );

			// Rewrite rules			
			// flush_rewrite_rules();

		}

		/**
		*	count of verification posts
		*/
		public static function mxzsm_count_of_verification_posts()
		{

			global $wpdb;

			$count_of_posts = false;

			$posts_table = $wpdb->prefix . 'posts';

			$posts_results = $wpdb->get_results( 
				"SELECT ID FROM $posts_table
					WHERE post_status = 'verification_prop'"
			);

			if( count( $posts_results ) > 0 ) {

				$count_of_posts = count( $posts_results );

			}

			return $count_of_posts;

		}

}
