<?php
/**
 * General
 *
 * This file contains any general functions for Core Functionality.
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/CapWebSolutions/capweb-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Don't Update Plugin
 * @since 1.0.0
 *
 * This prevents you being prompted to update if there's a public plugin
 * with the same name.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */
function capweb_core_functionality_hidden( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}
add_filter( 'http_request_args', 'capweb_core_functionality_hidden', 5, 2 );

// Use shortcodes in widgets
add_filter( 'widget_text', 'do_shortcode' );

// Add Genesis theme support for WooCommerce
add_theme_support( 'genesis-connect-woocommerce' );

//Remove theme and plugin editor links
add_action('admin_init','cws_hide_editor_and_tools');
function cws_hide_editor_and_tools() {
	remove_submenu_page('themes.php','theme-editor.php');
	remove_submenu_page('plugins.php','plugin-editor.php');
}

/*
 * Prevent the Jetpack publicize connections from being auto-selected,
 * so you need to manually select them if you’d like to publicize something.
 * Source: http://jetpack.me/2013/10/15/ever-accidentally-publicize-a-post-that-you-didnt/
 */
add_filter( 'publicize_checkbox_default', '__return_false' );

// Re-enable links manager. Source: http://codex.wordpress.org/Links_Manager
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

/**
 * Remove Menu Items
 * @since 1.0.0
 *
 * Remove unused menu items by adding them to the array.
 * See the commented list of menu items for reference.
 *
 */
function capweb_remove_menus () {
	global $menu;
	$restricted = array(__('Links'));
	// Example:
	//$restricted = array(__('Dashboard'), __('Posts'), __('Media'), __('Links'), __('Pages'), __('Appearance'), __('Tools'), __('Users'), __('Settings'), __('Comments'), __('Plugins'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action( 'admin_menu', 'capweb_remove_menus' );


/**
 * Reorder admin menus
 * @since 1.0.1
 *
 * Reorder menu items into preferred ordering.
 *
 */
function capweb_reorder_admin_menu( $menu_ord ) {
	// Only do this for me. 
	if ( !('capwebsolutions.com' === substr( wp_get_current_user()->user_email, -19 ) ) ) return false;

	if ( !$menu_ord ) return true;   //Required to activate the custom_menu_order filter
	
	return array(
		'index.php',                // Dashboard
		'edit.php?post_type=page',  // Pages 
		'edit.php',                 // Posts
		'plugins.php',              // Plugins
		'themes.php',               // Appearance
		'tools.php',                // Tools
		'options-general.php',      // Settings 
		);
 }

 add_filter( 'custom_menu_order', 'capweb_reorder_admin_menu', 11, 1); //First call to activate filter
 add_filter( 'menu_order', 'capweb_reorder_admin_menu', 11, 1);	 //Second call to do re-ordering


/**
 * Customize Admin Bar Items
 * @since 1.0.0
 * @link http://wp-snippets.com/addremove-wp-admin-bar-links/
 */
function capweb_admin_bar_items() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'new-link', 'new-content' );
}
add_action( 'wp_before_admin_bar_render', 'capweb_admin_bar_items' );


/**
 * Customize Menu Order
 * @since 1.0.0
 *
 * @param array $menu_ord. Current order.
 * @return array $menu_ord. New order.
 *
 */
function capweb_custom_menu_order( $menu_ord ) {
	if ( !$menu_ord ) return true;
	return array(
		'index.php', // this represents the dashboard link
		'edit.php?post_type=page', //the page tab
		'edit.php', //the posts tab
		'edit-comments.php', // the comments tab
		'upload.php', // the media manager
    );
}
//add_filter( 'custom_menu_order', 'capweb_custom_menu_order' );
//add_filter( 'menu_order', 'capweb_custom_menu_order' );

/**
 * Pretty Printing
 *
 * @author Chris Bratlien
 *
 * @param mixed
 * @return null
 */
function capweb_pp( $obj, $label = '' ) {

	$data = json_encode(print_r($obj,true));
    ?>
    <style type="text/css">
      #bsdLogger {
      position: absolute;
      top: 30px;
      right: 0px;
      border-left: 4px solid #bbb;
      padding: 6px;
      background: white;
      color: #444;
      z-index: 999;
      font-size: 1.25em;
      width: 400px;
      height: 800px;
      overflow: scroll;
      }
    </style>
    <script type="text/javascript">
      var doStuff = function(){
        var obj = <?php echo $data; ?>;
        var logger = document.getElementById('bsdLogger');
        if (!logger) {
          logger = document.createElement('div');
          logger.id = 'bsdLogger';
          document.body.appendChild(logger);
        }
        ////console.log(obj);
        var pre = document.createElement('pre');
        var h2 = document.createElement('h2');
        pre.innerHTML = obj;

        h2.innerHTML = '<?php echo addslashes($label); ?>';
        logger.appendChild(h2);
        logger.appendChild(pre);
      };
      window.addEventListener ("DOMContentLoaded", doStuff, false);

    </script>
    <?php
}

