<?php

class Veso_Pricing_Table {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_pricing_table', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode( $atts , $content, $team_list ) {
		extract( shortcode_atts( array(
			'image' => '',
			'title' => __( "Title", "veso-theme-plugin" ),
			'price' => 19,
			'prefix' => '$',
			'suffix' => '',
			'btn_text' => __( "Buy now", "veso-theme-plugin" ),
			'btn_link' => '',
			'btn_size' => 'btn-sm',
			'btn_type' => 'btn-outline',
			'btn_color' => 'btn-dark',
			'btn_align' => 'text-center',
			'bg_btn' => '#fff',
			'text_color' => '#333',
			'accent_color' => '',
			'btn_text_color' => '#333',
			'btn_accent_color' => '#ffb573',
			'el_class' => '',
			'css' => ''
		), $atts ) );

		$id = 'id_'.uniqid(true).mt_rand();		
		$output = '';
		if($content !== '') {
			$content = '<div class="desc">'.wpautop($content).'</div>';
		}

		if($prefix !== '') {
			$prefix = '<span>'.$prefix.'</span>';
		}
		if($suffix !== '') {
			$suffix = '<span>'.$suffix.'</span>';
		} 

		$image = wp_get_attachment_image_src( $image, 'large');
		if(is_array($image) && isset($image[0])) {
			$image = '<img alt="" src="'.$image[0].'"/>';
		} else {
			$image = '';
		}

		$output .= '<div class="veso-pricing-table '.$id.' ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$el_class.' animate-text" style="color: '.$text_color.'">'.$image.'<div class="pricing-content"><h3 class="text-center">'.$title.'</h3><div class="price text-center" style="color: '.$accent_color.'">'.$prefix.''.$price.''.$suffix.'</div>';
		$url = $btn_bg = $btn_txt = '';

		$url = vc_build_link( $btn_link );

		if($btn_color == 'custom') {
			if($btn_type == 'btn-solid') {
				$btn_bg = 'style="background: '.$bg_btn.'"';
			} 
			if($btn_type == 'btn-outline') {
				$btn_bg = 'style="border-color: '.$btn_text_color.'"';
			}
			$btn_txt = 'style="color: '.$btn_text_color.'"';
		}

		$target = (isset($url['target']) && $url['target'] !== '') ? "target='". esc_attr( trim($url['target']) ). "'" : '';

		$btn = '<div class="'.$btn_align.' " style="position: relative; z-index: 1"><a href="' . esc_attr( $url['url'] ) . '" '.$target.' class="btn '.$btn_size.' '.$btn_color.' '.$btn_type.' " '.$btn_bg.' '.$btn_txt.'>
				<span class="btn-text" '.$btn_txt.'>'.$btn_text.'</span>
			</a></div>';
		$output .= $content . $btn;
		$output .= '</div></div>';
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .desc li:before { background-color: '.$accent_color.'; } .'.$id.' .btn.btn-solid.custom:after { background-color: '.$btn_accent_color.'; } .'.$id.' .btn.btn-outline.custom:after { border-color: '.$btn_accent_color.'; } .'.$id.' .btn.custom.btn-underline:hover .btn-text:after { border-color: '.$btn_accent_color.'; }"></div>';

		return $output; 

	}
	
	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Pricing Table", "veso-theme-plugin" ),
			"base" => "veso_pricing_table",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Add image", "veso-theme-plugin" ),
					"param_name" => "image",
					"value" => '',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Title", "veso-theme-plugin" ),
					"param_name" => "title",
					"admin_label" => true,
					"value" => __( "Title", "veso-theme-plugin" ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Price", "veso-theme-plugin" ),
					"param_name" => "price",
					"admin_label" => true,
					"value" => 19,
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Price prefix", "veso-theme-plugin" ),
					"param_name" => "prefix",
					"admin_label" => true,
					"value" => __( "$", "veso-theme-plugin" ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Price suffix", "veso-theme-plugin" ),
					"param_name" => "suffix",
					"admin_label" => true,
					"value" => '',
				),
				array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __( "Description", "veso-theme-plugin" ),
					"param_name" => "content",
					"admin_label" => false,
					"value" => '',
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "text_color",		
					"value" => "",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",		
					"value" => "",
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),		
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Button text", "veso-theme-plugin" ),
					"param_name" => "btn_text",
					'value' => __( 'Buy now', 'veso-theme-plugin' ),
					'group' => __( 'Button', 'js_composer' ),	
				),

				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
					'param_name' => 'btn_link',
					'description' => __( 'Add link to button.', 'veso-theme-plugin' ),
					'group' => __( 'Button', 'js_composer' ),	
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button position", "veso-theme-plugin" ),
					"param_name" => "btn_align",
					"value" => array(
						'Left' => 'text-left',
						'Center' => 'text-center',
						'Right' => 'text-right',
					),
					'std' => 'text-center',
					'group' => __( 'Button', 'js_composer' ),	
				),
				
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button size", "veso-theme-plugin" ),
					"param_name" => "btn_size",
					"value" => array(
						'Large' => 'btn-lg',
						'Medium' => 'btn-md',
						'Small' => 'btn-sm',
						'Extra small' => 'btn-xs',
					),
					'std' => 'btn-sm',
					'group' => __( 'Button', 'js_composer' ),	
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button type", "veso-theme-plugin" ),
					"param_name" => "btn_type",
					"value" => array(
						__( "Solid color", "veso-theme-plugin" ) => 'btn-solid',
						__( "Outline", "veso-theme-plugin" ) => 'btn-outline',
						__( "Underline", "veso-theme-plugin" ) => 'btn-underline',
					),
					'std' => 'btn-outline',
					'group' => __( 'Button', 'js_composer' ),	
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button color", "veso-theme-plugin" ),
					"param_name" => "btn_color",
					"value" => array(
						'Dark' => 'btn-dark',
						'Light' => 'btn-light',
						'Custom color' => 'custom',
					),
					'std' => 'btn-dark',
					'group' => __( 'Button', 'js_composer' ),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_btn",		
					"value" => "#fff",
					'description' => __( 'Select background color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'js_composer' ),						
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "btn_text_color",		
					"value" => "#333",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'js_composer' ),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "btn_accent_color",		
					"value" => "#ffb573",
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),
					'group' => __( 'Button', 'js_composer' ),	
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'veso-theme-plugin' ),
					'param_name' => 'el_class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'veso-theme-plugin' ),
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