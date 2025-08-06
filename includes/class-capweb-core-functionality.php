<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Capweb_Core_Functionality
 * @subpackage Capweb_Core_Functionality/includes
 * @author     Your Name <email@example.com>
 */
class Capweb_Core_Functionality {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Capweb_Core_Functionality_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $capweb_core_functionality    The string used to uniquely identify this plugin.
	 */
	protected $capweb_core_functionality;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'CAPWEB_CORE_FUNCTIONALITY_VERSION' ) ) {
			$this->version = CAPWEB_CORE_FUNCTIONALITY_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->capweb_core_functionality = 'capweb-core-functionality';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		// Initialize additional functionality classes
		new Capweb_Core_Functionality_Post_Types();
		new Capweb_Core_Functionality_Taxonomies();
		new Capweb_Core_Functionality_Editor_Styles();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Capweb_Core_Functionality_Loader. Orchestrates the hooks of the plugin.
	 * - Capweb_Core_Functionality_i18n. Defines internationalization functionality.
	 * - Capweb_Core_Functionality_Admin. Defines all hooks for the admin area.
	 * - Capweb_Core_Functionality_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-capweb-core-functionality-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-capweb-core-functionality-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-capweb-core-functionality-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-capweb-core-functionality-public.php';

		/**
		 * The class responsible for post types functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-capweb-core-functionality-post-types.php';

		/**
		 * The class responsible for taxonomies functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-capweb-core-functionality-taxonomies.php';

		/**
		 * The class responsible for editor styles functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-capweb-core-functionality-editor-styles.php';

		$this->loader = new Capweb_Core_Functionality_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Capweb_Core_Functionality_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Capweb_Core_Functionality_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Capweb_Core_Functionality_Admin( $this->get_capweb_core_functionality(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Automatic tweaks to uploaded media files. 
		$this->loader->add_action( 'add_attachment', $plugin_admin, 'capweb_set_image_meta_upon_image_upload' );
		$this->loader->add_action( 'sanitize_file_name', $plugin_admin, 'capweb_custom_sanitize_file_name' );

		// Turn off the troublesome admin editors.
		$this->loader->add_action( 'admin_init', $plugin_admin, 'capweb_hide_editor_and_tools' );

		// Reorder admin menus - First call to activate filter; Second call to do re-ordering.
		$this->loader->add_action( 'custom_menu_order', $plugin_admin, 'capweb_reorder_admin_menu' );
		$this->loader->add_action( 'menu_order', $plugin_admin, 'capweb_reorder_admin_menu' );

		$this->loader->add_filter( 'display_posts_shortcode_output', $plugin_admin, 'be_dps_links_new_window' );

		// Set up custom shortcode
		$this->loader->add_action( 'admin_init', $plugin_admin, 'capweb_add_affiliate_disclaimer_shortcode' );

		// Add default featured image settings page
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_default_featured_image_settings_page' );

		// Add admin functionality hooks
		$this->loader->add_filter( 'http_request_args', $plugin_admin, 'capweb_core_functionality_hidden', 5, 2 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'capweb_remove_menus' );
		$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'capweb_admin_bar_items' );
		$this->loader->add_action( 'init', $plugin_admin, 'add_portfolio_image_size' );
		$this->loader->add_action( 'init', $plugin_admin, 'capweb_add_affiliate_disclaimer_shortcode' );
		$this->loader->add_filter( 'updraftplus_exclude_file', $plugin_admin, 'capweb_updraftplus_exclude_file', 10, 2 );
		$this->loader->add_action( 'wp_before_admin_bar_render', $plugin_admin, 'updraftplus_remove_toolbar_menu', 999 );

		// Custom login functionality
		$this->loader->add_action( 'login_head', $plugin_admin, 'capweb_login_logo' );
		$this->loader->add_filter( 'login_headerurl', $plugin_admin, 'capweb_login_logo_url' );
		$this->loader->add_filter( 'login_redirect', $plugin_admin, 'custom_login_redirect', 10, 3 );
		$this->loader->add_action( 'init', $plugin_admin, 'hide_admin_bar_from_non_admins' );
		$this->loader->add_action( 'init', $plugin_admin, 'redirect_non_admin_users' );

	}	

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Capweb_Core_Functionality_Public( $this->get_capweb_core_functionality(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// $this->loader->add_action( 'init', $plugin_public, 'capweb_add_affiliate_disclaimer_shortcode' );

		// Add default featured image filters
		$this->loader->add_filter( 'post_thumbnail_html', $plugin_public, 'filter_post_thumbnail_html', 10, 5 );
		$this->loader->add_filter( 'post_thumbnail_id', $plugin_public, 'filter_post_thumbnail_id', 10, 2 );
		$this->loader->add_filter( 'post_thumbnail_url', $plugin_public, 'filter_post_thumbnail_url', 10, 3 );
		$this->loader->add_filter( 'post_thumbnail_alt', $plugin_public, 'filter_post_thumbnail_alt', 10, 2 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_capweb_core_functionality() {
		return $this->capweb_core_functionality;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Capweb_Core_Functionality_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
