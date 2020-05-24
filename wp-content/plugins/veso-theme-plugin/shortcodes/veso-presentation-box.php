<?php

class Veso_Presentation_Box {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_presentation_box', array($this, 'veso_shortcode' ));
	}


	function veso_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'image' => '',
			'img_pos' => 'cta-left-img',
			'cta_btn' => '1',
			'btn_text' => __( "Button text", "veso-theme-plugin" ),
			'padding_top_bottom' => '60px',
			'padding_right_left' => '60px',
			'btn_link' => '',
			'btn_size' => 'btn-md',
			'btn_type' => 'btn-solid',
			'btn_color' => 'btn-dark',
			'btn_align' => 'text-center',
			'bg_btn' => '#fff',
			'btn_text_color' => '#333',
			'btn_accent_color' => '#ffb573',
			'overlay_color' => 'rgba(0,0,0,0.5)',
			'text_color' => '',
			'accent_color' => '',
			'background_color' => '',
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			'css' => '',
			'extra_class' => '',
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();
		$bg_image = wp_get_attachment_image_src( $image, 'large' );
		$bg_image = $bg_image[0];
		$image = wp_get_attachment_image($image, 'large');
		$btn_border = $button = $icon_class = $rounded_class = $full_class = $url = $btn_class = $overlay = $btn_bg = $btn_txt = '';

		if($btn_color == 'custom') {
			if($btn_type == 'btn-solid') {
				$btn_bg = 'style="background: '.$bg_btn.'"';
			} 
			if($btn_type == 'btn-outline') {
				$btn_bg = 'style="border-color: '.$btn_text_color.'"';
			} 
			$btn_txt = 'style="color: '.$btn_text_color.'"';
		}

		if($padding_top_bottom !== '') {
			preg_match("/([0-9]+)([a-zA-Z%]+)/", $padding_top_bottom, $padding_top_bottom_array );
			if ( !empty($padding_top_bottom_array) ) {
				$padding_top_bottom = $padding_top_bottom_array[1];
			}
			if($padding_top_bottom_array[2] == '') {
				$padding_top_bottom .= 'px';
			} else {
				$padding_top_bottom .= $padding_top_bottom_array[2];
			}
		}
		if($padding_right_left !== '') {
			preg_match("/([0-9]+)([a-zA-Z%]+)/", $padding_right_left, $padding_right_left_array );
			if ( !empty($padding_right_left_array) ) {
				$padding_right_left = $padding_right_left_array[1];
			}
			if($padding_right_left_array[2] == '') {
				$padding_right_left .= 'px';
			} else {
				$padding_right_left .= $padding_right_left_array[2];
			}
		}
		if($cta_btn == '') {
			$button = '';
		} else {
			$url = vc_build_link( $btn_link );

			$target = (isset($url['target']) && $url['target'] !== '') ? "target='". esc_attr( trim($url['target']) ). "'" : '';
	
			$button = '<div class="'.$btn_align.'" style="margin-bottom: 0; position: relative; z-index: 1; "><a href="' . esc_attr( $url['url'] ) . '" '.$target.' class="btn '.$btn_size.' '.$btn_type.' '.$btn_color.' "'.$btn_bg.'>
				<span class="btn-text '.$btn_class.'" '.$btn_txt.'>'.$btn_text.'</span>
			</a></div>';
		}
		if(!$image) {
			$img_pos = '';
		}
		if($img_pos == 'cta-bg-img cta-content-light') {
			$overlay = '<div class="cta-overlay" style="background: '.$overlay_color.'"></div>';
		} 
			$output = '<div class="' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$extra_class.' call-to-action cta-sm animate-text"><div class="page-bg-color '.$id.' element-cta '.$img_pos.'" style="background: '.$background_color.'">';
				if($image) {
					$output .= '
					<div class="image-content img-bg-wrapper" >
						<div class="img-bg-inner">
							<div class="mosaic-img" style="background-image: url('.$bg_image.'); background-size: cover; background-position: center center; "></div>
						</div>
					</div>';
				}
				$output .= $overlay.'
					<div class="cta-content animate-text loaded-text" style="padding: '.$padding_top_bottom.' '.$padding_right_left.'">
						<div class="content" style="color: '.$text_color.'">'.wpautop($content).'</div>
						'.$button.'
					</div>
				</div></div>

		';

		$output .= '<div class="custom-styles" data-styles=".'.$id.'.element-cta p, .'.$id.'.element-cta li { color: '.$text_color.'; } .'.$id.'.element-cta.page-bg-color { background-color: '.$background_color.'; } .'.$id.' .btn.btn-solid.custom:after { background-color: '.$accent_color.'; } .'.$id.' .btn.btn-outline.custom:after { border-color: '.$accent_color.'; } .'.$id.' .btn.custom.btn-underline:hover .btn-text:after { border-color: '.$btn_accent_color.'; }"></div>';
		return $output;
	}


	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Presentation Box", "veso-theme-plugin" ),
			"base" => "veso_presentation_box",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Upload image", "veso-theme-plugin" ),
					"param_name" => "image",
					"value" => '',
				),
				array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __( "Content", "veso-theme-plugin" ),
					"param_name" => "content",
					"value" => '',
					'description' => __( 'Enter text for heading.', 'veso-theme-plugin' ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Padding top & bottom", "veso-theme-plugin" ),
					"param_name" => "padding_top_bottom",
					'value' => __( '60px', 'veso-theme-plugin' ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Padding right & left", "veso-theme-plugin" ),
					"param_name" => "padding_right_left",
					'value' => __( '60px', 'veso-theme-plugin' ),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Image position", "veso-theme-plugin" ),
					"param_name" => "img_pos",
					"value" => array(
						'Left' => 'cta-left-img',
						'Right' => 'cta-right-img',
						'Background image' => 'cta-bg-img cta-content-light',
					),
					'std' => 'text-center',
					"dependency" => array("element" => "image","not_empty" => true),
				),

				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __( "Show button", "veso-theme-plugin" ),
					"param_name" => "cta_btn",
					'description' => __( 'Add button for call to action.', 'veso-theme-plugin' ),
					"value" => array(
						__( "On", "veso-theme-plugin" ) => '1',
					),
					'std' => '1',
				),
				
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Button text", "veso-theme-plugin" ),
					"param_name" => "btn_text",
					'value' => __( "Button text", "veso-theme-plugin" ),
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
					'param_name' => 'btn_link',
					'description' => __( 'Add link to button.', 'veso-theme-plugin' ),
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button position", "veso-theme-plugin" ),
					"param_name" => "btn_align",
					"value" => array(
						__( 'Left', 'veso-theme-plugin' ) => 'text-left',
						__( 'Center', 'veso-theme-plugin' ) => 'text-center',
						__( 'Right', 'veso-theme-plugin' ) => 'text-right',
					),
					"dependency" => array("element" => "cta_btn","value" => '1'),
					'std' => 'text-center',
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button size", "veso-theme-plugin" ),
					"param_name" => "btn_size",
					"value" => array(
						__('Large', 'veso-theme-plugin') => 'btn-lg',
						__('Medium', 'veso-theme-plugin') => 'btn-md',
						__('Small', 'veso-theme-plugin') => 'btn-sm',
						__('Extra small', 'veso-theme-plugin') => 'btn-xs',
					),
					"dependency" => array("element" => "cta_btn","value" => '1'),
					'std' => 'btn-md',
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button style", "veso-theme-plugin" ),
					"param_name" => "btn_type",
					"value" => array(
						__( "Solid color", "veso-theme-plugin" ) => 'btn-solid',
						__( "Outline", "veso-theme-plugin" ) => 'btn-outline',
						__( "Underline", "veso-theme-plugin" ) => 'btn-underline',
					),
					'std' => 'btn-solid',
					"dependency" => array("element" => "cta_btn","value" => '1'),
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button color scheme", "veso-theme-plugin" ),
					"param_name" => "btn_color",
					"value" => array(
						__('Dark button' , 'veso-theme-plugin')=> 'btn-dark',
						__('Light button', 'veso-theme-plugin') => 'btn-light',
						__('Custom color', 'veso-theme-plugin') => 'custom'
					),
					'std' => 'btn-dark',
					"dependency" => array("element" => "cta_btn","value" => '1'),
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_btn",		
					"value" => "#fff",
					'description' => __( 'Select background color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'veso-theme-plugin' ),
					
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "btn_text_color",		
					"value" => "#333",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "btn_accent_color",		
					"value" => "#ffb573",
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Overlay color", 'veso-theme-plugin'),
					"param_name" => "overlay_color",	
					"value" => "rgba(0,0,0,0.5)",	
					'description' => __( 'Select color for overlay.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "img_pos","value" => 'cta-bg-img cta-content-light'),
					"group" => __('Colors', 'veso-theme-plugin')
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "background_color",	
					"value" => "",	
					'description' => __( 'Select background color.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin')
				),

				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "text_color",	
					"value" => "",	
					'description' => __( 'Select color for text.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin')
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",	
					"value" => "",	
					'description' => __( 'Select color for featured elements.', 'veso-theme-plugin' ),		
					"group" => __('Colors', 'veso-theme-plugin')
				),
				array(
					'type' => 'css_editor',
					'heading' => __( 'CSS box', 'veso-theme-plugin' ),
					'param_name' => 'css',
					'group' => __( 'Design Options', 'veso-theme-plugin' ),
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