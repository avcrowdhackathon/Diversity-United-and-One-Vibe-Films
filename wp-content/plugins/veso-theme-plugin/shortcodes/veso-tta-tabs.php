<?php
if(function_exists('vc_map')) {

$titleColor = array(
	"type" => "colorpicker",
	"heading" => __("Tab title color", 'veso-theme-plugin'),
	"param_name" => "text",		
	"value" => "",
	'description' => __( 'Select tab title color.', 'veso-theme-plugin' ),
);
$color = array(
	"type" => "colorpicker",
	"heading" => __("Tabs color", 'veso-theme-plugin'),
	"param_name" => "color",		
	"value" => "",
	'description' => __( 'Select tabs color.', 'veso-theme-plugin' ),
);
$style = array(
	"type" => "colorpicker",
	"heading" => __("Active tab color", 'veso-theme-plugin'),
	"param_name" => "style",		
	"value" => "",
	'description' => __( 'Select color for active tab.', 'veso-theme-plugin' ),
);
$accent = array(
	"type" => "colorpicker",
	"heading" => __("Accent color", 'veso-theme-plugin'),
	"param_name" => "accent",		
	"value" => "",
	'description' => __( 'Select accent color.', 'veso-theme-plugin' ),
);


vc_remove_param('vc_tta_tabs', 'color');
vc_remove_param('vc_tta_tabs', 'style');
vc_remove_param('vc_tta_tabs', 'shape');
vc_remove_param('vc_tta_tabs', 'gap');
vc_remove_param('vc_tta_tabs', 'spacing');
vc_remove_param('vc_tta_tabs', 'pagination_style');
vc_remove_param('vc_tta_tabs', 'pagination_color');
vc_add_param( 'vc_tta_tabs', $titleColor );
vc_add_param( 'vc_tta_tabs', $color );
vc_add_param( 'vc_tta_tabs', $style );
vc_add_param( 'vc_tta_tabs', $accent );

}