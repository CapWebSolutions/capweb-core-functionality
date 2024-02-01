<?php
/**
 * General
 *
 * This file contains any general functions related to WooCommerce
 *
 * @package      Core_Functionality
 * @since        3.0.0
 * @link         https://github.com/CapWebSolutions/capweb-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */




// WooCommerce Specific ===================================================

// Declare general WooCommerce Support
// Source: http://docs.woothemes.com/document/third-party-custom-theme-compatibility/
add_action( 'after_setup_theme', 'capweb_woocommerce_support' );
function capweb_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

/**
 * @snippet       Show Hidden Custom Fields @ WooCommerce Product / Order Edit
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 5.1
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 * @link          https://www.businessbloomer.com/woocommerce-view-product-hidden-custom-fields-protected-meta
 */
 
add_filter( 'is_protected_meta', '__return_false' ); 

// Add Product Direct to Checkout
// Source: http://www.remicorson.com/woocommerce-skip-product-cart-pages/
add_filter ('woocommerce_add_to_cart_redirect', 'capweb_redirect_to_checkout');

function capweb_redirect_to_checkout() {
  // $checkout_url = WC()->cart->get_checkout_url();
  // return $checkout_url;
  return wc_get_checkout_url();   //ref: businessbloomer.com/woocommerce-redirect-checkout-add-cart
}

// Source: http://docs.woothemes.com/document/remove-related-posts-output/
/*
 * wc_remove_related_products
 */
function capweb_wc_remove_related_products( $args ) {
  return array();
}
add_filter('woocommerce_related_products_args','capweb_wc_remove_related_products', 10);

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
// add_filter('woocommerce_available_payment_gateways','capweb_filter_gateways');

