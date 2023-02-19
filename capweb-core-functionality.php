<?php
/**
 * Plugin Name: Cap Web Solutions Core Functionality 
 * Plugin URI: https://github.com/CapWebSolutions/capweb-core-functionality
 * Description: This contains all this site's core functionality so that it
 *  is theme independent.
 * 
 * @version: 3.17.0
 * @author:  Matt Ryan [Cap Web Solutions]
 * @link:    https://capwebsolutions.com
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License version 2, as published
 * by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 * FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Plugin Directory. Set constant so we know where we are installed.
$plugin_url = plugin_dir_url( __FILE__ );
if ( is_ssl() ) {
  $plugin_url = str_replace( 'http://', 'https://', $plugin_url );
}
define( 'CORE_FUNCTION_URL', $plugin_url );
define( 'CORE_FUNCTION_DIR', plugin_dir_path( __FILE__ ) );


// Post Types
require_once CORE_FUNCTION_DIR . '/lib/functions/post-types.php';

// Taxonomies
include_once( CORE_FUNCTION_DIR . '/lib/functions/taxonomies.php' );

// Editor Style Refresh
require_once CORE_FUNCTION_DIR . '/lib/functions/editor-style-refresh.php';

// General
require_once CORE_FUNCTION_DIR . '/lib/functions/general.php';

// Woo tweaks. Only if WooCommerce active.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	include_once( CORE_FUNCTION_DIR . '/lib/functions/wootweaks.php' );
	// include_once( CORE_FUNCTION_DIR . '/lib/functions/wootweaks-emails.php' );
	include_once( CORE_FUNCTION_DIR . '/lib/functions/wootweaks-snippets.php' );
}
// Gravity Forms tweaks. .
include_once( CORE_FUNCTION_DIR . '/lib/functions/gravitytweaks.php' );

//
// Enqueue / register needed scripts & styles
add_action( 'wp_enqueue_scripts', 'core_functionality_enqueue_needed_scripts' );
add_action( 'admin_enqueue_scripts', 'core_functionality_enqueue_needed_scripts' );
function core_functionality_enqueue_needed_scripts() {
   wp_enqueue_script( 'jstz-script', CORE_FUNCTION_URL . 'assets/js/jstz.min.js', array(), null, true );
   wp_enqueue_script( 'core-funct-jquery', CORE_FUNCTION_URL . 'assets/js/capweb-core-jquery.js', array( 'jquery' ), '' );
   
   wp_enqueue_style( 'core_funct-style', CORE_FUNCTION_URL . 'assets/css/capweb-core-style.css', array( 'jquery' ), '' );

	// Ref: application of these fonts: https://sridharkatakam.com/using-font-awesome-wordpress/
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', array( 'jquery' ), CHILD_THEME_VERSION, true );
}
