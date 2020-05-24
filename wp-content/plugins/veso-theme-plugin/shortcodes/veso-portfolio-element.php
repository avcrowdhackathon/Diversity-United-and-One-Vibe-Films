<?php

class Veso_Portfolio_Element {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_portfolio_element', array($this, 'veso_shortcode' ));
	}

	public function veso_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'portfolio_id' => '',
			'style' => false,
			'hover_type' => 'hover1',
			'bg_hover_color' => '',
			'bg_hover_color1' => '',
			'hover_color' => '',
			'hover_color1' => '',
			'accent_color' => '',
			'open_portfolio' => 'lightbox',
			'header_size' => '3'
		), $atts ) );
		$output = '';
		$id = 'id_'.uniqid(true).mt_rand();		
		$post = get_post($portfolio_id);
		$output .= '<div class="'.$id.' animate-text">';
		if($post instanceof WP_Post) {
			if($post->post_type == 'veso_portfolio') {
				$image = get_the_post_thumbnail_url($portfolio_id, 'full');
				$output .= veso_get_portfolio_markup($image, false, $hover_type, $style, $post, $open_portfolio);
			}
		}
		$output .= '</div>';
		if($hover_type == 'hover4') {
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .portfolio-hover-img { background: '.$bg_hover_color.'; } .'.$id.' .veso-portfolio-item.text-on-hover .portfolio-text { color: '.$hover_color.' !important; } .'.$id.' .veso-portfolio-item.text-below:hover .portfolio-text { color: '.$hover_color.' !important; background: '.$bg_hover_color.'}"></div>';
		} else {
			$output .= '<div class="custom-styles" data-styles=".'.$id.' .veso-portfolio-item .portfolio-text { background-color: '.$bg_hover_color1.' !important; } .'.$id.' .veso-portfolio-item .portfolio-text { color: '.$hover_color1.' } .'.$id.' .veso-portfolio-item.text-below.hover3 .image-wrapper:after { background-color: '.$accent_color.'} .'.$id.' .veso-portfolio-item.text-on-hover.hover3 .portfolio-text { background-color: '.$bg_hover_color1.'; }"></div>';
		} 
		return $output; 
	}

	public function veso_map_vc_params() {
		$args = array( 'post_type' => 'veso_portfolio');

		vc_map(array(
			"name" => __("Veso Single Portfolio Item", "veso-theme-plugin"),
			"base" => "veso_portfolio_element",
			"class" => "",
			"description" => __('Add Portfolio item', 'veso-theme-plugin'),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"category" => __( "Content", "veso-theme-plugin"),
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Insert portfolio id", "veso-theme-plugin" ),
					"param_name" => "portfolio_id",
					"value" => ""
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Show title & categories", "veso-theme-plugin" ),
					"param_name" => 'style',
					"value" => array(
						__('below image', 'veso-theme-plugin') => false,
						__('on hover', 'veso-theme-plugin') => true,
					),
					"std" => false
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select header size", "veso-theme-plugin" ),
					"param_name" => 'header_size',
					"value" => array(
						'H1' => '1',
						'H2' => '2',
						'H3' => '3',
						'H4' => '4',
						'H5' => '5',
					),
					"std" => "3"
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Open portfolio", "veso-theme-plugin" ),
					"param_name" => 'open_portfolio',
					"value" => array(
						__('In lightbox', 'veso-theme-plugin') => 'lightbox',
						__('In portfolio page', 'veso-theme-plugin') => 'portfolio_page',
					),
					"std" => "lightbox"
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select hover type", "veso-theme-plugin" ),
					"param_name" => 'hover_type',
					"value" => array(
						__('Hover 1', 'veso-theme-plugin') => 'hover1',
						// __('Hover 2', 'veso-theme-plugin') => 'hover2',
						__('Hover 2', 'veso-theme-plugin') => 'hover3',
						__('Hover 3', 'veso-theme-plugin') => 'hover4',
						// __('Hover 5', 'veso-theme-plugin') => 'hover5',
					),
					"std" => "hover1"
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_hover_color",		
					"value" => "",
					'description' => __( 'Select background color for 4th hover style. Leave empty to different colors on each portfolios (Notice: set in portfolio item).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => 'hover4'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_hover_color1",		
					"value" => "",
					'description' => __( 'Select background color hover.', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => array('hover1','hover2','hover3','hover5')),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "hover_color",		
					"value" => "",
					'description' => __( 'Select text color for 4th hover style. Leave empty to different colors on each portfolios (Notice: set in portfolio item).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => 'hover4'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "hover_color1",		
					"value" => "",
					'description' => __( 'Select text color hover', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => array('hover1','hover2','hover3','hover5')),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",		
					"value" => "",
				),
			)
		));
	}
}