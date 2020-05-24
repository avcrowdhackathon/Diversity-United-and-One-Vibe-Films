<?php 
/**
 * Plugin Name: Veso Theme Plugin
 * Plugin URI: http://yosoftware.com/
 * Description: Custom post types and metaboxes required in theme
 * Version: 1.0.3
 * Author: yosoftware
 * Author URI: http://themeforest.net/user/yosoftware
 * License: Themeforest Split Licence
 */

define('VESO_PLUG_DIR', plugin_dir_path(__FILE__));
define('VESO_PLUG_DIR_URI', plugin_dir_url( __FILE__ ));

include_once 'post-types/class-post-types.php';
include_once 'reveal-ids-for-wp-admin-25/reveal-ids-for-wp-admin-25.php';
include_once 'acf/acf.php';
include_once 'acf-sliders/acf-sliders.php';
include_once 'acf-typography/acf-typography.php';
include_once 'acf-rgba-color-picker/acf-rgba-color-picker.php';
include_once 'settings/veso_acf_settings.php';
include_once 'functions/output_styles.php';
include_once 'functions/typography.php';
include_once 'functions/importer.php';
include_once 'functions/portfolio.php';
include_once 'functions/custom_nav_walker.php';
include_once 'functions/custom_mobile_nav_walker.php';
include_once 'functions/social.php';
include_once 'functions/contact_forms.php';

class Veso_Theme_Plugin {
	private static $instance = null;
	protected $modules = array();

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {
		load_plugin_textdomain( 'veso-theme-plugin', false, dirname( plugin_basename(__FILE__) ) . '/languages/' ); 
		$this->load_modules();
		add_filter('acf/settings/path', array($this, 'veso_acf_settings_path'));
		add_filter('acf/settings/dir', array($this, 'veso_acf_settings_dir'));
		remove_action('wp_head', 'wp_generator');

		add_filter('script_loader_src', array($this, 'veso_remove_script_version'), 15, 1 );
		add_filter('style_loader_src', array($this, 'veso_remove_script_version'), 15, 1 );
		add_action('get_header', array($this, 'veso_remove_admin_login_header'));
		if(get_option('veso-cf7-init') == false) {
			add_action( 'admin_init', array($this, 'check_cf7' ));
		}
		add_action( 'admin_enqueue_scripts', array($this,'admin_enqueue_scipts'), 15);
		add_action('wp_enqueue_scripts', array($this, 'veso_register_inline_scripts'), 15);

		add_filter('acf/rgba_color_picker/palette', array($this, 'veso_color_palette'));
	}
	public function veso_color_palette($test) {
		return array('rgb(0, 0, 0)', 'rgb(255, 255, 255)', 'rgb(221, 51, 51)', 'rgb(221, 153, 51)', 'rgb(238, 238, 34)', 'rgb(129, 215, 66)', 'rgb(30, 115, 190)', 'rgb(130, 36, 227) ');
	}

	public function veso_register_inline_scripts() {
		if(get_field('veso_custom_js', 'option')) {
			$custom_js = get_field('veso_custom_js', 'option');
			wp_add_inline_script('veso-global', $custom_js);
		}
	}

	public function veso_remove_script_version( $src ){
		$parts = explode( '?ver', $src );
		return $parts[0];
	}

	public function load_modules()
	{
		$this->modules[] = new Veso_Post_Types;
		add_action( 'vc_after_init', array($this, 'include_shortcodes') );

		add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style') );
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');

		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}

	public function include_shortcodes() {
		include_once 'shortcodes/class-shortcodes.php';
		$this->modules[] = new Veso_Shortcodes;
	}
	function veso_acf_settings_path( $path ) {
		$path = VESO_PLUG_DIR . '/acf/';
		return $path;	
	}

	function veso_acf_settings_dir( $dir ) {
		$dir = VESO_PLUG_DIR_URI . '/acf/';
		return $dir;
	}

	function veso_remove_admin_login_header() {
		remove_action('wp_head', '_admin_bar_bump_cb');
	}


	function load_custom_wp_admin_style($hook) {
		wp_enqueue_style( 'veso_custom_wp_admin_css', plugins_url('css/admin.css', __FILE__) );
	}
	function admin_enqueue_scipts() {
		wp_enqueue_script( 'veso-plugin-admin-js', plugins_url('js/admin.js', __FILE__), array('jquery'), '1.0.0', true );
	}


	function check_cf7() {
		if(class_exists('WPCF7')) {
			update_option('veso-cf7-init', true);
			veso_contact_form_markup();
			veso_horizontal_contact_form_markup();
		}
	}
}

Veso_Theme_Plugin::get_instance();

function veso_shortcode_btn( $atts, $content ) {
	$atts = shortcode_atts( array('size'=>'', 'color'=>'', 'href'=>'', 'target'=>'',), $atts );
	return '<a href="'.$atts['href'].'" class="btn '.$atts['size'].' '.$atts['color'].'" target="'.$atts['target'].'"><span class="btn-text">' . do_shortcode($content) . '</span></a>';
}
add_shortcode( 'btn', 'veso_shortcode_btn' );

function veso_shortcode_dropcap( $atts, $content ) {
	$atts = shortcode_atts( array('color'=>'', 'style' =>''), $atts );
	if($atts['style'] != '') {
		$style = 'style="color: '.$atts['style'].'"';
	} else {
		$style = '';
	}
	return '<span class="dropcap '.$atts['color'].'" '.$style.'>' . do_shortcode($content) . '</span>';
}
add_shortcode( 'dropcap', 'veso_shortcode_dropcap' );

function veso_shortcode_highlight( $atts, $content ) {
	$atts = shortcode_atts( array('color'=>'', 'style' =>'', 'text-color'=>''), $atts );
	if($atts['style'] != '' || $atts['text-color'] != '') {
		$style = 'style="background-color: '.$atts['style'].'; color: '.$atts['text-color'].'"';
	} else {
		$style = '';
	}
	return '<span class="veso-highlight '.$atts['color'].'" '.$style.'>' . do_shortcode($content) . '</span>';
}
add_shortcode( 'highlight', 'veso_shortcode_highlight' );

function veso_update_cta() {
	$newParamData = array(
		'param_name' => 'use_custom_fonts_h4',
		'edit_field_class' => 'hidden',
	);
	vc_update_shortcode_param( 'vc_cta', $newParamData );
}
add_action('vc_after_init', 'veso_update_cta');