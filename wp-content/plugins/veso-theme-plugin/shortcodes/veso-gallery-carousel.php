<?php

class Veso_Gallery_Carousel {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_gallery_carousel', array($this, 'veso_shortcode' ));
	}

	
	function veso_shortcode( $atts , $content ) {
		extract( shortcode_atts( array(
			'images' => '',
			'height_options' => 'false',
			'custom_height' => '',
			'bg_hover_color' => '#fff',
			'extra_class' => '',
			'arrows' => '0',
			'pagination' => '1',
			'autoplay' => '0',
		), $atts ) );

		$output = '';
		if($height_options == 'true') {
			$height = $custom_height;
			if(substr($height, -1)=="%") {
				$height = $height - substr($height, -1);
			}
			if(substr($height, -2)=="px") {
				$height = $height - substr($height, -2);
			}
		} else { 
			$height = 'full'; 
		}
		
		$output .='<div class="veso-portfolio swiper-container veso-portfolio-carousel veso-gallery-element '.$extra_class.'" data-height="'.$height.'" data-autoplay="'.$autoplay.'" data-arrows="'.$arrows.'" data-pagination="'.$pagination.'">
				<div class="swiper-wrapper animate-text">';
		if($images != '') {
			$images = explode(',', $images);
		}
		if(is_array($images)) {
			foreach ($images as $image) {
				$image_url = wp_get_attachment_image_src($image, 'full');
				$output .= '<div class="swiper-slide">';
				$output .= '<div class="veso-portfolio-item open-lightbox_images text-on-hover"><a class="portfolio-link" href="'.$image_url[0].'" data-size="'.$image_url[1].'x'.$image_url[2].'"></a><div class="image-wrapper"><div class="portfolio-hover-img" style="background: '.$bg_hover_color.'"></div><img class="b-lazy" height="'.$image_url[2].'" width="'.$image_url[1].'" src="'.veso_image_preloader($image_url[1],$image_url[2]).'" alt="" data-src="'.esc_html($image_url[0]).'"></div></div>';
				$output .= '</div>';
			}
		}
		$output .= '</div>';
		if($pagination == "1") {
			$output .= '<div class="veso-gallery-pagination"></div>';
		}
		if($arrows == '1') {
			$output .= '<div class="veso-gallery-arrows show-for-large swiper-arrows">
					<div class="arrow-prev arrow">
						<div class="arrow-icon"><i class="fa fa-angle-left text-color"></i></div>
					</div>
					<div class="arrow-next arrow">
						<div class="arrow-icon"><i class="fa fa-angle-right text-color"></i></div>
					</div>
				</div>';
		}

		$output .= '</div>';
		return $output;
	}

	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Gallery Carousel", "veso-theme-plugin" ),
			"base" => "veso_gallery_carousel",
			"description" => __('Add a gallery', 'veso-theme-plugin'),
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "attach_images",
					"heading" => __("Images", "veso-theme-plugin"),
					"param_name" => "images",
					"admin_label" => false,
					"description" => __("Please images to display in gallery", "veso-theme-plugin")
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Carousel height", "veso-theme-plugin" ),
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
					"heading" => __( "Add custom height in pixels", "veso-theme-plugin" ),
					"param_name" => "custom_height",
					'value' => "",
					"dependency" => array("element" => "height_options","value" => 'true'),		
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Show arrows", 'veso-theme-plugin'),
					"param_name" => "arrows",	
					"value" => array(
						'on' => '1',
					),
					'std' => '0',
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Show pagination", 'veso-theme-plugin'),
					"param_name" => "pagination",	
					"value" => array(
						'on' => '1',
					),
					'std' => '1',
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Autoplay", 'veso-theme-plugin'),
					"param_name" => "autoplay",	
					"value" => array(
						'on' => '1',
					),
					'std' => '0',
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Hover background color", 'veso-theme-plugin'),
					"param_name" => "bg_hover_color",		
					"value" => "#fff",
					'description' => __( 'Select background color for hover.', 'veso-theme-plugin' ),	
				),
				array(
					"type" => "textfield",
					"heading" => __("Extra class", "veso-theme-plugin"),
					"param_name" => "extra_class",
					"admin_label" => false,
				),
			),
		));
	}

}
