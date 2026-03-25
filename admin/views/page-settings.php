<?php
/**
 * Main settings page template.
 *
 * @package TurboBlocks
 */

// Prevent direct file access.
defined( 'ABSPATH' ) || exit;

$zntb_settings           = ZNTB_Settings::get_settings();
$zntb_snapshot           = ZNTB_Admin::get_asset_snapshot();
$zntb_debug_info         = ZNTB_Admin::get_debug_info();
$zntb_disabled_scripts_n = count( $zntb_settings['disabled_scripts'] );
$zntb_disabled_styles_n  = count( $zntb_settings['disabled_styles'] );
?>

<div class="wrap zntb-wrap">
	<!-- Page Header -->
	<div class="zntb-page-header">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p class="zntb-page-subtitle"><?php esc_html_e( 'Manage asset loading to improve your site performance.', 'turboblocks-smart-asset-loader' ); ?></p>
	</div>

	<!-- Status Summary Grid -->
	<div class="zntb-status-grid">
		<div class="zntb-status-tile zntb-status-tile--success">
			<span class="zntb-status-tile-label"><?php esc_html_e( 'Plugin Status', 'turboblocks-smart-asset-loader' ); ?></span>
			<span class="zntb-status-tile-value zntb-status-tile-value--on"><?php esc_html_e( 'Active', 'turboblocks-smart-asset-loader' ); ?></span>
		</div>
		<div class="zntb-status-tile <?php echo $zntb_settings['safe_mode'] ? 'zntb-status-tile--success' : 'zntb-status-tile--neutral'; ?>">
			<span class="zntb-status-tile-label"><?php esc_html_e( 'Safe Mode', 'turboblocks-smart-asset-loader' ); ?></span>
			<?php if ( $zntb_settings['safe_mode'] ) : ?>
				<span class="zntb-status-tile-value zntb-status-tile-value--on"><?php esc_html_e( 'ON', 'turboblocks-smart-asset-loader' ); ?></span>
			<?php else : ?>
				<span class="zntb-status-tile-value zntb-status-tile-value--off"><?php esc_html_e( 'OFF', 'turboblocks-smart-asset-loader' ); ?></span>
			<?php endif; ?>
		</div>
		<div class="zntb-status-tile <?php echo ( $zntb_settings['woo_kill_switch'] && class_exists( 'WooCommerce' ) ) ? 'zntb-status-tile--success' : 'zntb-status-tile--neutral'; ?>">
			<span class="zntb-status-tile-label"><?php esc_html_e( 'WooCommerce', 'turboblocks-smart-asset-loader' ); ?></span>
			<?php if ( $zntb_settings['woo_kill_switch'] && class_exists( 'WooCommerce' ) ) : ?>
				<span class="zntb-status-tile-value zntb-status-tile-value--on"><?php esc_html_e( 'ON', 'turboblocks-smart-asset-loader' ); ?></span>
			<?php else : ?>
				<span class="zntb-status-tile-value zntb-status-tile-value--off"><?php esc_html_e( 'OFF', 'turboblocks-smart-asset-loader' ); ?></span>
			<?php endif; ?>
		</div>
		<div class="zntb-status-tile zntb-status-tile--info">
			<span class="zntb-status-tile-label"><?php esc_html_e( 'Scripts Off', 'turboblocks-smart-asset-loader' ); ?></span>
			<span class="zntb-status-tile-value zntb-status-tile-value--count"><?php echo esc_html( $zntb_disabled_scripts_n ); ?></span>
		</div>
		<div class="zntb-status-tile zntb-status-tile--info">
			<span class="zntb-status-tile-label"><?php esc_html_e( 'Styles Off', 'turboblocks-smart-asset-loader' ); ?></span>
			<span class="zntb-status-tile-value zntb-status-tile-value--count"><?php echo esc_html( $zntb_disabled_styles_n ); ?></span>
		</div>
	</div>

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
		<div class="zntb-card">
			<div class="zntb-card-header">
				<h2><?php esc_html_e( 'Quick Optimization', 'turboblocks-smart-asset-loader' ); ?></h2>
				<p class="zntb-card-desc"><?php esc_html_e( 'Toggle key optimization features with a single click.', 'turboblocks-smart-asset-loader' ); ?></p>
			</div>

			<div class="zntb-settings-rows">
				<div class="zntb-setting-row">
					<div class="zntb-setting-label"><?php esc_html_e( 'Safe Mode', 'turboblocks-smart-asset-loader' ); ?></div>
					<div class="zntb-setting-field">
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
					</div>
				</div>
				<div class="zntb-setting-row">
					<div class="zntb-setting-label"><?php esc_html_e( 'WooCommerce Kill Switch', 'turboblocks-smart-asset-loader' ); ?></div>
					<div class="zntb-setting-field">
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
					</div>
				</div>
			</div>
		</div>

		<!-- Section 2: Asset Control -->
		<div class="zntb-card zntb-card--hero">
			<div class="zntb-card-header">
				<h2>
					<?php esc_html_e( 'Asset Control', 'turboblocks-smart-asset-loader' ); ?>
					<?php if ( $zntb_disabled_scripts_n || $zntb_disabled_styles_n ) : ?>
						<span class="zntb-heading-count">
							<?php
							printf(
								/* translators: 1: disabled scripts count, 2: disabled styles count */
								esc_html__( '%1$d scripts, %2$d styles disabled', 'turboblocks-smart-asset-loader' ),
								absint( $zntb_disabled_scripts_n ),
								absint( $zntb_disabled_styles_n )
							);
							?>
						</span>
					<?php endif; ?>
				</h2>
				<p class="zntb-card-desc">
					<?php esc_html_e( 'Select scripts and styles to disable globally. Assets detected from your latest visited front-end page.', 'turboblocks-smart-asset-loader' ); ?>
				</p>
			</div>

			<p class="zntb-tip">
				<?php esc_html_e( 'Tip: Start by disabling scripts and styles from plugins that are not needed on this page. Safe Mode helps protect core WordPress assets.', 'turboblocks-smart-asset-loader' ); ?>
			</p>

			<?php if ( empty( $zntb_snapshot['scripts'] ) && empty( $zntb_snapshot['styles'] ) ) : ?>
				<div class="zntb-empty-state">
					<p><?php esc_html_e( 'No assets detected yet. Visit any front-end page on your site, then return here to manage scripts and styles.', 'turboblocks-smart-asset-loader' ); ?></p>
				</div>
			<?php else : ?>

				<?php if ( ! empty( $zntb_snapshot['scripts'] ) ) : ?>
					<h3 class="zntb-asset-heading"><?php esc_html_e( 'Scripts', 'turboblocks-smart-asset-loader' ); ?></h3>
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
									<td>
										<code class="zntb-handle"><?php echo esc_html( $zntb_handle ); ?></code>
										<?php if ( ZNTB_Admin::is_heavy_asset( $zntb_handle ) ) : ?>
											<span class="zntb-badge-heavy"><?php esc_html_e( 'Heavy', 'turboblocks-smart-asset-loader' ); ?></span>
										<?php endif; ?>
									</td>
									<td class="zntb-src" title="<?php echo esc_attr( $zntb_src ); ?>"><?php echo esc_html( ZNTB_Admin::shorten_asset_src( $zntb_src ) ); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						</table>
					</fieldset>
				<?php endif; ?>

				<?php if ( ! empty( $zntb_snapshot['styles'] ) ) : ?>
					<h3 class="zntb-asset-heading"><?php esc_html_e( 'Styles', 'turboblocks-smart-asset-loader' ); ?></h3>
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
									<td>
										<code class="zntb-handle"><?php echo esc_html( $zntb_handle ); ?></code>
										<?php if ( ZNTB_Admin::is_heavy_asset( $zntb_handle ) ) : ?>
											<span class="zntb-badge-heavy"><?php esc_html_e( 'Heavy', 'turboblocks-smart-asset-loader' ); ?></span>
										<?php endif; ?>
									</td>
									<td class="zntb-src" title="<?php echo esc_attr( $zntb_src ); ?>"><?php echo esc_html( ZNTB_Admin::shorten_asset_src( $zntb_src ) ); ?></td>
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
	<div class="zntb-card">
		<div class="zntb-card-header">
			<h2><?php esc_html_e( 'Debug Info', 'turboblocks-smart-asset-loader' ); ?></h2>
			<p class="zntb-card-desc">
				<?php esc_html_e( 'Environment details useful for troubleshooting.', 'turboblocks-smart-asset-loader' ); ?>
			</p>
		</div>

		<div class="zntb-debug-grid">
			<div class="zntb-debug-item">
				<span class="zntb-debug-label"><?php esc_html_e( 'Plugin Version', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value"><?php echo esc_html( $zntb_debug_info['plugin_version'] ); ?></span>
			</div>
			<div class="zntb-debug-item">
				<span class="zntb-debug-label"><?php esc_html_e( 'WordPress Version', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value"><?php echo esc_html( $zntb_debug_info['wp_version'] ); ?></span>
			</div>
			<div class="zntb-debug-item">
				<span class="zntb-debug-label"><?php esc_html_e( 'PHP Version', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value"><?php echo esc_html( $zntb_debug_info['php_version'] ); ?></span>
			</div>
			<div class="zntb-debug-item">
				<span class="zntb-debug-label"><?php esc_html_e( 'Active Theme', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value"><?php echo esc_html( $zntb_debug_info['theme'] ); ?></span>
			</div>
			<div class="zntb-debug-item">
				<span class="zntb-debug-label"><?php esc_html_e( 'Safe Mode', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value"><?php echo esc_html( $zntb_debug_info['safe_mode'] ); ?></span>
			</div>
			<div class="zntb-debug-item">
				<span class="zntb-debug-label"><?php esc_html_e( 'WooCommerce', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value"><?php echo esc_html( $zntb_debug_info['woocommerce'] ); ?></span>
			</div>
			<div class="zntb-debug-item zntb-debug-item--full">
				<span class="zntb-debug-label"><?php esc_html_e( 'Disabled Scripts', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value">
					<?php
					if ( ! empty( $zntb_settings['disabled_scripts'] ) ) {
						echo esc_html( implode( ', ', $zntb_settings['disabled_scripts'] ) );
					} else {
						esc_html_e( 'None', 'turboblocks-smart-asset-loader' );
					}
					?>
				</span>
			</div>
			<div class="zntb-debug-item zntb-debug-item--full">
				<span class="zntb-debug-label"><?php esc_html_e( 'Disabled Styles', 'turboblocks-smart-asset-loader' ); ?></span>
				<span class="zntb-debug-value">
					<?php
					if ( ! empty( $zntb_settings['disabled_styles'] ) ) {
						echo esc_html( implode( ', ', $zntb_settings['disabled_styles'] ) );
					} else {
						esc_html_e( 'None', 'turboblocks-smart-asset-loader' );
					}
					?>
				</span>
			</div>
		</div>
	</div>

	<!-- Reset Settings -->
	<div class="zntb-card zntb-card--danger">
		<div class="zntb-card-header">
			<h2><?php esc_html_e( 'Danger Zone', 'turboblocks-smart-asset-loader' ); ?></h2>
			<p class="zntb-card-desc">
				<?php esc_html_e( 'This will remove all disabled asset selections and restore Safe Mode and other options to their defaults. This action cannot be undone.', 'turboblocks-smart-asset-loader' ); ?>
			</p>
		</div>
		<form method="post">
			<?php wp_nonce_field( 'zntb_reset_settings', '_zntb_reset_nonce' ); ?>
			<p>
				<button type="submit" name="zntb_reset_settings" value="1" class="button button-secondary zntb-reset-btn"
					onclick="return confirm( '<?php echo esc_js( __( 'Are you sure? All disabled asset selections will be cleared and settings restored to defaults. This cannot be undone.', 'turboblocks-smart-asset-loader' ) ); ?>' );">
					<?php esc_html_e( 'Reset All Settings', 'turboblocks-smart-asset-loader' ); ?>
				</button>
			</p>
		</form>
	</div>
</div>
