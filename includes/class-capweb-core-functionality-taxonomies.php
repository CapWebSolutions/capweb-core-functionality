<?php

/**
 * Taxonomies functionality
 *
 * This file handles custom taxonomies registration
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/includes
 * @since      5.0.0
 */

/**
 * Taxonomies class
 *
 * @since 5.0.0
 */
class Capweb_Core_Functionality_Taxonomies {

	/**
	 * Initialize the class
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_portfolio_category_taxonomy' ) );
		add_action( 'init', array( $this, 'register_portfolio_tag_taxonomy' ) );
	}

	/**
	 * Create Portfolio Category Taxonomy
	 *
	 * @since 5.0.0
	 */
	public function register_portfolio_category_taxonomy() {
		$labels = array(
			'name'                       => __( 'Portfolio Categories', 'capweb-core-functionality' ),
			'singular_name'              => __( 'Portfolio Category', 'capweb-core-functionality' ),
			'menu_name'                  => __( 'Portfolio Categories', 'capweb-core-functionality' ),
			'edit_item'                  => __( 'Edit Portfolio Category', 'capweb-core-functionality' ),
			'update_item'                => __( 'Update Portfolio Category', 'capweb-core-functionality' ),
			'add_new_item'               => __( 'Add New Portfolio Category', 'capweb-core-functionality' ),
			'new_item_name'              => __( 'New Portfolio Category Name', 'capweb-core-functionality' ),
			'parent_item'                => __( 'Parent Portfolio Category', 'capweb-core-functionality' ),
			'parent_item_colon'          => __( 'Parent Portfolio Category:', 'capweb-core-functionality' ),
			'all_items'                  => __( 'All Portfolio Categories', 'capweb-core-functionality' ),
			'search_items'               => __( 'Search Portfolio Categories', 'capweb-core-functionality' ),
			'popular_items'              => __( 'Popular Portfolio Categories', 'capweb-core-functionality' ),
			'separate_items_with_commas' => __( 'Separate portfolio categories with commas', 'capweb-core-functionality' ),
			'add_or_remove_items'        => __( 'Add or remove portfolio categories', 'capweb-core-functionality' ),
			'choose_from_most_used'      => __( 'Choose from the most used portfolio categories', 'capweb-core-functionality' ),
			'not_found'                  => __( 'No portfolio categories found.', 'capweb-core-functionality' ),
			'items_list_navigation'      => __( 'Portfolio categories list navigation', 'capweb-core-functionality' ),
			'items_list'                 => __( 'Portfolio categories list', 'capweb-core-functionality' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'portfolio_category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		register_taxonomy( 'portfolio_category', 'portfolio', $args );
	}

	/**
	 * Create Portfolio Tag Taxonomy
	 *
	 * @since 5.0.0
	 */
	public function register_portfolio_tag_taxonomy() {
		$labels = array(
			'name'                       => __( 'Portfolio Tags', 'capweb-core-functionality' ),
			'singular_name'              => __( 'Portfolio Tag', 'capweb-core-functionality' ),
			'menu_name'                  => __( 'Portfolio Tags', 'capweb-core-functionality' ),
			'edit_item'                  => __( 'Edit Portfolio Tag', 'capweb-core-functionality' ),
			'update_item'                => __( 'Update Portfolio Tag', 'capweb-core-functionality' ),
			'add_new_item'               => __( 'Add New Portfolio Tag', 'capweb-core-functionality' ),
			'new_item_name'              => __( 'New Portfolio Tag Name', 'capweb-core-functionality' ),
			'parent_item'                => __( 'Parent Portfolio Tag', 'capweb-core-functionality' ),
			'parent_item_colon'          => __( 'Parent Portfolio Tag:', 'capweb-core-functionality' ),
			'all_items'                  => __( 'All Portfolio Tags', 'capweb-core-functionality' ),
			'search_items'               => __( 'Search Portfolio Tags', 'capweb-core-functionality' ),
			'popular_items'              => __( 'Popular Portfolio Tags', 'capweb-core-functionality' ),
			'separate_items_with_commas' => __( 'Separate portfolio tags with commas', 'capweb-core-functionality' ),
			'add_or_remove_items'        => __( 'Add or remove portfolio tags', 'capweb-core-functionality' ),
			'choose_from_most_used'      => __( 'Choose from the most used portfolio tags', 'capweb-core-functionality' ),
			'not_found'                  => __( 'No portfolio tags found.', 'capweb-core-functionality' ),
			'items_list_navigation'      => __( 'Portfolio tags list navigation', 'capweb-core-functionality' ),
			'items_list'                 => __( 'Portfolio tags list', 'capweb-core-functionality' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => 'portfolio_tag' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		register_taxonomy( 'portfolio_tag', 'portfolio', $args );
	}
} 