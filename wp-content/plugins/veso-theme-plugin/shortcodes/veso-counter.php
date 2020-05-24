<?php

class Veso_Counter {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_counter', array($this, 'veso_shortcode' ));
	}


	function veso_shortcode( $atts ) {
		wp_enqueue_script('front-js', get_template_directory_uri().'/assets/js/libs/countUp.js');
		extract( shortcode_atts( array(
			'counter_title' => 'Counter title',
			'font_size_title' => '14',
			'counter_value' => '2017',
			'font_size_counter' => '72',
			'counter_sep' => '',
			'counter_decimal' => '.',
			'speed' => '3',
			'el_class' => '',
			'color' => '#333',
			'color_txt' => '#333',
			'counter_prefix' => '',
			'counter_suffix' => '',
			'css' => ''
		), $atts ) );
		$id = 'counter_'.uniqid();
		$output = '';

		preg_match("/([0-9]+)([a-zA-Z%]+)/", $font_size_counter, $font_size_counter_array );
		if ( !empty($font_size_counter_array) ) {
			$font_size_counter = $font_size_counter_array[1];
		}
		preg_match("/([0-9]+)([a-zA-Z%]+)/", $font_size_title, $font_size_title_array );
		if ( !empty($font_size_title_array) ) {
			$font_size_title = $font_size_title_array[1];
		}
		if($counter_decimal == ""){
			$counter_decimal = 'none';
		}
		if($counter_sep == ""){
			$counter_sep = 'none';
		}
		if($counter_prefix != '') { 
			$counter_prefix = '<span style="font-size: .5em; vertical-align: middle;">'.$counter_prefix.'</span>';
		}
		if($counter_suffix != '') {
			$counter_suffix = '<span style="font-size: .5em; vertical-align: middle;">'.$counter_suffix.'</span>';
		}
		$output .= '<div class="veso-counter ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$el_class.' animate-text">';
		$output .= '<div class="text-center" style="font-size: '.$font_size_counter.'px; color: '.$color.';">'.$counter_prefix.'<span id="'.$id.'" class="counter text-center" style="font-size: '.$font_size_counter.'px; color: '.$color.'" data-id="'.$id.'" data-speed="'.$speed.'" data-counter="'.$counter_value.'" data-decimal="'.$counter_decimal.'" data-separator="'.$counter_sep.'">0</span>'.$counter_suffix.'</div><div class="counter-title text-center" style="font-size: '.$font_size_title.'px; color: '.$color_txt.'">'.$counter_title.'</div>';
		$output .= '</div>';

		return $output;
	}


	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Counter", "veso-theme-plugin" ),
			"base" => "veso_counter",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" =>  array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Counter Title ", 'veso-theme-plugin'),
					"param_name" => "counter_title",
					"admin_label" => true,
					"value" => __("Counter title", 'veso-theme-plugin'),
					"description" => __("Enter title for stats counter block", 'veso-theme-plugin'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Title Font Size", 'veso-theme-plugin'),
					"param_name" => "font_size_title",
					"value" => '14',
					"suffix" => "px",
					"description" => __("Enter value in pixels.", 'veso-theme-plugin'),
					"dependency" => Array("element" => "counter_title", "not_empty" => true),
					"description" => __("Insert title font size in pixels.", 'veso-theme-plugin')
				),
			 	array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Counter Value", 'veso-theme-plugin'),
					"param_name" => "counter_value",
					"value" => "2017",
					"description" => __("Enter number for counter without any special character. You may enter a decimal number. Eg 12.76", 'veso-theme-plugin')
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Add prefix to value", 'veso-theme-plugin'),
					"param_name" => "counter_prefix",
					"value" => '',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Add suffix to value", 'veso-theme-plugin'),
					"param_name" => "counter_suffix",
					"value" => '',
				),
			    array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Counter Font Size", 'veso-theme-plugin'),
					"param_name" => "font_size_counter",
					"value" => '72',
					"description" => __("Insert counter font size in pixels.", 'veso-theme-plugin')
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Thousands Separator", 'arkona'),
					"param_name" => "counter_sep",
					"value" => "",
					"description" => __("Enter character for thousand separator. e.g. ',' will separate 125000 into 125,000", 'arkona'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Replace Decimal Point With", 'arkona'),
					"param_name" => "counter_decimal",
					"value" => ".",
					"description" => __("Did you enter a decimal number (Eg - 12.76) The decimal point '.'will be replaced with value that you will enter above.", 'arkona'),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Counter rolling time", 'veso-theme-plugin'),
					"param_name" => "speed",
					"value" => '3',
					"description" => __("How many seconds the counter should roll?", 'veso-theme-plugin'),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Counter color", 'veso-theme-plugin'),
					"param_name" => "color",		
					"value" => "#333",
					'group' => __('Colors', 'veso-theme-plugin'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Counter titile", 'veso-theme-plugin'),
					"param_name" => "color_txt",		
					"value" => "#333",	
					'group' => __('Colors', 'veso-theme-plugin'),	
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
			)
		));
	}

}