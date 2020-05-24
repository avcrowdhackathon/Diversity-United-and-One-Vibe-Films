<?php

class Veso_Button {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_button', array($this, 'veso_shortcode' ));
	}


	function veso_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'btn_text' => __( "Button text", "veso-theme-plugin" ),
			'btn_link' => '',
			'btn_size' => 'btn-md',
			'btn_type' => 'btn-solid',
			'btn_color' => 'btn-dark',
			'btn_align' => 'text-center',
			'bg_btn' => '#fff',
			'text_color' => '#333',
			'accent_color' => '#ffb573',
			'el_class' => '',
			'css' => ''
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();
		$url = $btn_bg = $btn_txt = '';

		$url = vc_build_link( $btn_link );

		if($btn_color == 'custom') {
			if($btn_type == 'btn-solid') {
				$btn_bg = 'style="background: '.$bg_btn.'"';
			} 
			if($btn_type == 'btn-outline') {
				$btn_bg = 'style="border-color: '.$text_color.'"';
			}
			$btn_txt = 'style="color: '.$text_color.'"';
		}

		$target = (isset($url['target']) && $url['target'] !== '') ? "target='". esc_attr( trim($url['target']) ). "'" : '';

		$output = '<div class="'.$btn_align.' '.$id.' ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$el_class.' animate-text"><a href="' . esc_url( $url['url'] ) . '" '.$target.' class="btn '.$btn_size.' '.$btn_color.' '.$btn_type.' " '.$btn_bg.' '.$btn_txt.'>
				<span class="btn-text" '.$btn_txt.'>'.$btn_text.'</span>
			</a></div>';
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .btn.btn-solid.custom:after { background-color: '.$accent_color.'; }
			.'.$id.' .btn.btn-outline.custom:after { border-color: '.$accent_color.'; }
			.'.$id.' .btn.custom.btn-underline:hover .btn-text:after { border-color:  '.$accent_color.'; }"></div>';

		return $output;
	}


	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Button", "veso-theme-plugin" ),
			"base" => "veso_button",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Button text", "veso-theme-plugin" ),
					"param_name" => "btn_text",
					'value' => __( "Button text", "veso-theme-plugin" ),
				),

				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
					'param_name' => 'btn_link',
					'description' => __( 'Add link to button.', 'veso-theme-plugin' ),
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
					'std' => 'btn-md',
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
					'std' => 'btn-solid',
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button color", "veso-theme-plugin" ),
					"param_name" => "btn_color",
					"value" => array(
						__('Dark button' , 'veso-theme-plugin')=> 'btn-dark',
						__('Light button', 'veso-theme-plugin') => 'btn-light',
						__('Custom color', 'veso-theme-plugin') => 'custom'
					),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_btn",		
					"value" => "#fff",
					'description' => __( 'Select background color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "text_color",		
					"value" => "#333",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",		
					"value" => "#ffb573",
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
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