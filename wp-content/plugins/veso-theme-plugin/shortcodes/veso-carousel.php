<?php

class Veso_Carousel {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_carousel', array($this, 'veso_shortcode_container' ));
		add_shortcode( 'veso_carousel_item', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode_container( $atts , $content ) {
		extract( shortcode_atts( array(
			'slides' => '4',
			'loop_slide' 	  => 'yes',
			'autoplay' 		  => '3000',
			'arrow_color' 	  => '',
			'active_color' 	  => '',
			'show_arrows' 	  => 'false',
			'show_dots' 	  => 'false',
			'el_class' => '',
			'css' 	  => '',
		), $atts ) );
		$selector = '';

		$id = 'id_'.uniqid(true).mt_rand();		

		$loop_slide = $loop_slide == 'yes' ? 'true' : 'false';
		$padding = '';
		if($show_arrows == 'true') {
			$padding = 'padding: 0 60px';
		}

		$output = '<div class="veso-carousel-container '.$id.' ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$el_class.'"><div class="veso-carousel swiper-container " data-autoplay="'.$autoplay.'" data-arrows="'.$show_arrows.'" data-slide-loop="'.$loop_slide.'" data-slides="'.$slides.'"><div class="swiper-wrapper">';
		$output .= do_shortcode( $content );		
		$output .= '</div>';
		
		if($show_arrows == 'true') {
			$output .= '<div class="veso-carousel-arrows show-for-large swiper-arrows" style="'.$padding.';">
					<div class="arrow-prev arrow">
						<div class="arrow-icon"><i class="fa fa-angle-left text-color" style="color: '.$arrow_color.'"></i></div>
					</div>
					<div class="arrow-next arrow">
						<div class="arrow-icon"><i class="fa fa-angle-right text-color" style="color: '.$arrow_color.'"></i></div>
					</div>
				</div>';
		}
		$output .= '</div>';
		if($show_dots == "true") { 
			$output .= '<div class="veso-carousel-pagination"></div>';
		}
		$output .= '</div>';
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .swiper-pagination-bullet, .'.$id.' .veso-carousel-arrows .arrow .arrow-icon { background-color: '.$arrow_color.'; } .'.$id.' .veso-carousel-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active { background-color: '.$active_color.'}"></div>';
		
		return $output; 

	}
	function veso_shortcode( $atts , $content ) {
		extract( shortcode_atts( array(
			'image' => '',
			'img_pos' => 'text-center',
			'img_width' => '',
			'img_circle' => '',
		), $atts ) );
		$image = wp_get_attachment_image_src( $image, 'full');
		if(is_array($image) && isset($image[0])) {
			$image = $image[0];
		}
		$id = 'id_'.uniqid(true).mt_rand();		
		if($content !== '') {
			$content = '<div class="item-desc">'.wpautop($content).'</div>';
		}
		$margin = $border_radius = '';
		if($image !== '' && $content !== '') {
			$margin = 'margin-bottom: 30px';
		}
		if($img_circle == 'yes') {
			$border_radius = 'border-radius: 50%;';
		}
		if(isset($image)) {
			$img = '<img src="'.$image.'" alt="team-member-img" style="'.$border_radius.' width: '.$img_width.'"/></a>';
		} else {
			$img = '';
		}

		$output = '<div class="veso-carousel-item swiper-slide '.$id.'">
					<div class="slide">';
					if(isset($image)) {
						$output .= '<div class="veso-carousel-img '.$img_pos.'" style="'.$margin.'">
							<span class="img-wrapper">
								'.$img.'
							</span>
						</div>';
					}
						
		$output .=	'<div class="veso-carousel-desc">
							'.apply_filters('the_content', $content).'
						</div>
					</div>
				</div> ';

		return $output; 

	}
	
	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Carousel", "veso-theme-plugin" ),
			"base" => "veso_carousel",
			"as_parent" => array( "only" => "veso_carousel_item" ),
			"class" => "",
			"content_element" 		  => true,
			"show_settings_on_create" => true,
			"js_view"				  => "VcColumnView",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					'type' 		  => 'dropdown',
					'heading' 	  => __( 'Slides per view', 'veso-theme-plugin' ),
					'param_name'  => 'slides',
					'value' 	  => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
					),
					'std' => '4',
					'description' => __( 'Show prev/next control arrows.', 'veso-theme-plugin' )
				),
				array(
					'type' 		  => 'checkbox',
					'heading' 	  => __( 'Slider loop', 'veso-theme-plugin' ),
					'param_name'  => 'loop_slide',
					'description' => __( 'Enables loop mode.', 'veso-theme-plugin' ),
					'value' 	  => array( __( 'Yes, please', 'veso-theme-plugin' ) => 'yes' ),
					'std' => 'yes',
				),
				array(
					'type' 		  => 'textfield',
					'heading' 	  => __( 'Autoplay', 'veso-theme-plugin' ),
					'param_name'  => 'autoplay',
					'value' 	  => '3000',
					'description' => __( 'Delay between transitions (in ms). If this parameter is not specified, auto play will be disabled.', 'veso-theme-plugin' )
				),
				array(
					'type' 		  => 'dropdown',
					'heading' 	  => __( 'Show prev/next arrows', 'veso-theme-plugin' ),
					'param_name'  => 'show_arrows',
					'value' 	  => array(
						__( 'On', 'veso-theme-plugin' )  => 'true',
						__( 'Off', 'veso-theme-plugin' ) => 'false',
					),
					'std' => 'false',
					'description' => __( 'Show prev/next control arrows.', 'veso-theme-plugin' )
				),
				array(
					'type' 		 => 'dropdown',
					'heading' 	 => __( 'Show dots', 'veso-theme-plugin' ),
					'param_name' => 'show_dots',
					'value' 	  => array(
						__( 'On', 'veso-theme-plugin' )  => 'true',
						__( 'Off', 'veso-theme-plugin' ) => 'false',
					),
					'std' => 'false',
					'description' => __( 'Show pagination.', 'veso-theme-plugin' )
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Inactive arrows/pagination color", 'veso-theme-plugin'),
					"param_name" => "arrow_color",	
					"value" => "",	
					'description' => __( 'Select color for arrows and pagination dots.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Active pagination color", 'veso-theme-plugin'),
					"param_name" => "active_color",	
					"value" => "",	
					'description' => __( 'Select color for active pagination.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin'),	
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
			"name"      	  => __( "Veso Carousel Item", "veso-theme-plugin" ),
			"base"      	  => "veso_carousel_item",
			"content_element" => true,
			"as_child"     	  => array( "only" => "veso_carousel" ),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params"     	  => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Add image", "veso-theme-plugin" ),
					"param_name" => "image",
					"value" => '',
				),
				array(
					'type' 		  => 'dropdown',
					'heading' 	  => __( 'Image position', 'veso-theme-plugin' ),
					'param_name'  => 'img_pos',
					'value' 	  => array(
						__( 'Left', 'veso-theme-plugin' )  => 'text-left',
						__( 'Center', 'veso-theme-plugin' ) => 'text-center',
						__( 'Right', 'veso-theme-plugin' ) => 'text-right',
					),
					'std' => 'text-center',
					'description' => __( 'Show prev/next control arrows.', 'veso-theme-plugin' )
				),
				array(
					'type' 		  => 'checkbox',
					'heading' 	  => __( 'Circle image', 'veso-theme-plugin' ),
					'param_name'  => 'img_circle',
					'description' => __( 'Enables circle image.', 'veso-theme-plugin' ),
					'value' 	  => array( __( 'Yes, please', 'veso-theme-plugin' ) => 'yes' ),
					'std' => '',
				),
				array(
					'type' 		  => 'textfield',
					'heading' 	  => __( 'Image width', 'veso-theme-plugin' ),
					'param_name'  => 'img_width',
					'value' 	  => '',
					'description' => __( 'Insert image width in pixels or percent', 'veso-theme-plugin' )
				),
				array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __( "Content", "veso-theme-plugin" ),
					"param_name" => "content",
					"admin_label" => true,
					"value" => '',
				),
			),
		));
	}

}
