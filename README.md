# 🚀 TurboBlocks – Smart Asset Loader

Unload unused scripts & styles in WordPress based on actual block usage.

TurboBlocks is a lightweight performance plugin designed for modern WordPress (Gutenberg & Full Site Editing). It intelligently prevents unnecessary assets from loading, helping you reduce page size, improve Core Web Vitals, and speed up your site — without complex configuration.

---

## ✨ Why TurboBlocks?

Most WordPress plugins load their CSS/JS on every page — even when not used.

TurboBlocks solves this by:

* Detecting which blocks are actually used
* Preventing unused scripts/styles from loading
* Giving you safe, simple control over asset loading

---

## ⚡ Key Features (MVP)

### 🧩 Block Detection Engine

* Detects Gutenberg blocks used on a page
* Uses `parse_blocks()` with fallback support
* Foundation for block-based optimization

---

### ⚙️ Global Asset Control

* View all enqueued scripts and styles
* Disable unnecessary assets globally
* Safe and controlled unloading

---

### 🛒 WooCommerce Kill Switch

* Disable WooCommerce scripts on non-shop pages
* Works with:

  * Blog pages
  * Static pages
  * Non-commerce routes

---

### 🧯 Safe Mode (Default ON)

* Prevents accidental site breakage
* Only allows safe asset removal
* Includes one-click reset to defaults

---

### 🖥️ Simple Admin Interface

* Clean and beginner-friendly UI
* No overwhelming options
* Quick toggles for optimization

---

## 🎯 Plugin Goals

* Improve performance without complexity
* Reduce script bloat in Gutenberg/FSE sites
* Provide safe and predictable optimizations
* Work out-of-the-box with minimal configuration

---

## 📦 Installation

1. Download or clone this repository:

   ```bash
   git clone https://github.com/your-username/turboblocks-smart-asset-loader.git
   ```

2. Move the plugin folder to:

   ```
   /wp-content/plugins/
   ```

3. Activate the plugin from WordPress Admin:

   ```
   Plugins → Installed Plugins → TurboBlocks – Smart Asset Loader
   ```

4. Configure settings:

   ```
   Settings → TurboBlocks
   ```

---

## ⚙️ Requirements

* WordPress 6.0+
* PHP 7.4+
* Gutenberg / Block Editor enabled

---

## 🧠 How It Works

```text
Page Load
   ↓
Detect Blocks (parse_blocks)
   ↓
Get Enqueued Scripts/Styles
   ↓
Apply Rules (User Settings)
   ↓
Dequeue Unused Assets
   ↓
Render Optimized Page
```

---

## 🔐 Safety First

TurboBlocks is built with safety in mind:

* Does NOT remove core WordPress assets
* Respects script dependencies
* Safe Mode enabled by default
* One-click reset option available

---

## 🚧 Roadmap (Upcoming Features)

* Per-page asset control
* Smart auto-detection of unused assets
* Frontend debug mode
* Performance insights dashboard
* Advanced rule engine
* AI-based optimization suggestions

---

## 🤝 Contributing

Contributions are welcome!

Please follow:

* WordPress Coding Standards (WPCS)
* Proper prefixing (`zntb_`)
* Clean and modular architecture

---

## 🧪 Development Notes

* Prefix: `zntb_`
* Class Prefix: `ZNTB_`
* Text Domain: `turboblocks-smart-asset-loader`
* Built for WordPress.org compliance

---

## 📄 License

This plugin is licensed under the GPL v2 or later.

---

## 👨‍💻 Author

Developed by **Zignites**

---

## ⭐ Support the Project

If you find this plugin useful:

* Star this repository ⭐
* Share feedback
* Contribute improvements

---

## 🚀 Vision

TurboBlocks aims to become the go-to solution for:

> “Fixing WordPress performance in minutes — without breaking your site.”

---
