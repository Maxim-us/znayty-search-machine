<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Error Handle calss
*/
class MXZSM_Display_Error
{

	/**
	* Error notice
	*/
	public $mxzsm_error_notice = '';

	public function __construct( $mxzsm_error_notice )
	{

		$this->mxzsm_error_notice = $mxzsm_error_notice;

	}

	public function mxzsm_show_error()
	{
		add_action( 'admin_notices', function() { ?>

			<div class="notice notice-error is-dismissible">

			    <p><?php echo $this->mxzsm_error_notice; ?></p>
			    
			</div>
		    
		<?php } );
	}

}