function capweb_filter_gateways($gateways) {
  $payment_NAME = 'cheque'; // <--------------- change this
  $category_ID = '187';  // <----------- and this
  // 10/14/2020 - pulled this out. We don't even have product category 187 any more. 
  // Don't even remember what it was. 
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




/**
 * Complete virtual downloadable orders when payment made. 
 * https://www.skyverge.com/blog/how-to-set-woocommerce-virtual-order-status-to-complete-after-payment/
 */

add_filter( 'woocommerce_payment_complete_order_status', 'capweb_virtual_order_payment_complete_order_status', 10, 2 );
 
function capweb_virtual_order_payment_complete_order_status( $order_status, $order_id ) {
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
 
add_action( 'product_cat_pre_add_form', 'capweb_list_all_product_cat_ids', 5 );
 
function capweb_list_all_product_cat_ids() {
 
$ids = '';
 
$categories = get_categories( array(                    
    'taxonomy' => 'product_cat' ) );
  
foreach( $categories as $category ) {
    $ids .= $category->term_id . ', ';
} 
 
echo 'Category IDs: ' . $ids;
}

/**
 * Add order note programmatically to all new Maintenance Orders
 * ref: https://woomultistore.com/add-message-or-notes-to-order-emails-woocommerce/
 */
add_action( 'woocommerce_email_order_meta', 'capweb_woo_add_order_notes_to_email' );
function capweb_woo_add_order_notes_to_email() {
	global $woocommerce, $post;
	$args = array(
		'post_id' 	=> $post->ID,
		'approve' 	=> 'approve',
		'type' 		=> 'order_note'
	);
	$notes = get_comments( $args );
	
  if ( $notes ) {
    echo '<hr style="height:5px;border-width:0;color:#546E91;background-color:#546E91">';
    echo '<h2>' . __( 'NOTES', 'woocommerce' ) . '</h2>';
    echo '<ul class="order_notes">';
    foreach( $notes as $note ) {
			$note_classes = get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ? array( 'customer-note', 'note' ) : array( 'note' );
			?>
			<li rel="<?php echo absint( $note->comment_ID ) ; ?>" class="<?php echo implode( ' ', $note_classes ); ?>">
				<div class="note_content">
					<?php printf( __( 'added %s ago', 'woocommerce' ), human_time_diff( strtotime( $note->comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?>) <?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
				</div>
			</li>
			<?php
		}
    echo '</ul>';
    echo '<hr style="height:5px;border-width:0;color:#546E91;background-color:#546E91;margin-bottom: 5px;">';
	// } else {
		// echo '<li class="order-note-highlight">' . __( 'If not already provided, please send us your website details so that we can start to work on your website. Visit <a href="https://capwebsolutions.com/contact/provide-website-maintenance-credentials/">Provide Website Credentials</a>.', 'woocommerce' ) . '</li>';
	}

}


/**
 * @snippet       Add Custom Field @ WooCommerce Checkout Page
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 */
  
add_action( 'woocommerce_before_order_notes', 'capweb_add_custom_checkout_field' );
  
function capweb_add_custom_checkout_field( $checkout ) { 
   $current_user = wp_get_current_user();
   $saved_target_website = $current_user->target_website;
   woocommerce_form_field( 'target_website', array(        
      'type' => 'text',        
      'class' => array( 'form-row-wide' ),        
      'label' => 'Website subscribing to WPcare support',       
      'placeholder' => 'https://example.com',        
      'required' => true,        
      'default' => $saved_target_website,        
   ), $checkout->get_value( 'target_website' ) ); 
}
/**
 * @snippet       Validate Custom Field @ WooCommerce Checkout Page
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 */
 
add_action( 'woocommerce_checkout_process', 'capweb_validate_new_checkout_field' );
  
function capweb_validate_new_checkout_field() {    
   if ( ! $_POST['target_website'] ) {
      wc_add_notice( 'Please enter the web address for this WPcare service.', 'error' );
   }
}

/**
 * @snippet       Save & Display Custom Field @ WooCommerce Order
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_action( 'woocommerce_checkout_update_order_meta', 'capweb_save_new_checkout_field' );
 
function capweb_save_new_checkout_field( $order_id ) { 
    if ( $_POST['target_website'] ) update_post_meta( $order_id, '_target_website', esc_attr( $_POST['target_website'] ) );
}
  
add_action( 'woocommerce_admin_order_data_after_billing_address', 'capweb_show_new_checkout_field_order', 10, 1 );
   
function capweb_show_new_checkout_field_order( $order ) {    
   $order_id = $order->get_id();
   if ( get_post_meta( $order_id, '_target_website', true ) ) echo '<p><strong>Target Website:</strong> ' . get_post_meta( $order_id, '_target_website', true ) . '</p>';
}
 
add_action( 'woocommerce_email_after_order_table', 'capweb_show_new_checkout_field_emails', 20, 4 );
  
function capweb_show_new_checkout_field_emails( $order, $sent_to_admin, $plain_text, $email ) {
    if ( get_post_meta( $order->get_id(), '_target_website', true ) ) 
      echo '<p>WPcare Support on Website: ' . get_post_meta( $order->get_id(), '_target_website', true ) . '</p>';
}


/**
 * @snippet       Add Cc: or Bcc: Recipient @ WooCommerce Completed Order Email
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 4.6
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
// add_filter( 'woocommerce_email_headers', 'capweb_order_completed_email_add_cc_bcc', 9999, 3 );
 
function capweb_order_completed_email_add_cc_bcc( $headers, $email_id, $order ) {
//*    if ( 'customer_completed_order' == $email_id || 'customer_completed_renewal_order' == $email_id ) {  
        // $headers .= "Cc: Name <your@email.com>" . "\r\n"; // del if not needed
        $headers .= "Bcc: Matt <matt+order@capwebsolutions.com>" . "\r\n"; // del if not needed
//    }
    return $headers;
}


/**
 * @snippet       Add Text to Customer Processing Order Email
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    Woo 4.6
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
add_action( 'woocommerce_email_before_order_table', 'capweb_add_content_specific_email', 20, 4 );
  
function capweb_add_content_specific_email( $order, $sent_to_admin, $plain_text, $email ) {
  if ( $email->id == 'customer_completed_renewal_order' ) {
      if ( get_post_meta( $order->get_id(), '_target_website', true ) ) 
      echo '<p>WPcare Support on Website: ' . get_post_meta( $order->get_id(), '_target_website', true ) . '</p>';
  }
}


// Changes on 3/1 after failure. Change number of args from 4->2. Change args to $subject, $order
// add_action( 'woocommerce_subscriptions_email_subject_customer_completed_renewal_order', 'capweb_add_target_website_to_subject', 20, 2 );
  
function capweb_add_target_website_to_subject( $subject, $order ) {
      if ( get_post_meta( $order->get_id(), '_target_website', true ) ) {
        return $subject .= $subject . get_post_meta( $order->get_id(), '_target_website', true );
      }
}


add_filter( 'woocommerce_hidden_order_itemmeta', 'capweb_add_hidden_order_items' );
function capweb_add_hidden_order_items( $order_items ) {
    $order_items[] = '_subscription_interval';
    $order_items[] = '_subscription_length';
    $order_items[] = '_subscription_period';
    $order_items[] = '_subscription_trial_length';
    $order_items[] = '_subscription_trial_period';
    $order_items[] = '_subscription_recurring_amount';
    $order_items[] = '_subscription_sign_up_fee';
    $order_items[] = '_recurring_line_total';
    $order_items[] = '_recurring_line_tax';
    $order_items[] = '_recurring_line_subtotal';
    $order_items[] = '_recurring_line_subtotal_tax';
    // end so on...

    return $order_items;
}

/**
 * Add a BCC email address to ALL WC emails
 */
add_filter( 'woocommerce_email_headers', 'capweb_custom_headers_filter_function', 10, 2);

function capweb_custom_headers_filter_function($headers, $object) {
    $headers = array();
    $headers[] = 'Bcc: info@capwebsolutions.com';
    $headers[] = 'Content-Type: text/html';
    return $headers;
}
/**
 * @snippet       Disable Payment Method for Specific Category
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=19892
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.2.5
 */
 
add_filter( 'woocommerce_available_payment_gateways', 'capweb_unset_gateway_by_category' );

function capweb_unset_gateway_by_category( $available_gateways ) {
// global $woocommerce;
// $unset = false;
// $category_ids = array( 237 );

// Below line generating PHP 8.1 & 8.0 Warnings.  11.30.2023
// Warning (Suppressed)	Attempt to read property "cart_contents" on null	
// Warning (Suppressed)	foreach() argument must be of type array|object, null given

// foreach ( $woocommerce->cart->cart_contents as $key => $values ) {
//    $terms = get_the_terms( $values['product_id'], 'product_cat' );    
//    foreach ( $terms as $term ) {        
//        if ( in_array( $term->term_id, $category_ids ) ) {
//            $unset = true;
//            break;
//        }
//    }
// }
  if ( is_admin() ) return $available_gateways;
  if ( ! is_checkout() ) return $available_gateways;
  $unset = false;
  $category_id = 237; // TARGET CATEGORY
  foreach ( WC()->cart->get_cart_contents() as $key => $values ) {
      $terms = get_the_terms( $values['product_id'], 'product_cat' );    
      foreach ( $terms as $term ) {        
          if ( $term->term_id == $category_id ) {
              $unset = true; // CATEGORY IS IN THE CART
              break;
          }
      }
  }

  if ( $unset == true ) unset( $available_gateways['cheque'] );
  return $available_gateways;
}



/**
 * Manage WooCommerce styles and scripts to get a performance boost.  
 * ref: https://gregrickaby.com/blog/remove-woocommerce-styles-and-scripts
 * Added 2/18/2021
 */
function grd_woocommerce_script_cleaner() {

	// Remove the generator tag
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

	// Unless we're in the store, remove all the cruft!
	if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce-general');
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_script( 'selectWoo' );
		wp_deregister_script( 'selectWoo' );
		wp_dequeue_script( 'wc-add-payment-method' );
		wp_dequeue_script( 'wc-lost-password' );
		wp_dequeue_script( 'wc_price_slider' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-add-to-cart' );
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_dequeue_script( 'wc-credit-card-form' );
		wp_dequeue_script( 'wc-checkout' );
		wp_dequeue_script( 'wc-add-to-cart-variation' );
		wp_dequeue_script( 'wc-single-product' );
		wp_dequeue_script( 'wc-cart' );
		wp_dequeue_script( 'wc-chosen' );
		wp_dequeue_script( 'woocommerce' );
		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
		wp_dequeue_script( 'jquery-blockui' );
		wp_dequeue_script( 'jquery-placeholder' );
		wp_dequeue_script( 'jquery-payment' );
		wp_dequeue_script( 'fancybox' );
		wp_dequeue_script( 'jqueryui' );
	}
}
add_action( 'wp_enqueue_scripts', 'grd_woocommerce_script_cleaner', 99 );



add_action('wp_loaded', 'capweb_woocommerce_coupon_links', 30);
add_action('woocommerce_add_to_cart', 'capweb_woocommerce_coupon_links');
/**
 * Cap Web Woocommerce Coupon links.
 * 
 * Add query parms to permit calling url with WC coupon code auto applied.
 * 
 * Ref: https://www.webroomtech.com/apply-coupon-via-url-in-woocommerce/
 * @return void
 */
function capweb_woocommerce_coupon_links(){

	// Bail if WooCommerce or sessions aren't available.

	if (!function_exists('WC') || !WC()->session) {
		return;
	}

	/**
	 * Filter the coupon code query variable name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $query_var Query variable name.
	 */
	$query_var = apply_filters('woocommerce_coupon_links_query_var', 'coupon_code');

	// Bail if a coupon code isn't in the query string.

	if (empty($_GET[$query_var])) {
		return;
	}

	// Set a session cookie to persist the coupon in case the cart is empty.

	WC()->session->set_customer_session_cookie(true);

	// Apply the coupon to the cart if necessary.

	if (!WC()->cart->has_discount($_GET[$query_var])) {

		// WC_Cart::add_discount() sanitizes the coupon code.

		WC()->cart->add_discount($_GET[$query_var]);
	}
}


// Set minimum quantity per product before checking out
add_action( 'woocommerce_check_cart_items', 'capweb_set_min_qty_per_product' );
/**
 * Set minimum quantity per product
 * @link https://www.sitepoint.com/minimum-checkout-requirements-in-woocommerce/
 * @link https://www.businessbloomer.com/woocommerce-define-add-cart-min-max-incremental-quantities/
 * @link https://www.businessbloomer.com/woocommerce-set-min-purchase-amount-for-specific-product/
 *
 * @return void
 */
function capweb_set_min_qty_per_product() {
	// Only run in the Cart or Checkout pages
	if( is_cart() || is_checkout() ) {	
		global $woocommerce;

		// Product Id and Min. Quantities per Product
    // 1225 is the Hourly Consulting product. 
		$product_min_qty = array( 
			array( 'id' => 1225, 'min' => 2 ),
		);

		// Will increment
		$i = 0;
		// Will hold information about products that have not
		// met the minimum order quantity
		$bad_products = array();

		// Loop through the products in the Cart
		foreach( $woocommerce->cart->cart_contents as $product_in_cart ) {
			// Loop through our minimum order quantities per product
			foreach( $product_min_qty as $product_to_test ) {
				// If we can match the product ID to the ID set on the minimum required array
				if( $product_to_test['id'] == $product_in_cart['product_id'] ) {
					// If the quantity required is less than than the quantity in the cart now
					if( $product_in_cart['quantity'] < $product_to_test['min'] ) {
						// Get the product ID
						$bad_products[$i]['id'] = $product_in_cart['product_id'];
						// Get the Product quantity already in the cart for this product
						$bad_products[$i]['in_cart'] = $product_in_cart['quantity'];
						// Get the minimum required for this product
						$bad_products[$i]['min_req'] = $product_to_test['min'];
					}
				}
			}
			// Increment $i
			$i++;
		}

		// Time to build our error message to inform the customer
		// About the minimum quantity per order.
		if( is_array( $bad_products) && count( $bad_products ) > 1 ) {
			// Lets begin building our message
			$message = '<strong>A minimum quantity per product has not been met.</strong><br />';
			foreach( $bad_products as $bad_product ) {
				// Append to the current message
				$message .= get_the_title( $bad_product['id'] ) .' requires a minimum quantity of '
						 . $bad_product['min_req'] 
						 .'. You currently have: '. $bad_product['in_cart'] .'.<br />';
			}
			wc_add_notice( $message, 'error' );
		}
	}
}

/**
 * Set minimum quantity per product & monitor cart/checkout changes. 
 * @link https://www.sitepoint.com/minimum-checkout-requirements-in-woocommerce/
 * @link https://www.businessbloomer.com/woocommerce-define-add-cart-min-max-incremental-quantities/
 * @link https://www.businessbloomer.com/woocommerce-set-min-purchase-amount-for-specific-product/
 *
 */
  
add_filter( 'woocommerce_quantity_input_args', 'capweb_woocommerce_quantity_changes', 10, 2 );
   
function capweb_woocommerce_quantity_changes( $args, $product ) {
   
  // Only concerned with Hourly support product id = 1225.
  $got_discount_code = false;
  $product_id = $product->get_id();
  if ( !'1225' == $product_id ) { return; }

      $args['step'] = .25; // Increment/decrement by this value (default = 1)
      $args['min_value'] = 2; // Min quantity (default = 2 for hourly support)
    $args['input_value'] = 2; // Start from this value

  $target_coupon_code = 'ratevip21-XZ8UC9SQ';
  if ( WC()->cart->has_discount( $target_coupon_code )  ) { $got_discount_code = true; }

    if ( ! is_cart() ) {

      $args['input_value'] = 2; // Start from this value (default = 1) 
      $args['max_value'] = 10; // Max quantity (default = -1)
      $args['min_value'] = 2; // Min quantity (default = 0)
      $args['step'] = .25; // Increment/decrement by this value (default = 1)
      if ( $got_discount_code ) {
        $args['input_value'] = 1; // Start from this value (default = 1) 
        $args['min_value'] = 1; // Min quantity (default = 0)
      }

    } else {

      // Cart's "min_value" is already 0 and we don't need "input_value"
      $args['max_value'] = 10; // Max quantity (default = -1)
      $args['step'] = .25; // Increment/decrement by this value (default = 0)
      // COMMENT OUT FOLLOWING IF STEP < MIN_VALUE
      // $args['min_value'] = 2; // Min quantity (default = 0)

    }
    
    return $args;
  }
