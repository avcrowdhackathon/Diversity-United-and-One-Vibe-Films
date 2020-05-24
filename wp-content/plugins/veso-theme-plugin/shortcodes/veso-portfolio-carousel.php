<?php

class Veso_Portfolio_Carousel {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_portfolio_carousel', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'criteria' => 'categories',
			'category' => '',
			'style' => false,
			'posts' => '',
			'hover_type' => 'hover1',
			'open_portfolio' => 'lightbox',
			'perpage' => 3,
			'bg_hover_color' => '',
			'bg_hover_color1' => '',
			'hover_color' => '',
			'hover_color1' => '',
			'accent_color' => '',
			'speed' => '1000',
			'height_options' => 'false',
			'custom_height' => '',
			'header_size' => '3',
			'arrows' => '0',
			'pagination' => '1',
			'autoplay' => '0',
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();		

		$output = $style_hover = '';
		if($height_options == 'true') {
			$height = $custom_height;
			if(substr($height, -1)=="%") {
				$height = $height - substr($height, -1);
			}
			if(substr($height, -2)=="px") {
				$height = $height - substr($height, -2);
			}
		} else { 
			$height = 'full'; 
		}
		$output .='<div class="veso-portfolio swiper-container veso-portfolio-carousel '.$id.'" data-header-size="'.$header_size.'" data-height="'.$height.'" data-autoplay="'.$autoplay.'" data-speed="'.$speed.'" data-arrows="'.$arrows.'" data-pagination="'.$pagination.'">
				<div class="swiper-wrapper animate-text">';
		$categoryArray = explode(',', $category);

		if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
		elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
		else { $paged = 1; }

		if(is_array($categoryArray) && !empty($category)) {
			$query_params['tax_query'] = array(array('taxonomy'=>'veso_portfolio_categories', 'include_children'=>true, 'terms'=> $categoryArray, 'field' => 'term_id'));
		}
		$query_params = array(
			'post_status' => 'publish',
			'post_type' => 'veso_portfolio',
			'posts_per_page' => $perpage,
			'ignore_sticky_posts' => true,
			'order'=>'ASC',
			'orderby'=>'meta_value_num',
			'meta_key'=>'veso_portfolio_order',
		);

		if($criteria == 'categories' && is_array($categoryArray) && !empty($category)) {
			$query_params['tax_query'] = array(array('taxonomy'=>'veso_portfolio_categories', 'include_children'=>true, 'terms'=> $categoryArray, 'field' => 'term_id'));
		}

		if($criteria == 'posts' && !empty($posts)) {
			$postsArray = explode(',', $posts);
			if(is_array($postsArray)) {
				$query_params['post__in'] = $postsArray;
				$query_params['orderby'] = 'post__in';
			}
		}

		$the_query = new WP_Query( $query_params );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$post = $the_query->post;
				$image = '';
				if ( has_post_thumbnail() ) {
					$image = get_the_post_thumbnail_url( '', 'full');
				} 
				
				$output .= '<div class="swiper-slide">'.veso_get_portfolio_markup($image, false, $hover_type, $style, $post, $open_portfolio).'</div>';
			endwhile;		
		} 
		wp_reset_postdata();

		$output .= '</div>';
		if($pagination == "1") {
			$output .= '<div class="veso-gallery-pagination"></div>';
		}
		if($arrows == '1') {
			$output .= '<div class="veso-gallery-arrows show-for-large swiper-arrows">
					<div class="arrow-prev arrow">
						<div class="arrow-icon"><i class="fa fa-angle-left text-color"></i></div>
					</div>
					<div class="arrow-next arrow">
						<div class="arrow-icon"><i class="fa fa-angle-right text-color"></i></div>
					</div>
				</div>';
		}		
		$output .= '</div>';
		if($hover_type == 'hover4') {
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .portfolio-hover-img { background: '.$bg_hover_color.'; } .'.$id.' .veso-portfolio-item.text-on-hover .portfolio-text { color: '.$hover_color.' !important; } .'.$id.' .veso-portfolio-item.text-below:hover .portfolio-text { color: '.$hover_color.' !important; background: '.$bg_hover_color.'}"></div>';
		} else {
			$output .= '<div class="custom-styles" data-styles=".'.$id.' .veso-portfolio-item .portfolio-text { background-color: '.$bg_hover_color1.' !important; } .'.$id.' .veso-portfolio-item .portfolio-text { color: '.$hover_color1.' } .'.$id.' .veso-portfolio-item.text-below.hover3 .image-wrapper:after { background-color: '.$accent_color.'} .'.$id.' .veso-portfolio-item.text-on-hover.hover3 .portfolio-text { background-color: '.$bg_hover_color1.'; }"></div>';
		} 
		return $output;
	}


	public static function veso_map_vc_params() {
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
		vc_map( array(
			"name" => __( "Veso Portfolio Carousel", "veso-theme-plugin" ),
			"base" => "veso_portfolio_carousel",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
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
					'value' => 3,
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
					"heading" => __( "Carousel height", "veso-theme-plugin" ),
					"param_name" => 'height_options',
					"value" => array(
						__('full height', 'veso-theme-plugin') => 'false',
						__('custom height', 'veso-theme-plugin') => 'true',
					),
					"std" => 'false'
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Add custom height in pixels", "veso-theme-plugin" ),
					"param_name" => "custom_height",
					'value' => "",
					"dependency" => array("element" => "height_options","value" => 'true'),		
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
					"std" => '3'
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
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Show arrows", 'veso-theme-plugin'),
					"param_name" => "arrows",	
					"value" => array(
						'on' => '1',
					),
					'std' => '0',
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Show pagination", 'veso-theme-plugin'),
					"param_name" => "pagination",	
					"value" => array(
						'on' => '1',
					),
					'std' => '1',
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Autoplay", 'veso-theme-plugin'),
					"param_name" => "autoplay",	
					"value" => array(
						'on' => '1',
					),
					'std' => '0',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __("Autoplay speed", 'veso-theme-plugin'),
					"param_name" => "speed",	
					"value" => "1000",
					"dependency" => array("element" => "autoplay","value" => '1'),		
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
					'description' => __( 'Select background color hover.', 'veso-theme-plugin' ),
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
					'description' => __( 'Select text color hover', 'veso-theme-plugin' ),
					"dependency" => array("element" => "hover_type","value" => array('hover1','hover2','hover3','hover5')),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",		
					"value" => "",
				),
			)
		));
	}

}