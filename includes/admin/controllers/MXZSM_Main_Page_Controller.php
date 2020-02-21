<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class MXZSM_Main_Page_Controller extends MXZSM_Controller
{
	
	public function index()
	{

		$model_inst = new MXZSM_Main_Page_Model();

		$data = $model_inst->mxzsm_get_row( NULL, 'id', 1 );

		return new MXZSM_View( 'main-page', $data );

	}

	public function submenu()
	{

		return new MXZSM_View( 'sub-page' );

	}

	public function hidemenu()
	{

		return new MXZSM_View( 'hidemenu-page' );

	}

	public function settings_menu_item_action()
	{

		return new MXZSM_View( 'settings-page' );

	}

}