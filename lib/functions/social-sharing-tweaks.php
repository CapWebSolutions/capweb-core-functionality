<?php
/**
 * Social Sharing Tweaks
 *
 * This file contains any  functions related to our sharing plugins to Core Functionality.
 *
 * @package      Core_Functionality
 * @since        3.64.1
 * @link         https://github.com/CapWebSolutions/capweb-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Reorder icons in Simple Social Icons plugin

add_filter( 'simple_social_default_profiles', 'capweb_custom_reorder_simple_icons' );

function capweb_custom_reorder_simple_icons( $icons ) {

	// Set your new order here
	$new_icon_order = array(
		'twitter'     => '',
		'facebook'    => '',
		'github'      => '',
		'linkedin'    => ''
  	);

	foreach( $new_icon_order as $icon => $icon_info ) {
		$new_icon_order[ $icon ] = $icons[ $icon ];
	}

	return $new_icon_order;
}

/**
 * @link: https://github.com/robincornett/scriptless-social-sharing?tab=readme-ov-file#how-can-i-add-a-custom-sharing-button
 */
// add_filter( 'scriptlesssocialsharing_register', 'capweb_scriptless_add_tumblr_button' );
/**
* Adds a custom sharing button to Scriptless Social Sharing.
*
* @return void
*/
function capweb_scriptless_add_threads_button( $buttons ) {
	$buttons['tumblr'] = array(
		'label'    => __( 'Threads', 'scriptless-social-sharing' ),
		'url_base' => 'https://www.threads.net/share/link',
		'args'     => array(
			'query_args' => array(
				'name' => '%%title%%',
				'url'  => '%%permalink%%',
			),
			'color'      => '#35465c',
			'svg'        => 'tumblr-square', // Use this with the SVG icons and add the SVG file to your theme's `assets/svg` folder
			'icon'       => 'f173', // Use this when using the FontAwesome font for icons
		),
	);

	return $buttons;
}