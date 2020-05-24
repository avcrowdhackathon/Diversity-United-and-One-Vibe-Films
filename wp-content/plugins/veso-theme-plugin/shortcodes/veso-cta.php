<?php
if(function_exists('vc_map')) {


$bgcolor = array(
	'type' => 'colorpicker',
	'heading' => __( 'Background color', 'js_composer' ),
	'param_name' => 'custom_background',
	'value' => '#ffb573',
	'description' => __( 'Select custom background color.', 'js_composer' ),
);
$color = array(
	'type' => 'colorpicker',
	'heading' => __( 'Color', 'js_composer' ),
	'param_name' => 'custom_text',

	'description' => __( 'Select custom color.', 'js_composer' ),
);

vc_remove_param('vc_cta', 'h4');
vc_remove_param('vc_cta', 'shape');
vc_remove_param('vc_cta', 'color');
vc_remove_param('vc_cta', 'style');
vc_remove_param('vc_cta', 'add_icon');
vc_add_param( 'vc_cta', $bgcolor );
vc_add_param( 'vc_cta', $color );


function veso_get_button_map_array() {
$btn = array(
	array(
		"type" => "textfield",
		"class" => "",
		"heading" => __( "Button text", "veso-theme-plugin" ),
		"param_name" => "btn_text",
		'value' => __( 'Button', 'veso-theme-plugin' ),
		'group' => __( 'Button', 'js_composer' ),
		"dependency" => array("element" => "add_button","not_empty" => true),	
	),

	array(
		'type' => 'vc_link',
		'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
		'param_name' => 'btn_link',
		'description' => __( 'Add link to button.', 'veso-theme-plugin' ),
		'group' => __( 'Button', 'js_composer' ),
		"dependency" => array("element" => "add_button","not_empty" => true),	
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
		"dependency" => array("element" => "add_button","value" => array('top', 'bottom')),
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
		'std' => 'btn-md',
		'group' => __( 'Button', 'js_composer' ),
		"dependency" => array("element" => "add_button","not_empty" => true),	
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
		'group' => __( 'Button', 'js_composer' ),
		"dependency" => array("element" => "add_button","not_empty" => true),	
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
		'group' => __( 'Button', 'js_composer' ),
		"dependency" => array("element" => "add_button","not_empty" => true),	
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
		"param_name" => "text_color",		
		"value" => "#333f51",
		'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
		"dependency" => array("element" => "btn_color","value" => 'custom'),	
		'group' => __( 'Button', 'js_composer' ),
	),
);
	return $btn;
}

function veso_remove_params_cta() {
	$shortcode_vc_cta = WPBMap::getShortCode('vc_cta');
	unset($shortcode_vc_cta['base']);
	$btn = veso_get_button_map_array();
	$key = 0;
	for ($i=23; $i < 57; $i++) { 
		unset($shortcode_vc_cta['params'][$i]);
		if(isset($btn[$key])) {
			$shortcode_vc_cta['params'][$i] = $btn[$key];
			$key ++;
		}	
	}
	ksort($shortcode_vc_cta['params']);
	$shortcode_vc_cta['params'][18]['value'] ='#f5f3f4';
	$shortcode_vc_cta['params'][22]['std'] ='right';

	$settings = $shortcode_vc_cta;
	vc_map_update( 'vc_cta', $settings );
}

add_action('init', 'veso_remove_params_cta');
}