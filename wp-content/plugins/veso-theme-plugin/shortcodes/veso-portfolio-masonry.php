<?php

class Veso_Portfolio_Masonry {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_portfolio_masonry', array($this, 'veso_shortcode' ));
		add_action( 'rest_api_init', array($this, 'veso_rest_portfolio_load_more' ));
	}

	public function veso_shortcode( $atts ) {
		$body_accent_color = get_field('veso_accent_color', 'option');
		extract(shortcode_atts(array(
			'criteria' => 'categories',
			'category' => '',
			'posts' => '',
			'type' => 'veso-pinterest veso-1-col',
			'classes' => '',
			'style' => false,
			'hover_type' => 'hover1',
			'bg_hover_color' => '',
			'bg_hover_color1' => '',
			'accent_color' => '',
			'hover_color' => '',
			'hover_color1' => '',
			'header_size' => '5',
			'open_portfolio' => 'lightbox',
			'btn_size' => 'btn-md',
			'btn_type' => 'btn-outline',
			'btn_color' => 'btn-dark',
			'btn_align' => 'text-center',
			'bg_btn' => '#fff',
			'text_color' => '#333',
			'accent_color' => $body_accent_color,
			'perpage' => 8,
			'show_gutter' => '',
			'layout_type' => 'default',
			'custom_layout'=>'1,1,2,1,1,3', 
		), $atts));
		$id = 'id_'.uniqid(true).mt_rand();		
		$default_layout = '';
		$imgAsBg = (strpos($type, 'pinterest') == false) ? true : false;
		$masonryClass = (strpos($type, 'masonry')) ? true : false;
		$layout_index1 = 0;
		$layout = 0;
		$categoryArray = explode(',', $category);

		if($layout_type == 'default' && $masonryClass) {
			$default_layout = array(
				'w2-h2', 'w2-h1', 'w1-h1', 'w1-h1',
				'w1-h1', 'w1-h2', 'w1-h1', 'w1-h1', 'w1-h1', 'w2-h1',
				'w1-h1', 'w1-h1', 'w2-h2', 'w1-h1', 'w1-h1',
				'w2-h1', 'w1-h1', 'w1-h1',
				'w2-h1', 'w1-h2', 'w1-h1', 'w1-h1', 'w1-h1', 'w1-h1',
				'w2-h1', 'w1-h1', 'w1-h1', 'w1-h1', 'w1-h1', 'w2-h1',
				'w1-h1', 'w2-h1', 'w1-h1',
				'w1-h1', 'w1-h1', 'w2-h1',
				'w1-h1', 'w2-h2', 'w1-h1', 'w1-h1', 'w1-h1',
			);
		}

		$layoutString = '';
		if ( isset($custom_layout) && $custom_layout != '' && $layout_type == 'custom_masonry' ) {
			$layoutString = $custom_layout;
			$custom_layout = str_replace('1', 'w1-h1', $custom_layout);
			$custom_layout = str_replace('2', 'w2-h1', $custom_layout);
			$custom_layout = str_replace('3', 'w1-h2', $custom_layout);
			$custom_layout = str_replace('4', 'w2-h2', $custom_layout);
			$custom_layout = explode(',', $custom_layout);
			$layouts = $custom_layout;
		} else {
			$layouts = $default_layout;
		}


		$output = '<div class="veso-portfolio  '.$id.'" data-header-size="'.$header_size.'"><div class="veso-portfolio-masonry '.$type.' '.$show_gutter.' '.$classes.' animate-text" data-hover="'.esc_attr($hover_type).'" data-ppp="'.esc_attr($perpage).'" data-style="'.(int)$style.'" data-cat="'.$category.'" data-ismasonry="'.(int)$masonryClass.'" data-imgbg="'.(int)$imgAsBg.'" data-layout="'.$layout_type.'" data-custom-layout="'.$layoutString.'" data-open="'.$open_portfolio.'"><div class="grid-sizer"></div><div class="gutter-sizer"></div>';

		$query_params = array(
			'post_status' => 'publish',
			'post_type' => 'veso_portfolio',
			'posts_per_page' => $perpage,
			'ignore_sticky_posts' => true,
			'order'=>'ASC',
			'orderby'=>'meta_value_num',
			'meta_key'=>'veso_portfolio_order',
		);
		$btn_txt = $btn_bg = '';
		if($btn_color == 'custom') {
			if($btn_type == 'btn-solid') {
				$btn_bg = 'style="background: '.$bg_btn.'"';
			} 
			if($btn_type == 'btn-outline') {
				$btn_bg = 'style="border-color: '.$text_color.'"';
			}
			$btn_txt = 'style="color: '.$text_color.'"';
		}

		if($criteria == 'categories' && is_array($categoryArray) && !empty($category)) {
			$query_params['tax_query'] = array(array('taxonomy'=>'veso_portfolio_categories', 'include_children'=>true, 'terms'=> $categoryArray, 'field' => 'term_id'));
		}

		if($criteria == 'posts' && !empty($posts)) {
			$postsArray = explode(',', $posts);
			if(is_array($postsArray)) {
				$query_params['post__in'] = $postsArray;
				$query_params['orderby'] = 'post__in';
				$query_params['posts_per_page'] = -1;
			}
		}

		$the_query = new WP_Query( $query_params );

		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$post = $the_query->post;
				$image = (has_post_thumbnail()) ? get_the_post_thumbnail_url( '', 'large') : '';
				if(strpos($image, '.gif') != false) {
					$image = (has_post_thumbnail()) ? get_the_post_thumbnail_url( '', 'full') : '';
				}

				if($layout_type != 'default') {
					$layout = $layout_index1;
					if(isset($layouts[$layout])) {
						$layout = $layouts[$layout];
					} else { 
						$layout = 'w1-h1';
					}
				}

				if($masonryClass) {
					if($layout_type == 'default') {
						$sizeClass = ($sizeClass = get_field('veso_portfolio_grid_size')) ? $sizeClass : 'w1-h1'; 
					} else {
						$sizeClass = $layout;
					}
 					$output .= '<article class="'.$sizeClass.'">';
				} else {
					$output .= '<article>';
				}
				$output .= veso_get_portfolio_markup($image, $imgAsBg, $hover_type, $style, $post, $open_portfolio);
				$output .= '</article>';
				$layout_index1++;
				if ( !isset($layouts[$layout_index1]) ) {
					$layout_index1 = 0;
				}
			endwhile;		
		} 

		wp_reset_postdata();
		$loadMore = ($the_query->max_num_pages > 1) ? '<div class="veso-load-more-wrapper '.$btn_align.'"><div class="btn '.$btn_size.' '.$btn_color.' '.$btn_type.' veso-load-more veso-load-more" '.$btn_bg.' data-index="'.$layout_index1.'"><span class="btn-text" '.$btn_txt.'>'.__('Load more','veso-theme-plugin').'</span></div></div>' : '';
		$output .= '</div>'.$loadMore.'</div>';

		$output .= '<div class="custom-styles" data-styles=".'.$id.' .btn.btn-solid.custom:after { background-color: '.$accent_color.'; } .'.$id.' .btn.btn-outline.custom:after { border-color: '.$accent_color.'; } .'.$id.' .btn.custom.btn-underline:hover .btn-text:after { border-color:  '.$accent_color.'; }"></div>';
		if($hover_type == 'hover4') {
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .portfolio-hover-img { background: '.$bg_hover_color.'; } .'.$id.' .veso-portfolio-item.text-on-hover .portfolio-text { color: '.$hover_color.' !important; } .'.$id.' .veso-portfolio-item.text-below:hover .portfolio-text { color: '.$hover_color.' !important; background: '.$bg_hover_color.'}"></div>';
		} else {
			$output .= '<div class="custom-styles" data-styles=".'.$id.' .veso-portfolio-item .portfolio-text { background-color: '.$bg_hover_color1.' !important; } .'.$id.' .veso-portfolio-item .portfolio-text { color: '.$hover_color1.' } .'.$id.' .veso-portfolio-item.text-below.hover3 .image-wrapper:after { background-color: '.$accent_color.'} .'.$id.' .veso-portfolio-item.text-on-hover.hover3 .portfolio-text { background-color: '.$bg_hover_color1.'; }"></div>';
		} 

		return $output; 
	}

	public function veso_map_vc_params() {
		$taxonomy = 'veso_portfolio_categories';
		$terms = get_terms( array( 
		    'taxonomy' => $taxonomy,
		) );
		$categories = array();
		foreach($terms as $term) {
			$name = $term->name;
			$id = $term->term_id;
			$categories[$name] = $id;
		}
		vc_map(array(
			"name" => __("Veso Portfolio", "veso-theme-plugin"),
			"base" => "veso_portfolio_masonry",
			"class" => "",
			"description" => __('Add Portfolio', 'veso-theme-plugin'),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"category" => __( "Content", "veso-theme-plugin"),
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select query criteria", "veso-theme-plugin" ),
					"param_name" => 'criteria',
					"value" => array(
						__('categories', 'veso-theme-plugin') => 'categories',
						__('posts', 'veso-theme-plugin') => 'posts',
					),
				),				
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __( "Show categories", "veso-theme-plugin" ),
					"param_name" => "category",
					"value" => $categories,
					'dependency' => array('element' => 'criteria', 'value' => 'categories')
				),

				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Posts ID's", "veso-theme-plugin" ),
					"param_name" => "posts",
					'value' => '',
					'dependency' => array('element' => 'criteria', 'value' => 'posts')
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Posts per page", "veso-theme-plugin" ),
					"param_name" => "perpage",
					'value' => 8,
					'dependency' => array('element' => 'criteria', 'value' => 'categories')
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select type", "veso-theme-plugin" ),
					"param_name" => "type",
					'value' => array(
						__('Single column', 'veso-theme-plugin') => 'veso-pinterest veso-1-col',
						__('Grid - 2 columns', 'veso-theme-plugin') => 'veso-grid veso-2-col',
						__('Grid - 3 columns', 'veso-theme-plugin') => 'veso-grid veso-3-col',
						__('Grid - 4 columns', 'veso-theme-plugin') => 'veso-grid veso-4-col',
						__('Grid - 5 columns', 'veso-theme-plugin') => 'veso-grid veso-5-col',
						__('Grid - 6 columns', 'veso-theme-plugin') => 'veso-grid veso-6-col',
						__('Masonry - 3 columns', 'veso-theme-plugin') => 'veso-masonry veso-3-col',
						__('Masonry - 4 columns', 'veso-theme-plugin') => 'veso-masonry veso-4-col',
						__('Masonry - 5 columns', 'veso-theme-plugin') => 'veso-masonry veso-5-col',
						__('Masonry - 6 columns', 'veso-theme-plugin') => 'veso-masonry veso-6-col',
						__('Masonry scattered - 3 columns', 'veso-theme-plugin') => 'veso-masonry-sd veso-3-col',
						__('Masonry scattered - 4 columns', 'veso-theme-plugin') => 'veso-masonry-sd veso-4-col',
						__('Pinterest - 2 columns', 'veso-theme-plugin') => 'veso-pinterest veso-2-col',
						__('Pinterest - 3 columns', 'veso-theme-plugin') => 'veso-pinterest veso-3-col',
						__('Pinterest - 4 columns', 'veso-theme-plugin') => 'veso-pinterest veso-4-col',
						__('Pinterest - 5 columns', 'veso-theme-plugin') => 'veso-pinterest veso-5-col',
						__('Pinterest - 6 columns', 'veso-theme-plugin') => 'veso-pinterest veso-6-col',
					),
				),

				array(
					'type' => 'dropdown',
					'heading' => __('Masonry layout', 'veso-theme-plugin'),
					"param_name" => "layout_type",
					"value" => array(
						__('From elements') => 'default',
						__('Custom masonry') => 'custom_masonry',
					),
					'dependency' => array('element' => 'type', 'value' => array('veso-masonry veso-3-col', 'veso-masonry veso-4-col', 'veso-masonry veso-5-col', 'veso-masonry veso-6-col', 'veso-masonry-sd veso-3-col', 'veso-masonry-sd veso-4-col')),
				),
				array(
					'type' => 'textarea',
					'heading' => __('Custom masonry string', 'veso-theme-plugin'),
					"param_name" => "custom_layout",
					'value' => '1,1,2,1,1,3',
					"description" => __("Write your custom layout loop. Use values: 1 - square, 2 - wide rectangle, 3 - tall rectangle, 4 - large square. Separate values with comma, i.e. 1,1,2,1,1,3", "veso-theme-plugin"),
					'dependency' => array('element' => 'layout_type', 'value' => 'custom_masonry'),
				),


				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Show title & categories", "veso-theme-plugin" ),
					"param_name" => 'style',
					"value" => array(
						__('below image', 'veso-theme-plugin') => false,
						__('on hover', 'veso-theme-plugin') => true,
					),
					"std" => false
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select hover type", "veso-theme-plugin" ),
					"param_name" => 'hover_type',
					"value" => array(
						__('Hover 1', 'veso-theme-plugin') => 'hover1',
						// __('Hover 2', 'veso-theme-plugin') => 'hover2',
						__('Hover 2', 'veso-theme-plugin') => 'hover3',
						__('Hover 3', 'veso-theme-plugin') => 'hover4',
						// __('Hover 5', 'veso-theme-plugin') => 'hover5',
					),
					"std" => "hover1"
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select header size", "veso-theme-plugin" ),
					"param_name" => 'header_size',
					"value" => array(
						'H1' => '1',
						'H2' => '2',
						'H3' => '3',
						'H4' => '4',
						'H5' => '5',
					),
					"std" => "5"
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Open portfolio", "veso-theme-plugin" ),
					"param_name" => 'open_portfolio',
					"value" => array(
						__('in lightbox', 'veso-theme-plugin') => 'lightbox',
						__('featured image') => 'lightbox_images',
						__('in portfolio page', 'veso-theme-plugin') => 'portfolio_page',
					),
					"std" => "lightbox"
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( 'Show spacing between elements', 'veso-theme-plugin' ),
					"param_name" => 'show_gutter',
					"value" => array(
						__('No', 'veso-theme-plugin') => '',
						__('Yes', 'veso-theme-plugin') => 'show-gutter',
					),
				),
					array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_hover_color",		
					"value" => "",
					'description' => __( 'Select background color for 4th hover style. Leave empty to different colors on each portfolios (Notice: set in portfolio item).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => 'hover4'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_hover_color1",		
					"value" => "",
					// 'description' => __( 'Select background color hover.', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => array('hover1','hover2','hover3','hover5')),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "hover_color",		
					"value" => "",
					'description' => __( 'Select text color for 4th hover style. Leave empty to different colors on each portfolios (Notice: set in portfolio item).', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => 'hover4'),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "hover_color1",		
					"value" => "",
					// 'description' => __( 'Select text color hover', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => array('hover1','hover2','hover3','hover5')),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",		
					"value" => "",	
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Extra classes", "veso-theme-plugin" ),
					"param_name" => "classes",
					'value' => '',
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
					'std' => 'btn-sm',
					'group' => __( 'Button', 'js_composer' ),	
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
					'std' => 'btn-outline',
					'group' => __( 'Button', 'js_composer' ),	
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button color", "veso-theme-plugin" ),
					"param_name" => "btn_color",
					"value" => array(
						'Dark' => 'btn-dark',
						'Light' => 'btn-light',
						'Custom color' => 'custom',
					),
					'std' => 'btn-dark',
					'group' => __( 'Button', 'js_composer' ),	
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
					"param_name" => "btn_text_color",		
					"value" => "#333",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'js_composer' ),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "btn_accent_color",		
					"value" => "#ffb573",
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),
					'group' => __( 'Button', 'js_composer' ),	
				),
			)
		));
	}

	function veso_rest_portfolio_load_more() {
		// Declare our namespace
		$namespace = 'wp/v2';

		// Register the route
		register_rest_route( $namespace, '/portfolio/', array(
			'methods'   => 'POST',
			'callback'  => array($this, 'veso_rest_portfolio_load_more_handler'),
			'args'      => array(
				'page'  => array( 'required' => true ),
				'posts_per_page' => array('required' => true),
				'categories',
			)
		) );
	}

	function veso_rest_portfolio_load_more_handler( $request ) {
		$params = $request->get_params();
		$output = '';
		$default_layout = '';
		$imgAsBg = $params['imgbg'];
		$hover_type = $params['hover'];
		$style = (bool)$params['style'];
		$masonryClass = (bool)$params['masonry'];
		$layout_type = $params['layout'];
		$custom_layout = $params['custom_layout'];
		$layout_index1 = $params['index'];
		$category = $params["categories"];
		$open_portfolio = $params['open_portfolio'];
		$query_params = array(
			'post_status' => 'publish',
			'post_type' => 'veso_portfolio',
			'paged' => $params['page'],
			'posts_per_page' => $params['posts_per_page'],
			'ignore_sticky_posts' => true,
			'order'=>'ASC',
			'orderby'=>'meta_value_num',
			'meta_key'=>'veso_portfolio_order',
		);

		$categoryArray = explode(',', $category);
		if(is_array($categoryArray) && !empty($category)) {
			$query_params['tax_query'] = array(array('taxonomy'=>'veso_portfolio_categories', 'include_children'=>true, 'terms'=> $categoryArray, 'field' => 'term_id'));
		}


		if($layout_type == 'default' && $masonryClass) {
			$default_layout = array(
				'w2-h2', 'w2-h1', 'w1-h1', 'w1-h1',
				'w1-h1', 'w1-h2', 'w1-h1', 'w1-h1', 'w1-h1', 'w2-h1',
				'w1-h1', 'w1-h1', 'w2-h2', 'w1-h1', 'w1-h1',
				'w2-h1', 'w1-h1', 'w1-h1',
				'w2-h1', 'w1-h2', 'w1-h1', 'w1-h1', 'w1-h1', 'w1-h1',
				'w2-h1', 'w1-h1', 'w1-h1', 'w1-h1', 'w1-h1', 'w2-h1',
				'w1-h1', 'w2-h1', 'w1-h1',
				'w1-h1', 'w1-h1', 'w2-h1',
				'w1-h1', 'w2-h2', 'w1-h1', 'w1-h1', 'w1-h1',
			);
		}

		if ( isset($custom_layout) && $custom_layout != '' && $layout_type == 'custom_masonry' ) {
			$custom_layout = str_replace('1', 'w1-h1', $custom_layout);
			$custom_layout = str_replace('2', 'w2-h1', $custom_layout);
			$custom_layout = str_replace('3', 'w1-h2', $custom_layout);
			$custom_layout = str_replace('4', 'w2-h2', $custom_layout);
			$custom_layout = explode(',', $custom_layout);
			$layouts = $custom_layout;
		} else {
			$layouts = $default_layout;
		}

		$the_query = new WP_Query( $query_params );

		if($params['page'] == $the_query->max_num_pages) {
			$nextPage = false;
		} else {
			$nextPage = $params['page'] + 1;
		}

		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$post = $the_query->post;
				$image = (has_post_thumbnail()) ? get_the_post_thumbnail_url( '', 'large') : '';
				if(strpos($image, '.gif') != false) {
					$image = (has_post_thumbnail()) ? get_the_post_thumbnail_url( '', 'full') : '';
				}

				if($layout_type != 'default') {
					$layout = $layout_index1;
					$layout = $layouts[$layout];
				}

				if($masonryClass) {
					if($layout_type == 'default') {
						$sizeClass = ($sizeClass = get_field('veso_portfolio_grid_size')) ? $sizeClass : 'w1-h1'; 
					} else {
						$sizeClass = $layout;
					}
 					$output .= '<article class="'.$sizeClass.'">';
				} else {
					$output .= '<article>';
				}
				$output .= veso_get_portfolio_markup($image, $imgAsBg, $hover_type, $style, $post, $open_portfolio);
				$output .= '</article>';
				$layout_index1++;
				if ( !isset($layouts[$layout_index1]) ) {
					$layout_index1 = 0;
				}
			endwhile;		
		} 

		return new WP_REST_Response( array('status' => 'success', 'output'=>$output, 'next'=>$nextPage, 'index'=>$layout_index1), 200 );
	}

}