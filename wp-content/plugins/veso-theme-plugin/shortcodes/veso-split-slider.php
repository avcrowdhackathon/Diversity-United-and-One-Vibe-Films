<?php

class Veso_Split_Slider {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_split_slider', array($this, 'veso_shortcode_container' ));
		add_shortcode( 'veso_split_slider_item', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode_container( $atts , $content ) {
		extract( shortcode_atts( array(
			'criteria' => 'categories',
			'category' => '',
			'posts' => '',
			'autoplay' => '3000',
			'show_dots' => 'true',
			'show_arrows' => 'false',
			'mouse_wheel' => '1',
			'btn' => '',
			// 'btn_text' => '',
			// 'btn_link' => '',
			// 'btn_align' => 'text-center',
			// 'btn_size' => 'btn-md',
			// 'btn_type' => 'btn-solid',
			// 'btn_color' => 'btn-dark',
			// 'bg_btn' => '#fff',
			// 'btn_text_color' => '#333',
			// 'btn_accent_color' => '#ffb573',
			'show_cat' => '1',
			'el_class' => '',
			'css' 	  => '',
		), $atts ) );
		
		$id = 'id_'.uniqid(true).mt_rand();		
		$categoryArray = explode(',', $category);
		$query_params = array(
			'post_status' => 'publish',
			'post_type' => 'veso_portfolio',
			// 'posts_per_page' => -1,
			'ignore_sticky_posts' => true,
			'order'=>'ASC',
			'orderby'=>'meta_value_num',
			'meta_key'=>'veso_portfolio_order',
		);
		
		// $btn_txt = $btn_bg = '';
		// if($btn_color == 'custom') {
		// 	if($btn_type == 'btn-solid') {
		// 		$btn_bg = 'style="background: '.$bg_btn.'"';
		// 	} 
		// 	if($btn_type == 'btn-outline') {
		// 		$btn_bg = 'style="border-color: '.$text_color.'"';
		// 	}
		// 	$btn_txt = 'style="color: '.$text_color.'"';
		// }

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
		if($mouse_wheel == '1') {
			$mouse_wheel = true;
		} else {
			$mouse_wheel = false;
		}
		
		$the_query = new WP_Query( $query_params );

		$fixed_container = $normal_container = $btn_container = '';
		$fixed_container .= '<div class="fixed-container">';
		$btn_container .= '<div class="btn-container">';
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$categories_list = $url = '';
				$post = $the_query->post;
				$image = (has_post_thumbnail()) ? get_the_post_thumbnail_url( '', 'full') : '';
				if(strpos($image, '.gif') != false) {
					$image = (has_post_thumbnail()) ? get_the_post_thumbnail_url( '', 'full') : '';
				}
				if($show_cat == '1') {
					$postCats = get_the_terms( $post->ID, 'veso_portfolio_categories' );
					if (is_array($postCats)) {
						foreach ($postCats as $c => $category) {
							$categories_list .= '<li><a class="" href="' . esc_url(get_category_link($category->term_id)) . '" title="' . esc_attr($category->name) . '">' . esc_html($category->name) . '</a></li>';
						}
					} 
				}

				if(get_field('veso_portfolio_custom_link', $post->ID) == '') {
					$url = get_permalink($post->ID);
				} else {
					$url = get_field('veso_portfolio_custom_link', $post->ID);
				}

				$fixed_container .= '<div class="veso-split-slide-desc" style="color: '.get_field('veso_portfolio_color',$post->ID).'">
					<div class="slide-text-wrapper h1"><h1><a href="'.$url.'">'.$post->post_title.'</a></h1>
						<ul class="portfolio-category">
							'.$categories_list.'
						</ul>
					</div>
				</div>';
				$btn_container .= '<div class="split-slider-btn"><a class="" href="'.$url.'"><span><span>'.__('See project', 'veso-theme-plugin').'</span></span></a></div>';
				$normal_container .= '<div class="veso-split-slide-item swiper-slide" data-bg-color="'.get_field('veso_portfolio_bg', $post->ID).'" data-color="'.get_field('veso_portfolio_color',$post->ID).'">
					<div class="slide">
						<div class="slide-img">
							<div class="img-wrapper" style="opacity: '.get_field('veso_portfolio_image_opacity', $post->ID).'">
								<img src="'.$image.'" alt="split-slide-img"/>
							</div>
						</div>
					</div>
				</div>';

			endwhile;		
		} 

		wp_reset_postdata();	

		$fixed_container .= '</div>';
		$btn_container .= '</div>';
		if($btn == '') {
			$btn_container = '';
		}
		$output = '<div class="veso-split-slider-container '.$id.' ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$el_class.'" ><div class="veso-split-slider swiper-container " data-autoplay="'.$autoplay.'" data-mouse-wheel="'.$mouse_wheel.'">';
			$output .= $fixed_container;
			$output .= $btn_container;
			$output .= '<div class="swiper-wrapper">';
				$output .= $normal_container;

			$output .= '</div>';
		if($show_dots == "true") { 
			$output .= '<div class="veso-split-slider-pagination"></div>';
		}
		if($show_arrows == "true") {
			$output .= '<div class="veso-split-slider-arrows">
				<div class="arrow-prev arrow"><div class="arrow-icon"><i class="fa fa-angle-up text-color"></i><span></span></div></div>
				<div class="arrow-next arrow"><div class="arrow-icon"><i class="fa fa-angle-down text-color"></i><span></span></div></div>
			</div>';
		}
		$output .= '</div></div>';
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
			"name" => __( "Veso Portfolio Split Slider", "veso-theme-plugin" ),
			"base" => "veso_split_slider",
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
					"heading" => __( "Select categories", "veso-theme-plugin" ),
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
					'type' 		  => 'textfield',
					'heading' 	  => __( 'Autoplay', 'veso-theme-plugin' ),
					'param_name'  => 'autoplay',
					'value' 	  => '3000',
					'description' => __( 'Delay between transitions (in ms). If this parameter is not specified, auto play will be disabled.', 'veso-theme-plugin' )
				),
				array(
					'type' 		 => 'dropdown',
					'heading' 	 => __( 'Show pagination', 'veso-theme-plugin' ),
					'param_name' => 'show_dots',
					'value' 	  => array(
						__( 'On', 'veso-theme-plugin' )  => 'true',
						__( 'Off', 'veso-theme-plugin' ) => 'false',
					),
					'std' => 'true',
				),
				array(
					'type' 		 => 'dropdown',
					'heading' 	 => __( 'Show arrows', 'veso-theme-plugin' ),
					'param_name' => 'show_arrows',
					'value' 	  => array(
						__( 'On', 'veso-theme-plugin' )  => 'true',
						__( 'Off', 'veso-theme-plugin' ) => 'false',
					),
					'std' => 'false',
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Mouse wheel control", 'veso-theme-plugin'),
					"param_name" => "mouse_wheel",	
					"value" => array(
						'on' => '1',
					),
					'std' => '1',
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Show categories", 'veso-theme-plugin'),
					"param_name" => "show_cat",	
					"value" => array(
						'on' => '1',
					),
					'std' => '1',
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __( "Show button", "veso-theme-plugin" ),
					"param_name" => "btn",
					"value" => array(
						__( "On", "veso-theme-plugin" ) => '1',
					),
					'std' => '',
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Extra class name', 'veso-theme-plugin' ),
					'param_name' => 'el_class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'veso-theme-plugin' ),
				),
				array(
					'type' => 'css_editor', 
					'heading' => __( 'CSS box', 'veso-theme-plugin' ),
					'param_name' => 'css',
					'group' => __( 'Design Options', 'veso-theme-plugin' ),
				),
			),
		));
	}

}
