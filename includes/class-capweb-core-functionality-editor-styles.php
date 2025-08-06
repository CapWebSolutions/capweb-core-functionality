<?php

/**
 * Editor Styles functionality
 *
 * This file handles editor style refresh functionality
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/includes
 * @since      5.0.0
 */

/**
 * Editor Styles class
 *
 * @since 5.0.0
 */
class Capweb_Core_Functionality_Editor_Styles {

	/**
	 * Initialize the class
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		add_filter( 'mce_css', array( $this, 'fresh_editor_style' ) );
	}

	/**
	 * Adds a parameter of the last modified time to all editor stylesheets.
	 *
	 * @since 5.0.0
	 * @param string $css Comma separated stylesheet URIs
	 * @return string
	 */
	public function fresh_editor_style( $css ) {
		global $editor_styles;

		if ( empty( $css ) || empty( $editor_styles ) ) {
			return $css;
		}

		// Modified copy of _WP_Editors::editor_settings()
		$mce_css   = array();
		$style_uri = get_stylesheet_directory_uri();
		$style_dir = get_stylesheet_directory();

		if ( is_child_theme() ) {
			$template_uri = get_template_directory_uri();
			$template_dir = get_template_directory();

			foreach ( $editor_styles as $key => $file ) {
				if ( $file && file_exists( "$template_dir/$file" ) ) {
					$mce_css[] = add_query_arg(
						'version',
						filemtime( "$template_dir/$file" ),
						"$template_uri/$file"
					);
				}
			}
		}

		foreach ( $editor_styles as $file ) {
			if ( $file && file_exists( "$style_dir/$file" ) ) {
				$mce_css[] = add_query_arg(
					'version',
					filemtime( "$style_dir/$file" ),
					"$style_uri/$file"
				);
			}
		}

		return implode( ',', $mce_css );
	}
} 