<?php
/**
 * General
 *
 * This file contains any general login or logout functions
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/capwebsolutions/penncat-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @copyright    Copyright (c) 2023, Matt Ryan
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


/**	
 * Redirect non-admin users to home page on logout. 
 */
function capweb_logout_redirect( $redirect_to, $requested_redirect, $user ) {
    if ( ! is_wp_error( $user ) && ! current_user_can( 'administrator' ) ) {
        // Redirect non-admin users to the home page after logout
        $redirect_to = home_url();
    }
    return $redirect_to;
}
add_filter( 'logout_redirect', 'capweb_logout_redirect', 10, 3 );

/** 
 * Redirect xxx role to Video Tutorials page on login
 */

//  https://capwebsolutions.com/wp-admin/admin.php?page=wp101