/**
 * Disable WPSEO Nag on Dev Server
 *
 */
function capweb_disable_wpseo_nag( $options ) {
	if( strpos( site_url(), 'localhost' ) || strpos( site_url() ,'master-wp' ) )
		$options['ignore_blog_public_warning'] = 'ignore';
	return $options;
}
add_filter( 'option_wpseo', 'capweb_disable_wpseo_nag' );

// Disable WPSEO columns on edit screen
add_filter( 'wpseo_use_page_analysis', '__return_false' );

//* Automatically link Twitter names to Twitter URL
// Ref: https://www.nutsandboltsmedia.com/how-to-create-a-custom-functionality-plugin-and-why-you-need-one/
function twtreplace($content) {
	$twtreplace = preg_replace('/([^a-zA-Z0-9-_&])@([0-9a-zA-Z_]+)/',"$1<a href=\"http://twitter.com/$2\" target=\"_blank\" rel=\"nofollow\">@$2</a>",$content);
	return $twtreplace;
}
add_filter('the_content', 'twtreplace');
add_filter('comment_text', 'twtreplace');
//
// Force Stupid IE to NOT use compatibility mode
// Ref: https://www.nutsandboltsmedia.com/how-to-create-a-custom-functionality-plugin-and-why-you-need-one/
add_filter( 'wp_headers', 'wsm_keep_ie_modern' );
function wsm_keep_ie_modern( $headers ) {
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false ) ) {
                $headers['X-UA-Compatible'] = 'IE=edge,chrome=1';
        }
        return $headers;
}
//
//* Customize search form input box text
//* Ref: https://my.studiopress.com/snippets/search-form/
add_filter( 'genesis_search_text', 'sp_search_text' );
function sp_search_text( $text ) {
	// return esc_attr( 'Search my blog...' );
	return esc_attr( 'Seach ' . get_bloginfo( $show = '', 'display' ));
	get_permalink();
}

// Custom avatar_size
add_filter( 'avatar_defaults', 'add_custom_gravatar' );
function add_custom_gravatar( $avatar_defaults ) {
     $myavatar = get_stylesheet_directory_uri() . '/images/custom-gravatar.jpg';
     $avatar_defaults[$myavatar] = "Custom Gravatar";
     return $avatar_defaults;
}

add_filter( 'comment_form_defaults', 'cd_pre_comment_text' );
/**
 * Change the text output that appears before the comment form
 * Note: Logged in user will not see this text.
 *
 * @author Carrie Dils <http://www.carriedils.com>
 * @uses comment_notes_before <http://codex.wordpress.org/Function_Reference/comment_form>
 *  ref: http://www.carriedils.com/customize-wordpress-comments/
 */
function cd_pre_comment_text( $arg ) {
  $arg['comment_notes_before'] = "Want to see your pic by your comment? Get a free custom avatar at <a href='http://www.gravatar.com' target='_blank' >Gravatar</a>.";
  return $arg;
}

// ref: http://www.carriedils.com/customize-wordpress-comments/
add_action( 'pre_ping', 'disable_self_ping' );
function disable_self_ping( &$links ) {
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);
}

add_shortcode('AffiliateDisclaimer', 'capweb_affiliate_disclaimer');
/**
 * Create shortcode and set content for affiliate disclosure.
 *
 * @author Matt Ryan <http://www.mattryan.co>
 * @since 1.0.0
 */
function capweb_affiliate_disclaimer() {
	    return '<em><small>Disclaimer:  Some of the off-site links referenced on this site are what is referred to as an affiliate link. If you choose to purchase or use the product or service through that link, the post author will get a small referral fee from the service or product provider. Your price is the same whether or not you use the affiliate link. </small></em>';
}

//* Change the footer text
// add_filter('genesis_footer_creds_text', 'cws_sp_footer_creds_filter');
function cws_sp_footer_creds_filter( $creds ) {
  $creds = '[footer_copyright first="2013" Before="Copyright "] &middot; Cap Web Solutions LLC &middot; Built on the <a href="/go/genesis" title="Genesis Framework">Genesis Framework</a> using a \'customized by Cap Web\' <a href="/go/digital-pro" title="Digital Pro Genesis Child Theme">Digital Pro theme</a>.<br>Powered by <a href="http://wordpress.org/">WordPress</a> &middot; <a href="/privacy-policy/">Privacy Policy</a> &middot; <a href="/sitemap/">Sitemap</a><br><a target="_blank" href="https://shareasale.com/r.cfm?b=541358&amp;u=993211&amp;m=41388&amp;urllink=&amp;afftrack="><img src="/wp-content/uploads/2019/01/hosted_by_wp_engine2.png" border="0" alt="Hosted by WP Engine" /></a>';
  return $creds;
}


