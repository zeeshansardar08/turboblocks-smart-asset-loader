<?php
/**
 * Admin page controller.
 *
 * Registers the settings page and enqueues admin assets.
 *
 * @package TurboBlocks
 */

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

/**
 * Class ZNTB_Admin
 *
 * Manages the admin settings page, settings registration, and
 * captures a snapshot of front-end assets for the UI.
 */
class ZNTB_Admin {

	/**
	 * Hook suffix for the settings page.
	 *
	 * @var string|false
	 */
	private $hook_suffix = false;

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'handle_reset' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register settings via the Settings API.
	 *
	 * @return void
	 */
	public function register_settings() {
		$settings = new ZNTB_Settings();
		$settings->register();
	}

	/**
	 * Add the settings page under the Settings menu.
	 *
	 * @return void
	 */
	public function add_menu_page() {
		$this->hook_suffix = add_options_page(
			__( 'TurboBlocks Settings', 'turboblocks-smart-asset-loader' ),
			__( 'TurboBlocks', 'turboblocks-smart-asset-loader' ),
			'manage_options',
			'turboblocks',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue admin CSS only on the plugin's settings page.
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( $this->hook_suffix !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'zntb-admin',
			ZNTB_URL . 'admin/css/zntb-admin.css',
			array(),
			ZNTB_VERSION
		);
	}

	/**
	 * Get the stored asset snapshot.
	 *
	 * @return array { scripts: array, styles: array }
	 */
	public static function get_asset_snapshot() {
		$snapshot = get_transient( 'zntb_asset_snapshot' );

		if ( ! is_array( $snapshot ) ) {
			$snapshot = array(
				'scripts' => array(),
				'styles'  => array(),
			);
		}

		return $snapshot;
	}

	/**
	 * Handle the reset settings action.
	 *
	 * Verifies nonce and capability, deletes the option, then redirects.
	 *
	 * @return void
	 */
	public function handle_reset() {
		if ( ! isset( $_POST['zntb_reset_settings'] ) ) {
			return;
		}

		if ( ! isset( $_POST['_zntb_reset_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_zntb_reset_nonce'] ) ), 'zntb_reset_settings' ) ) {
			wp_die(
				esc_html__( 'Security check failed.', 'turboblocks-smart-asset-loader' ),
				esc_html__( 'Error', 'turboblocks-smart-asset-loader' ),
				array( 'response' => 403 )
			);
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die(
				esc_html__( 'You do not have permission to perform this action.', 'turboblocks-smart-asset-loader' ),
				esc_html__( 'Error', 'turboblocks-smart-asset-loader' ),
				array( 'response' => 403 )
			);
		}

		delete_option( ZNTB_Settings::OPTION_KEY );
		delete_transient( 'zntb_asset_snapshot' );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'       => 'turboblocks',
					'zntb_reset' => '1',
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	/**
	 * Get debug information for the Debug Info section.
	 *
	 * @return array
	 */
	public static function get_debug_info() {
		global $wp_version;

		$theme = wp_get_theme();

		$info = array(
			'plugin_version' => ZNTB_VERSION,
			'wp_version'     => $wp_version,
			'php_version'    => PHP_VERSION,
			'theme'          => $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ),
			'safe_mode'      => ZNTB_Settings::get_settings()['safe_mode'] ? 'ON' : 'OFF',
			'woocommerce'    => class_exists( 'WooCommerce' ) ? 'Active' : 'Not active',
		);

		return $info;
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		include ZNTB_PATH . 'admin/views/page-settings.php';
	}
}
