<?php
/**
 * Settings registration and retrieval.
 *
 * Manages the zntb_settings option via the WordPress Settings API.
 *
 * @package TurboBlocks
 */

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

/**
 * Class ZNTB_Settings
 *
 * Registers a single option (zntb_settings) with the Settings API,
 * provides sanitisation, and offers static helpers to read settings.
 */
class ZNTB_Settings {

	/**
	 * Option name used in the database.
	 *
	 * @var string
	 */
	const OPTION_KEY = 'zntb_settings';

	/**
	 * Settings page slug.
	 *
	 * @var string
	 */
	const PAGE_SLUG = 'turboblocks';

	/**
	 * Default settings values.
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return array(
			'disabled_scripts' => array(),
			'disabled_styles'  => array(),
			'woo_kill_switch'  => false,
			'safe_mode'        => true,
		);
	}

	/**
	 * Retrieve plugin settings with defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$settings = get_option( self::OPTION_KEY, array() );

		return wp_parse_args( $settings, self::get_defaults() );
	}

	/**
	 * Register settings with the WordPress Settings API.
	 *
	 * @return void
	 */
	public function register() {
		register_setting(
			self::PAGE_SLUG,
			self::OPTION_KEY,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize' ),
				'default'           => self::get_defaults(),
			)
		);
	}

	/**
	 * Sanitise the settings array before saving.
	 *
	 * @param array $input Raw form input.
	 * @return array Sanitised settings.
	 */
	public function sanitize( $input ) {
		$clean = self::get_defaults();

		// Disabled scripts — array of sanitised handle strings.
		if ( isset( $input['disabled_scripts'] ) && is_array( $input['disabled_scripts'] ) ) {
			$clean['disabled_scripts'] = array_map( 'zntb_sanitize_handle', $input['disabled_scripts'] );
			$clean['disabled_scripts'] = array_values( array_filter( $clean['disabled_scripts'] ) );
		}

		// Disabled styles — array of sanitised handle strings.
		if ( isset( $input['disabled_styles'] ) && is_array( $input['disabled_styles'] ) ) {
			$clean['disabled_styles'] = array_map( 'zntb_sanitize_handle', $input['disabled_styles'] );
			$clean['disabled_styles'] = array_values( array_filter( $clean['disabled_styles'] ) );
		}

		// WooCommerce kill switch — boolean.
		$clean['woo_kill_switch'] = ! empty( $input['woo_kill_switch'] );

		// Safe mode — boolean.
		$clean['safe_mode'] = ! empty( $input['safe_mode'] );

		return $clean;
	}
}
