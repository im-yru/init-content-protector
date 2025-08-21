# Init Content Protector — JS Encryption & Anti-Scraping Guard 🔒🛡️

[![Releases](https://img.shields.io/github/v/release/im-yru/init-content-protector?label=Releases&color=blue)](https://github.com/im-yru/init-content-protector/releases)

Protect your site from copying, scraping, and inspection with JS blocking, keyword cloaking, noise injection, and optional AES-256 encryption.

![content protection](https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1400&q=80)

---

Table of contents

- Features
- How it protects content
- Install
- Quick start (Vanilla JS)
- WordPress plugin
- Config options
- API reference
- Examples
- Best practices
- Troubleshooting
- Contribute
- License

---

Features

- JavaScript blocking to stop cursors, selection, and right-click.
- Keyword cloaking to hide sensitive markers in DOM and source.
- Noise injection to add harmless DOM items and random attributes.
- Optional AES-256 content encryption using CryptoJS.
- Small, vanilla JavaScript with no framework required.
- WordPress plugin wrapper for easy install.
- Configurable rules and allowlist for search engines and bots.
- Runtime hooks for custom integration and logging.

Why use this

- Reduce content scraping and mass copy.
- Obscure internal labels and data points to slow automated parsers.
- Add a second layer of protection beyond standard server rules.
- Encrypt critical payloads that must run client-side.
- Keep the code small and fast so it does not hurt page speed.

How it protects content

1. JS blocking
   - The script stops text selection, drag, and context menu. It blocks direct DOM inspection in common flows.
2. Keyword cloaking
   - The script swaps or masks key words or phrases in the DOM. It stores masked values in attributes and restores them at render time.
3. Noise injection
   - The script adds DOM nodes and attributes that look like real content. Scrapers follow noise and waste resources.
4. Full encryption (optional)
   - For high-value pages, the script encrypts selected blocks with AES-256. The client decrypts them at runtime with a transient key.
5. Allowlist and rules
   - You can let search bots or trusted agents bypass protection via rules and signatures.

Install

- Use the Releases page to get packaged builds:
  https://github.com/im-yru/init-content-protector/releases — the release file must be downloaded and executed.
- The package contains:
  - dist/init-content-protector.min.js
  - wp-plugin/init-content-protector.zip
  - docs and examples

If the Releases link does not work, check the "Releases" section of this repository for builds and archives.

Quick start — Vanilla JavaScript

1. Add the script to your page header or before closing body.

```html
<script src="/path/to/init-content-protector.min.js"></script>
<script>
  const protector = new ICP({
    mode: "hybrid",         // block, cloaking, hybrid, encrypt
    allowlist: ["Googlebot"],
    keywords: ["price", "email"],
    injectNoise: true,
    encryption: {
      enabled: false,
      key: null            // or set per-page key
    }
  });

  protector.init();
</script>
```

2. Wrap content you want protected

```html
<article data-icp-protect="true">
  <h1>Premium Article</h1>
  <p data-icp-key="email">contact@example.com</p>
</article>
```

3. The script runs at DOM ready. It cloaks keywords, blocks selection, and injects noise. If encryption is enabled, it decrypts blocks in memory once the key is provided.

WordPress plugin

- Locate the WP plugin zip in Releases.
- Upload via the WP admin plugins page or place the `init-content-protector` folder into `wp-content/plugins/`.
- Activate the plugin.
- Configure under Settings → Init Content Protector.

Plugin features

- Per-post protection toggle.
- Global allowlist for bots.
- Per-role exclusion.
- Auto-encrypt selected blocks with a post meta key.
- Shortcode support: [icp_protect]...[/icp_protect]

Config options

- mode: "block" | "cloak" | "hybrid" | "encrypt"
- allowlist: array of strings or regexes
- keywords: array of strings or regexes
- injectNoise: boolean
- noiseDensity: number (0-1)
- encryption.enabled: boolean
- encryption.key: string | null
- encryption.algorithm: "AES-256-CBC" (default)
- hooks: { onProtect, onRestore, onDecrypt }

Example config

```js
{
  mode: "encrypt",
  allowlist: ["Googlebot", /^bingbot/],
  keywords: ["email", "price", "serial"],
  injectNoise: true,
  noiseDensity: 0.25,
  encryption: {
    enabled: true,
    key: "per-page-key-string"
  },
  hooks: {
    onProtect: (node) => { /* custom log */ },
    onDecrypt: (node) => { /* audit */ }
  }
}
```

API reference

- Constructor: new ICP(config)
  - Returns an instance with methods below.

- protector.init()
  - Start protection. Attach handlers and run transforms.

- protector.protectNode(node)
  - Protect a single DOM node on demand.

- protector.unprotectNode(node)
  - Restore original node content.

- protector.encryptNode(node, key)
  - Encrypt DOM node content with AES-256.

- protector.decryptNode(node, key)
  - Decrypt DOM node content.

- protector.addKeyword(word)
  - Add a keyword to cloaking list.

- protector.removeKeyword(word)
  - Remove from cloaking list.

- protector.setAllowlist(list)
  - Replace allowlist.

Encryption notes

- The library uses CryptoJS for AES-256. The encrypted payload stores IV and ciphertext in base64.
- Use a short-lived key for pages that decrypt in the browser.
- Avoid exposing long-term private keys in client code.
- Provide server-side rotation and per-page salts for better protection.

Examples

1. Simple keyword cloaking

```js
const p = new ICP({ mode: "cloak", keywords: ["email"] });
p.init();
```

2. AES-256 protected block

```html
<div id="secret" data-icp-protect="true" data-icp-encrypted="true">
  U2FsdGVkX19...  <!-- base64 cipher -->
</div>

<script>
  const p = new ICP({ encryption: { enabled: true } });
  p.init();
  // Server provides one-time key via secure endpoint
  fetch("/api/one-time-key")
    .then(r => r.text())
    .then(key => p.decryptNode(document.getElementById("secret"), key));
</script>
```

3. WordPress shortcode

Use shortcode to mark content for protection:

[icp_protect mode="encrypt" key="post-key"]Hidden content here[/icp_protect]

Best practices

- Keep critical keys off public code.
- Combine server-side bot rules with client-side protection.
- Use allowlist rules to avoid SEO impact.
- Use noise injection sparingly to avoid DOM bloat.
- Test the site with major crawlers after enabling protection.

Performance

- The script uses batched DOM operations.
- Noise injection has a density control to limit nodes added per second.
- Encryption runs in Web Worker if available to avoid main thread blocking.

Security model

- This tool raises the cost for automated scraping.
- It does not replace server-side access controls or DRM.
- Use it as part of a layered defense with rate limits, CAPTCHAs, and server rules.

Troubleshooting

- If content does not decrypt, check the console for decryption errors.
- If a crawler fails to index protected pages, add it to the allowlist.
- If layout shifts appear, lower noiseDensity and check CSS selectors.

Releases and download

- Visit the Releases page to get release archives, builds, and plugin zips.
- Release downloads: https://github.com/im-yru/init-content-protector/releases — the release file must be downloaded and executed.
- If the link does not open, use the Releases tab on this repository.

Contribute

- Fork the project.
- Create a feature branch.
- Add tests and docs for your feature.
- Open a pull request with a clear description.

Issue policy

- Open issues with clear steps to reproduce.
- Attach environment details and browser versions.
- Include minimal reproduction code when possible.

Maintainers

- im-yru and contributors list are in the repository.

Related topics

- aes-256
- anti-copy
- anti-scraping
- content-protection
- copy-protection
- cryptojs
- encryption
- javascript-protection
- scraping-protection
- vanilla-javascript
- wordpress-plugin

Badges

[![License](https://img.shields.io/github/license/im-yru/init-content-protector)](https://github.com/im-yru/init-content-protector/blob/main/LICENSE)
[![Top language](https://img.shields.io/github/languages/top/im-yru/init-content-protector)](https://github.com/im-yru/init-content-protector)

Contact and support

- Open issues on GitHub.
- Use Discussions for ideas or custom help.
- For high-value integrations, include a detailed threat profile and expected traffic.

License

- See LICENSE in the repo for full terms.

Images and assets

- UI mockups and icons live in /assets.
- Use the Releases page for prebuilt bundles and zip archives.

End of file