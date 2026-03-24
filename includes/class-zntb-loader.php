<?php
/**
 * Plugin loader and bootstrapper.
 *
 * @package TurboBlocks
 */

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

/**
 * Class ZNTB_Loader
 *
 * Responsible for loading plugin dependencies and initialising components.
 */
class ZNTB_Loader {

	/**
	 * Run the loader.
	 *
	 * Loads required files and hooks into WordPress.
	 *
	 * @return void
	 */
	public function run() {
		$this->load_dependencies();
		$this->init_frontend();
		$this->init_admin();
	}

	/**
	 * Load all class files.
	 *
	 * @return void
	 */
	private function load_dependencies() {
		require_once ZNTB_PATH . 'includes/class-zntb-assets.php';
		require_once ZNTB_PATH . 'includes/class-zntb-settings.php';

		if ( is_admin() ) {
			require_once ZNTB_PATH . 'includes/class-zntb-admin.php';
		}
	}

	/**
	 * Initialise front-end functionality.
	 *
	 * @return void
	 */
	private function init_frontend() {
		$assets = new ZNTB_Assets();
		$assets->init();

		// Capture front-end asset snapshot for the admin UI.
		add_action( 'wp_enqueue_scripts', array( 'ZNTB_Assets', 'capture_asset_snapshot' ), 9999 );
	}

	/**
	 * Initialise admin-specific functionality.
	 *
	 * @return void
	 */
	private function init_admin() {
		if ( is_admin() ) {
			$admin = new ZNTB_Admin();
			$admin->init();
		}
	}
}
