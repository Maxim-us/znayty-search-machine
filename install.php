<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


class MXZSM_Basis_Plugin_Class
{

	private static $table_slug = MXZSM_TABLE_SLUG;

	public static function activate()
	{

		global $wp_roles;

		if( ! $wp_roles->is_role( 'mxzsm_contr_obj' ) ) {

			add_role( 'mxzsm_contr_obj', 'Додавач Об\'єктів',
				array(
					'read'         => true,
					'edit_posts'   => false,
					'upload_files' => true,
				)
			);

		}
		
	}

	public static function deactivate()
	{

		// Rewrite rules
		// flush_rewrite_rules();

	}

	/*
	* This function sets the option in the table for CPT rewrite rules
	*/
	public static function create_option_for_activation()
	{

		add_option( 'mxzsm_flush_rewrite_rules', 'go_flush_rewrite_rules' );

	}

}