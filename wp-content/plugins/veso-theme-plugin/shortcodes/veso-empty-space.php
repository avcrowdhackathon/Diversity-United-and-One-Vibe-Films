<?php

if(function_exists('vc_map')) {
	vc_remove_param("vc_empty_space", "el_class");

	vc_add_param("vc_empty_space",  array(
	  	'type' => 'textfield',
		'heading' => __( 'Height for mobile devices', 'js_composer' ),
		'param_name' => 'height2',
		'value' => '',
		'admin_label' => true,
		'description' => __( 'Enter empty space height (Note: CSS measurement units allowed).', 'js_composer' ),
	));
	vc_add_param("vc_empty_space",  array(
	  'type' => 'textfield',
		'heading' => __( 'Extra class name', 'js_composer' ),
		'param_name' => 'el_class',
		'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
	));
}
