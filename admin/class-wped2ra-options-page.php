<?php

if ( !defined( 'ABSPATH' ) ):
	exit;
endif;

if ( !class_exists( 'WPExtendedDataToRestAPI_Options_Page' ) ):

	class WPExtendedDataToRestAPI_Options_Page {

		function __construct() {
			add_filter( 'plugin_action_links_' . WPEDTRA_BASE_DIRECTORY, array( $this, 'plugin_action_links' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}

		function plugin_action_links( array $links ) {
			$url = get_admin_url() . "options-general.php?page=wp-extended-data-to-rest-api";
			$settings_link = '<a href="' . $url . '">' . __( 'Settings', 'wp-extended-data-to-rest-api' ) . '</a>';
			$links[] = $settings_link;
			return $links;
		}

		function admin_menu() {
			add_options_page(
					__( 'WP Extended Data to REST API', 'wp-extended-data-to-rest-api' ), __( 'WP Extended Data to REST API', 'wp-extended-data-to-rest-api' ), 'manage_options', 'wp-extended-data-to-rest-api', array(
				$this,
				'settings_page'
					)
			);
		}

		function register_settings() {

			add_option( 'wpedtra-meta', 'no' );
			register_setting( 'wpedtra-options-group', 'wpedtra-meta' );

			add_option( 'wpedtra-terms', 'no' );
			register_setting( 'wpedtra-options-group', 'wpedtra-terms' );

			add_option( 'wpedtra-p2p', 'no' );
			register_setting( 'wpedtra-options-group', 'wpedtra-p2p' );

			add_option( 'wpedtra-children_ids', 'no' );
			register_setting( 'wpedtra-options-group', 'wpedtra-children_ids' );

			foreach ( get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ), 'names' ) as $pt ) {
				add_option( 'wpedtra-pt-' . $pt, 'no' );
				register_setting( 'wpedtra-options-group', 'wpedtra-pt-' . $pt );
			}
		}

		function settings_page() {

			echo '<div class="wrap">';
			echo '<h1>' . __( 'WP Extended Data to REST API Configuration' ) . '</h1>';

			echo '<form method="post" action="options.php">';

			echo '<h3>' . __( 'type of data' ) . '</h3>';

			echo '<label for="wpedtra-meta">post meta</label>:&nbsp;';
			echo '<select name="wpedtra-meta" id="wpedtra-meta">';
			echo '<option value="no" ' . (selected( get_option( 'wpedtra-meta' ), 'no' )) . '>no</option>';
			echo '<option value="yes" ' . (selected( get_option( 'wpedtra-meta' ), "yes" )) . '>yes</option>';
			echo '</select><br><br>';

			echo '<label for="wpedtra-terms">taxonomy terms</label>:&nbsp;';
			echo '<select name="wpedtra-terms" id="wpedtra-terms">';
			echo '<option value="no" ' . (selected( get_option( 'wpedtra-terms' ), 'no' )) . '>no</option>';
			echo '<option value="yes" ' . (selected( get_option( 'wpedtra-terms' ), "yes" )) . '>yes</option>';
			echo '</select><br><br>';

			echo '<label for="wpedtra-children_ids">children ids (max 100 items)</label>:&nbsp;';
			echo '<select name="wpedtra-children_ids" id="wpedtra-children_ids">';
			echo '<option value="no" ' . (selected( get_option( 'wpedtra-children_ids' ), 'no' )) . '>no</option>';
			echo '<option value="yes" ' . (selected( get_option( 'wpedtra-children_ids' ), "yes" )) . '>yes</option>';
			echo '</select><br><br>';			
			
			echo '<label for="wpedtra-p2p">post to post</label>:&nbsp;';
			echo '<select name="wpedtra-p2p" id="wpedtra-p2p">';
			echo '<option value="no" ' . (selected( get_option( 'wpedtra-p2p' ), 'no' )) . '>no</option>';
			echo '<option value="yes" ' . (selected( get_option( 'wpedtra-p2p' ), "yes" )) . '>yes</option>';
			echo '</select><br><br>';


			
			
			
			echo '<h3>' . __( 'for which post type?' ) . '</h3>';
			settings_fields( 'wpedtra-options-group' );

			foreach ( get_post_types( array( 'public' => true, 'show_in_nav_menus' => true ), 'names' ) as $pt ):
				echo '<label for="wpedtra-pt-' . $pt . '">' . $pt . '</label>:&nbsp;';
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
