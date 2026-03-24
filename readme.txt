=== TurboBlocks – Smart Asset Loader ===
Contributors:      zignites
Tags:              performance, assets, scripts, styles, speed
Requires at least: 5.8
Tested up to:      6.9.4
Requires PHP:      7.4
Stable tag:        1.0.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Unload unused scripts and styles with safe global controls and WooCommerce optimization. Improve performance safely with a beginner-friendly interface.

== Description ==

TurboBlocks – Smart Asset Loader is a lightweight performance plugin for WordPress. It safely prevents unnecessary CSS and JavaScript from loading on pages where they are not needed.

**Key Features:**

* **Global Asset Control** – View and disable enqueued scripts and styles globally from a single settings page.
* **WooCommerce Kill Switch** – Automatically unload WooCommerce assets on non-shop pages with one toggle.
* **Safe Mode** – Prevents accidental removal of core WordPress assets (enabled by default).
* **One-Click Reset** – Restore all defaults instantly if anything goes wrong.
* **Beginner-Friendly UI** – Clean settings page with simple toggles and clear labels.

**How It Works:**

Most plugins and themes load their CSS and JavaScript files on every page, whether they are needed or not. TurboBlocks detects all enqueued assets on your site and lets you disable the ones you don't need — reducing page size and improving load times.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Go to **Settings → TurboBlocks** to configure.

== Frequently Asked Questions ==

= Will this break my site? =

TurboBlocks ships with Safe Mode enabled by default, which prevents core and critical assets from being removed. You can also reset all settings with one click.

= Does it work with WooCommerce? =

Yes. The WooCommerce Kill Switch lets you unload WooCommerce assets on pages that are not shop, cart, checkout, or account pages.

= Does the plugin make external API calls? =

No. TurboBlocks operates entirely within your WordPress installation. It does not phone home, track usage, or make any external requests.

== Screenshots ==

1. Settings page – Quick Optimization section.
2. Asset Control – Select scripts and styles to disable.
3. Debug Info – View environment and detected data.

== Changelog ==

= 1.0.0 =
* Initial release.
* Global Asset Control — disable scripts and styles from a single page.
* WooCommerce Kill Switch — remove Woo assets on non-shop pages.
* Safe Mode — protects core assets from accidental removal.
* One-click reset to defaults.
* Debug Info panel.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
