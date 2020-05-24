<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Include the required files & dependencies
 *
 * @since 0.1.0
 */
function _wp_term_images() {

	// Setup the main file
	$plugin_path = plugin_dir_path( __FILE__ );

	// Classes
	require_once $plugin_path . '/includes/class-wp-term-meta-ui.php';
	require_once $plugin_path . '/includes/class-wp-term-images.php';
}
add_action( 'plugins_loaded', '_wp_term_images' );

/**
 * Instantiate the main class
 *
 * @since 0.2.0
 */
function _wp_term_images_init() {
	new WP_Term_Images( __FILE__ );
}
add_action( 'init', '_wp_term_images_init', 88 );
