<?php
/**
 * Veso functions and definitions
 */

require_once( get_template_directory(). '/inc/global-functions.php' );
/** Enqueue scripts */
require_once( get_template_directory(). '/inc/enqueue-scripts.php' );
/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );
require_once( get_template_directory(). '/inc/comments-functions.php' );
require_once( get_template_directory(). '/inc/class-tgm-plugin-activation.php' );
require_once( get_template_directory(). '/inc/woocommerce.php' );

if( function_exists('acf_add_options_page') ) {
	$parent = acf_add_options_page(array(
		'page_title' 	=> esc_html__('Theme Settings', 'veso'),
		'menu_title' 	=> esc_html__('Theme Settings', 'veso'),
		'menu_slug'     => 'acf-options',
		'redirect' 		=> false,
		'autoload'      => true,
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> esc_html__('Typography', 'veso'),
		'menu_title' 	=> esc_html__('Typography', 'veso'),
		'parent_slug' 	=> $parent['menu_slug'],
	));

	if( !defined('VESO_SHOW_ACF') ) {
		add_filter('acf/settings/show_admin', '__return_false');
	}
}