<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// require Route-Registrar.php
require_once MXZSM_PLUGIN_ABS_PATH . 'includes/core/Route-Registrar.php';

/*
* Routes class
*/
class MXZSM_Route
{

	public function __construct()
	{
		// ...
	}
	
	public static function mxzsm_get( ...$args )
	{

		return new MXZSM_Route_Registrar( ...$args );

	}
	
}