//* Add Archive Settings option to Portolio CPT
add_post_type_support( 'portfolio', 'genesis-cpt-archives-settings' );

//* Define a custom image size for images on Portfolio archives
add_image_size( 'portfolio', 500, 300, true );




add_filter( 'genesis_attr_entry-header', 'capweb_add_css_attr' );
add_filter( 'genesis_attr_entry-content', 'capweb_add_css_attr' );
/**
 * Add Custom Class
 *
 * Add custom layout width class to specific pages.
 *
 * @link https://capwebsolutions.com
 *
 * @package WordPress
 * @since 1.0.0
 * @license GNU General Public License 2.0+
 */
function capweb_add_css_attr( $attributes ) {
    // add original plus extra CSS classes
    if ( is_page( 'lets-work-together' ) ) $attributes['class'] .= ' custom-full-width-class';
    
    // return the attributes
    return $attributes;
}



// Gravity Forms Specific Stuff =======================================

/**
 * Fix Gravity Form Tabindex Conflicts
 * http://gravitywiz.com/fix-gravity-form-tabindex-conflicts/
 */
add_filter( 'gform_tabindex', 'gform_tabindexer', 10, 2 );
function gform_tabindexer( $tab_index, $form = false ) {
    $starting_index = 1000; // if you need a higher tabindex, update this number
    if( $form )
        add_filter( 'gform_tabindex_' . $form['id'], 'gform_tabindexer' );
    return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
}

// Enable Gravity Forms Visibility Setting
// Ref: https://www.gravityhelp.com/gravity-forms-v1-9-placeholders/
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

// Move submit button on form & add a little following comment.
//add_filter( 'gform_submit_button_10', 'add_paragraph_below_submit', 10, 2 );
function add_paragraph_below_submit( $button, $form ) {
    return $button .= "<small>By joining the Cap Web Nuggets newsletter, you agree to a basic  <a href=\"privacy-policy/\">Privacy Policy</a>. Got questions? <a href=\"contact/\">Get in touch.</a>.</small>";
}

// Tweak Gravity Forms website field validator to insert protocol if missing (assumes http)
// Ref: https://www.itsupportguides.com/knowledge-base/gravity-forms/gravity-forms-how-to-automatically-add-http-to-submitted-website-field-values-before-validation/
add_filter( 'gform_pre_render', 'itsg_check_website_field_value' );
add_filter( 'gform_pre_validation', 'itsg_check_website_field_value' );
function itsg_check_website_field_value( $form ) {
    foreach ( $form['fields'] as &$field ) {  // for all form fields
        if ( 'website' == $field['type'] || ( isset( $field['inputType'] ) && 'website' == $field['inputType']) ) {  // select the fields that are 'website' type
            $value = RGFormsModel::get_field_value($field);  // get the value of the field
            if (! empty($value) ) { // if value not empty
                $field_id = $field['id'];  // get the field id
                if (! preg_match("~^(?:f|ht)tps?://~i", $value) ) {  // if value does not start with ftp:// http:// or https://
                    $value = "http://" . $value;  // add http:// to start of value
                }

                $_POST['input_' . $field_id] = $value; // update post with new value
            }
        }
    }
    return $form;
}


// End of Gravity Forms Specific Stuff ================================

// Custom 404 Pages ===================================================

// Call the sitemap generator
// Source: http://www.carriedils.com/custom-404-wordpress-html-sitemap/
// remove_action( 'genesis_loop', 'genesis_404' ); // Remove the default Genesis 404 content
add_action( 'genesis_loop', 'cd_custom_404' ); // Add function for custom 404 content
function cd_custom_404() {
    if ( is_404() ) {
        get_template_part('/partials/sitemap'); // Plop in our customized sitemap code
    }
}


// Remove emoji from loading on every page. 
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );


/* Are we in the tree of the given page ID? */
// $pid = The ID of the page we're looking for pages underneath
function capweb_is_tree($pid) {
  // load details about this page
	global $post;
  if( is_page() && ( $post->post_parent == $pid || is_page( $pid ) ) ) {
    // we're at the page or at a sub page
    return true;
  } else {
    // we're elsewhere
    return false;
  }
};

/**
 * Display Posts, open links in a new window
 * @see https://displayposts.com/2019/02/20/open-links-in-a-new-window/
 *
 */
function be_dps_links_new_window( $output ) {
	$output = str_replace( 'href="', 'target="_blank" href="', $output );
	return $output;
}
add_filter( 'display_posts_shortcode_output', 'be_dps_links_new_window' );
