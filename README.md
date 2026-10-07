# WP Blanco Popup

A lightweight, high-performance, and generic WordPress plugin designed to isolate any page or post content and display it within a clean, centered popup window. It completely strips away header templates, main navigations, widgets, and footers without breaking the native responsive layout utilities of Full Site Editing (FSE), Classic, or Block themes.

## Features

- **Theme-Agnostic Isolation:** Bypasses theme headers and footers by intercepting the rendering lifecycle safely via the `the_content` filter hierarchy.
- **Server-Side Navigation Strip:** Completely cancels out both Gutenberg FSE navigation blocks (`core/navigation`) and classic navigation walkers (`wp_nav_menu`) on the server level before delivery, preventing unneeded asset leakage and avoiding PHP 8.4 notices.
- **Fluid & Responsive:** Wraps content layout components inside the native Gutenberg `.wp-site-blocks` structural classes to fully preserve CSS Custom Properties, layout contexts, and responsive grid calculations.
- **Proxy-Safe Detection:** Validates environment request sequences directly from the raw `REQUEST_URI` string to prevent parameter-stripping issues common in Docker volumes, Nginx reverse-proxies, or aggressive caching layers.
- **Flexible Deployment:** Features an easy-to-use shortcode `[mb_popup]` alongside dynamic asset versioning anchored to the plugin header state.

## Installation

1. Download or clone this repository into your local WordPress instance:
   ```bash
   cd /var/www/html/wp-content/plugins/
   git clone https://github.com
   ```
2. Ensure your directory structure matches the standard pattern layout:
   ```text
   wp-content/plugins/wp-blanco-popup/
   ├── wp-blanco-popup.php
   └── js/
       └── popup.js
   ```
3. Navigate to your WordPress Admin Dashboard -> **Plugins** -> **Installed Plugins**.
4. Locate **WP Blanco Popup** and click **Activate**.

## Usage

### Using the Shortcode

You can deploy the blank popup link anywhere inside your Gutenberg block areas, classic content editors, or form builders using the native `[mb_popup]` shortcode:

```wordpress
[mb_popup src="/avg-stichting-zino/"]Click here to view our AVG Terms[/mb_popup]
```

### Custom Dimensions

By default, the popup launches within a centered browser window of **800px** width and **600px** height. You can customize these boundaries by passing optional attributes:

```wordpress
[mb_popup src="/avg-stichting-zino/" width="1024" height="768"]Open Large View[/mb_popup]
```

### Manual HTML Anchor

If you need to craft custom hardcoded links within template sections or explicit anchor structures, you can hook the central JavaScript function directly into an `onclick` event attribute. Ensure you always apply a relative pathing pattern or match the active origin structure:

```html
<a href="/avg-stichting-zino/" onclick="openWordPressPopup(event, this.href, 800, 600);">
    Click here to view our AVG Terms
</a>
```

## How It Works Under the Hood

### The JavaScript Interception (`js/popup.js`)
When a user triggers an anchor link, the script halts standard link behavior (`event.preventDefault()`). It calculates the exact center vectors of the visitor's physical screen boundaries and dynamically appends the conditional `popup=true` tracking flag to the destination URL while sanitizing trailing slashes to avert 301 canonical redirect drops.

### The Backend Processing (`wp-blanco-popup.php`)
1. **Short-Circuiting Layouts:** As the request is initialized, the plugin drops classic and modern layout block instances on server compile-time via `pre_render_block` and `pre_wp_nav_menu`.
2. **Buffer Interception:** During `the_content` loop execution, if `popup=true` is isolated out of the raw environment parameters, the plugin flushes downstream cache headers, invokes isolated `wp_head()` configurations, and mounts structural layout nodes.
3. **Execution Termination:** Once post data loops and script handles (`wp_footer()`) finish streaming, a terminal `exit;` function cuts compilation pipelines short, effectively locking out any footer and sidebar template patterns.

## Contribution and Development

### Repository Guidelines

When contributing features or patches to this project, please adhere to our strict branch architecture and continuous delivery practices:

- Always write fully structured and strict documentation code annotations in **English**.
- Do not perform direct testing iterations directly inside main production tags. Maintain semantic code separation and increment metadata version structures within the plugin header only when finalizing stable release builds.
