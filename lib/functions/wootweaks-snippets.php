<?php
/**
 * General
 *
 * This file contains code snippets related to WooCommerce
 *
 * @package      Core_Functionality
 * @since        3.0.1
 * @link         https://github.com/CapWebSolutions/capweb-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// WooCommerce Specific ===================================================
add_action( 'after_setup_theme', 'capweb_were_alive', 20 );
function capweb_were_alive( ) {
      error_log( print_r( (object)
        [
            'file' => __FILE__,
            'method' => __METHOD__,
            'line' => __LINE__,
            'dump' => [
                CHILD_THEME_VERSION,
            ],
        ], true ) );
}