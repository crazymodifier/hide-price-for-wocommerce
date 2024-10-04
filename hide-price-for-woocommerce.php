<?php

/**
 * 
 * @package   Hide Price For WooCommerce
 * @author    Crazy Modifier <modifiercrazy@gmail.com>
 * @license   GPL-3.0+
 * @link      http://crazymodifier.com
 * @copyright Crazy Modifier
 *
 * @wordpress-plugin
 * Plugin Name:       Hide Price For WooCommerce
 * Plugin URI:        https://github.com/crazymodifier/hide-price-for-woocommerce
 * Description:       Hide Price For WooCommerce is an add-on plugin for WooCommerce, By using this plugin you can set the condition to display Price and Add to cart button.
 * Version:           1.0.1
 * Author:            Crazy Modifier
 * Author URI:        https://crazymodifier.com
 * Text Domain:       hide-price-for-woocommerce
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/crazymodifier/hide-price-for-woocommerce
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

define( 'CM_HPFW_TXTDOMAIN', 'hide-price-for-woocommerce');
define('CM_HPFW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CM_HPFW_PLUGIN_URL', plugin_dir_url(__FILE__));

define('CM_HPFW_PLUGIN_NAME', 'hpfwc');
define('CM_HPFW_PLUGIN_VER', time());


/**
 * Checking for multisite
 */
if (function_exists('is_multisite') && is_multisite()) {
	include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

/**
 * Checkes if WooCommerce id active
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	include_once 'includes/class-hide-price-for-wc-main.php';
} else {
	/**
	 * To show error notice if woocommerce is not activated.
	 * @name hide_price_for_woocommerce_error_notice
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function hide_price_for_woocommerce_error_notice()
	{
	?>
		<div class="error notice is-dismissible">
			<p><?php esc_html_e( 'WooCommerce is not activated. Please install WooCommerce first, to use the Hide Price For WooCommerce plugin !!!' , 'hide-price-for-woocommerce'); ?></p>
		</div>
	<?php
	}

	add_action('admin_init', 'hide_price_for_woocommerce_deactivate');
	/**
	 * Deactivating plugins
	 * @name hide_price_for_woocommerce_deactivate
	 * @author CedCommerce <plugins@cedcommerce.com>
	 * @link http://cedcommerce.com/
	 */
	function hide_price_for_woocommerce_deactivate()
	{
		deactivate_plugins(plugin_basename(__FILE__));
		add_action('admin_notices', 'hide_price_for_woocommerce_error_notice');
	}
}

function hide_price_for_wc_load_textdomain() {
	$domain = "hide-price-for-woocommerce";
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	load_textdomain( $domain, plugin_dir_path(__FILE__) .'language/'.$domain.'-' . $locale . '.mo' );
	load_plugin_textdomain( 'hide-price-for-woocommerce', false, plugin_basename( dirname(__FILE__) ) . '/languages/' );
}
add_action('plugins_loaded', 'hide_price_for_wc_load_textdomain');