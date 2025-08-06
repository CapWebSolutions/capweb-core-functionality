<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/admin
 * @author     Your Name <email@example.com>
 */
class Capweb_Core_Functionality_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $capweb_core_functionality    The ID of this plugin.
	 */
	private $capweb_core_functionality;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $capweb_core_functionality       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $capweb_core_functionality, $version ) {

		$this->capweb_core_functionality = $capweb_core_functionality;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Capweb_Core_Functionality_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Capweb_Core_Functionality_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->capweb_core_functionality, plugin_dir_url( __FILE__ ) . 'css/capweb-core-functionality-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'capweb-core-style', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/capweb-core-style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Capweb_Core_Functionality_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Capweb_Core_Functionality_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->capweb_core_functionality, plugin_dir_url( __FILE__ ) . 'js/capweb-core-functionality-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'capweb-core-jquery', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/capweb-core-jquery.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jstz-script', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/jstz.min.js', array(), $this->version, 'defer' );

		// Enqueue media library scripts for default featured image settings
		wp_enqueue_media();

	}

	/**
	 * Automatically set the image Title, Alt-Text, Caption & Description upon upload
	 *
	 * @param [type] $post_ID
	 * @return void
	 */
	function capweb_set_image_meta_upon_image_upload( $post_ID ) {
	
		// Check if uploaded file is an image, else do nothing
	
		if ( wp_attachment_is_image( $post_ID ) ) {
	
			$my_image_title = get_post( $post_ID )->post_title;
	
			// Sanitize the title:  remove hyphens, underscores & extra spaces:
			$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );
	
			// Sanitize the title:  capitalize first letter of every word (other letters lower case):
			$my_image_title = ucwords( strtolower( $my_image_title ) );
	
			// Create an array with the image meta (Title, Caption, Description) to be updated
			// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
			$my_image_meta = array(
				'ID'		=> $post_ID,			// Specify the image (ID) to be updated
				'post_title'	=> $my_image_title,		// Set image Title to sanitized title
				//'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
				//'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
			);
	
			// Set the image Alt-Text
			update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );
	
			// Set the image meta (e.g. Title, Excerpt, Content)
			wp_update_post( $my_image_meta );
	
		} 
	}
	function capweb_custom_sanitize_file_name($filename) {
		// Convert the filename to lowercase
		$filename = strtolower($filename);
	
		// Replace spaces with underscores
		$filename = str_replace(' ', '_', $filename);
	
		return $filename;
	}

	//Remove theme and plugin editor links

	function capweb_hide_editor_and_tools() {
		remove_submenu_page('themes.php','theme-editor.php');
		remove_submenu_page('plugins.php','plugin-editor.php');
	}
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
			'edit.php?post_type=portfolio', //portfolio posts
			'plugins.php',              // Plugins
			'themes.php',               // Appearance
			'tools.php',                // Tools
			'options-general.php',      // Settings 
			);
	}

	/**
	 * Display Posts, open links in a new window
	 * @see https://displayposts.com/2019/02/20/open-links-in-a-new-window/
	 *
	 */
	function be_dps_links_new_window( $output ) {
		$output = str_replace( 'href="', 'target="_blank" href="', $output );
		return $output;
	}

	/**
	 * Create shortcode and set content for affiliate disclosure.
	 *
	 * @author Matt Ryan <http://www.mattryan.co>
	 * @since 1.0.0
	 */
	function capweb_add_affiliate_disclaimer_shortcode(){
		add_shortcode('AffiliateDisclaimer', array( $this, 'capweb_affiliate_disclaimer' ));
	}

	/**
	 * Add default featured image settings page to Tools menu
	 *
	 * @since 1.0.0
	 */
	public function add_default_featured_image_settings_page() {
		add_management_page(
			'Default Featured Image Settings',
			'Default Featured Image',
			'manage_options',
			'default-featured-image-settings',
			array( $this, 'render_default_featured_image_settings_page' )
		);
	}

	/**
	 * Render the default featured image settings page
	 *
	 * @since 1.0.0
	 */
	public function render_default_featured_image_settings_page() {
		// Handle form submission
		if ( isset( $_POST['submit'] ) && wp_verify_nonce( $_POST['default_featured_image_nonce'], 'default_featured_image_settings' ) ) {
			$this->save_default_featured_image_settings();
		}

		$default_image_id = get_option( 'capweb_default_featured_image_id', '' );
		$default_image_url = '';
		$default_image_alt = '';

		if ( $default_image_id ) {
			$default_image_url = wp_get_attachment_image_url( $default_image_id, 'medium' );
			$default_image_alt = get_post_meta( $default_image_id, '_wp_attachment_image_alt', true );
		}

		?>
		<div class="wrap">
			<h1>Default Featured Image Settings</h1>
			<p>Set a default featured image that will be displayed when blog posts don't have a featured image assigned.</p>

			<form method="post" action="">
				<?php wp_nonce_field( 'default_featured_image_settings', 'default_featured_image_nonce' ); ?>
				
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="default_featured_image">Default Featured Image</label>
						</th>
						<td>
							<div id="default-featured-image-container">
								<?php if ( $default_image_url ) : ?>
									<img src="<?php echo esc_url( $default_image_url ); ?>" alt="<?php echo esc_attr( $default_image_alt ); ?>" style="max-width: 300px; height: auto; margin-bottom: 10px;" />
								<?php endif; ?>
							</div>
							
							<input type="hidden" id="default_featured_image_id" name="default_featured_image_id" value="<?php echo esc_attr( $default_image_id ); ?>" />
							
							<button type="button" id="select-default-featured-image" class="button">
								<?php echo $default_image_id ? 'Change Image' : 'Select Image'; ?>
							</button>
							
							<?php if ( $default_image_id ) : ?>
								<button type="button" id="remove-default-featured-image" class="button button-secondary">
									Remove Image
								</button>
							<?php endif; ?>
							
							<p class="description">
								Select an image from the media library to use as the default featured image for posts without a featured image.
							</p>
						</td>
					</tr>
				</table>

				<?php submit_button( 'Save Settings' ); ?>
			</form>
		</div>

		<script type="text/javascript">
		jQuery(document).ready(function($) {
			var mediaUploader;
			
			$('#select-default-featured-image').click(function(e) {
				e.preventDefault();
				
				if (mediaUploader) {
					mediaUploader.open();
					return;
				}
				
				mediaUploader = wp.media({
					title: 'Select Default Featured Image',
					button: {
						text: 'Use this image'
					},
					multiple: false
				});
				
				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();
					$('#default_featured_image_id').val(attachment.id);
					$('#default-featured-image-container').html('<img src="' + attachment.sizes.medium.url + '" alt="' + attachment.alt + '" style="max-width: 300px; height: auto; margin-bottom: 10px;" />');
					$('#select-default-featured-image').text('Change Image');
					$('#remove-default-featured-image').show();
				});
				
				mediaUploader.open();
			});
			
			$('#remove-default-featured-image').click(function(e) {
				e.preventDefault();
				$('#default_featured_image_id').val('');
				$('#default-featured-image-container').empty();
				$('#select-default-featured-image').text('Select Image');
				$(this).hide();
			});
		});
		</script>
		<?php
	}

	/**
	 * Save default featured image settings
	 *
	 * @since 1.0.0
	 */
	private function save_default_featured_image_settings() {
		if ( isset( $_POST['default_featured_image_id'] ) ) {
			$image_id = intval( $_POST['default_featured_image_id'] );
			
			if ( $image_id > 0 && wp_attachment_is_image( $image_id ) ) {
				update_option( 'capweb_default_featured_image_id', $image_id );
				add_action( 'admin_notices', array( $this, 'default_featured_image_settings_saved_notice' ) );
			} elseif ( $image_id === 0 ) {
				delete_option( 'capweb_default_featured_image_id' );
				add_action( 'admin_notices', array( $this, 'default_featured_image_settings_saved_notice' ) );
			}
		}
	}

	/**
	 * Display success notice when settings are saved
	 *
	 * @since 1.0.0
	 */
	public function default_featured_image_settings_saved_notice() {
		?>
		<div class="notice notice-success is-dismissible">
			<p>Default featured image settings saved successfully.</p>
		</div>
		<?php
	}

	/**
	 * Get the default featured image ID
	 *
	 * @since 1.0.0
	 * @return int|false The default featured image ID or false if not set
	 */
	public static function get_default_featured_image_id() {
		return get_option( 'capweb_default_featured_image_id', false );
	}

	/**
	 * Get the default featured image URL
	 *
	 * @since 1.0.0
	 * @param string $size The image size to retrieve
	 * @return string|false The default featured image URL or false if not set
	 */
	public static function get_default_featured_image_url( $size = 'medium' ) {
		$image_id = self::get_default_featured_image_id();
		if ( $image_id ) {
			return wp_get_attachment_image_url( $image_id, $size );
		}
		return false;
	}

	/**
	 * Get the default featured image alt text
	 *
	 * @since 1.0.0
	 * @return string|false The default featured image alt text or false if not set
	 */
	public static function get_default_featured_image_alt() {
		$image_id = self::get_default_featured_image_id();
		if ( $image_id ) {
			return get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		}
		return false;
	}

	/**
	 * Don't Update Plugin
	 * @since 5.0.0
	 *
	 * This prevents you being prompted to update if there's a public plugin
	 * with the same name.
	 *
	 * @param array $r, request arguments
	 * @param string $url, request url
	 * @return array request arguments
	 */
	public function capweb_core_functionality_hidden( $r, $url ) {
		if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) ) {
			return $r; // Not a plugin update request. Bail immediately.
		}
		$plugins = unserialize( $r['body']['plugins'] );
		unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
		unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
		$r['body']['plugins'] = serialize( $plugins );
		return $r;
	}

	/**
	 * Remove Menu Items
	 * @since 5.0.0
	 *
	 * Remove unused menu items by adding them to the array.
	 *
	 */
	public function capweb_remove_menus() {
		global $menu;
		$restricted = array( __( 'Links' ) );
		end( $menu );
		while ( prev( $menu ) ) {
			$value = explode( ' ', $menu[ key( $menu ) ][0] );
			if ( in_array( $value[0] != null ? $value[0] : '', $restricted ) ) {
				unset( $menu[ key( $menu ) ] );
			}
		}
	}

	/**
	 * Customize Admin Bar Items
	 * @since 5.0.0
	 */
	public function capweb_admin_bar_items() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'new-link', 'new-content' );
	}

	/**
	 * Pretty Printing
	 *
	 * @param mixed $obj
	 * @param string $label
	 * @return void
	 */
	public function capweb_pp( $obj, $label = '' ) {
		$data = json_encode( print_r( $obj, true ) );
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
				var pre = document.createElement('pre');
				var h2 = document.createElement('h2');
				pre.innerHTML = obj;
				h2.innerHTML = '<?php echo addslashes( $label ); ?>';
				logger.appendChild(h2);
				logger.appendChild(pre);
			};
			window.addEventListener ("DOMContentLoaded", doStuff, false);
		</script>
		<?php
	}

	/**
	 * Customize search form input box text
	 *
	 * @param string $text
	 * @return string
	 */
	public function sp_search_text( $text ) {
		return esc_attr( 'Search ' . get_bloginfo( $show = '', 'display' ) );
	}

	/**
	 * Create shortcode and set content for affiliate disclosure.
	 *
	 * @return string
	 */
	public function capweb_affiliate_disclaimer() {
		return '<em><small>Disclaimer:  Some of the off-site links referenced on this site are what is referred to as an affiliate link. If you choose to purchase or use the product or service through that link, the post author will get a small referral fee from the service or product provider. Your price is the same whether or not you use the affiliate link. </small></em>';
	}

	/**
	 * Add Archive Settings option to Portfolio CPT
	 */
	public function add_portfolio_archive_support() {
		add_post_type_support( 'portfolio', 'genesis-cpt-archives-settings' );
	}

	/**
	 * Define a custom image size for images on Portfolio archives
	 */
	public function add_portfolio_image_size() {
		add_image_size( 'portfolio', 500, 300, true );
	}

	/**
	 * Add Custom Class
	 *
	 * Add custom layout width class to specific pages.
	 *
	 * @param array $attributes
	 * @return array
	 */
	public function capweb_add_css_attr( $attributes ) {
		// add original plus extra CSS classes
		if ( is_page( 'lets-work-together' ) ) {
			$attributes['class'] .= ' custom-full-width-class';
		}
		// return the attributes
		return $attributes;
	}

	/**
	 * Fix Gravity Form Tabindex Conflicts
	 *
	 * @param int $tab_index
	 * @param array $form
	 * @return int
	 */
	public function gform_tabindexer( $tab_index, $form = false ) {
		$starting_index = 1000; // if you need a higher tabindex, update this number
		if ( $form ) {
			add_filter( 'gform_tabindex_' . $form['id'], 'gform_tabindexer' );
		}
		return GFCommon::$tab_index >= $starting_index ? GFCommon::$tab_index : $starting_index;
	}

	/**
	 * Move submit button on form & add a little following comment.
	 *
	 * @param string $button
	 * @param array $form
	 * @return string
	 */
	public function add_paragraph_below_submit( $button, $form ) {
		return $button .= "<small>By joining the Cap Web Nuggets newsletter, you agree to a basic  <a href=\"privacy-policy/\">Privacy Policy</a>. Got questions? <a href=\"contact/\">Get in touch.</a>.</small>";
	}

	/**
	 * Tweak Gravity Forms website field validator to insert protocol if missing (assumes http)
	 *
	 * @param array $form
	 * @return array
	 */
	public function itsg_check_website_field_value( $form ) {
		foreach ( $form['fields'] as &$field ) {  // for all form fields
			if ( 'website' == $field['type'] || ( isset( $field['inputType'] ) && 'website' == $field['inputType'] ) ) {  // select the fields that are 'website' type
				$value = RGFormsModel::get_field_value( $field );  // get the value of the field
				if ( ! empty( $value ) ) { // if value not empty
					$field_id = $field['id'];  // get the field id
					if ( ! preg_match( "~^(?:f|ht)tps?://~i", $value ) ) {  // if value does not start with ftp:// http:// or https://
						$value = 'http://' . $value;  // add http:// to start of value
					}
					$_POST['input_' . $field_id] = $value; // update post with new value
				}
			}
		}
		return $form;
	}

	/**
	 * Are we in the tree of the given page ID?
	 *
	 * @param int $pid The ID of the page we're looking for pages underneath
	 * @return bool
	 */
	public function capweb_is_tree( $pid ) {
		// load details about this page
		global $post;
		if ( is_page() && ( $post->post_parent == $pid || is_page( $pid ) ) ) {
			// we're at the page or at a sub page
			return true;
		} else {
			// we're elsewhere
			return false;
		}
	}

	/**
	 * Exclude backup of thumbnails from backups.
	 *
	 * @param bool $filter
	 * @param string $file
	 * @return bool
	 */
	public function capweb_updraftplus_exclude_file( $filter, $file ) {
		return preg_match( "/-\d+x\d+\.(?:png|jpe?g|bmp|tiff|gif|webp|avif)$/", $file ) ? true : $filter;
	}

	/**
	 * Turn off admin bar for updraftplus
	 */
	public function updraftplus_remove_toolbar_menu() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'updraft_admin_node' );
	}

	/**
	 * Deregister Gravity Forms scripts and styles on specific pages
	 */
	public function capweb_deregister_scripts() {
		// Change this conditional to target whatever page or form you need.
		if ( is_front_page() ) {
			// These are the CSS stylesheets.
			wp_deregister_style( 'gforms_reset_css' );
			wp_deregister_style( 'gforms_formsmain_css' );
			wp_deregister_style( 'gforms_ready_class_css' );
			wp_deregister_style( 'gforms_browsers_css' );
		}
	}

	/**
	 * Adds a custom sharing button to Scriptless Social Sharing.
	 *
	 * @param array $buttons
	 * @return array
	 */
	public function capweb_scriptless_add_threads_button( $buttons ) {
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

	/**
	 * Custom login logo and styling
	 */
	public function capweb_login_logo() {
		?>
		<style type="text/css">
			/* CHANGE THESE COLORS TO MATCH YOUR BRAND */
			:root {
				--brand-color: #64748B;
				--brand-color-hover: #334155;
				--background-color: #f8fafc;
			}
			/* Body */
			body.login {
				background: var(--background-color);
				display: flex;
			}

			/* Logo */
			#login h1 a, .login h1 a {
				background-image: url(https://capwebsolutions.com/wp-content/uploads/2016/03/TRANS-CapWebSolutions_Logo_NoCap-504x152.png);
				height: 80px; /* Adjust height if needed */
				width: 100%;
				max-width: 263px; /* Adjust height if needed */
				background-size: contain;
				background-repeat: no-repeat;
			}

			/* Login wrapper */
			body.login div#login {
				padding: clamp(2rem, 0.714rem + 1.429vw, 3rem);
				margin-block: auto;
				margin-inline: auto;
				border-radius: 1.5em;
				box-shadow:
					0px 2.8px 2.2px rgba(0, 0, 0, 0.006),
					0px 6.7px 5.3px rgba(0, 0, 0, 0.008),
					0px 12.5px 10px rgba(0, 0, 0, 0.01),
					0px 22.3px 17.9px rgba(0, 0, 0, 0.012),
					0px 41.8px 33.4px rgba(0, 0, 0, 0.014),
					0px 100px 80px rgba(0, 0, 0, 0.02);
				background-color: white;
			}

			/* Login form */
			body.login div#login form {
				border: none;
				box-shadow: none;
				padding: 1rem 1rem 2rem;
			}

			/* Form inputs focus color */
			body.login input:focus {
				outline: 2px solid var(--brand-color);
				border: 0px;
			}

			/* Submit button */
			body.login div#login #wp-submit {
				background-color: var(--brand-color);
				border: 0px;
			}

			/* Submit button on hover */
			body.login div#login #wp-submit:hover {
				background-color: var(--brand-color-hover);
			}

			/* "Lost your passworld" link hover color */
			body.login div#login p#nav a:hover, body.login div#login p#backtoblog a:hover {
				color: var(--brand-color-hover);
			}

			/* "Lost your password" positioning */
			body.login div#login p#nav, body.login div#login p#backtoblog {
				display: flex;
				justify-content: center;
				margin-top: .5rem;
			}

			body.login .message {
				border-left: 4px solid var(--brand-color-hover);
			}

			.custom-support-link {
				position: fixed;
				bottom: 12px;
				left: 50%;
				transform: translatex(-50%);
				color: var(--surface-70);
				text-decoration: none;
			}

			.custom-support-link:hover {
				color: var(--surface-90);
			}

			body.login div#backtoblog {
				display: none;
			}
		</style>
		<?php
	}

	/**
	 * Change login logo URL
	 *
	 * @return string
	 */
	public function capweb_login_logo_url() {
		return home_url();
	}

	/**
	 * Custom login redirect
	 *
	 * @param string $redirect_to
	 * @param string $request
	 * @param object $user
	 * @return string
	 */
	public function custom_login_redirect( $redirect_to, $request, $user ) {
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			if ( in_array( 'administrator', $user->roles ) ) {
				return $redirect_to;
			} else {
				return home_url();
			}
		} else {
			return $redirect_to;
		}
	}

	/**
	 * Hide admin bar from non-admins
	 */
	public function hide_admin_bar_from_non_admins() {
		if ( ! current_user_can( 'administrator' ) ) {
			show_admin_bar( false );
		}
	}

	/**
	 * Redirect non-admin users
	 */
	public function redirect_non_admin_users() {
		if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}

}
