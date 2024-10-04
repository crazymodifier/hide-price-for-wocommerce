<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/**
 * undocumented class
 */

if (!class_exists('Hide_Price_For_WC_Main')) {

	class Hide_Price_For_WC_Main
	{
		/**
		 * Class constructor.
		 * @name __construct
		 * @author Crazy Modifier <plugins@crazymodifier.com> 
		 * @link https://crazymodifier.com/
		 */
		public function __construct()
		{

			$this->load_dependencies();
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 * 
		 * 
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 **/
		private function load_dependencies()
		{
			# code...

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once CM_HPFW_PLUGIN_DIR . 'admin/class-hide-price-for-wc-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public area.
			 */
			require_once CM_HPFW_PLUGIN_DIR . 'public/class-hide-price-for-wc-public.php';
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks()
		{

			$plugin_admin = new Hide_Price_For_WC_Admin();

			add_action('admin_enqueue_scripts', [&$plugin_admin, 'enqueue_styles']);
			add_action('admin_enqueue_scripts', [&$plugin_admin, 'enqueue_scripts']);
			add_filter('woocommerce_get_settings_pages', [&$plugin_admin, 'hideprice_extending_wc_settings'], 10);
		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks()
		{

			$plugin_public = new Hide_Price_For_WC_Public();

			add_action('wp_enqueue_scripts', [&$plugin_public, 'enqueue_styles']);
			add_action('wp_enqueue_scripts', [&$plugin_public, 'enqueue_scripts']);
			add_action('init', [&$plugin_public, 'init'], 99);


			add_action('wp_ajax_do_login', [&$plugin_public, 'do_login_via_ajax']);
			add_action('wp_ajax_nopriv_do_login', [&$plugin_public, 'do_login_via_ajax']);

			
		}
	}
}

$priceHide = new Hide_Price_For_WC_Main();