<?php
/**
 * Load JS and CSS files
 */
function cherrytheme_enqueue_styles()
{
    // Load css files
    wp_enqueue_style('main-css', get_stylesheet_directory_uri() . '/assets/styles/main.css', [], time(), 'all');

    // Load JS files
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/assets/js/main.js', [], 1, true);
}
add_action('wp_enqueue_scripts', 'cherrytheme_enqueue_styles');

// ACF
function cherrytheme_register_acf_blocks()
{
    register_block_type(__DIR__ . '/blocks/testimonial');
}
add_action('init', 'cherrytheme_register_acf_blocks');