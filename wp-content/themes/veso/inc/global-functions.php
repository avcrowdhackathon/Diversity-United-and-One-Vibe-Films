<?php
add_action( 'after_setup_theme', 'veso_theme_setup' );

function veso_theme_setup() {
	register_nav_menus( array(
		'top'    => esc_html__('Main Menu', 'veso' ),
	));

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array(
		'gallery',
		'caption',
	) );
	add_theme_support( 'post-formats', array(
		'image', 'gallery', 'quote', 'link', 'video', 'audio'
	) );
	add_theme_support( 'automatic-feed-links' );
	add_image_size( 'veso-food', 900, 680, true );
	add_image_size( 'veso-fullscreen', 1920, 1080, true ); 
	if ( ! isset( $content_width ) ) $content_width = 810;
	
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	load_theme_textdomain( 'veso', get_template_directory() . '/languages' );
}


add_action( 'vc_before_init', 'veso_vcSetAsTheme' );

function veso_vcSetAsTheme() {
	$list = array(
		'page',
		'veso_footer',
		'veso_portfolio'
	);
	vc_set_default_editor_post_types( $list );
	vc_set_as_theme();
}


function veso_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'veso' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'veso' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title"><span>',
		'after_title'   => '</span></h5>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Woocommerce Sidebar', 'veso' ),
		'id'            => 'shop',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'veso' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title"><span>',
		'after_title'   => '</span></h5>',
	) );
}
add_action( 'widgets_init', 'veso_widgets_init' );

function veso_custom_excerpt_length($length) {
	return 30;
}
add_filter('excerpt_length', 'veso_custom_excerpt_length');

function veso_add_body_class($classes) {
	if(class_exists('VESO_THEME_PLUGIN')){
		$classes[] = 'plugin-on';
	} else {
		$classes[] = 'plugin-off';
	}
	return $classes;
}
add_filter( 'body_class', 'veso_add_body_class');


function veso_get_nav_socials() {
	$social_list = '';
	if(function_exists('get_field')) {
		if(get_field('veso_navigation_social_profiles', 'option') == '1') {	
			if( have_rows('veso_navigation_social_profiles_list', 'option') ) {
				while( have_rows('veso_navigation_social_profiles_list', 'option') ) {
					the_row();
					$social_name = get_sub_field('social_profile_class');
					$social_url = get_sub_field('social_profile_url');
					$social_color = get_sub_field('social_icon_hover_color');
					$social_profile[] = array('name'=>$social_name, 'url'=>$social_url, 'color'=>$social_color);
				}
				if($social_profile && is_array($social_profile)) {
					foreach($social_profile as $profile) {
						$social_list .= '<li class="profile"><a href="'.esc_url($profile['url']).'" target="_blank" data-color="'.esc_attr($profile['color']).'"><i class="fa '.esc_attr($profile['name']).'"></i></a></li>';
					}
				}
			}
			if($social_list != '') {
				$social_list = '<li class="nav-social-profiles"><ul>'.$social_list.'</ul></li>';
			}
		}
	}
	return $social_list;
}

function veso_formats_button($buttons) {
	array_unshift($buttons, 'styleselect');

	return $buttons;
}

add_filter('mce_buttons', 'veso_formats_button');

function veso_mce_before_init_insert_formats($init_array) {

	$style_formats = array(  
		array(  
			'title' => 'Font weight 100',  
			'inline' => 'span',  
			'styles' => array('font-weight' => '100'),
		),
		array(  
			'title' => 'Font weight 200',  
			'inline' => 'span',  
			'styles' => array('font-weight' => '200'),
		),
		array(  
			'title' => 'Font weight 300',  
			'inline' => 'span',  
			'styles' => array('font-weight' => '300'),
		),
		array(  
			'title' => 'Font weight 400',  
			'inline' => 'span',  
			'styles' => array('font-weight' => '400'),
		),
		array(  
			'title' => 'Font weight 500',  
			'inline' => 'span',  
			'styles' => array('font-weight' => '500'),
		),
		array(  
			'title' => 'Font weight 600',  
			'inline' => 'span',  
			'styles' => array('font-weight' => '600'),
		),
		array(  
			'title' => 'Font weight 700',  
			'inline' => 'span',
			'styles' => array('font-weight' => '700'),
		),
		array(  
			'title' => 'Font weight 800',  
			'inline' => 'span',
			'styles' => array('font-weight' => '800'),
		),
		array(  
			'title' => 'Font weight 900',  
			'inline' => 'span',  
			'styles' => array('font-weight' => '900'),
		),
	);
	$init_array['style_formats'] = json_encode( $style_formats );  
	$sizes = '';
	for($size = 10; $size <= 160; $size+=2) {
		$sizes .= ' '.$size.'px';
	}
	$init_array['fontsizeselect'] = true;
	$init_array['fontsize_formats'] = $sizes;
	return $init_array;
}

