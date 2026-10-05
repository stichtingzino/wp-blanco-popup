<?php
/**
 * Plugin Name: WP Blanco Popup
 * Description: Toont specifieke pagina's zonder header en footer in een strak pop-up venster via een URL-parameter of shortcode.
 * Version:     1.1.0
 * Author:      Mark Blom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Helperfunctie om dynamisch de actuele pluginversie op te halen uit de header
function wp_blanco_popup_get_version() {
    if ( ! function_exists( 'get_plugin_data' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data = get_plugin_data( __FILE__ );
    return $plugin_data['Version'];
}

// 1. Schakel de standaard header en footer uit als ?popup=true actief is
add_action('get_header', 'wp_blanco_popup_check');
function wp_blanco_popup_check() {
    if ( isset($_GET['popup']) && $_GET['popup'] == 'true' ) {
        remove_all_actions('wp_head'); 
        add_action('wp_head', 'wp_enqueue_scripts'); 
        
        echo '<!DOCTYPE html><html ' . get_language_attributes() . '><head><meta charset="' . get_bloginfo( 'charset' ) . '"><meta name="viewport" content="width=device-width, initial-scale=1">';
        wp_head();
        echo '<style>body { margin: 0; padding: 20px; background: #fff; }</style></head><body ' . implode(' ', get_body_class()) . '>';
        
        add_action('get_footer', function() {
            wp_footer();
            echo '</body></html>';
            exit; 
        });
    }
}

// 2. Laad het universele JavaScript-bestand met de dynamische versie
add_action('wp_enqueue_scripts', 'wp_blanco_popup_scripts');
function wp_blanco_popup_scripts() {
    wp_enqueue_script(
        'wp-blanco-popup-js', 
        plugin_dir_url( __FILE__ ) . 'js/popup.js', 
        array(), 
        wp_blanco_popup_get_version(), // Haalt de versie direct uit de plugin-header bovenin!
        true
    );
}

// 3. Registreer de [mb_popup] shortcode met 800x600 als default
add_shortcode('mb_popup', 'wp_blanco_popup_shortcode');
function wp_blanco_popup_shortcode($atts, $content = null) {
    $attributes = shortcode_atts(array(
        'src' => '',
        'width' => 800,  // Standaard breedte is nu 800
        'height' => 600, // Standaard hoogte is nu 600
    ), $atts);

    if (empty($attributes['src'])) {
        return $content;
    }

    // Genereer de HTML-link die de JavaScript-functie aanroept
    $output = sprintf(
        '<a href="%s" onclick="openWordPressPopup(event, this.href, %d, %d);">%s</a>',
        esc_url($attributes['src']),
        intval($attributes['width']),
        intval($attributes['height']),
        esc_html($content)
    );

    return $output;
}
