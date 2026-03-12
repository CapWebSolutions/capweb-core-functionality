<?php

/**
 * Post Types functionality
 *
 * This file handles custom post types registration
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/includes
 * @since      5.0.0
 */

/**
 * Post Types class
 *
 * @since 5.0.0
 */
class Capweb_Core_Functionality_Post_Types {

	/**
	 * Initialize the class
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_portfolio_post_type' ) );
	}

	/**
	 * Create Portfolio post type
	 *
	 * @since 5.0.0
	 */
	public function register_portfolio_post_type() {
		$labels = array(
			'name'                  => __( 'Portfolio', 'capweb-core-functionality' ),
			'singular_name'         => __( 'Portfolio Item', 'capweb-core-functionality' ),
			'menu_name'             => _x( 'Portfolio', 'admin menu', 'capweb-core-functionality' ),
			'name_admin_bar'        => _x( 'Portfolio Item', 'add new on admin bar', 'capweb-core-functionality' ),
			'add_new'               => __( 'Add New Item', 'capweb-core-functionality' ),
			'add_new_item'          => __( 'Add New Portfolio Item', 'capweb-core-functionality' ),
			'new_item'              => __( 'Add New Portfolio Item', 'capweb-core-functionality' ),
			'edit_item'             => __( 'Edit Portfolio Item', 'capweb-core-functionality' ),
			'view_item'             => __( 'View Item', 'capweb-core-functionality' ),
			'all_items'             => __( 'All Portfolio Items', 'capweb-core-functionality' ),
			'search_items'          => __( 'Search Portfolio', 'capweb-core-functionality' ),
			'parent_item_colon'     => __( 'Parent Portfolio Item:', 'capweb-core-functionality' ),
			'not_found'             => __( 'No portfolio items found', 'capweb-core-functionality' ),
			'not_found_in_trash'    => __( 'No portfolio items found in trash', 'capweb-core-functionality' ),
			'filter_items_list'     => __( 'Filter portfolio items list', 'capweb-core-functionality' ),
			'items_list_navigation' => __( 'Portfolio items list navigation', 'capweb-core-functionality' ),
			'items_list'            => __( 'Portfolio items list', 'capweb-core-functionality' ),
		);

		$supports = array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'author',
			'custom-fields',
			'revisions',
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'publicly_queryable'  => true,
			'capability_type' => 'post',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'show_in_rest'        => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'         => array( 'slug' => 'portfolio' ),
			'menu_position'   => 5,
			'menu_icon'       => 'dashicons-portfolio',
			'has_archive'     => true,
		);

		register_post_type( 'portfolio', $args );
	}
} 