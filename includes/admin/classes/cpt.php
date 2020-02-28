<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSMCPTclass
{

	/*
	* MXZSMCPTclass constructor
	*/
	public function __construct()
	{		

	}

	/*
	* Observe function
	*/
	public static function createCPT()
	{

		add_action( 'init', array( 'MXZSMCPTclass', 'mxzsm_custom_init' ) );

	}

	/*
	* Create a Custom Post Type
	*/
	public static function mxzsm_custom_init()
	{
		
		register_post_type( 'mxzsm_objects', array(

			'labels'             => array(
				'name'               => 'Об\'єкти',
				'singular_name'      => 'Об\'єкт',
				'add_new'            => 'Додати новий',
				'add_new_item'       => 'Додати Об\'єкт',
				'edit_item'          => 'Редагувати Об\'єкт',
				'new_item'           => 'Новий Об\'єкт',
				'view_item'          => 'Дивитися Об\'єкт',
				'search_items'       => 'Шукати Об\'єкт',
				'not_found'          =>  'Об\'єкт не знайдено',
				'not_found_in_trash' => 'Об\'єкт не знайдено в кошику',
				'parent_item_colon'  => '',
				'menu_name'          => 'Об\'єкти'

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
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )

		) );

		// new post status
		register_post_status( 'verification', array(
            'label'                     => _x( 'Verification ', 'post status label', 'bznrd' ),
            'public'                    => true,
            'label_count'               => _n_noop( 'Verification <span class="count">(%s)</span>', 'Verification <span class="count">(%s)</span>', 'plugin-domain' ),
            'post_type'                 => array( 'mxzsm_objects' ),
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'show_in_metabox_dropdown'  => true,
            'show_in_inline_dropdown'   => true,
            'dashicon'                  => 'dashicons-businessman',
        ) );

		// taxonomy		
		// category
		register_taxonomy( 'mxzsm_objects_category', [ 'mxzsm_objects' ], [ 
			'label'                 => '', // определяется параметром $labels->name
			'labels'                => [
				'name'              => 'Категорія об\'єкта',
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
			'description'           => 'Наприклад: Ресторани',
			'public'                => true,
			'hierarchical'          => true,
			'rewrite'               => true,
			'capabilities'          => array(),
			'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		] );

		// tags
		register_taxonomy( 'mxzsm_objects_keywords', [ 'mxzsm_objects' ], [ 
			'label'                 => '', // определяется параметром $labels->name
			'labels'                => [
				'name'              => 'Ключове слово',
				'singular_name'     => 'Ключове слово',
				'search_items'      => 'Шукати Ключове слово',
				'all_items'         => 'Всі Ключові слова',
				'view_item '        => 'Переглянути',
				'parent_item'       => 'Батьківське Ключове слово',
				'parent_item_colon' => 'Батьківське Ключове слово:',
				'edit_item'         => 'Редагувати Ключове слово',
				'update_item'       => 'Оновити Ключове слово',
				'add_new_item'      => 'Додати Ключове слово',
				'new_item_name'     => 'Назва Ключового слова',
				'menu_name'         => 'Ключове слово',
			],
			'description'           => 'Ключові Слова',
			
		] );

		// Rewrite rules
		if( is_admin() && get_option( 'mxzsm_flush_rewrite_rules' ) == 'go_flush_rewrite_rules' )
		{

			delete_option( 'mxzsm_flush_rewrite_rules' );

			flush_rewrite_rules();

		}

	}

}