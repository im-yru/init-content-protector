=== Init Content Protector – Anti-Copy, Anti-Scrape, Encrypt-All ===
Contributors: brokensmile.2103
Tags: content protection, anti-copy, copy protection, encryption, anti-scraping
Requires at least: 5.5
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Protect your content from copying, scraping, and inspection using JS blocking, keyword cloaking, noise injection, and optional full encryption.

== Description ==

**Init Content Protector** is a powerful yet lightweight plugin that safeguards your post content from unauthorized copying, scraping tools, and inspection via browser developer tools.

This plugin is part of the [Init Plugin Suite](https://en.inithtml.com/init-plugin-suite-minimalist-powerful-and-free-wordpress-plugins/) — a collection of minimalist, fast, and developer-focused tools for WordPress.

GitHub repository: [https://github.com/brokensmile2103/init-content-protector](https://github.com/brokensmile2103/init-content-protector)

**Features:**
- JavaScript-based copy protection (blocks selection, right-click, print, DevTools access)
- Full content encryption with client-side decryption using CryptoJS
- Keyword cloaking using CSS pseudo-elements
- Invisible noise injection to confuse crawlers
- Per-post type configuration
- Custom encryption key per site
- Custom content selector support

Use this plugin to harden your site's content visibility while maintaining a smooth reading experience for real users.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/init-content-protector` directory, or install via the WordPress plugin screen.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings → Init Content Protector** and configure your preferred options.

== Frequently Asked Questions ==

= Will this affect SEO? =
If you enable full content encryption, search engines will not be able to see the content. Only use this option if SEO visibility is not required.

= Does this plugin support custom post types? =
Yes. You can choose which post types are protected in the settings page.

= Can I use my own encryption key? =
Yes. You can set a custom key per site for added security.

== Screenshots ==

1. **Settings Page** – Configure protection methods, encryption, keyword cloaking, and per-post type options.

== Changelog ==

= 1.0.0 – July 23, 2025 =
- Initial release
- JavaScript-based content protection (block copy, right-click, print, DevTools)
- Full AES-256 content encryption with CryptoJS decryption
- Invisible keyword cloaking via ::before and randomized CSS class
- Random noise injection (hidden spans) to confuse crawlers
- Supports multiple post types (customizable)
- Custom encryption key per site
- Custom content selector for JS targeting
- Fallback styling compatible with light/dark themes
- Modular settings page with sanitize and validation

== Source Code ==

This plugin uses [CryptoJS](https://github.com/brix/crypto-js) for encryption.  
- Minified version: `assets/js/crypto-js.min.js`  
- Source version: [GitHub Repo](https://github.com/brix/crypto-js)

== License ==

This plugin is licensed under the GPLv2 or later.  
You are free to use, modify, and distribute it under the same license.
