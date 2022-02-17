
<?php
/**
 * Wootweaks - Emails
 *
 * This file contains any custom functions related to WooCommerce emails.
 *
 * @package      Core_Functionality
 * @since        3.0.0
 * @link         https://github.com/CapWebSolutions/capweb-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */



/**
 * Add a BCC email address to ALL WC emails
 */
add_filter( 'woocommerce_email_headers', 'cws_custom_headers_filter_function', 10, 4);

function cws_custom_headers_filter_function( $headers, $email_id, $email_for_obj, $email_class ) {
    // $headers = array();
    $headers .= 'Bcc: info@capwebsolutions.com' . "\r\n";
    // $headers[] = 'Content-Type: text/html';
    return $headers;
} 

/**
 * Add order note programmatically to all new Care Plan Orders
 * ref: https://woomultistore.com/add-message-or-notes-to-order-emails-woocommerce/
 */
add_action( 'woocommerce_email_order_meta', 'capweb_woo_add_order_notes_to_email' );
function capweb_woo_add_order_notes_to_email( $email ) {
	global $woocommerce, $post;

	if ( $email->id == 'new_order' ) {

		$args = array(
			'post_id' 	=> $post->ID,
			'approve' 	=> 'approve',
			'type' 		=> 'order_note'
		);
		$notes = get_comments( $args );
		
		echo '<hr style="height:5px;border-width:0;color:#CCAA97;background-color:#CCAA97">';
		echo '<h2>' . __( 'NEXT STEPS', 'woocommerce' ) . '</h2>';
		echo '<ul class="order_notes">';
		if ( $notes ) {
			foreach( $notes as $note ) {
				$note_classes = get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ? array( 'customer-note', 'note' ) : array( 'note' );
				?>
				<li rel="<?php echo absint( $note->comment_ID ) ; ?>" class="<?php echo implode( ' ', $note_classes ); ?>">
					<div class="note_content">
						(<?php printf( __( 'added %s ago', 'woocommerce' ), human_time_diff( strtotime( $note->comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?>) <?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
					</div>
				</li>
				<?php
			}
		} else {
			echo '<li class="order-note-highlight">' . __( 'If not already provided, please send us your website details so that we can start to work on your website. Visit <a href="https://capwebsolutions.com/contact/provide-website-care-plan-credentials/">Provide Website Credentials</a>.', 'woocommerce' ) . '</li>';
		}
		echo '</ul>';
		echo '<hr style="height:5px;border-width:0;color:#CCAA97;background-color:#CCAA97;margin-bottom: 5px;">';
	}
}



/**
 * @snippet       Add Cc: or Bcc: Recipient @ WooCommerce Completed Order Email
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 4.6
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
// add_filter( 'woocommerce_email_headers', 'cap_web_bcws_order_completed_email_add_cc_bcc', 9999, 3 );
 
function cap_web_bcws_order_completed_email_add_cc_bcc( $headers, $email_id, $order ) {
	//*    if ( 'customer_completed_order' == $email_id || 'customer_completed_renewal_order' == $email_id ) {  
			// $headers .= "Cc: Name <your@email.com>" . "\r\n"; // del if not needed
			$headers .= "Bcc: Matt <matt+order@capwebsolutions.com>" . "\r\n"; // del if not needed
	//    }
		return $headers;
	}
	
	
	/**
	 * @snippet       Add Text to Customer Processing Order Email
	 * @author        Rodolfo Melogli
	 * @testedwith    Woo 4.6
	 */
	  
	add_action( 'woocommerce_email_before_order_table', 'bcws_add_content_specific_email', 20, 4 );
	  
	function bcws_add_content_specific_email( $order, $sent_to_admin, $plain_text, $email ) {
	  if ( $email->id == 'customer_completed_renewal_order' ) {
		  if ( get_post_meta( $order->get_id(), '_target_website', true ) ) 
		  echo '<p>WPcare Support on Website: ' . get_post_meta( $order->get_id(), '_target_website', true ) . '</p>';
	  }
	}
	
/**
 * @snippet       Save & Display Custom Field @ WooCommerce Order
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
add_action( 'woocommerce_email_after_order_table', 'capweb_bcws_show_new_checkout_field_emails', 20, 4 );
function capweb_bcws_show_new_checkout_field_emails( $order, $sent_to_admin, $plain_text, $email ) {
	if ( get_post_meta( $order->get_id(), '_target_website', true ) ) 
	  echo '<p>WPcare Support on Website: ' . get_post_meta( $order->get_id(), '_target_website', true ) . '</p>';
}
