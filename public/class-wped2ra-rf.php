<?php

if ( !defined( 'ABSPATH' ) ):
	exit;
endif;

if ( !class_exists( 'WPExtendedDataToRestAPI_Register_Rest_Fields' ) ):

	class WPExtendedDataToRestAPI_Register_Rest_Fields {

		function __construct() {
			add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
		}

		function rest_api_init() {

			$post_types = get_post_types();
			foreach ( $post_types as $pt ):
				if ( get_option( 'wpedtra-pt-' . $pt, 'no' ) === 'yes' ):


					if ( get_option( 'wpedtra-meta', 'no' ) === 'yes' ):

						register_rest_field( $pt, "meta", array( "get_callback" => function ($post) {
								$meta = get_post_meta( $post['id'] );
								foreach ( $meta as $k => $v ):
									if ( strpos( $k, '_' ) === 0 ):
										unset( $meta[$k] );
									endif;
								endforeach;
								return $meta;
							} ) );
					endif;

					if ( get_option( 'wpedtra-terms', 'no' ) === 'yes' ):
						register_rest_field( $pt, "terms", array( "get_callback" => function ($post) {
								$taxonomies = get_post_taxonomies( $post['id'] );
								$terms_and_taxonomies = [];
								foreach ( $taxonomies as $taxonomy_name ) {
									$terms_and_taxonomies[$taxonomy_name] = wp_get_post_terms( $post['id'], $taxonomy_name );
								}
								return $terms_and_taxonomies;
							} ) );
					endif;

					if ( get_option( 'wpedtra-children_ids', 'no' ) === 'yes' ):
						register_rest_field( $pt, "children_ids", array( "get_callback" => function ($post) use($pt) {
								$args = array(
									'post_parent' => $post['id'],
									'post_type' => $pt,
									'numberposts' => 100,
									'post_status' => 'publish',
									'fields' => 'ids'
								);

								$children = get_children( $args, ARRAY_N );


								return $children;
							} ) );
					endif;


					if ( get_option( 'wpedtra-p2p', 'no' ) === 'yes' ):
						if ( function_exists( '_p2p_load' ) ):

							global $wpdb;
							if ( !empty( $wpdb->p2p ) && !empty( $wpdb->p2pmeta ) ) :
								register_rest_field( $pt, "p2p", array( "get_callback" => function ($post) {
										global $wpdb;
										$p2p_to = $wpdb->get_results( $wpdb->prepare( "SELECT p2p_id, p2p_from , p2p_to, p2p_type  FROM {$wpdb->p2p} WHERE p2p_to = %d ", array( $post['id'] ) ), OBJECT );
										foreach ( $p2p_to as $k => $v ):
											$p = get_post( $v->p2p_from );
											$p2p_to[$k]->p2p_type = $v->p2p_type;
											$p2p_to[$k]->post_title = $p->post_title;
											$p2p_meta_to = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, meta_key  FROM {$wpdb->p2pmeta} WHERE  p2p_id=%d", array( $v->p2p_id ) ), OBJECT );
											foreach ( $p2p_meta_to as $k_meta => $v_meta ):
												$p2p_to[$k]->$k_meta = $v_meta;
											endforeach;
										endforeach;
										$p2p_from = $wpdb->get_results( $wpdb->prepare( "SELECT p2p_id, p2p_from , p2p_to, p2p_type  FROM {$wpdb->p2p} WHERE p2p_from = %d ", array( $post['id'] ) ), OBJECT );
										foreach ( $p2p_from as $k => $v ):
											$p = get_post( $v->p2p_to );
											$p2p_from[$k]->p2p_type = $v->p2p_type;
											$p2p_from[$k]->post_title = $p->post_title;
											$p2p_meta_to = $wpdb->get_results( $wpdb->prepare( "SELECT meta_value, meta_key  FROM {$wpdb->p2pmeta} WHERE  p2p_id=%d", array( $v->p2p_id ) ), OBJECT );
											foreach ( $p2p_meta_to as $k_meta => $v_meta ):
												$p2p_from[$k]->$k_meta = $v_meta;
											endforeach;
										endforeach;

										$p2p = array();
										$p2p['to'] = $p2p_to;
										$p2p['from'] = $p2p_from;

										return $p2p;
									} ) );
							endif;

						endif;
					endif;
				endif;
			endforeach;
		}

	}

	

	

	

	

	

	
	
endif;
