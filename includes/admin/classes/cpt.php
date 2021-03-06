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

		// publish post
		add_action( 'edit_post_mxzsm_objects', array( 'MXZSMCPTclass', 'mxzsm_send_email_to_the_user_publish_post' ), 20, 2 );

	}

	/*
	* Create a Custom Post Type
	*/
	public static function mxzsm_custom_init()
	{

		$count_v_posts = self::mxzsm_count_of_verification_posts();

		$_menu_title = 'Об\'єкти';

		if( $count_v_posts !== false ) {

			$_menu_title =  'Об\'єкти (' . $count_v_posts . ')';

		}
		
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
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )

		) );

		// new post status
		register_post_status( 'verification', array(
            'label'                     => _x( 'На підтвердження ', 'post status label', 'bznrd' ),
            'public'                    => true,
            'label_count'               => _n_noop( 'На підтвердження <span class="count">(%s)</span>', 'На підтвердження <span class="count">(%s)</span>', 'plugin-domain' ),
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
					WHERE post_status = 'verification'"
			);

			if( count( $posts_results ) > 0 ) {

				$count_of_posts = count( $posts_results );

			}

			return $count_of_posts;

		}

	/*
	*	Send email to the user, when object will be publish
	*/
	public static function mxzsm_send_email_to_the_user_publish_post( $post_id, $post )
	{

		$email = 'user@mail.ru';

		$header  = 'From: Знайти сервіс <support@znayty.com.ua>' . "\r\n";
		$header .= 'Reply-To: support@znayty.com.ua' . "\r\n";

		$header .= "Content-Type: text/html; charset=UTF-8\r\n";
		
		$subject = 'Ваш об\'єкт опубліковано!';

		$message = 'Ваш об\'єкт опубліковано. Ви можете його переглянути тут: <a href="' . $post->guid . '">' . $post->guid . '</a>';

		wp_mail( $email, $subject, $message, $header );

	}

}