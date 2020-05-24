<?php
if(function_exists('vc_map')) {
$txt = array(
	"type" => "colorpicker",
	"heading" => __("Text color", 'veso-theme-plugin'),
	"param_name" => "txt",		
	"value" => "",
	'description' => __( 'Select text color.', 'veso-theme-plugin' ),
);
$border = array(
	"type" => "colorpicker",
	"heading" => __("Separator color", 'veso-theme-plugin'),
	"param_name" => "border",		
	"value" => "#ddd",
	'description' => __( 'Select separator color.', 'veso-theme-plugin' ),
);
$color = array(
	"type" => "colorpicker",
	"heading" => __("Accent color", 'veso-theme-plugin'),
	"param_name" => "color",		
	"value" => "",
	'description' => __( 'Select accent color.', 'veso-theme-plugin' ),
);
$style = array(
	"type" => "colorpicker",
	"heading" => __("Background color", 'veso-theme-plugin'),
	"param_name" => "style",		
	"value" => "",
	'description' => __( 'Select background color for accordion.', 'veso-theme-plugin' ),
);

vc_remove_param('vc_tta_accordion', 'color');
vc_remove_param('vc_tta_accordion', 'style');
vc_remove_param('vc_tta_accordion', 'shape');
vc_remove_param('vc_tta_accordion', 'pagination_style');
vc_remove_param('vc_tta_accordion', 'pagination_color');
vc_add_param( 'vc_tta_accordion', $txt );
vc_add_param( 'vc_tta_accordion', $border );
vc_add_param( 'vc_tta_accordion', $color );
vc_add_param( 'vc_tta_accordion', $style );
}