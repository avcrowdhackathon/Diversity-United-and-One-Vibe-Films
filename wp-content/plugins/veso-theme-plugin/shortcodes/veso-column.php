<?php
if(function_exists('vc_map')) {
$parallax =	array(
	'type' => 'dropdown',
	'heading' => __( 'Parallax', 'js_composer' ),
	'param_name' => 'parallax',
	'value' => array(
		__( 'None', 'js_composer' ) => '',
		__( 'Simple', 'js_composer' ) => 'content-moving',
	),
	'description' => __( 'Add parallax type background for row.', 'veso-theme-plugin' ),
);
vc_add_param( 'vc_column', $parallax );
$parallax_image = array(
	'type' => 'attach_image',
	'heading' => __( 'Image', 'js_composer' ),
	'param_name' => 'parallax_image',
	'value' => '',
	'description' => __( 'Select image from media library.', 'veso-theme-plugin' ),
);
vc_add_param( 'vc_column', $parallax_image );
$parallax_speed = array(
	'type' => 'textfield',
	'heading' => __( 'Parallax speed', 'veso-theme-plugin' ),
	'param_name' => 'parallax_speed_bg',
	'value' => '0.5',
	'description' => __( 'Enter parallax speed ratio (Note: Default value is 0.5, min value is 0, max is 1)', 'veso-theme-plugin' ),
	'dependency' => array(
		'element' => 'parallax',
		'not_empty' => true,
	),
);
vc_add_param( 'vc_column', $parallax_speed );
$parallax_bg_size = array(
	'type' => 'dropdown',
	'heading' => __( 'Background image size', 'js_composer' ),
	'param_name' => 'parallax_bg_size',
	'value' => array(
		__( 'Cover', 'js_composer' ) => 'cover',
		__( 'Contain', 'js_composer' ) => 'contain',
	),
	'description' => __( 'Add parallax type background for row.', 'veso-theme-plugin' ),
	'dependency' => array(
		'element' => 'parallax_image',
		'not_empty' => true,
	),
);
vc_add_param( 'vc_column', $parallax_bg_size );

$show_on_hover =	array(
	'type' => 'checkbox',
	'heading' => __( 'Show content on hover', 'js_composer' ),
	'param_name' => 'show_on_hover',
	'description' => __( 'Check to show content only on hover.', 'veso-theme-plugin' ),
);
vc_add_param( 'vc_column', $show_on_hover );
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

$overlay_hover = array(
	"type" => "colorpicker",
	"heading" => __("Overlay color on hover", 'veso-theme-plugin'),
	"param_name" => "overlay_color_hover",		
	"value" => "",
	'description' => __( 'Select color and opacity for overlay on hover', 'veso-theme-plugin' ),
);

$text_color = array(
	"type" => "colorpicker",
	"heading" => __("Text color", 'veso-theme-plugin'),
	"param_name" => "text_color",		
	"value" => "",
	'description' => __( 'Select text color', 'veso-theme-plugin' ),
);
vc_add_param( 'vc_column', $gradient );
vc_add_param( 'vc_column', $gradient_orient );
vc_add_param( 'vc_column', $gradient_color );
vc_add_param( 'vc_column', $gradient_opacity );
vc_add_param( 'vc_column', $overlay );
vc_add_param( 'vc_column', $overlay_hover );
vc_add_param( 'vc_column', $text_color );


vc_add_param( 'vc_column_inner', $parallax );
vc_add_param( 'vc_column_inner', $parallax_image );
vc_add_param( 'vc_column_inner', $parallax_speed );
vc_add_param( 'vc_column_inner', $parallax_bg_size );
vc_add_param( 'vc_column_inner', $show_on_hover );
vc_add_param( 'vc_column_inner', $gradient );
vc_add_param( 'vc_column_inner', $gradient_orient );
vc_add_param( 'vc_column_inner', $gradient_color );
vc_add_param( 'vc_column_inner', $overlay );
vc_add_param( 'vc_column_inner', $overlay_hover );
vc_add_param( 'vc_column_inner', $text_color );



}
?>