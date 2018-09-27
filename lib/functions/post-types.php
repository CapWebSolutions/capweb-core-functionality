<?php
/**
 * Post Types
 *
 * This file registers any custom post types
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Create Portfolio post type
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

function capweb_register_portfolio_post_type() {
	$labels = array(
		'name'                  => __( 'Portfolio', 'portfolio-post-type' ),
		'singular_name'         => __( 'Portfolio Item', 'portfolio-post-type' ),
		'menu_name'             => _x( 'Portfolio', 'admin menu', 'portfolio-post-type' ),
		'name_admin_bar'        => _x( 'Portfolio Item', 'add new on admin bar', 'portfolio-post-type' ),
		'add_new'               => __( 'Add New Item', 'portfolio-post-type' ),
		'add_new_item'          => __( 'Add New Portfolio Item', 'portfolio-post-type' ),
		'new_item'              => __( 'Add New Portfolio Item', 'portfolio-post-type' ),
		'edit_item'             => __( 'Edit Portfolio Item', 'portfolio-post-type' ),
		'view_item'             => __( 'View Item', 'portfolio-post-type' ),
		'all_items'             => __( 'All Portfolio Items', 'portfolio-post-type' ),
		'search_items'          => __( 'Search Portfolio', 'portfolio-post-type' ),
		'parent_item_colon'     => __( 'Parent Portfolio Item:', 'portfolio-post-type' ),
		'not_found'             => __( 'No portfolio items found', 'portfolio-post-type' ),
		'not_found_in_trash'    => __( 'No portfolio items found in trash', 'portfolio-post-type' ),
		'filter_items_list'     => __( 'Filter portfolio items list', 'portfolio-post-type' ),
		'items_list_navigation' => __( 'Portfolio items list navigation', 'portfolio-post-type' ),
		'items_list'            => __( 'Portfolio items list', 'portfolio-post-type' ),
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
		'capability_type' => 'post',
		'rewrite'         => array( 'slug' => 'portfolio', ), // Permalinks format
		'menu_position'   => 5,
		'menu_icon'       => 'dashicons-portfolio',
		'has_archive'     => true,
	);

	register_post_type( 'Portfolio', $args );
}
add_action( 'init', 'capweb_register_portfolio_post_type' );	