add_filter('tiny_mce_before_init', 'veso_mce_before_init_insert_formats');

if ( ! function_exists( 'veso_mce_buttons' ) ) {
	function veso_mce_buttons( $buttons ) {
		array_push( $buttons, 'fontsizeselect' );
		return $buttons;
	}
}
add_filter( 'mce_buttons_2', 'veso_mce_buttons' );

add_action('admin_init', 'veso_add_button');

function veso_add_button() {
	if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
		add_filter('mce_external_plugins', 'veso_add_plugin');
		add_filter('mce_buttons_2', 'veso_register_button');
	}
}
add_filter('mce_buttons_2', 'veso_register_button');
function veso_register_button($buttons) {
	array_push($buttons, "veso");
	return $buttons;
}

function veso_add_plugin($plugin_array) {
	$plugin_array['veso'] = get_template_directory_uri(). '/inc/tinymce/shortcodes.js';
	return $plugin_array;
}

function veso_mobile_lang_switcher() {
	$languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );
	if ( $languages ) {
 
		if(!empty($languages)){
 			echo '<ul class="lng">';
			foreach($languages as $l){
				if(!$l['active']){
					echo '<li><a href="'.esc_url($l['url']).'">'.esc_html($l['language_code']).'</a></li> ';
				}
			}
			echo '</ul>';
		}
	}
}

function veso_image_preloader($width, $height) {
	return 'data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D&#039;http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg&#039;%20viewBox%3D&#039;0%200%20'.$width.'%20'.$height.'&#039;%2F%3E';
}

function veso_theme_add_editor_styles() {
    add_editor_style();
}
add_action( 'admin_init', 'veso_theme_add_editor_styles' );

function veso_register_required_plugins() {
	$plugins = array(
		array(
			'name'               => esc_html__('Veso Theme Plugin', 'veso'),
			'slug'               => 'veso-theme-plugin',
			'source'             =>  get_template_directory() . '/inc/plugins/veso-theme-plugin.zip',
			'required'           => true,
			'version'            => '1.0.3',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'is_callable'        => '',
		),

		array(
			'name'               => esc_html__('WPBakery Page Builder', 'veso'),
			'slug'               => 'js_composer',
			'source'             => get_template_directory() . '/inc/plugins/js_composer.zip',
			'required'           => false,
			'version'            => '5.4.7',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'is_callable'        => '',
		),
		array(
			'name'               => esc_html__('Slider Revolution', 'veso'),
			'slug'               => 'revslider',
			'source'             => get_template_directory() . '/inc/plugins/revslider.zip',
			'required'           => false,
			'version'            => '5.4.7.2',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'is_callable'        => '',
		),
	);

	$config = array(
		'id'           => 'veso',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => '',
	);

	tgmpa( $plugins, $config );
}
add_action('tgmpa_register', 'veso_register_required_plugins');

function validate_colors($gradient_color) {
	$gradient_color = preg_replace('/\s+/', '', $gradient_color);
	$gradient_color = explode(',', $gradient_color);
	$output_color = array();
	foreach ($gradient_color as $value)
	{
	    preg_match('/(#[a-f0-9]{3}([a-f0-9]{3})?)/i', $value, $matches);
	    if (isset($matches[1]))
	    {
	        $output_color[] = $matches[1];
	    }
	}
	$gradient_color = implode(',',$output_color);
	return $gradient_color; 
}