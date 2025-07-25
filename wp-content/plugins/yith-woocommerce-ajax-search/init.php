<?php
/**
 * Plugin Name: YITH WooCommerce Ajax Search
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-ajax-search/
 * Description: <code><strong>YITH WooCommerce Ajax Search</strong></code> is the plugin that allows you to search for a specific product by inserting a few characters. Thanks to <strong>Ajax Search</strong>, users can quickly find the contents they are interested in without wasting time among site pages. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 2.15.0
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-ajax-search
 * Domain Path: /languages/
 * WC requires at least: 9.7
 * WC tested up to: 9.9
 * Requires Plugins: woocommerce
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Ajax Search
 * @version 2.15.0
 */

/*
Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! defined( 'YITH_WCAS_DIR' ) ) {
	define( 'YITH_WCAS_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Framework Loader.
if ( file_exists( plugin_dir_path( __FILE__ ) . 'plugin-fw/init.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'plugin-fw/init.php';
}


if ( defined( 'YITH_WCAS_PREMIUM' ) ) {
	/**
	 * Trigger a notice if the premium version is active.
	 */
	function yith_wcas_install_free_admin_notice() {
		?>
		<div class="error">
			<p><?php esc_html_e( 'You can\'t activate the free version of YITH WooCommerce Ajax Search while you are using the premium one.', 'yith-woocommerce-ajax-search' ); ?></p>
		</div>
		<?php
	}

	add_action( 'admin_notices', 'yith_wcas_install_free_admin_notice' );

	deactivate_plugins( plugin_basename( __FILE__ ) );
	return;
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

! defined( 'YITH_WCAS_VERSION' ) && define( 'YITH_WCAS_VERSION', '2.15.0' );
! defined( 'YITH_WCAS_FREE_INIT' ) && define( 'YITH_WCAS_FREE_INIT', plugin_basename( __FILE__ ) );
! defined( 'YITH_WCAS' ) && define( 'YITH_WCAS', true );
! defined( 'YITH_WCAS_FILE' ) && define( 'YITH_WCAS_FILE', __FILE__ );
! defined( 'YITH_WCAS_URL' ) && define( 'YITH_WCAS_URL', plugin_dir_url( __FILE__ ) );
! defined( 'YITH_WCAS_TEMPLATE_PATH' ) && define( 'YITH_WCAS_TEMPLATE_PATH', YITH_WCAS_DIR . 'templates' );
! defined( 'YITH_WCAS_ASSETS_URL' ) && define( 'YITH_WCAS_ASSETS_URL', YITH_WCAS_URL . 'assets' );
! defined( 'YITH_WCAS_ASSETS_IMAGES_URL' ) && define( 'YITH_WCAS_ASSETS_IMAGES_URL', YITH_WCAS_ASSETS_URL . '/images/' );
! defined( 'YITH_WCAS_SLUG' ) && define( 'YITH_WCAS_SLUG', 'yith-woocommerce-ajax-search' );
! defined( 'YITH_WCAS_INC' ) && define( 'YITH_WCAS_INC', YITH_WCAS_DIR . 'includes/' );
! defined( 'YITH_WCAS_DIR' ) && define( 'YITH_WCAS_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'YITH_WCAS_BLOCK_PATH' ) && define( 'YITH_WCAS_BLOCK_PATH', YITH_WCAS_DIR . 'assets/js/blocks/src/blocks/' );
! defined( 'YITH_WCAS_BUILD_BLOCK_PATH' ) && define( 'YITH_WCAS_BUILD_BLOCK_PATH', YITH_WCAS_DIR . 'assets/js/blocks/build/' );
// Require plugin autoload.
if ( ! class_exists( 'YITH_WCAS_Autoloader' ) ) {
	require_once YITH_WCAS_INC . 'class-yith-wcas-autoloader.php';
}

// Load plugin's functions.
require_once YITH_WCAS_DIR . '/includes/deprecated-functions-yith-wcas.php';
require_once YITH_WCAS_DIR . '/includes/functions-yith-wcas.php';
require_once YITH_WCAS_DIR . '/includes/functions-yith-wcas-update.php';
/**
 * Start plugin.
 */
function yith_ajax_search_constructor() {

	if ( ! function_exists( 'WC' ) ) {
		/**
		 * Check if WooCommerce is installed.
		 */
		function yith_wcas_install_woocommerce_admin_notice() {
			?>
			<div class="error">
				<p><?php esc_html_e( 'YITH WooCommerce Ajax Search is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-ajax-search' ); ?></p>
			</div>
			<?php
		}

		add_action( 'admin_notices', 'yith_wcas_install_woocommerce_admin_notice' );
	} else {
		// Add support with HPOS system for WooCommerce 8.
		add_action(
			'before_woocommerce_init',
			function () {
				if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', YITH_WCAS_FREE_INIT );
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', YITH_WCAS_FREE_INIT );
				}
			}
		);

		global $yith_wcas;
		$yith_wcas = ywcas();
		add_action(
			'init',
			function () {
				ywcas()->get_class_name( 'YITH_WCAS_Install' )::init();
			},
			20
		);
	}
}

add_action( 'plugins_loaded', 'yith_ajax_search_constructor' );

if ( ! function_exists( 'ywcas' ) ) {
	/**
	 * Return the instance of the main class
	 *
	 * @return YITH_WCAS
	 */
	function ywcas() {
		return YITH_WCAS::get_instance();
	}
}
