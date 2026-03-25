<?php
/**
 * Main settings page template.
 *
 * @package TurboBlocks
 */

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

$zntb_settings   = ZNTB_Settings::get_settings();
$zntb_snapshot   = ZNTB_Admin::get_asset_snapshot();
$zntb_debug_info = ZNTB_Admin::get_debug_info();
?>

<div class="wrap zntb-wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<p><?php esc_html_e( 'Manage asset loading to improve your site performance.', 'turboblocks-smart-asset-loader' ); ?></p>

	<?php
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only query param for admin notice.
	if ( isset( $_GET['zntb_reset'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['zntb_reset'] ) ) ) :
	?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'All settings have been reset to defaults.', 'turboblocks-smart-asset-loader' ); ?></p>
		</div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php settings_fields( ZNTB_Settings::PAGE_SLUG ); ?>

		<!-- Section 1: Quick Optimization -->
		<div class="zntb-section">
			<h2><?php esc_html_e( 'Quick Optimization', 'turboblocks-smart-asset-loader' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Toggle key optimization features with a single click.', 'turboblocks-smart-asset-loader' ); ?>
			</p>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'Safe Mode', 'turboblocks-smart-asset-loader' ); ?></th>
					<td>
						<label>
							<input
								type="checkbox"
								name="<?php echo esc_attr( ZNTB_Settings::OPTION_KEY ); ?>[safe_mode]"
								value="1"
								<?php checked( $zntb_settings['safe_mode'] ); ?>
							/>
							<?php esc_html_e( 'Enable Safe Mode (recommended)', 'turboblocks-smart-asset-loader' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'Prevents removal of core WordPress assets. Keep this on unless you know what you are doing.', 'turboblocks-smart-asset-loader' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'WooCommerce Kill Switch', 'turboblocks-smart-asset-loader' ); ?></th>
					<td>
						<label>
							<input
								type="checkbox"
								name="<?php echo esc_attr( ZNTB_Settings::OPTION_KEY ); ?>[woo_kill_switch]"
								value="1"
								<?php checked( $zntb_settings['woo_kill_switch'] ); ?>
								<?php disabled( ! class_exists( 'WooCommerce' ) ); ?>
							/>
							<?php esc_html_e( 'Disable WooCommerce assets on non-shop pages', 'turboblocks-smart-asset-loader' ); ?>
						</label>
						<?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
							<p class="description">
								<?php esc_html_e( 'WooCommerce is not active. This setting has no effect.', 'turboblocks-smart-asset-loader' ); ?>
							</p>
						<?php else : ?>
							<p class="description">
								<?php esc_html_e( 'Removes WooCommerce scripts and styles on pages that are not shop, cart, checkout, or account pages.', 'turboblocks-smart-asset-loader' ); ?>
							</p>
						<?php endif; ?>
					</td>
				</tr>
			</table>
		</div>

		<!-- Section 2: Asset Control -->
		<div class="zntb-section">
			<h2><?php esc_html_e( 'Asset Control', 'turboblocks-smart-asset-loader' ); ?></h2>
			<p class="description">
				<?php esc_html_e( 'Select scripts and styles to disable globally. Assets detected from your latest visited front-end page.', 'turboblocks-smart-asset-loader' ); ?>
			</p>

			<?php if ( empty( $zntb_snapshot['scripts'] ) && empty( $zntb_snapshot['styles'] ) ) : ?>
				<p><em><?php esc_html_e( 'No asset data found yet. Visit any page on your site\'s front end, then reload this settings page.', 'turboblocks-smart-asset-loader' ); ?></em></p>
			<?php else : ?>

				<?php if ( ! empty( $zntb_snapshot['scripts'] ) ) : ?>
					<h3><?php esc_html_e( 'Scripts', 'turboblocks-smart-asset-loader' ); ?></h3>
					<fieldset>
						<table class="widefat zntb-asset-table">
							<thead>
								<tr>
									<th class="check-column"><span class="screen-reader-text"><?php esc_html_e( 'Disable', 'turboblocks-smart-asset-loader' ); ?></span></th>
									<th><?php esc_html_e( 'Handle', 'turboblocks-smart-asset-loader' ); ?></th>
									<th><?php esc_html_e( 'Source', 'turboblocks-smart-asset-loader' ); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ( $zntb_snapshot['scripts'] as $zntb_handle => $zntb_src ) : ?>
								<tr>
									<td>
										<input
											type="checkbox"
											name="<?php echo esc_attr( ZNTB_Settings::OPTION_KEY ); ?>[disabled_scripts][]"
											value="<?php echo esc_attr( $zntb_handle ); ?>"
											<?php checked( in_array( $zntb_handle, $zntb_settings['disabled_scripts'], true ) ); ?>
										/>
									</td>
									<td><code><?php echo esc_html( $zntb_handle ); ?></code></td>
									<td class="zntb-src"><?php echo esc_html( $zntb_src ); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</fieldset>
				<?php endif; ?>

				<?php if ( ! empty( $zntb_snapshot['styles'] ) ) : ?>
					<h3><?php esc_html_e( 'Styles', 'turboblocks-smart-asset-loader' ); ?></h3>
					<fieldset>
						<table class="widefat zntb-asset-table">
							<thead>
								<tr>
									<th class="check-column"><span class="screen-reader-text"><?php esc_html_e( 'Disable', 'turboblocks-smart-asset-loader' ); ?></span></th>
									<th><?php esc_html_e( 'Handle', 'turboblocks-smart-asset-loader' ); ?></th>
									<th><?php esc_html_e( 'Source', 'turboblocks-smart-asset-loader' ); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ( $zntb_snapshot['styles'] as $zntb_handle => $zntb_src ) : ?>
								<tr>
									<td>
										<input
											type="checkbox"
											name="<?php echo esc_attr( ZNTB_Settings::OPTION_KEY ); ?>[disabled_styles][]"
											value="<?php echo esc_attr( $zntb_handle ); ?>"
											<?php checked( in_array( $zntb_handle, $zntb_settings['disabled_styles'], true ) ); ?>
										/>
									</td>
									<td><code><?php echo esc_html( $zntb_handle ); ?></code></td>
									<td class="zntb-src"><?php echo esc_html( $zntb_src ); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</fieldset>
				<?php endif; ?>

			<?php endif; ?>
		</div>

		<?php submit_button( __( 'Save Settings', 'turboblocks-smart-asset-loader' ) ); ?>
	</form>

	<!-- Section 3: Debug Info -->
	<div class="zntb-section">
		<h2><?php esc_html_e( 'Debug Info', 'turboblocks-smart-asset-loader' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Environment details useful for troubleshooting.', 'turboblocks-smart-asset-loader' ); ?>
		</p>

		<table class="widefat zntb-debug-table">
			<tbody>
				<tr>
					<td><strong><?php esc_html_e( 'Plugin Version', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td><?php echo esc_html( $zntb_debug_info['plugin_version'] ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'WordPress Version', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td><?php echo esc_html( $zntb_debug_info['wp_version'] ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'PHP Version', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td><?php echo esc_html( $zntb_debug_info['php_version'] ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Active Theme', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td><?php echo esc_html( $zntb_debug_info['theme'] ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Safe Mode', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td><?php echo esc_html( $zntb_debug_info['safe_mode'] ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'WooCommerce', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td><?php echo esc_html( $zntb_debug_info['woocommerce'] ); ?></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Disabled Scripts', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td>
						<?php
						if ( ! empty( $zntb_settings['disabled_scripts'] ) ) {
							echo esc_html( implode( ', ', $zntb_settings['disabled_scripts'] ) );
						} else {
							esc_html_e( 'None', 'turboblocks-smart-asset-loader' );
						}
						?>
					</td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e( 'Disabled Styles', 'turboblocks-smart-asset-loader' ); ?></strong></td>
					<td>
						<?php
						if ( ! empty( $zntb_settings['disabled_styles'] ) ) {
							echo esc_html( implode( ', ', $zntb_settings['disabled_styles'] ) );
						} else {
							esc_html_e( 'None', 'turboblocks-smart-asset-loader' );
						}
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Reset Settings -->
	<div class="zntb-section zntb-section-danger">
		<h2><?php esc_html_e( 'Reset Settings', 'turboblocks-smart-asset-loader' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'This will remove all TurboBlocks settings and restore defaults. This action cannot be undone.', 'turboblocks-smart-asset-loader' ); ?>
		</p>
		<form method="post">
			<?php wp_nonce_field( 'zntb_reset_settings', '_zntb_reset_nonce' ); ?>
			<p>
				<button type="submit" name="zntb_reset_settings" value="1" class="button button-secondary zntb-reset-btn"
					onclick="return confirm( '<?php echo esc_js( __( 'Are you sure you want to reset all settings?', 'turboblocks-smart-asset-loader' ) ); ?>' );">
					<?php esc_html_e( 'Reset All Settings', 'turboblocks-smart-asset-loader' ); ?>
				</button>
			</p>
		</form>
	</div>
</div>
