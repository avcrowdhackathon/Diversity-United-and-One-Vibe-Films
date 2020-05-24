<?php

class Veso_Cascade_Images {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_cascade_images', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'image1' => '',
			'image2' => '',
			'image3' => '',
			'image_pos' => 'style1',
			'hor_img1' => '5%',
			'ver_img1' => '30%',
			'hor_img2' => '-20%',
			'ver_img2' => '-15%',
			'hor_img3' => '30%',
			'ver_img3' => '-35%'
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();
		if($image_pos == 'style1') {
			$hor_img1 = '5%';
			$ver_img1 = '30%';
			$hor_img2 = '-20%';
			$ver_img2 = '-15%';
			$hor_img3 = '30%';
			$ver_img3 = '-35%';
		} elseif($image_pos == 'style2') {
			$hor_img1 = '-50%';
			$ver_img1 = '-40%';
			$hor_img2 = '-20%';
			$ver_img2 = '0%';
			$hor_img3 = '13%';
			$ver_img3 = '40%';
		}

		if($image1 != '') {
			$img1 = wp_get_attachment_image_src($image1, 'large');
			$img1_url = $img1[0];
			$url = $img1_url;
			$image1 = '<div class="img-cascade img-cascade1"><div class="img-wrap"><span class="img" style="transform: translateX('.$hor_img1.') translateY('.$ver_img1.')"><span><img class="b-lazy" alt="" src="'.veso_image_preloader($img1[1],$img1[2]).'" data-src="'.$img1_url.'"/></span></div></div><a class="cascade-lightbox" href="'.$url.'"></a>';
		}
		if($image2 != '') {
			$img2 = wp_get_attachment_image_src($image2, 'large');
			$img2_url = $img2[0];
			$url = $img2_url;
			$image2 = '<div class="img-cascade img-cascade2"><div class="img-wrap"><a class="img" style="transform: translateX('.$hor_img2.') translateY('.$ver_img2.')" href="'.$url.'"><span><img class="b-lazy" alt="" src="'.veso_image_preloader($img2[1],$img2[2]).'" data-src="'.$img2_url.'"/></a></span></div></div>';
		}
		if($image3 != '') {
			$img3 = wp_get_attachment_image_src($image3, 'large');
			$img3_url = $img3[0];
			$url = $img3_url;
			$image3 = '<div class="img-cascade img-cascade3"><div class="img-wrap"><a class="img" style="transform: translateX('.$hor_img3.') translateY('.$ver_img3.')" href="'.$url.'"><span><img class="b-lazy" alt="" src="'.veso_image_preloader($img3[1],$img3[2]).'" data-src="'.$img3_url.'"/></a></span></div></div>';
		}

		$output = '<div class="images-cascade">';
		$output .= $image3;
		$output .= $image2;
		$output .= $image1;
		$output .= '</div>';
		
		return $output;
	}

	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Cascade Images", "veso-theme-plugin" ),
			"base" => "veso_cascade_images",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Add image 1", "veso-theme-plugin" ),
					"param_name" => "image1",
					"value" => '',
					"group" => __('Image 1', 'veso-theme-plugin'),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __("Image position", 'veso-theme-plugin'),
					"param_name" => "image_pos",
					"value" => array(
						__("Style 1", 'veso-theme-plugin') => "style1", 
						__("Style 2", 'veso-theme-plugin') => "style2",
						__("Custom view", 'veso-theme-plugin') => "custom"
					),
					'description' => __( 'Select image position or set custom position', 'veso-theme-plugin' ),
					"group" => __('Image 1', 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Horizontal movement", 'veso-theme-plugin'),
					"param_name" => "hor_img1",	
					"value" => "5%",	
					'description' => __( 'Move an image from center to left(- %)/right(+ %).', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "image_pos","value" => "custom"),
					"group" => __('Image 1', 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Vertical movement", 'veso-theme-plugin'),
					"param_name" => "ver_img1",	
					"value" => "30%",	
					'description' => __( 'Move an image from center to top(- %)/bottom(+ %).', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "image_pos","value" => "custom"),
					"group" => __('Image 1', 'veso-theme-plugin'),
				),
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Add image 2", "veso-theme-plugin" ),
					"param_name" => "image2",
					"value" => '',
					"group" => __('Image 2', 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Horizontal movement", 'veso-theme-plugin'),
					"param_name" => "hor_img2",	
					"value" => "-20%",	
					'description' => __( 'Move an image from center to left(- %)/right(+ %).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "image_pos","value" => "custom"),	
					"group" => __('Image 2', 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Vertical movement", 'veso-theme-plugin'),
					"param_name" => "ver_img2",	
					"value" => "-15%",	
					'description' => __( 'Move an image from center to top(- %)/bottom(+ %).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "image_pos","value" => "custom"),
					"group" => __('Image 2', 'veso-theme-plugin'),
				),
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Add image 3", "veso-theme-plugin" ),
					"param_name" => "image3",
					"value" => '',
					"group" => __('Image 3', 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Horizontal movement", 'veso-theme-plugin'),
					"param_name" => "hor_img3",	
					"value" => "30%",	
					'description' => __( 'Move an image from center to left(- %)/right(+ %).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "image_pos","value" => "custom"),
					"group" => __('Image 3', 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Vertical movement", 'veso-theme-plugin'),
					"param_name" => "ver_img3",	
					"value" => "-35%",	
					'description' => __( 'Move an image from center to top(- %)/bottom(+ %).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "image_pos","value" => "custom"),
					"group" => __('Image 3', 'veso-theme-plugin'),
				),
			),
		));
	}
}