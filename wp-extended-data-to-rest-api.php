<?php

/**
 * Plugin Name: WP Extended Data to REST API
 * Description: Exposes Extended Data (meta, terms, p2p, ...) in the WordPress REST API
 * Version: 1.0.5
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author: ZanclOn
 * Author URI: https://github.com/ZanclOn/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Plugin URI: https://github.com/ZanclOn/wp-extended-data-to-rest-api
 * Text Domain: wp-extended-data-to-rest-api
 */
if ( !defined( 'ABSPATH' ) ):
	exit;
endif;
define( 'WPEDTRA_BASE_DIRECTORY', plugin_basename( __FILE__ ) );

if ( is_admin() ):
	require_once dirname( __FILE__ ) . '/admin/class-wped2ra-options-page.php';
	new WPExtendedDataToRestAPI_Options_Page();
endif;

require_once dirname( __FILE__ ) . '/public/class-wped2ra-rf.php';

new WPExtendedDataToRestAPI_Register_Rest_Fields();

