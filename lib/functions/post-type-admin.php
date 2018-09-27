<?php
/**
 * Portfolio Post Type
 *
 * @package   Portfolio_Post_Type
 * @author    Devin Price
 * @author    Gary Jones
 * @license   GPL-2.0+
 * @link      http://wptheming.com/portfolio-post-type/
 * @copyright 2011 Devin Price, Gary Jones
 */

/**
 * Register post types and taxonomies.
 *
 * @package Portfolio_Post_Type
 * @author  Devin Price
 * @author  Gary Jones
 */
class Portfolio_Post_Type_Admin {

	protected $registration_handler;

	public function __construct( $registration_handler ) {
		$this->registration_handler = $registration_handler;
	}

	public function init() {

		// Allow filtering of posts by taxonomy in the admin view
		add_action( 'restrict_manage_posts', array( $this, 'add_taxonomy_filters' ) );

		// Adds menu icons
		add_action( 'admin_head', array( $this, 'add_icons' ) );
	}

	/**
	 * Add columns to post type list screen.
	 *
	 * @link http://wptheming.com/2010/07/column-edit-pages/
	 *
	 * @param array $columns Existing columns.
	 *
	 * @return array Amended columns.
	 */
	public function add_thumbnail_column( $columns ) {
		$column_thumbnail = array( 'thumbnail' => __( 'Thumbnail', 'portfolio-post-type' ) );
		return array_slice( $columns, 0, 2, true ) + $column_thumbnail + array_slice( $columns, 1, null, true );
	}

	/**
	 * Get the friendly taxonomy name, if given a taxonomy slug.
	 *
	 * @param  string $tax_slug Taxonomy slug.
	 *
	 * @return string Friendly name of taxonomy, or empty string if not a valid taxonomy.
	 */
	protected function get_taxonomy_name_from_slug( $tax_slug ) {
		$tax_obj = get_taxonomy( $tax_slug );
		if ( ! $tax_obj )
			return '';
		return $tax_obj->labels->name;
	}
}