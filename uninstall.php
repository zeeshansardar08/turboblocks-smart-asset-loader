<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package TurboBlocks
 */

// Abort if not called by WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove plugin options.
delete_option( 'zntb_settings' );

// Remove transients.
delete_transient( 'zntb_asset_snapshot' );
