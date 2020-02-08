<?php

if ( !defined( 'ABSPATH' ) ):
	exit;
endif;

if ( !class_exists( 'WPExtendedDataToRestAPI_Options_Page' ) ):

	class WPExtendedDataToRestAPI_Options_Page {

		/**
		 * Constructor.
		 */
		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

		/**
		 * Registers a new settings page under Settings.
		 */
		function admin_menu() {
			add_options_page(
					__( 'Extended Data to REST API', 'wp-extended-data-to-rest-api' ),
					__( 'Extended Data to REST API', 'wp-extended-data-to-rest-api' ),
					'manage_options',
					'wp-extended-data-to-rest-api',
					array(
						$this,
						'settings_page'
					)
			);
		}

		function register_settings() {

			foreach ( get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ), 'names' ) as $pt ) {
				add_option( 'wpedtra-pt-' . $pt, 'no' );
				register_setting( 'wpedtra-options-group', 'wpedtra-pt-' . $pt );
			}
		}

		/**
		 * Settings page display callback.
		 */
		function settings_page() {

			echo '<div class="wrap">';
			echo '<h1>'.__('Extended Data to REST API Configuration').'</h1>';
			echo '<h3>'.__('enable/disable Extended Data for each post type').'</h3>';
			echo '<form method="post" action="options.php">';
			settings_fields( 'wpedtra-options-group' );

			foreach ( get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ), 'names' ) as $pt ):
				echo '<div><b>' . $pt . '</b></div><hr>';
				echo '<select name="wpedtra-pt-' . $pt . '" id="wpedtra-pt-' . $pt . '">';
				echo '<option value="no" ' . (selected( get_option( 'wpedtra-pt-' . $pt ), 'no' )) . '>no</option>';
				echo '<option value="yes" ' . (selected( get_option( 'wpedtra-pt-' . $pt ), "yes" )) . '>yes</option>';
				echo '</select><br><br>';
			endforeach;
			echo '<input type="submit" class="button-primary" id="submit" name="submit" value="' . __( 'Save Changes' ) . '" />';

			echo '</form>';

			echo '</div>';
		}

	}

	

	

	

	

	

	
endif;
