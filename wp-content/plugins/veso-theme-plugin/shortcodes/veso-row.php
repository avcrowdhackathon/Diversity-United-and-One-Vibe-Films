<?php
if(function_exists('vc_map')) {

function veso_row_get_video_start() {

	$videoStart = array(
		"type" => "textfield",
		"heading" => __("Start video from:", "veso-theme-plugin"),
		"param_name" => "video_start",
		"admin_label" => false,
		"description" => __("Type from which second video should start", "veso-theme-plugin"),
		'dependency' => array('element'=>'video_bg', 'not_empty'=>true),
		'value' => 0,
	);
	return $videoStart;
}


$image = array(
	'type' => 'attach_image',
	'heading' => __( 'Image', 'veso-theme-plugin' ),
	'param_name' => 'parallax_image',
	'value' => '',
	'description' => __( 'Select image from media library.', 'veso-theme-plugin' ),
);

vc_add_param( 'vc_row', $image );
vc_add_param( 'vc_section', $image );
vc_remove_param('vc_row', 'css_animation');
vc_remove_param('vc_section', 'css_animation');
$tooltip_fullpage = array(
	"type" => "textfield",
	"heading" => __("Add tooltip to full page", 'veso-theme-plugin'),
	"param_name" => "tooltip_fullpage",
	"value" => "",
	'description' => __( "Add tooltips to pagination of full pages. It's working only if you add rows as full pages.", 'veso-theme-plugin' ),
);
vc_add_param( 'vc_row', $tooltip_fullpage );
vc_add_param( 'vc_section', $tooltip_fullpage );
$img_pattern = array(
	'type' => 'checkbox',
	'heading' => __( 'Enable pattern looping', 'veso-theme-plugin' ),
	'param_name' => 'img_pattern',
	'value' => array(
		__( "On", "veso-theme-plugin" ) => '1',
	),
	'description' => __( 'Use image as pattern (disables parallax)', 'veso-theme-plugin' ),
);
vc_add_param( 'vc_row', $img_pattern );
vc_add_param( 'vc_section', $img_pattern );

$gradient = array(
	'type' => 'checkbox',
	'heading' => __( 'Gradient background', 'veso-theme-plugin' ),
	'param_name' => 'gradient',
	'value' => array(
		__( "On", "veso-theme-plugin" ) => '1',
	), 
	'std' => ''
	// 'description' => __( 'Use image as pattern (disables parallax)', 'veso-theme-plugin' ),
);
$gradient_orient =	array(
	'type' => 'dropdown',
	'heading' => __( 'Gradient Orientation', 'veso-theme-plugin' ),
	'param_name' => 'gradient_orient',
	'value' => array(
		__( 'Horizontal', 'veso-theme-plugin' ) => 'left-right',
		__( 'Vertical', 'veso-theme-plugin' ) => 'top-bottom',
		__( 'Diagonal', 'veso-theme-plugin' ) => 'diagonal',
		__( 'Radial', 'veso-theme-plugin' ) => 'radial',
	),
	'dependency' => array(
		'element' => 'gradient',
		'not_empty' => true,
	),
);
$gradient_color = array(
	"type" => "textfield",
	"heading" => __("Gradient colors", 'veso-theme-plugin'),
	"param_name" => "gradient_color",
	"value" => "",
	'description' => __( 'Add hexadecimal colors. Separate values with comma, i.e. #1452cc,#8814cc,#cc145e,#cc6314', 'veso-theme-plugin' ),
	'dependency' => array(
		'element' => 'gradient',
		'value' => '1',
	),
);
$gradient_opacity =	array(
	'type' => 'dropdown',
	'heading' => __( 'Gradient opacity', 'veso-theme-plugin' ),
	'param_name' => 'gradient_opacity',
	'value' => array(
		__( '0.1', 'veso-theme-plugin' ) => '0.1',
		__( '0.2', 'veso-theme-plugin' ) => '0.2',
		__( '0.3', 'veso-theme-plugin' ) => '0.3',
		__( '0.4', 'veso-theme-plugin' ) => '0.4',
		__( '0.5', 'veso-theme-plugin' ) => '0.5',
		__( '0.6', 'veso-theme-plugin' ) => '0.6',
		__( '0.7', 'veso-theme-plugin' ) => '0.7',
		__( '0.8', 'veso-theme-plugin' ) => '0.8',
		__( '0.9', 'veso-theme-plugin' ) => '0.9',
		__( '1', 'veso-theme-plugin' ) => '1',
	),
	'std' => '1',
	'dependency' => array(
		'element' => 'gradient',
		'value' => '1',
	),
);

$overlay = array(
	"type" => "colorpicker",
	"heading" => __("Overlay color", 'veso-theme-plugin'),
	"param_name" => "overlay_color",
	"value" => "",
	'description' => __( 'Select color and opacity for overlay', 'veso-theme-plugin' ),
);
$text_color = array(
	"type" => "colorpicker",
	"heading" => __("Text color", 'veso-theme-plugin'),
	"param_name" => "text_color",
	"value" => "",
	'description' => __( 'Select text color', 'veso-theme-plugin' ),
);
vc_add_param( 'vc_row', $gradient );
vc_add_param( 'vc_row', $gradient_orient );
vc_add_param( 'vc_row', $gradient_color );
vc_add_param( 'vc_row', $gradient_opacity );
vc_add_param( 'vc_row', $overlay );
vc_add_param( 'vc_row', $text_color );
vc_add_param( 'vc_section', $overlay );
vc_add_param( 'vc_section', $text_color );


function veso_remove_params_row($videoStart) {
	$shortcode_vc_row = WPBMap::getShortCode('vc_row');

	unset($shortcode_vc_row['base']);
	unset($shortcode_vc_row['params'][8]['value']['With fade']);
	unset($shortcode_vc_row['params'][9]['value']['With fade']);
	$shortcode_vc_row['params'][11]['value'] = '0.5';
	$shortcode_vc_row['params'][11]['description'] ='Enter parallax speed ratio (Note: Default value is 0.5, min value is 0, max is 1)';
	$shortcode_vc_row['params'][12]['value'] = '0.5';
	$shortcode_vc_row['params'][12]['description'] ='Enter parallax speed ratio (Note: Default value is 0.5, min value is 0, max is 1)';

	$shortcode_vc_row['params'][0]['value']['Stretch row left'] = 'stretch_row_left';
	$shortcode_vc_row['params'][0]['value']['Stretch row right'] = 'stretch_row_right';

	$shortcode_vc_row['params'][1]['value'] = array('Default' => '', 'Remove gap'=>'remove-gap', 'Large'=>'inner-100');
 
	
	$videoStart = veso_row_get_video_start();
	array_splice($shortcode_vc_row['params'],8,0,array($videoStart));

	$settings = $shortcode_vc_row;
	vc_map_update( 'vc_row', $settings );

	$shortcode_vc_section = WPBMap::getShortCode('vc_section');
	unset($shortcode_vc_section['base']);
	
	unset($shortcode_vc_section['params'][5]['value']['With fade']);
	unset($shortcode_vc_section['params'][6]['value']['With fade']);
	$shortcode_vc_section['params'][8]['value'] = '0.5';
	$shortcode_vc_section['params'][8]['description'] ='Enter parallax speed ratio (Note: Default value is 0.5, min value is 0, max is 1)';
	$shortcode_vc_section['params'][9]['value'] = '0.5';
	$shortcode_vc_section['params'][9]['description'] ='Enter parallax speed ratio (Note: Default value is 0.5, min value is 0, max is 1)';

	array_splice($shortcode_vc_section['params'],5,0,array($videoStart));
	$settings = $shortcode_vc_section;
	vc_map_update( 'vc_section', $settings );
}

add_action('init', 'veso_remove_params_row');



}