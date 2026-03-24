<?php
/**
 * Asset control engine.
 *
 * Handles dequeuing of scripts and styles based on plugin settings.
 *
 * @package TurboBlocks
 */

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

/**
 * Class ZNTB_Assets
 *
 * Hooks into wp_enqueue_scripts at a late priority to safely remove
 * scripts and styles that the user has marked as disabled.
 */
class ZNTB_Assets {

	/**
	 * Core script handles that must never be removed.
	 *
	 * @var array
	 */
	const PROTECTED_SCRIPTS = array(
		'jquery',
		'jquery-core',
		'jquery-migrate',
		'wp-hooks',
		'wp-i18n',
		'wp-polyfill',
		'wp-url',
		'wp-api-fetch',
		'wp-dom-ready',
		'wp-a11y',
		'admin-bar',
		'heartbeat',
	);

	/**
	 * Core style handles that must never be removed.
	 *
	 * @var array
	 */
	const PROTECTED_STYLES = array(
		'admin-bar',
		'dashicons',
		'wp-block-library',
	);

	/**
	 * Known WooCommerce script handles.
	 *
	 * @var array
	 */
	const WOO_SCRIPT_HANDLES = array(
		'woocommerce',
		'wc-add-to-cart',
		'wc-cart-fragments',
		'wc-add-to-cart-variation',
		'wc-single-product',
		'wc-checkout',
		'wc-address-i18n',
		'wc-country-select',
		'wc-password-strength-meter',
		'selectWoo',
		'select2',
	);

