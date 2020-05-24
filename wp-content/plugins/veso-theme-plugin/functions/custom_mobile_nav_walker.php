<?php

class Veso_Burger_Nav_Walker extends Walker_Nav_Menu {

	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul $class_names>{$n}";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "$indent</ul>{$n}";
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .' class="h3">';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		if($item->object == 'veso_mega_menu') {
			$header_text = get_post_meta($item->object_id, 'veso_mega_menu_header_text', true);
			$mega_menu_1 = get_post_meta($item->object_id, 'veso_mega_menu_column_1', true);
			$mega_menu_2 = get_post_meta($item->object_id, 'veso_mega_menu_column_2', true);
			$mega_menu_3 = get_post_meta($item->object_id, 'veso_mega_menu_column_3', true);
			$mega_menu_4 = get_post_meta($item->object_id, 'veso_mega_menu_column_4', true);
			$mega_menu_5 = get_post_meta($item->object_id, 'veso_mega_menu_column_5', true);
			$ver_position = get_post_meta($item->object_id, 'veso_mega_menu_featured_content_position', true);
			$overlay_color = get_post_meta($item->object_id, 'veso_mega_menu_featured_overlay', true);
			$i = 0;
			$empty_headers = false;
			if($mega_menu_1) { $i++; }
			if($mega_menu_2) { $i++; }
			if($mega_menu_3) { $i++; }
			if($mega_menu_4) { $i++; }
			if($mega_menu_4) { $i++; }

			if($i > 0) {
				if($i != 5) {
					$col_class = 'large-'.(12 / $i);
				} else {
					$col_class = '';
				}

				$output .= '<ul class="sub-menu" data-width="'.get_post_meta($item->object_id, 'veso_mega_menu_width', true).'">';

				$image = get_the_post_thumbnail_url($item->object_id, 'large');
				$post_content = get_post($item->object_id);
				$post_content = $post_content->post_content;
				
				for ($index=1; $index <= 5; $index++) { 
					$menu_post = wp_get_nav_menu_items(get_post_meta($item->object_id, 'veso_mega_menu_column_'.$index.'', 1));
					if($menu_post) {
						// $output .= '<div class="'.$col_class.' columns"><ul class="menu-vertical">';
						if($empty_headers == false) {
							if(get_post_meta($item->object_id, 'veso_mega_menu_column_'.$index.'_header', true)) {
								$header = '<li><a href="#" class="h3">'.get_post_meta($item->object_id, 'veso_mega_menu_column_'.$index.'_header', true).'</a></li>';
							} else {
								$header = '';
							}
							$output .= $header;
						}
					
					    foreach( $menu_post as $menu_item ){
					    	$before = ( '#' == $menu_item->url ) ? '<h5>' : '<a href="'. $menu_item->url .'" class="h4">';
					    	$after = ( '#' == $menu_item->url ) ? '</h5>' : '</a>';
					    	
					    	$output .= '<li class="menu-item '. implode(' ', $menu_item->classes) .'">'. $before . $menu_item->title . $after .'</li>';	
					    }
					}
				}

				$output .= '</ul>';
			}
		}
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$output .= "</li>{$n}";
	}

} // Walker_Nav_Menu
