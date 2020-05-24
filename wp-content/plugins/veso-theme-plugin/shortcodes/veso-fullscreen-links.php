<?php

class Veso_Fullscreen_Links {

	function __construct() {
		add_shortcode( 'veso_fullscreen_links', array( $this, 'container_shortcode' ) );
		add_shortcode( 'veso_fullscreen_single_link', array( $this, 'item_shortcode' ) );
		add_action('init', array($this, 'map'));
	}

	function container_shortcode($atts, $content) {
		extract( shortcode_atts( array(
			'align' => 'left',
			'header_size' => '1',
			'max_width' => '',
			'css' => ''
		), $atts ) );
		$output = $max_width_output = '';
		if($max_width != '') {
			$max_width_output = 'max-width: '.$max_width.';';
		}

		$content = str_replace('veso_fullscreen_single_link', 'veso_fullscreen_single_link header_size="'.$header_size.'"', $content);
		
		$output .= '<div class="veso-fullscreen-links veso-fullscreen-links-'.$align.'" >';
		$output .= '<div class="veso-fullscreen-links-inner">';
		$output .='<ul class="veso-fullscreen-links-list" style="'.$max_width_output.'">';
		$output .= do_shortcode( $content ); 
		$output .= '</ul>';
		$output .='<div class="veso-fullscreen-links-images">';
		$output .='<ul></ul>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	function item_shortcode($atts, $content) {
		extract( shortcode_atts( array(
			'title' => '',
			'subtitle' => '',
			'link' => '',
			'image' => '',
			'header_size' => '1',
			'override_header_size' => '',
			'css' => '',
		), $atts ) );
		
		if($override_header_size != '') {
			$header_size = $override_header_size;
		}

		$url = vc_build_link( $link );
		$image = wp_get_attachment_image_src( $image, 'large');
		$image = $image[0];
		$output = '<li class="h'.$header_size.'"><a href="'.esc_url($url['url']).'" target="'.esc_attr($url['target']).'" title="'.esc_attr($url['title']).'"><span class="title">'.$title.'</span><span class="subtitle">'.$subtitle.'</span></a></li><li class="veso-fullscreen-link-image"><div><img src="'.esc_url($image).'"/></div></li>';

		return $output;
	}

	function map() {
		vc_map( array(
			"name" => __( "Veso Fullscreen Links", "veso-theme-plugin" ),
			"base" => "veso_fullscreen_links",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"as_parent" => array('only' => 'veso_fullscreen_single_link'),
			"js_view" => 'VcColumnView',
			'params' => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Items alignment", "veso-theme-plugin" ),
					"param_name" => "align",
					"value" => array(
						'Left' => 'left',
						'Center' => 'center',
						'Right' => 'right',
					),
					'std' => 'left',
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select links size", "veso-theme-plugin" ),
					"param_name" => 'header_size',
					"value" => array(
						'H1' => '1',
						'H2' => '2',
						'H3' => '3',
						'H4' => '4',
						'H5' => '5',
					),
					"std" => "1"
				),
				array(
					"type" => "textfield",
					"heading" => __("Max width (with unit - px or %)", "veso-theme-plugin"),
					"param_name" => "max_width",
					"value" => '',
				),
			),
		));

		vc_map( array(
			"name" => __("Veso Fullscreen Link", "veso-theme-plugin"),
			"base" => "veso_fullscreen_single_link",
			"content_element" => true,
			"as_child" => array('only' => 'veso_fullscreen_links'),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => __("Title", "veso-theme-plugin"),
					"param_name" => "title",
					"admin_label" => true,
				),
				array(
					"type" => "textfield",
					"heading" => __("Subtitle", "veso-theme-plugin"),
					"param_name" => "subtitle",
				),
				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
					'param_name' => 'link',
				),
				array(
					"type" => "attach_image",
					"heading" => __("Image", "veso-theme-plugin"),
					"param_name" => "image",
					"admin_label" => false,
				),
				array(
					"type" => "textfield",
					"heading" => __("Extra class name", "veso-theme-plugin"),
					"param_name" => "el_class",
					"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "veso-theme-pluginn")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Override link size", "veso-theme-plugin" ),
					"param_name" => 'override_header_size',
					"value" => array(
						'As parent container' => '',
						'H1' => '1',
						'H2' => '2',
						'H3' => '3',
						'H4' => '4',
						'H5' => '5',
					),
					"std" => "",
				),
			)
		));
	}

}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_veso_fullscreen_links extends WPBakeryShortCodesContainer {
    }
}