	/**
	 * Known WooCommerce style handles.
	 *
	 * @var array
	 */
	const WOO_STYLE_HANDLES = array(
		'woocommerce-general',
		'woocommerce-layout',
		'woocommerce-smallscreen',
		'woocommerce-inline',
		'wc-blocks-style',
		'wc-blocks-vendors-style',
		'select2',
	);

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		if ( is_admin() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_disabled_assets' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_disable_woocommerce_assets' ), 999 );
	}

	/**
	 * Dequeue scripts and styles that are marked as disabled in settings.
	 *
	 * Runs at priority 999 so all plugins/themes have already enqueued.
	 *
	 * @return void
	 */
	public function dequeue_disabled_assets() {
		$settings = ZNTB_Settings::get_settings();

		$this->dequeue_scripts( $settings['disabled_scripts'] );
		$this->dequeue_styles( $settings['disabled_styles'] );
	}

	/**
	 * Conditionally disable WooCommerce assets on non-shop pages.
	 *
	 * Only runs when the WooCommerce Kill Switch is enabled and
	 * the current page is not a WooCommerce page.
	 *
	 * @return void
	 */
	public function maybe_disable_woocommerce_assets() {
		// Bail if WooCommerce is not active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$settings = ZNTB_Settings::get_settings();

		if ( empty( $settings['woo_kill_switch'] ) ) {
			return;
		}

		// Keep Woo assets on WooCommerce pages.
		if ( $this->is_woocommerce_page() ) {
			return;
		}

		// Dequeue WooCommerce scripts.
		foreach ( self::WOO_SCRIPT_HANDLES as $handle ) {
			if ( wp_script_is( $handle, 'enqueued' ) ) {
				wp_dequeue_script( $handle );
			}
		}

		// Dequeue WooCommerce styles.
		foreach ( self::WOO_STYLE_HANDLES as $handle ) {
			if ( wp_style_is( $handle, 'enqueued' ) ) {
				wp_dequeue_style( $handle );
			}
		}
	}

	/**
	 * Determine if the current page is a WooCommerce page.
	 *
	 * Checks shop, cart, checkout, account, and product pages.
	 *
	 * @return bool
	 */
	private function is_woocommerce_page() {
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			return true;
		}

		if ( function_exists( 'is_cart' ) && is_cart() ) {
			return true;
		}

		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			return true;
		}

		if ( function_exists( 'is_account_page' ) && is_account_page() ) {
			return true;
		}

		return false;
	}

	/**
	 * Safely dequeue a list of script handles.
	 *
	 * @param array $handles Script handles to dequeue.
	 * @return void
	 */
	private function dequeue_scripts( array $handles ) {
		if ( empty( $handles ) ) {
			return;
		}

		foreach ( $handles as $handle ) {
			$handle = zntb_sanitize_handle( $handle );

			if ( $this->is_protected_script( $handle ) ) {
				continue;
			}

			if ( wp_script_is( $handle, 'enqueued' ) ) {
				wp_dequeue_script( $handle );
			}
		}
	}

	/**
	 * Safely dequeue a list of style handles.
	 *
	 * @param array $handles Style handles to dequeue.
	 * @return void
	 */
	private function dequeue_styles( array $handles ) {
		if ( empty( $handles ) ) {
			return;
		}

		foreach ( $handles as $handle ) {
			$handle = zntb_sanitize_handle( $handle );

			if ( $this->is_protected_style( $handle ) ) {
				continue;
			}

			if ( wp_style_is( $handle, 'enqueued' ) ) {
				wp_dequeue_style( $handle );
			}
		}
	}

	/**
	 * Check whether a script handle is protected from removal.
	 *
	 * In safe mode, any handle from wp-includes is also protected.
	 *
	 * @param string $handle Script handle.
	 * @return bool
	 */
	private function is_protected_script( $handle ) {
		if ( in_array( $handle, self::PROTECTED_SCRIPTS, true ) ) {
			return true;
		}

		return $this->is_safe_mode_protected( $handle, 'scripts' );
	}

	/**
	 * Check whether a style handle is protected from removal.
	 *
	 * In safe mode, any handle from wp-includes is also protected.
	 *
	 * @param string $handle Style handle.
	 * @return bool
	 */
	private function is_protected_style( $handle ) {
		if ( in_array( $handle, self::PROTECTED_STYLES, true ) ) {
			return true;
		}

		return $this->is_safe_mode_protected( $handle, 'styles' );
	}

	/**
	 * When safe mode is ON, protect any asset registered from wp-includes.
	 *
	 * @param string $handle Asset handle.
	 * @param string $type   Either 'scripts' or 'styles'.
	 * @return bool
	 */
	private function is_safe_mode_protected( $handle, $type ) {
		$settings = ZNTB_Settings::get_settings();

		if ( empty( $settings['safe_mode'] ) ) {
			return false;
		}

		if ( 'scripts' === $type ) {
			$registered = wp_scripts();
		} else {
			$registered = wp_styles();
		}

		if ( ! isset( $registered->registered[ $handle ] ) ) {
			return false;
		}

		$src = $registered->registered[ $handle ]->src;

		// Protect anything loaded from wp-includes or wp-admin.
		if ( is_string( $src ) && ( strpos( $src, '/wp-includes/' ) !== false || strpos( $src, '/wp-admin/' ) !== false ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get all currently enqueued script handles with their source URLs.
	 *
	 * Used by the admin UI to display available scripts.
	 *
	 * @return array Associative array of handle => src.
	 */
	public static function get_enqueued_scripts() {
		$scripts = wp_scripts();
		$list    = array();

		foreach ( $scripts->queue as $handle ) {
			if ( isset( $scripts->registered[ $handle ] ) ) {
				$list[ $handle ] = $scripts->registered[ $handle ]->src;
			}
		}

		ksort( $list );
		return $list;
	}

	/**
	 * Get all currently enqueued style handles with their source URLs.
	 *
	 * Used by the admin UI to display available styles.
	 *
	 * @return array Associative array of handle => src.
	 */
	public static function get_enqueued_styles() {
		$styles = wp_styles();
		$list   = array();

		foreach ( $styles->queue as $handle ) {
			if ( isset( $styles->registered[ $handle ] ) ) {
				$list[ $handle ] = $styles->registered[ $handle ]->src;
			}
		}

		ksort( $list );
		return $list;
	}

	/**
	 * Capture a snapshot of all front-end enqueued scripts and styles.
	 *
	 * Stores the list as a transient so it is available on the admin page.
	 * Called via wp_enqueue_scripts at priority 9999 on front-end requests.
	 *
	 * @return void
	 */
	public static function capture_asset_snapshot() {
		if ( is_admin() ) {
			return;
		}

		$snapshot = array(
			'scripts' => self::get_enqueued_scripts(),
			'styles'  => self::get_enqueued_styles(),
		);

		set_transient( 'zntb_asset_snapshot', $snapshot, DAY_IN_SECONDS );
	}
}
