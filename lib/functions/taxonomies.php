<?php
/**
 * Taxonomies
 *
 * This file registers any custom taxonomies
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


/**
 * Create Portfolio Category Taxonomy
 * @since 1.0.0
 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

function capweb_register_portfolio_category_taxonomy() {
	$labels = array(
		'name'                       => __( 'Portfolio Categories', 'portfolio-post-type' ),
		'singular_name'              => __( 'Portfolio Category', 'portfolio-post-type' ),
		'menu_name'                  => __( 'Portfolio Categories', 'portfolio-post-type' ),
		'edit_item'                  => __( 'Edit Portfolio Category', 'portfolio-post-type' ),
		'update_item'                => __( 'Update Portfolio Category', 'portfolio-post-type' ),
		'add_new_item'               => __( 'Add New Portfolio Category', 'portfolio-post-type' ),
		'new_item_name'              => __( 'New Portfolio Category Name', 'portfolio-post-type' ),
		'parent_item'                => __( 'Parent Portfolio Category', 'portfolio-post-type' ),
		'parent_item_colon'          => __( 'Parent Portfolio Category:', 'portfolio-post-type' ),
		'all_items'                  => __( 'All Portfolio Categories', 'portfolio-post-type' ),
		'search_items'               => __( 'Search Portfolio Categories', 'portfolio-post-type' ),
		'popular_items'              => __( 'Popular Portfolio Categories', 'portfolio-post-type' ),
		'separate_items_with_commas' => __( 'Separate portfolio categories with commas', 'portfolio-post-type' ),
		'add_or_remove_items'        => __( 'Add or remove portfolio categories', 'portfolio-post-type' ),
		'choose_from_most_used'      => __( 'Choose from the most used portfolio categories', 'portfolio-post-type' ),
		'not_found'                  => __( 'No portfolio categories found.', 'portfolio-post-type' ),
		'items_list_navigation'      => __( 'Portfolio categories list navigation', 'portfolio-post-type' ),
		'items_list'                 => __( 'Portfolio categories list', 'portfolio-post-type' ),
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

function capweb_register_portfolio_tag_taxonomy() {
	$labels = array(
		'name'                       => __( 'Portfolio Tags', 'portfolio-post-type' ),
		'singular_name'              => __( 'Portfolio Tag', 'portfolio-post-type' ),
		'menu_name'                  => __( 'Portfolio Tags', 'portfolio-post-type' ),
		'edit_item'                  => __( 'Edit Portfolio Tag', 'portfolio-post-type' ),
		'update_item'                => __( 'Update Portfolio Tag', 'portfolio-post-type' ),
		'add_new_item'               => __( 'Add New Portfolio Tag', 'portfolio-post-type' ),
		'new_item_name'              => __( 'New Portfolio Tag Name', 'portfolio-post-type' ),
		'parent_item'                => __( 'Parent Portfolio Tag', 'portfolio-post-type' ),
		'parent_item_colon'          => __( 'Parent Portfolio Tag:', 'portfolio-post-type' ),
		'all_items'                  => __( 'All Portfolio Tags', 'portfolio-post-type' ),
		'search_items'               => __( 'Search Portfolio Tags', 'portfolio-post-type' ),
		'popular_items'              => __( 'Popular Portfolio Tags', 'portfolio-post-type' ),
		'separate_items_with_commas' => __( 'Separate portfolio tags with commas', 'portfolio-post-type' ),
		'add_or_remove_items'        => __( 'Add or remove portfolio tags', 'portfolio-post-type' ),
		'choose_from_most_used'      => __( 'Choose from the most used portfolio tags', 'portfolio-post-type' ),
		'not_found'                  => __( 'No portfolio tags found.', 'portfolio-post-type' ),
		'items_list_navigation'      => __( 'Portfolio tags list navigation', 'portfolio-post-type' ),
		'items_list'                 => __( 'Portfolio tags list', 'portfolio-post-type' ),
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
	);;

	register_taxonomy( 'portfolio_tag', 'portfolio', $args );
}

add_action( 'init', 'capweb_register_portfolio_category_taxonomy' );
add_action( 'init', 'capweb_register_portfolio_tag_taxonomy' );