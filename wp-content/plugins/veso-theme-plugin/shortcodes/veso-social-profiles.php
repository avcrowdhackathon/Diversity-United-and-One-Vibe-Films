<?php

class Veso_Social_Profiles {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_social_profiles', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'textalign' => 'text-center',
			'profiles_size' => '16px',
			'text_color' => '',
			'css' => ''
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();
		$social_list = '';
		$profiles = '';
		preg_match("/([0-9]+)([a-zA-Z%]+)/", $profiles_size, $profiles_size_array );
		if ( !empty($profiles_size_array) ) {
			$profiles_size = $profiles_size_array[1];
		}

		if(function_exists('have_rows')) {
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
							$social_list .= '<li class="profile accent-color"><a href="'.esc_url($profile['url']).'" style="font-size: '.$profiles_size.'px" target="_blank" data-color="'.esc_attr($profile['color']).'"><i class="fa '.esc_attr($profile['name']).'" style="color: '.$text_color.'"></i></a></li>';
						}
					}
					$profiles = '<div class="'.$textalign.' animate-text "><ul class="veso-social-profiles ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' ">'.$social_list.'</ul></div>';
				}
			}

		}
		$output = $profiles;

		return $output; 
	}

	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Social Profiles", "veso-theme-plugin" ),
			"base" => "veso_social_profiles",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Social profiles alignment", "veso-theme-plugin" ),
					"param_name" => "textalign",
					"value" => array(
						__( 'Left', 'veso-theme-plugin' ) => 'text-left',
						__( 'Center', 'veso-theme-plugin' ) => 'text-center',
						__( 'Right', 'veso-theme-plugin' ) => 'text-right',
					),
					'std' => 'text-center',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Social profiles size", "veso-theme-plugin" ),
					"param_name" => "profiles_size",
					"value" => '16px',
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Social profiles color", 'veso-theme-plugin'),
					"param_name" => "text_color",		
					"value" => "",
					'description' => __( 'Select color for social profiles.', 'veso-theme-plugin' ),		
				),

				array(
					'type' => 'css_editor',
					'heading' => __( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group' => __( 'Design Options', 'js_composer' ),
				),
			),
		));
	}

}