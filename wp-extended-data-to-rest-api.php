<?php

/**
 * Plugin Name: Extended Data to REST API
 * Description: Exposes Extended Data (meta, terms, p2p, ...) in the WordPress REST API
 * Author: Giuscia
 * Author URI: https://github.com/giuscia
 * Version: 3.1.0
 * Plugin URI: https://github.com/giuscia/p2p-to-rest-api
 * Text Domain: wp-extended-data-to-rest-api
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}




if ( is_admin() ):
	require_once dirname( __FILE__ ) . '/admin/class-wped2ra-options-page.php';
	new WPExtendedDataToRestAPI_Options_Page();
endif;

require_once dirname( __FILE__ ) . '/public/class-wped2ra-rf.php';

new WPExtendedDataToRestAPI_Register_Rest_Fields();

/*
add_action( "rest_api_init", function () {

} );
*/