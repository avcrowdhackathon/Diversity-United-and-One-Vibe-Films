<?php

class Veso_Additional_Info {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_additional_info', array($this, 'veso_shortcode' ));
	}


	function veso_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'alignment' => 'text-right',
			'el_class' => '',
			'background_color' => '',
			'text_color' => '',
			'css' => ''
		), $atts ) );
		$style = $bgClass = '';
		if($alignment == 'text-right') {
			$style = 'style="display: flex; justify-content: flex-end;"';
		} elseif($alignment == 'text-center') {
			$style = 'style="display: flex; justify-content: center"';
		}
		if($background_color != '') {
			$bgClass = 'add-padding-bg';
		}
		$output = '<div class="veso-add-info-wrapper '.$el_class.' ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' animate-text" '.$style.' ><div class="veso-add-info '.$bgClass.'" style="background-color: '.$background_color.'; color: '.$text_color.' !important">'.wpautop($content).'</div></div>';

		return $output;
	}


	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Additional Info", "veso-theme-plugin" ),
			"base" => "veso_additional_info",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __( "Content", "veso-theme-plugin" ),
					"param_name" => "content",
					"value" => '',
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Alignment", "veso-theme-plugin" ),
					"param_name" => "alignment",
					"value" => array(
						'Left' => 'text-left',
						'Center' => 'text-center',
						'Right' => 'text-right',
					),
					'std' => 'text-right',
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "background_color",		
					"value" => "",
					'description' => __( 'Select background color.', 'veso-theme-plugin' ),		
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