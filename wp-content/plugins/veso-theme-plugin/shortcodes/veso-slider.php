<?php

class Veso_Slider {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_slider', array($this, 'veso_shortcode_container' ));
		add_shortcode( 'veso_slider_item', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode_container( $atts , $content ) {
		extract( shortcode_atts( array(
			'images' => '',
			'height_options' => 'false',
			'custom_height' => '',
			'count_slides' => '4',
			'mobile_height' => '',
			'el_class' => '',
			'css' 	  => '',
		), $atts ) );
		$selector = $height = '';

		$id = 'id_'.uniqid(true).mt_rand();		
		if($height_options == 'false') {
			$height = 'height: 100vh';
		} else {
			if(substr($custom_height, -1)=="%") {
				$custom_height = $custom_height - substr($custom_height, -1);
				$custom_height = $custom_height.'vh';
			}
			$height = 'height: '.$custom_height.'';
		}
		preg_match("/([0-9]+)([a-zA-Z%]+)/", $mobile_height, $mobile_height_array );
		if ( !empty($mobile_height_array) ) {
			$mobile_height = $mobile_height_array[1];
		}
		$output = '<div class="veso-slider-container '.$id.' ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$el_class.'" data-count-slides="'.$count_slides.'">';
		$output .= '<div class="veso-parallax veso-parallax-slider" data-veso-parallax="0"><div class="veso-slider swiper-container"><div class="swiper-wrapper">';
		
		if($images != '') {
			$images = explode(',', $images);
		}
		if(is_array($images)) {
			foreach ($images as $image) {
				$image_url = wp_get_attachment_image_src($image, 'full');
				$output .= '<div class="swiper-slide">';
				$output .= '<div style="background-image: url('.esc_html($image_url[0]).'); '.$height.'"></div>';
				$output .= '</div>';
			}
		}
		$output .= '</div></div></div>';
		$output .= '<div class="slide-nav" style="'.$height.'" data-mobile-height="'.$mobile_height.'">'.do_shortcode( $content ).'</div>';
		$output .= '</div>';
		
		return $output; 

	}
	function veso_shortcode( $atts , $content ) {
		extract( shortcode_atts( array(
			'image' => '',
			'img_view' => '',
			'top_color' => '',
			'bottom_color' => '',
			'overlay_color' => 'rgba(0,0,0,0.6)',
			'show_overlay' => 'overlay',
			'slide_link' => '',
			'text_color' => '#fff',
			'text' => '',
			'title' => ''			
			
		), $atts ) );
		$image = wp_get_attachment_image_src( $image, 'full');
		if(is_array($image) && isset($image[0])) {
			$image = $image[0];
		}
		$id = 'id_'.uniqid(true).mt_rand();		
		
		$style = '';
		if($title != '') {
			$title = '<h2><span '.$style.'>'.$title.'</span></h2>';
		}
		if($text != '') {
			$text = '<p>'.$text.'</p>';
		}
		$overlay_bg = $img_class = '';
		if($img_view == 'yes') {
			$img_class = 'img-hover';
		}
		$url = vc_build_link( $slide_link );
		$target = (isset($url['target']) && $url['target'] !== '') ? "target='". esc_attr( trim($url['target']) ). "'" : '';

		if(isset($image)) {
			$img = '<div class="img-slide '.$img_class.'" style="background-image: url('.$image.');"></div>';
		} else {
			$img = '';
		}
		if($show_overlay == 'overlay') {
			$overlay_bg = '<div class="slide-overlay" style="background-color: '.$overlay_color.';"></div>';
		} else {
			sscanf($top_color, 'rgba(%d,%d,%d,%f)', $r1, $g1, $b1, $alpha1);
			sscanf($bottom_color, 'rgba(%d,%d,%d,%f)', $r2, $g2, $b2, $alpha2);
			$overlay_bg = "<div class='slide-overlay' style='background: -moz-linear-gradient(-45deg, rgba(".$r1.",".$g1.",".$b1.",".$alpha1.") 0%, rgba(".$r2.",".$g2.",".$b2.",".$alpha2.") 100%); background: -webkit-linear-gradient(-45deg, rgba(".$r1.",".$g1.",".$b1.",".$alpha1.") 0%,rgba(".$r2.",".$g2.",".$b2.",".$alpha2.") 100%); background: linear-gradient(135deg, rgba(".$r1.",".$g1.",".$b1.",".$alpha1.") 0%,rgba(".$r2.",".$g2.",".$b2.",".$alpha2.") 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#a6000000',GradientType=0 ); position: absolute; top: 0; left: 0; width: 100%; height: 100%; '></div>";
		}
						
		$output =	'<div class="veso-slide-content" style="color: '.$text_color.'"><a class="animate-text" href="' . esc_url( $url['url'] ) . '" '.$target.'><div class="item-desc"><div class="item-wrapper">'.$title.''.$text.'</div></div>'.$img.''.$overlay_bg.'</a></div>';

		return $output; 

	}
	
	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Slider", "veso-theme-plugin" ),
			"base" => "veso_slider",
			"as_parent" => array( "only" => "veso_slider_item" ),
			"class" => "",
			"content_element" 		  => true,
			"show_settings_on_create" => true,
			"js_view"				  => "VcColumnView",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "attach_images",
					"heading" => __("Images", "veso-theme-plugin"),
					"param_name" => "images",
					"admin_label" => false,
					"description" => __("Please images to display in slider", "veso-theme-plugin")
				),
				array(
					'type' 		 => 'dropdown',
					'heading' 	 => __( 'Slides per view', 'veso-theme-plugin' ),
					'param_name' => 'count_slides',
					'value' 	  => array(
						__( '2', 'veso-theme-plugin' ) => '2',
						__( '3', 'veso-theme-plugin' ) => '3',
						__( '4', 'veso-theme-plugin' ) => '4',
						__( '5', 'veso-theme-plugin' ) => '5',
						__( '6', 'veso-theme-plugin' ) => '6',
					),
					'std' => '4',
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Slider height", "veso-theme-plugin" ),
					"param_name" => 'height_options',
					"value" => array(
						__('full height', 'veso-theme-plugin') => 'false',
						__('custom height', 'veso-theme-plugin') => 'true',
					),
					"std" => 'false'
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Custom height", "veso-theme-plugin" ),
					"param_name" => "custom_height",
					'value' => "",
					'description' => __( 'Add custom height in pixels or vh', 'veso-theme-plugin' ),
					"dependency" => array("element" => "height_options","value" => 'true'),		
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Mobile height of slide", "veso-theme-plugin" ),
					"param_name" => "mobile_height",
					'value' => "",
					'description' => __( 'Add mobile height in pixels', 'veso-theme-plugin' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'veso-theme-plugin' ),
					'param_name' => 'el_class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'veso-theme-plugin' ),
				),
				array(
					'type' => 'css_editor', 
					'heading' => __( 'CSS box', 'veso-theme-plugin' ),
					'param_name' => 'css',
					'group' => __( 'Design Options', 'veso-theme-plugin' ),
				),
			),
		));
		vc_map( array(
			"name"      	  => __( "Veso Section Slider", "veso-theme-plugin" ),
			"base"      	  => "veso_slider_item",
			"content_element" => true,
			"as_child"     	  => array( "only" => "veso_slider" ),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params"     	  => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Add image", "veso-theme-plugin" ),
					"param_name" => "image",
					'description' => __( 'Show image on hover.', 'veso-theme-plugin' ),
					"value" => '',
				),
				array(
					'type' 		 => 'checkbox',
					'heading' 	 => __( 'Show image only on hover', 'veso-theme-plugin' ),
					'param_name' => 'img_view',
					'value' 	  => array( __( 'Yes, please', 'veso-theme-plugin' ) => 'yes' ),
					'std' => '',
					'description' => __( 'Note: images will be shown on mobile devices', 'veso-theme-plugin' ),
					"dependency" => array("element" => "image","not_empty" => true),		
				),
				array(
					"type" => "textarea",
					"class" => "",
					"heading" => __( "Title", "veso-theme-plugin" ),
					"param_name" => "title",
					'value' => "",
					'description' => __( 'Add title to slide', 'veso-theme-plugin' ),
				),
				array(
					"type" => "textarea",
					"class" => "",
					"heading" => __( "Second line", "veso-theme-plugin" ),
					"param_name" => "text",
					'value' => "",
					'description' => __( 'Add content to slide', 'veso-theme-plugin' ),
				),
				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
					'param_name' => 'slide_link',
					'description' => __( 'Add link to button.', 'veso-theme-plugin' ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "text_color",	
					"value" => "#fff",	
					'description' => __( 'Select color for text.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin'),	
				),
				array(
					'type' 		 => 'dropdown',
					'heading' 	 => __( 'Show overlay', 'veso-theme-plugin' ),
					'param_name' => 'show_overlay',
					'value' 	  => array(
						__( 'Standard', 'veso-theme-plugin' )  => 'overlay',
						__( 'Gradient', 'veso-theme-plugin' ) => 'gradient',
					),
					'std' => 'overlay',
					"group" => __('Colors', 'veso-theme-plugin'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Gradient top color", 'veso-theme-plugin'),
					"param_name" => "top_color",	
					"value" => "",	
					'description' => __( 'Select color for gradient top.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin'),	
					'dependency' => array('element' => 'show_overlay', 'value' => 'gradient')
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Gradient bottom color", 'veso-theme-plugin'),
					"param_name" => "bottom_color",	
					"value" => "",	
					'description' => __( 'Select color for gradient bottom.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin'),	
					'dependency' => array('element' => 'show_overlay', 'value' => 'gradient')
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Overlay color", 'veso-theme-plugin'),
					"param_name" => "overlay_color",
					"value" => "rgba(0,0,0,0.6)",	
					'description' => __( 'Select color for arrows and pagination dots.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin'),	
					'dependency' => array('element' => 'show_overlay', 'value' => 'overlay')
				),
			),
		));
	}

}
