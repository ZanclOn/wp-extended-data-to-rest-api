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

class WPDocs_Options_Page {
 
    /**
     * Constructor.
     */
    function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }
 
    /**
     * Registers a new settings page under Settings.
     */
    function admin_menu() {
        add_options_page(
            __( 'Page Title', 'textdomain' ),
            __( 'Circle Tree Login', 'textdomain' ),
            'manage_options',
            'options_page_slug',
            array(
                $this,
                'settings_page'
            )
        );
    }
 
    /**
     * Settings page display callback.
     */
    function settings_page() {
        echo __( 'This is the page content', 'textdomain' );
    }
}
 
new WPDocs_Options_Page;

add_action( "rest_api_init", function () {

	$post_types = get_post_types();
	foreach ( $post_types as $post_type ) {


		register_rest_field( $post_type, "terms", array( "get_callback" => function ($post) {
				$taxonomies = get_post_taxonomies( $post['id'] );
				$terms_and_taxonomies = [];
				foreach ( $taxonomies as $taxonomy_name ) {
					$terms_and_taxonomies[$taxonomy_name] = wp_get_post_terms( $post['id'], $taxonomy_name );
				}
				return $terms_and_taxonomies;
			} ) );



		register_rest_field( $post_type, "meta", array( "get_callback" => function ($post) {
				$meta = get_post_meta( $post['id'] );
				foreach ( $meta as $k => $v ):
					if ( strpos( $k, '_' ) === 0 ):
						unset( $meta[$k] );
					endif;
				endforeach;
				return $meta;
			} ) );



		if ( function_exists( '_p2p_load' ) ):

			global $wpdb;
			if ( !empty( $wpdb->p2p ) && !empty( $wpdb->p2pmeta )) :
				register_rest_field( $post_type, "p2p", array( "get_callback" => function ($post) {
						global $wpdb;
							$p2p_to = $wpdb->get_results( $wpdb->prepare( "SELECT p2p_id, p2p_from , p2p_to  FROM {$wpdb->p2p} WHERE p2p_to = %d ", array( $post['id'] ) ), OBJECT );
							foreach ( $p2p_to as $k => $v ):
								$p = get_post( $v->p2p_from );
								$p2p_to[$k]->title = $p->post_title;
								$p2p_meta_to = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, meta_key  FROM {$wpdb->p2pmeta} WHERE  p2p_id=%d", array( $v->p2p_id ) ), OBJECT );
								foreach ( $p2p_meta_to as $k_meta => $v_meta ):
									$p2p_to[$k]->$k_meta = $v_meta;
								endforeach;
							endforeach;
							$p2p_from = $wpdb->get_results( $wpdb->prepare( "SELECT p2p_id, p2p_from , p2p_to  FROM {$wpdb->p2p} WHERE p2p_from = %d ", array( $post['id'] ) ), OBJECT );
							foreach ( $p2p_from as $k => $v ):
								$p = get_post( $v->p2p_to );
								$p2p_from[$k]->title = $p->post_title;
								$p2p_meta_to = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, meta_key  FROM {$wpdb->p2pmeta} WHERE  p2p_id=%d", array( $v->p2p_id ) ), OBJECT );
								foreach ( $p2p_meta_to as $k_meta => $v_meta ):
									$p2p_from[$k]->$k_meta = $v_meta;
								endforeach;
							endforeach;

							$p2p = array();
							$p2p['p2p_to'] = $p2p_to;
							$p2p['p2p_from'] = $p2p_from;

							return $p2p;
					} ) );
			endif;

		endif;
	}
} );
