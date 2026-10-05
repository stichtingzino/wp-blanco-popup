<?php
/**
 * Plugin Name: WP Blanco Popup
 * Description: Toont specifieke pagina's zonder header en footer in een strak pop-up venster via een URL-parameter.
 * Version:     1.0.0
 * Author:      Mark Blom
 */

// Sla direct de toegang af als het bestand rechtstreeks wordt aangeroepen
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 1. Schakel de standaard header en footer uit als ?popup=true actief is
add_action('get_header', 'wp_blanco_popup_check');
function wp_blanco_popup_check() {
    if ( isset($_GET['popup']) && $_GET['popup'] == 'true' ) {
        
        // We overschrijven de header met een minimale HTML-opzet
        remove_all_actions('wp_head'); // Optioneel: schoonmaken van overbodige header rommel
        add_action('wp_head', 'wp_enqueue_scripts'); // Zorg dat scripts wel laden
        
        echo '<!DOCTYPE html><html ' . get_language_attributes() . '><head><meta charset="' . get_bloginfo( 'charset' ) . '"><meta name="viewport" content="width=device-width, initial-scale=1">';
        wp_head();
        echo '<style>body { margin: 0; padding: 20px; background: #fff; }</style></head><body ' . implode(' ', get_body_class()) . '>';
        
        // Zodra de pagina-inhoud klaar is, sluiten we de body netjes af en stoppen we het laden van het thema
        add_action('get_footer', function() {
            wp_footer();
            echo '</body></html>';
            exit; 
        });
    }
}

// 2. Laad het universele JavaScript-bestand in de website
add_action('wp_enqueue_scripts', 'wp_blanco_popup_scripts');
function wp_blanco_popup_scripts() {
    wp_enqueue_script(
        'wp-blanco-popup-js', 
        plugin_dir_url( __FILE__ ) . 'js/popup.js', 
        array(), 
        '1.0.0', 
        true
    );
}
