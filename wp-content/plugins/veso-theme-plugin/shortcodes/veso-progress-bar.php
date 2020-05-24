<?php
if(function_exists('vc_map')) {

$color = array(
	'type' => 'colorpicker',
	'heading' => __( 'Progress bar background color', 'js_composer' ),
	'param_name' => 'custombgcolor',
);


function veso_get_color_bar_map_array() {
	$barcolor = array(
		'type' => 'colorpicker',
		'heading' => __( 'Bar background color', 'js_composer' ),
		'param_name' => 'custombarcolor',
	);
	return $barcolor;
}
vc_remove_param('vc_progress_bar', 'bgcolor');
vc_remove_param('vc_progress_bar', 'options');

vc_add_param( 'vc_progress_bar', $color );

function veso_remove_params_progress_bar($barcolor) {
	$shortcode_vc_bar = WPBMap::getShortCode('vc_progress_bar');
	unset($shortcode_vc_bar['base']);
	$barcolor = veso_get_color_bar_map_array();
	array_splice($shortcode_vc_bar['params'],3,0,array($barcolor));

	$settings = $shortcode_vc_bar;
	vc_map_update( 'vc_progress_bar', $settings );
}

add_action('init', 'veso_remove_params_progress_bar');


}

