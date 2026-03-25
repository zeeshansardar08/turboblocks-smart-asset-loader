<?php
/**
 * Plugin Name:       TurboBlocks – Smart Asset Loader (Disable Unused CSS & JS)
 * Description:       Unload unused scripts and styles with safe global controls and WooCommerce optimization. Improve performance safely with a beginner-friendly interface.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Zignites
 * Author URI:        https://zignites.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       turboblocks-smart-asset-loader
 * Domain Path:       /languages
 *
 * @package TurboBlocks
 */

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

/**
 * Plugin version.
 */
define( 'ZNTB_VERSION', '1.0.0' );

/**
 * Plugin directory path (with trailing slash).
 */
define( 'ZNTB_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL (with trailing slash).
 */
define( 'ZNTB_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'ZNTB_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load the main loader class and boot the plugin.
 */
require_once ZNTB_PATH . 'includes/class-zntb-loader.php';

/**
 * Sanitise an asset handle while preserving case.
 *
 * Unlike sanitize_key(), this does not lowercase the string,
 * because WordPress asset handles are case-sensitive.
 *
 * @param string $handle Raw handle string.
 * @return string Sanitised handle containing only [a-zA-Z0-9_-].
 */
function zntb_sanitize_handle( $handle ) {
	return preg_replace( '/[^a-zA-Z0-9_\-]/', '', (string) $handle );
}

/**
 * Runs on plugin activation.
 *
 * Sets default options if they do not already exist.
 *
 * @return void
 */
function zntb_activate() {
	$defaults = array(
		'disabled_scripts'    => array(),
		'disabled_styles'     => array(),
		'woo_kill_switch'     => false,
		'safe_mode'           => true,
	);

	if ( false === get_option( 'zntb_settings' ) ) {
		add_option( 'zntb_settings', $defaults );
	}
}
register_activation_hook( __FILE__, 'zntb_activate' );

/**
 * Runs on plugin deactivation.
 *
 * Intentionally lightweight — cleanup happens in uninstall.php.
 *
 * @return void
 */
function zntb_deactivate() {
	// Nothing to do on deactivation; options are preserved.
	// Full cleanup is handled by uninstall.php.
}
register_deactivation_hook( __FILE__, 'zntb_deactivate' );

/**
 * Boot the plugin.
 */
function zntb_init() {
	$loader = new ZNTB_Loader();
	$loader->run();
}
add_action( 'plugins_loaded', 'zntb_init' );

/**
 * Add a "Settings" link on the Plugins page.
 */
add_filter( 'plugin_action_links_' . ZNTB_BASENAME, 'zntb_plugin_action_links' );

/**
 * Insert a Settings link into the plugin's action links.
 *
 * @param array $links Existing action links.
 * @return array Modified action links.
 */
function zntb_plugin_action_links( $links ) {
	$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=turboblocks' ) ) . '">' . esc_html__( 'Settings', 'turboblocks-smart-asset-loader' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
