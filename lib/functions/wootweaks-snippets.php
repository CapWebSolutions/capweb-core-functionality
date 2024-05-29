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

// WooCommerce Snippets ===================================================
// Hide SKU, Cats, Tags @ Single Product Page - WooCommerce.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// WooCommerce Remove Product Image Placeholder-ref: https://njengah.com/woocommerce-remove-product-image-placeholder/.
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

// Remove sale badge from product image placeholder
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

// Remove "Description" Title @ WooCommerce Single Product Tabs-https://businessbloomer.com/?p=97716.
add_filter( 'woocommerce_product_description_heading', '__return_null' );

// Remove range display on variable subscriptions-ref: https://stackoverflow.com/questions/57486088/hide-woocommerce-variable-subscription-product-price-range.
add_filter( 'woocommerce_get_price_html', 'hide_price_html_for_variable_subscription', 10, 2 );
function hide_price_html_for_variable_subscription( $price, $product ){
    if ( $product->is_type('variable-subscription') ) {
        $price = '';
    }
    return $price;
}

// Auto hide Ship to Different Address @ Checkout Page - Display of same hidden by CSS-Rodolfo Melogli.
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );

// Rename Description Product Tab Label @ WooCommerce Single Product. https://businessbloomer.com/?p=97724.
add_filter( 'woocommerce_product_description_tab_title', 'bbloomer_rename_description_product_tab_label' );
function bbloomer_rename_description_product_tab_label() {
     return 'Plan Details';
}

// Remove Additional Information Tab @ WooCommerce Single Product Page. Rodolfo Melogli. 
add_filter( 'woocommerce_product_tabs', 'bbloomer_remove_product_tabs', 9999 );
function bbloomer_remove_product_tabs( $tabs ) {
     unset( $tabs['additional_information'] ); 
     return $tabs;
}

//Change the 'Billing details' checkout label to 'Order details'
add_filter( 'gettext', 'capweb_change_billing_details_field_strings', 20, 3 );
function capweb_change_billing_details_field_strings( $translated_text, $text, $domain ) {
	switch ( $translated_text ) {
		case 'Billing details' :
			$translated_text = __( 'Order details', 'woocommerce' );
			break;
	}
	return $translated_text;
}

add_filter( 'woocommerce_checkout_fields', 'capweb_tweak_checkout_page', 9999 );
/*
 * capweb_tweak_checkout_page
 * Make a number of changes to requied and displayed fields on checkout. 
 *
 * @param [type] $woo_checkout_fields_array
 * @return Updated $woo_checkout_fields_array
 */
function capweb_tweak_checkout_page ( $woo_checkout_fields_array ) {
  // Change phone to not required.
	unset( $woo_checkout_fields_array['billing']['billing_phone']['required'] );
  // Remove all these fields from checkout.
	// unset( $woo_checkout_fields_array['billing']['billing_country'] );
	// unset( $woo_checkout_fields_array['billing']['billing_address_1'] );
	unset( $woo_checkout_fields_array['billing']['billing_address_2'] );
	// unset( $woo_checkout_fields_array['billing']['billing_city'] );
	// unset( $woo_checkout_fields_array['billing']['billing_state'] );
	// unset( $woo_checkout_fields_array['billing']['billing_postcode'] );
	// Move email to top of the page
	$woo_checkout_fields_array['billing']['billing_email']['priority'] = 4;
 
	return $woo_checkout_fields_array;
}
// Remove related products output.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// Show Hidden Custom Fields @ WooCommerce Product / Order Edit-Rodolfo Melogli.
add_filter( 'is_protected_meta', '__return_false' ); 


// Only use click to code function on pages where it is needed. 
// Do not want to add the hidden ‘Click To Copy’ esp on the front page.
function capweb_check_click2code() {
    // ID 9418 - https://capwebsolutions.com/contact/provide-hosting-migration-info/
    // ID 9431 - https://capwebsolutions.com/contact/provide-website-care-plan-credentials/
    $click2copy_pages = array('9418', '9431');
	
    if ( in_array( the_ID(), $click2copy_pages ) ) {
		add_action('wp_footer', 'codecopy_activate');
	} else {
		remove_action('wp_footer', 'codecopy_activate');
	}
}
add_action('wp_footer', 'capweb_check_click2code');

/**
 * @snippet       Programmatically Complete Paid WooCommerce Orders
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 * @community     https://businessbloomer.com/club/
 */
 
 add_filter( 'woocommerce_payment_complete_order_status', 'bbloomer_autocomplete_processing_orders', 9999 );
 
 function bbloomer_autocomplete_processing_orders() {
	return 'completed';
 }