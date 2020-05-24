<?php
if(function_exists('vc_map')) {
	function veso_remove_params_pie() {
		$shortcode_vc_pie = WPBMap::getShortCode('vc_pie');
		unset($shortcode_vc_pie['base']);

		$shortcode_vc_pie['params'][4]['std'] ='custom';
		$shortcode_vc_pie['params'][4]['edit_field_class'] = 'hidden';
		$settings = $shortcode_vc_pie;
		vc_map_update( 'vc_pie', $settings );
	};
	add_action('init', 'veso_remove_params_pie');
}