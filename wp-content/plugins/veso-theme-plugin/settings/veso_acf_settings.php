<?php
include_once 'veso_menu_settings.php';

function acf_load_navigation_field_choices( $field ) {
	$field['choices'] = array();


	// if has rows
	if( have_rows('veso_navigation_bars', 'option') ) {
		$i = 0;
		
		// while has rows
		while( have_rows('veso_navigation_bars', 'option') ) {
			$i++;
			// instantiate row
			the_row();
			
			// vars
			$value = $i;
			$label = get_sub_field('navigation_name');

			
			// append to choices
			$field['choices'][ $value ] = $label;
			
		}
		
	}

	return $field;
	
}

function acf_load_navigation_menus($field) {
    $terms = get_terms( 'nav_menu', array( 'hide_empty' => true ) ); 
    
    $field['choices'][0] = '--';
    foreach ($terms as $key => $value) {
    	$field['choices'][$value->slug] = $value->name;
    }

    return $field;
}

add_filter('acf/load_field/name=veso_navigation', 'acf_load_navigation_field_choices');

add_filter('acf/load_field/name=veso_mega_menu_column_1', 'acf_load_navigation_menus');
add_filter('acf/load_field/name=veso_mega_menu_column_2', 'acf_load_navigation_menus');
add_filter('acf/load_field/name=veso_mega_menu_column_3', 'acf_load_navigation_menus');
add_filter('acf/load_field/name=veso_mega_menu_column_4', 'acf_load_navigation_menus');
add_filter('acf/load_field/name=veso_mega_menu_column_5', 'acf_load_navigation_menus');


function acf_load_footer_select($field) {
	$field['choices'] = array();
	$args = array(
		'post_type' => 'veso_footer',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);
	$field['choices']['default'] = 'Default';
	$field['choices']['disabled'] = 'Disable footer';

	$posts = get_posts($args);
	foreach($posts as $post) {
		$field['choices'][$post->ID] = $post->post_title;
	}
	wp_reset_query();
	return $field;

}
add_filter('acf/load_field/name=veso_select_footer', 'acf_load_footer_select');


function acf_render_field_show_palette($field) {
	if(!isset($field['hide_palette'])) {
		$field['hide_palette'] = 0;
	}
	return $field;
}

add_filter('acf/load_field/type=extended-color-picker', 'acf_render_field_show_palette', 2);
