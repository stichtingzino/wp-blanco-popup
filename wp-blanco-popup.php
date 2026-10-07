<?php
/**
 * Plugin Name: WP Blanco Popup
 * Description: Generic plugin that displays specific pages without header navigation and footer in a fluid responsive layout via a proxy-safe URL parameter.
 * Version:     1.1.2
 * Author:      Mark Blom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Dynamically retrieves the current plugin version from the file header.
 * 
 * @return string The version number.
 */
function wp_blanco_popup_get_version() {
    if ( ! function_exists( 'get_plugin_data' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data = get_plugin_data( __FILE__ );
    return $plugin_data['Version'];
}

/**
 * Server-side elimination of all navigation elements to prevent them from being rendered or delivered.
 */
if ( isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'popup=true') !== false ) {
    
    // 1. Block Gutenberg/FSE Navigation blocks from rendering (Server-side drop)
    add_filter('pre_render_block', 'wp_blanco_popup_block_navigation_rendering', 10, 2);
    
    // 2. Short-circuit classic menu building to completely bypass core loops and prevent PHP 8.4 warnings
    add_filter('pre_wp_nav_menu', 'wp_blanco_popup_bypass_classic_menu', 10, 2);
}

/**
 * Intercepts core navigation blocks before rendering, returning an empty string to drop the asset.
 *
 * @param string|null $pre_render The pre-rendered block HTML.
 * @param array       $block      The parsed block data.
 * @return string Filtered block output.
 */
function wp_blanco_popup_block_navigation_rendering($pre_render, $block) {
    // Intercept both core navigation and theme-specific custom overlay navigation blocks
    if ( isset($block['blockName']) && ( $block['blockName'] === 'core/navigation' || strpos($block['blockName'], 'navigation') !== false ) ) {
        return ''; // Returning an empty string prevents WordPress from running the render logic
    }
    return $pre_render;
}

/**
 * Short-circuits the classic wp_nav_menu execution loop.
 * Returning a non-null value (like an empty HTML comment) forces WordPress to stop building the menu,
 * preventing any undefined property warnings.
 *
 * @param string|null $output The menu output string. Null by default.
 * @param stdClass    $args   An object containing wp_nav_menu() arguments.
 * @return string Filtered menu output.
 */
function wp_blanco_popup_bypass_classic_menu($output, $args) {
    return '<!-- Classic menu bypassed by WP Blanco Popup plugin -->';
}

/**
 * Intercepts the content loop early to extract raw text blocks, 
 * rendering them inside native Gutenberg core structural wrapper classes.
 */
add_filter('the_content', 'wp_blanco_popup_force_clean_canvas', 1);
function wp_blanco_popup_force_clean_canvas($content) {
    // Proxy-safe validation scanning the raw server request string
    if ( isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'popup=true') !== false ) {
        
        // Prevent infinite nested filtering loops within the content area
        remove_filter('the_content', 'wp_blanco_popup_force_clean_canvas', 1);
        
        // Force output stream cache invalidation headers
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Content-Type: text/html; charset=UTF-8");
        
        // Start building a universal, clean HTML layout structure
        echo '<!DOCTYPE html><html ' . get_language_attributes() . '><head><meta charset="' . get_bloginfo( 'charset' ) . '"><meta name="viewport" content="width=device-width, initial-scale=1">';
        
        // Enqueue essential core script definitions and style assets (necessary for forms)
        wp_head();
        
        // Inject structural boundary constraints to contain the text cleanly within the viewport
        echo '<style>
            html { box-sizing: border-box; }
            *, *:before, *:after { box-sizing: inherit; }
            body { 
                background: #fff !important; 
                padding: 40px 24px !important;
                margin: 0 !important;
            }
            /* Universal fluid container limiting width while preserving responsive auto-wrapping */
            .blanco-popup-content-isolate {
                width: 100% !important;
                max-width: 800px !important;
                margin: 0 auto !important;
                box-sizing: border-box !important;
            }
        </style></head><body ';
        
        // Render native theme body classes to preserve core CSS variable context
        body_class();
        
        echo '>';
        
        // CRUCIAL FSE FIX: Wrap contents within the exact structural wrapper classes ('wp-site-blocks') 
        // that Gutenberg/Otter require to calculate fluid text wrapping and layout column sizes.
        echo '<div class="wp-site-blocks blanco-popup-content-isolate">';
        
        echo $content;
        
        echo '</div>'; // Close structural layout wrapper
        
        // Enqueue vital footer javascript dependencies
        wp_footer();
        
        echo '</body></html>';
        
        // Immediately terminate server execution to ensure the active theme framework 
        // can never mount its global footer.php block pattern layouts.
        exit;
    }
    
    return $content;
}

/**
 * Enqueues the central JavaScript file on the main frontend views.
 */
add_action('wp_enqueue_scripts', 'wp_blanco_popup_scripts');
function wp_blanco_popup_scripts() {
    wp_enqueue_script(
        'wp-blanco-popup-js', 
        plugin_dir_url( __FILE__ ) . 'js/popup.js', 
        array(), 
        wp_blanco_popup_get_version(), 
        false // Loaded in the head so openWordPressPopup is immediately ready on click
    );
}

/**
 * Registers the [mb_popup] shortcode handler.
 */
add_shortcode('mb_popup', 'wp_blanco_popup_shortcode');
function wp_blanco_popup_shortcode($atts, $content = null) {
    $attributes = shortcode_atts(array(
        'src' => '',
        'width' => 800,
        'height' => 600,
    ), $atts);

    if (empty($attributes['src'])) {
        return $content;
    }

    return sprintf(
        '<a href="%s" onclick="openWordPressPopup(event, this.href, %d, %d);">%s</a>',
        esc_url($attributes['src']),
        intval($attributes['width']),
        intval($attributes['height']),
        esc_html($content)
    );
}
