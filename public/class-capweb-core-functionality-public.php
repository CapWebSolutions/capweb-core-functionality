<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/public
 * @author     Your Name <email@example.com>
 */
class Capweb_Core_Functionality_Public {

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
	 * @param      string    $capweb_core_functionality       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $capweb_core_functionality, $version ) {

		$this->capweb_core_functionality = $capweb_core_functionality;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->capweb_core_functionality, plugin_dir_url( __FILE__ ) . 'css/capweb-core-functionality-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'capweb-core-style', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/capweb-core-style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->capweb_core_functionality, plugin_dir_url( __FILE__ ) . 'js/capweb-core-functionality-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'capweb-core-jquery', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/capweb-core-jquery.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jstz-script', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/jstz.min.js', array(), $this->version, 'defer' );

	}

	/**
	 * Filter the post thumbnail to use default image when no featured image is set
	 *
	 * @since 1.0.0
	 * @param string $html The post thumbnail HTML
	 * @param int $post_id The post ID
	 * @param int $post_thumbnail_id The post thumbnail ID
	 * @param string $size The requested image size
	 * @param array $attr Additional attributes
	 * @return string The filtered post thumbnail HTML
	 */
	public function filter_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		// If there's already a thumbnail, return it
		if ( ! empty( $html ) ) {
			return $html;
		}

		// Check if we have a default featured image set
		$default_image_id = Capweb_Core_Functionality_Admin::get_default_featured_image_id();
		if ( ! $default_image_id ) {
			return $html;
		}

		// Get the default image HTML
		$default_image_html = wp_get_attachment_image( $default_image_id, $size, false, $attr );
		
		if ( $default_image_html ) {
			return $default_image_html;
		}

		return $html;
	}

	/**
	 * Filter the post thumbnail ID to use default image when no featured image is set
	 *
	 * @since 1.0.0
	 * @param int $thumbnail_id The post thumbnail ID
	 * @param int $post_id The post ID
	 * @return int The filtered post thumbnail ID
	 */
	public function filter_post_thumbnail_id( $thumbnail_id, $post_id ) {
		// If there's already a thumbnail ID, return it
		if ( $thumbnail_id ) {
			return $thumbnail_id;
		}

		// Check if we have a default featured image set
		$default_image_id = Capweb_Core_Functionality_Admin::get_default_featured_image_id();
		if ( $default_image_id ) {
			return $default_image_id;
		}

		return $thumbnail_id;
	}

	/**
	 * Filter the post thumbnail URL to use default image when no featured image is set
	 *
	 * @since 1.0.0
	 * @param string $url The post thumbnail URL
	 * @param int $post_id The post ID
	 * @param string $size The requested image size
	 * @return string The filtered post thumbnail URL
	 */
	public function filter_post_thumbnail_url( $url, $post_id, $size ) {
		// If there's already a URL, return it
		if ( $url ) {
			return $url;
		}

		// Check if we have a default featured image set
		$default_image_url = Capweb_Core_Functionality_Admin::get_default_featured_image_url( $size );
		if ( $default_image_url ) {
			return $default_image_url;
		}

		return $url;
	}

	/**
	 * Filter the post thumbnail alt text to use default image when no featured image is set
	 *
	 * @since 1.0.0
	 * @param string $alt The post thumbnail alt text
	 * @param int $post_id The post ID
	 * @return string The filtered post thumbnail alt text
	 */
	public function filter_post_thumbnail_alt( $alt, $post_id ) {
		// If there's already alt text, return it
		if ( $alt ) {
			return $alt;
		}

		// Check if we have a default featured image set
		$default_image_alt = Capweb_Core_Functionality_Admin::get_default_featured_image_alt();
		if ( $default_image_alt ) {
			return $default_image_alt;
		}

		return $alt;
	}

}
add_action('init', function () {
    // Replace with actual option names used by Kadence products
    // lic ktm_wc_order_rKDzJ4msgMiQw_am_xmbCHM0wA91g
    $kadence_options = [
        'stellarwp_uplink_update_status_kadence-blocks-pro',
        'stellarwp_uplink_update_status_kadence-theme-pro',
        'stellarwp_uplink_license_key_kadence-theme-pro',
        'stellarwp_uplink_license_key_kadence-blocks-pro',
        'stellarwp_uplink_license_key_kadence-shop-kit',
        'stellarwp_uplink_update_status_kadence-shop-kit',
		'kt_api_manager_kadence_build_child_defaults_data',
		'_site_transient_update_plugins'
    ];

    foreach ($kadence_options as $option) {
        if (get_option($option)) {
            delete_option($option);
            error_log("Deleted Kadence license: $option");
        }
    }
});
