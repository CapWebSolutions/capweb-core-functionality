<?php
/**
 * Plugin Name: Cap Web Solutions Core Functionality 
 * Plugin URI: https://github.com/billerickson/Core-Functionality
 * Description: This contains all this site's core functionality so that it is theme independent. Customized for capwebsolutions.com
 * Version: 2.0.0
 * Author: Matt Ryan [Cap Web Solutions]
 * Author URI: http://www.billerickson.net
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// Plugin Directory
define( 'CWS_DIR', dirname( __FILE__ ) );

// Post Types
include_once( CWS_DIR . '/lib/functions/post-types.php' );

// Taxonomies
include_once( CWS_DIR . '/lib/functions/taxonomies.php' );

// Metaboxes
//include_once( CWS_DIR . '/lib/functions/metaboxes.php' );

// Widgets
//include_once( CWS_DIR . '/lib/widgets/widget-social.php' );

// Editor Style Refresh
require_once CWS_DIR . '/lib/functions/editor-style-refresh.php';

// General
require_once CWS_DIR . '/lib/functions/general.php';

// Woo tweaks
require_once CWS_DIR . '/lib/functions/wootweaks.php';

// Plugin Directory.
define( 'CORE_FUNCTION_URL', $plugin_url );

//
// Enqueue / register needed scripts & styles
add_action( 'wp_enqueue_scripts', 'core_functionality_enqueue_needed_scripts' );
add_action( 'admin_enqueue_scripts', 'core_functionality_enqueue_needed_scripts' );
function core_functionality_enqueue_needed_scripts() {
   wp_enqueue_script( 'jstz-script', CORE_FUNCTION_URL . 'assets/js/jstz.min.js', array(), null, true );
}