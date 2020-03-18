<?php
/**
 * General
 *
 * This file contains any general functions related to WooCommerce
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/billerickson/Core-Functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */




// WooCommerce Specific ===================================================

// Declare general WooCommerce Support
// Source: http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// Add Product Direct to Checkout
// Source: http://www.remicorson.com/woocommerce-skip-product-cart-pages/
add_filter ('woocommerce_add_to_cart_redirect', 'woo_redirect_to_checkout');

function woo_redirect_to_checkout() {
  $checkout_url = WC()->cart->get_checkout_url();
  return $checkout_url;
}

// Source: http://docs.woothemes.com/document/remove-related-posts-output/
/*
 * wc_remove_related_products
 */
function wc_remove_related_products( $args ) {
  return array();
}
add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);

// Remove Sort By Drop Down
// Courtesy: http://bloke.org/wordpress/snippet-removing-sort-woocommerce/
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

// Remove payment gateway for named product category
// Courtesy: https://wordpress.org/support/topic/restrict-payment-options-based-on-product
/*
 * A simple filter to disable a user-specified payment gateway when a product with
 * a user-specified category is added to the shopping cart
 * Note:  If multiple products are added and only one has a matching category, it
 * will remove the payment gateway
 * Requires:
 *    payment_NAME : One of the five hardcoded Woocommerce standard types of
 *    payment gateways - paypal, cod, bacs, cheque or mijireh_checkout
 *    category_ID :   The ID of the category for which the gateway above will be removed.
 *      Get the ID by clicking on the category under Products -> Categories and reading the
 *      "tag_ID" in the address bar
 *      i.e. http://ubuntu.humble.lan/wp-admin/edit-tags.php?action=edit&taxonomy=product_cat&tag_ID=20&post_type=product
 *                      <-- the tag_ID is 20
 * Coded by sean _ at _ techonfoot.com
 * Thanks to boxoft -
 * 	http://stackoverflow.com/questions/15303031/woocommerce-get-category-for-product-page
 * Usual free code disclaimer - use at your own risk
 * This code was tested against Woocommerce 4.0 and WordPress 5.3.2
 */

function cws_filter_gateways($gateways) {

$payment_NAME = 'cheque'; // <--------------- change this
$category_ID = '187';  // <----------- and this

 global $woocommerce;
// Seems that get_cart doesn't like to run in the admin. 3/18/20
if ( ! is_admin() ) { 
  $cart_items = $woocommerce->cart->get_cart();
  foreach ( $cart_items as $key => $item ) {
    $terms = get_the_terms( $values['product_id'], 'product_cat' );
    // Because a product can have multiple categories, we need to iterate through the list of the products category for a match
    foreach ($terms as $term) {
      // 187 is the ID of the category for which we want to remove the payment gateway
      if($term->term_id == $category_ID){
        unset($gateways['cheque']);
        // If you want to remove another payment gateway, add it here i.e. unset($gateways['cod']);
        break;
      }
	    break;
    }
   }
  }
	return $gateways;
}

add_filter('woocommerce_available_payment_gateways','cws_filter_gateways');



/**
 * Complete virtual downloadable orders when payment made. 
 * https://www.skyverge.com/blog/how-to-set-woocommerce-virtual-order-status-to-complete-after-payment/
 */

add_filter( 'woocommerce_payment_complete_order_status', 'virtual_order_payment_complete_order_status', 10, 2 );
 
function virtual_order_payment_complete_order_status( $order_status, $order_id ) {
  $order = new WC_Order( $order_id );
 
  if ( 'processing' == $order_status &&
       ( 'on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status ) ) {
 
    $virtual_order = null;
 
    if ( count( $order->get_items() ) > 0 ) {
 
      foreach( $order->get_items() as $item ) {
 
        if ( 'line_item' == $item['type'] ) {
 
          $_product = $order->get_product_from_item( $item );
 
          if ( ! $_product->is_virtual() ) {
            // once we've found one non-virtual product we know we're done, break out of the loop
            $virtual_order = false;
            break;
          } else {
            $virtual_order = true;
          }
        }
      }
    }
 
    // virtual order, mark as completed
    if ( $virtual_order ) {
      return 'completed';
    }
  }
 
  // non-virtual order, return original status
  return $order_status;
}

/**
 * @snippet       Print List of Category IDs @ Product Categories Admin
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.1.1
 */
 
add_action( 'product_cat_pre_add_form', 'bbloomer_list_all_product_cat_ids', 5 );
 
function bbloomer_list_all_product_cat_ids() {
 
$ids = '';
 
$categories = get_categories( array(                    
    'taxonomy' => 'product_cat' ) );
  
foreach( $categories as $category ) {
    $ids .= $category->term_id . ', ';
} 
 
echo 'Category IDs: ' . $ids;
}