<?php

class Veso_Blog {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_blog', array($this, 'veso_shortcode' ));
		add_action( 'rest_api_init', array($this, 'veso_rest_blog_load_more' ));
	}

	function veso_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'style' => 'blog-list',
			'category' => '',
			'perpage' => 3,
			'grid_col' => 'veso-grid veso-3-col',
			'perview' => '3',
			'pagination' => '1',
			'autoplay' => '0',
			'alignment' => 'text-left',
			'accent' => '',
			'btn_size' => 'btn-md',
			'btn_type' => 'btn-solid',
			'btn_color' => 'btn-dark',
			'btn_align' => 'text-center',
			'bg_btn' => '#fff',
			'text_color' => '#333',
			'accent_color' => '#ffb573',
			'show_cat' => '1',
			'show_img' => '1',
			'extra_class' => '',
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();

		$output = $gutter = $image = $image_url = $carouselClass = $carouselAutoplay = $carouselPagination = $carouselPerview = $btn_txt = $btn_bg = '';
		if($btn_color == 'custom') {
			if($btn_type == 'btn-solid') {
				$btn_bg = 'style="background: '.$bg_btn.'"';
			} 
			if($btn_type == 'btn-outline') {
				$btn_bg = 'style="border-color: '.$text_color.'"';
			}
			$btn_txt = 'style="color: '.$text_color.'"';
		}
		$category = trim($category);
		$categoryArray = explode(',', $category);
		$gridClass = (strpos($grid_col, 'grid')) ? true : false;
		$imgAsBg = (strpos($grid_col, 'pinterest') == false) ? true : false;
		if($gridClass) {
			$default_layout = array('w1-h1');	
		} 
		if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
		elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
		else { $paged = 1; }
		$query_params = array(
			'post_type' => 'post',
			'paged' => $paged,
			'ignore_sticky_posts' => true
		);
		if(is_array($categoryArray) && !empty($category)) {
			$query_params['category__in'] = $categoryArray;
		}
		$query_params['posts_per_page'] = $perpage;

		$wp_query = new WP_Query( $query_params );
		if($style == 'blog-grid') {
			$gutter = 'show-gutter';
			$grid_col = $grid_col; 
		} else {
			$grid_col = '';
		}
		if($style == 'blog-carousel') {
			$carouselClass = 'swiper-container';
			$carouselAutoplay = 'data-autoplay="'.$autoplay.'"';
			$carouselPagination = 'data-pagination="'.$pagination.'"';
			$carouselPerview = 'data-perview="'.$perview.'"';
		}

		$output = '<div class="content animate-text blog veso-'.$style.' '.$grid_col.' '.$extra_class.' '.$gutter.' '.$carouselClass.' '.$id.'" data-ppp="'.esc_attr($perpage).'" data-imgbg="'.(int)$imgAsBg.'" data-cat="'.$category.' " '.$carouselAutoplay.' '.$carouselPagination.' '.$carouselPerview.' data-style="'.$style.'" data-show-img="'.$show_img.'" data-alignment="'.$alignment.'" data-grid-class="'.$gridClass.'" data-show-cat="'.$show_cat.'">';

		if($style != 'blog-carousel') {
			$output .= '<div class="blog-inner">';
		}

		if($style == 'blog-grid') {
			$output .= '<div class="grid-sizer"></div><div class="gutter-sizer"></div>';
		}
		if($style == 'blog-carousel') {
			$output .= '<div class="swiper-wrapper">';
		}
		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
				$output .= $this->generateMarkup($style, $show_img, $alignment, $gridClass, $show_cat);
			endwhile;		
			wp_reset_postdata();
		}
		
		if($style == 'blog-carousel') {
			$output .= '</div>';
			if($pagination) {
				$output .= '<div class="veso-carousel-pagination"></div>';
			}
		}
		if($style !== 'blog-carousel') {
		$loadMore = ($wp_query->max_num_pages > 1) ? '<div class="veso-load-more-wrapper '.$btn_align.'"><div class="btn '.$btn_size.' '.$btn_color.' '.$btn_type.' veso-load-more veso-load-more" '.$btn_bg.'><span class="btn-text" '.$btn_txt.'>'.__('Load more','veso-theme-plugin').'</span></div></div>' : '';
		} else {
			$loadMore = '';
		}
		if($style != 'blog-carousel') {
			$output .= '</div>';
		}
		$output .= $loadMore.'</div>';
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .btn.btn-solid.custom:after { background-color: '.$accent_color.'; }
			.'.$id.' .btn.btn-outline.custom:after { border-color: '.$accent_color.'; }
			.'.$id.' .btn.custom.btn-underline:hover .btn-text:after { border-color:  '.$accent_color.'; } .'.$id.'.blog .post-meta .post-title a > span { background-image: linear-gradient(transparent 75%, '.$accent.' 0%)} .'.$id.'.blog .veso-hover-text:before, .'.$id.'.blog .veso-hover-text:after { border-color: '.$accent.'} .'.$id.'.blog .btn-read-more:hover  .btn-text:after { border-color: '.$accent.'}"></div>';
		wp_reset_postdata();
		return $output;
	}

	public function generateMarkup($style, $show_img, $alignment, $gridClass, $show_cat, $isRest = false) {
		$output = '';
		$image = '';
		$sticky = '';
		if ( is_sticky() ) {
			$sticky = 'sticky-post';
		}
		$categories_list = '';
		if($show_cat == '1') {
			$postCats = get_the_category(); 
			if (is_array($postCats)) {
				foreach ($postCats as $c => $category) {
					$categories_list .= '<li><a class="veso-hover-text" href="' . esc_url(get_category_link($category->term_id)) . '" title="' . esc_attr($category->name) . '">' . esc_html($category->name) . '</a><span class="el-icon-spacing"></span></li>';
				}
			} 
		}

		if($isRest == false) {
			$loaded = 'loaded-post';
		} else {
			$loaded = '';
		}

		if($style == 'blog-list') {
			$output .= '<div class="post-header '.esc_attr($sticky).'">';
			if ( has_post_thumbnail() && $show_img == '1') {
				$image = get_post_thumbnail_id();
				$image_url = wp_get_attachment_image_src($image, 'large');

				$image = 'background-image: url('.esc_url($image_url[0]).')';
				$output .= '<a href="'.esc_url(get_permalink()).'" class="post-images">
					<img class="b-lazy" src="'.veso_image_preloader($image_url[1],$image_url[2]).'" data-src="'.$image_url[0].'" alt=""/>
				</a>
				<div class="post-meta '.$loaded.' '.$alignment.'">';
			} else {
				$output .= '<div class="post-meta '.$loaded.' '.$alignment.' meta-full-width">';		
			}
			$output .= '<ul class="post-cat-meta meta-author-date">
				<li class="posted-on post-cat-date">' . veso_time_link() . '</li>
			</ul><header><h4 class="post-title"><a href="'.esc_url(get_permalink()).'"><span>'.get_the_title().'</span></a></h4></header>';
			$output .= '<ul class="post-cat-meta"><li class="byline post-cat-author">'.__( 'By', 'veso' ).' <a class="url fn n veso-hover-text" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></li>
					' . $categories_list . '
				</ul>
			<div class="content excerpt">'.get_the_excerpt().'</div>
			<a href="'.esc_url(get_permalink()).'" class="btn btn-read-more btn-dark btn-md btn-underline">
				<span class="btn-text veso-header">'.esc_html('Read more', 'veso-theme-plugin').'</span>
			</a>
			</div></div>';
		} elseif ($style == 'blog-grid') {
			if($gridClass) {
				if ( has_post_thumbnail() && $show_img == '1') {
					$image = get_post_thumbnail_id();
					$image_url = wp_get_attachment_image_src($image, 'large');
				}
				$output .= '<article class="w1-h1 post-header '.esc_attr($sticky).'">
					<div class="veso-blog-item">';
						if ( has_post_thumbnail() && $show_img == '1') {
						$output .= '<div class="image-wrapper"><a class="post-images" href="'.esc_url(get_permalink()).'"><div class="img-bg b-lazy" data-src="'.$image_url[0].'"></div></a></div>';
						}
				$output .= '<div class="post-meta '.$loaded.' '.$alignment.'">
						<header><h5 class="post-title"><a href="'.esc_url(get_permalink()).'"><span>'.get_the_title().'</span></a></h5></header>
						<ul class="post-cat-meta">
							' . $categories_list . '
						</ul>
						<div class="content excerpt">'.get_the_excerpt().'</div>
						<ul class="post-cat-meta meta-author-date">
							'.veso_posted_on().'
						</ul>';
				$output .= '</div></div></article>';
			} else {
				$output .= '<article class="post-header '.esc_attr($sticky).'">';
				$output .= '<div class="veso-blog-item">';
				if ( has_post_thumbnail() && $show_img == '1') {
					$image = get_post_thumbnail_id();
					$image_url = wp_get_attachment_image_src($image, 'large');
					$output .= '<div class="image-wrapper">
						<a href="'.esc_url(get_permalink()).'" class="post-images"><img class="b-lazy" height="'.$image_url[2].'" width="'.$image_url[1].'" src="'.veso_image_preloader($image_url[1],$image_url[2]).'" alt="" data-src="'.esc_html($image_url[0]).'"></a>
						</div>';
				}

				$output .= '<div class="post-meta '.$loaded.' '.$alignment.'">
						<header><h5 class="post-title"><a href="'.esc_url(get_permalink()).'"><span>'.get_the_title().'</span></a></h5></header>
						<ul class="post-cat-meta">
							' . $categories_list . '
						</ul>
						<div class="content excerpt">'.get_the_excerpt().'</div>
						<ul class="post-cat-meta meta-author-date">
							'.veso_posted_on().'
						</ul>
					</div>
				</div></article>';
			}
		} elseif($style == 'blog-chessboard') {
			$output .= '<div class="post-header '.esc_attr($sticky).'">';
				if ( has_post_thumbnail() && $show_img == '1') {
					$image = get_the_post_thumbnail_url( '', 'large');
					$output .= '<a href="'.esc_url(get_permalink()).'" class="post-images">
						<div class="b-lazy" data-src="'.esc_url($image).'">
						</div>
					</a>';
				}
			$output .= '<div class="post-meta '.$loaded.' '.$alignment.'">
					<header><h5 class="post-title"><a href="'.esc_url(get_permalink()).'"><span>'.get_the_title().'</span></a></h5></header>
					<ul class="post-cat-meta">
						'.veso_posted_on().'
					</ul>
				</div>
			</div>';
		} else {
			$output .= '<div class="swiper-slide post-header '.esc_attr($sticky).'">';
				if ( has_post_thumbnail() && $show_img == '1') {
					$image = get_the_post_thumbnail_url( '', 'large');
					$output .= '<a href="'.esc_url(get_permalink()).'" class="post-images">
						<div class="b-lazy" data-src="'.esc_url($image).'">
						</div>
					</a>';
				}
				$output .= '<div class="post-meta '.$loaded.' '.$alignment.'">
					<header><h5 class="post-title"><a href="'.esc_url(get_permalink()).'"><span>'.get_the_title().'</span></a></h5></header>
					<ul class="post-cat-meta">
						' . $categories_list . '
					</ul>
					<div class="content excerpt">'.get_the_excerpt().'</div>
					<ul class="post-cat-meta meta-author-date">
						'.veso_posted_on().'
					</ul>
				</div>
			</div>';
		}
		return $output;
	}
	
	function veso_rest_blog_load_more() {
		// Declare our namespace
		$namespace = 'wp/v2';

		// Register the route
		register_rest_route( $namespace, '/blog/', array(
			'methods'   => 'POST',
			'callback'  => array($this, 'veso_rest_blog_load_more_handler'),
			'args'      => array(
				'page'  => array( 'required' => true ),
				'posts_per_page' => array('required' => true),
				'categories',
				'style' => array( 'required' => true ),
			)
		) );
	}

	function veso_rest_blog_load_more_handler( $request ) {
		$params = $request->get_params();
		$output = '';

		$grid_col = 3;
		$page = $params['page'];
		$perpage = $params['posts_per_page'];
		$style = $params['style'];
		$show_img = $params['show_img'];
		$alignment = $params['alignment'];
		$gridClass = $params['grid_class'];
		$show_cat = $params['show_cat'];
		$category = trim($params['categories']);

		$categoryArray = explode(',', $category);

		$query_params = array(
			'post_type' => 'post',
			'paged' => $page,
			'posts_per_page' => $perpage,
			'ignore_sticky_posts' => true
		);

		if(is_array($categoryArray) && !empty($category)) {
			$query_params['category__in'] = $categoryArray;
		}

		if($style !== 'blog-carousel') {
			$query_params['posts_per_page'] = $perpage;
		} else {
			$query_params['posts_per_page'] = -1;
		}
		$wp_query = new WP_Query( $query_params );

		if($params['page'] == $wp_query->max_num_pages) {
			$nextPage = false;
		} else {
			$nextPage = $params['page'] + 1;
		}

		if ( $wp_query->have_posts() ) {
			while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
				$output .= $this->generateMarkup($style, $show_img, $alignment, $gridClass, $show_cat, true);
			endwhile;		
		}
		wp_reset_postdata();

		return new WP_REST_Response( array('status' => 'success', 'output'=>$output, 'next'=>$nextPage), 200 );
	}

	public static function veso_map_vc_params() {
		$taxonomy = 'category';
		$terms = get_terms( array( 
		    'taxonomy' => 'category',
		) );

		$blogCategories = array();
		foreach($terms as $term) {
			$name = $term->name;
			$id = $term->term_id;
			$blogCategories[$name] = $id;
		}

		vc_map( array(
			"name" => __( "Veso Blog", "veso-theme-plugin" ),
			"base" => "veso_blog",
			"class" => "",
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"category" => __( "Content", "veso-theme-plugin"),
			"params" => array(
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __( "Show categories", "veso-theme-plugin" ),
					"param_name" => "category",
					"value" => $blogCategories,
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Style", "veso-theme-plugin" ),
					"param_name" => 'style',
					"value" => array(
						__('List', 'veso-theme-plugin') => 'blog-list',
						__('Masonry', 'veso-theme-plugin') => 'blog-grid',
						__('Carousel', 'veso-theme-plugin') => 'blog-carousel',
						__('Chessboard', 'veso-theme-plugin') => 'blog-chessboard',
					),
					"std" => 'blog-list'
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
					"heading" => __( "Masonry style", "veso-theme-plugin" ),
					"param_name" => 'grid_col',
					"value" => array(
						__('Grid - 2 columns', 'veso-theme-plugin') => 'veso-grid veso-2-col',
						__('Grid - 3 columns', 'veso-theme-plugin') => 'veso-grid veso-3-col',
						__('Grid - 4 columns', 'veso-theme-plugin') => 'veso-grid veso-4-col',
						__('Masonry - 2 columns', 'veso-theme-plugin') => 'veso-pinterest veso-2-col',
						__('Masonry - 3 columns', 'veso-theme-plugin') => 'veso-pinterest veso-3-col',
						__('Masonry - 4 columns', 'veso-theme-plugin') => 'veso-pinterest veso-4-col',
					),
					"std" => 'veso-grid veso-3-col',
					"dependency" => array("element" => "style","value" => 'blog-grid'),	
				),	
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Posts per view", "veso-theme-plugin" ),
					"param_name" => 'perview',
					"value" => array(
						__('2', 'veso-theme-plugin') => '2',
						__('3', 'veso-theme-plugin') => '3',
						__('4', 'veso-theme-plugin') => '4',
					),
					"std" => '3',
					"dependency" => array("element" => "style","value" => 'blog-carousel'),	
				),	
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Content position", "veso-theme-plugin" ),
					"param_name" => 'alignment',
					"value" => array(
						__('Left', 'veso-theme-plugin') => 'text-left',
						__('Center', 'veso-theme-plugin') => 'text-center',
						__('Right', 'veso-theme-plugin') => 'text-right',
					),
					"std" => 'text-left'
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
					"dependency" => array("element" => "style","value" => 'blog-carousel'),	
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
					"dependency" => array("element" => "style","value" => 'blog-carousel'),	
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Show image", 'veso-theme-plugin'),
					"param_name" => "show_img",	
					"value" => array(
						'on' => '1',
					),
					'std' => '1',
					"dependency" => array("element" => "style","value" => array('blog-list', 'blog-grid', 'blog-carousel')),	
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
					"dependency" => array("element" => "style","value" => array('blog-list', 'blog-grid', 'blog-carousel')),	
				),
				array(
					"type" => "textfield",
					"heading" => __("Extra class", "veso-theme-plugin"),
					"param_name" => "extra_class",
					"admin_label" => false,
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent",		
					"value" => "",
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),		
					'group' => __( 'Colors', 'veso-theme-plugin' ),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button position", "veso-theme-plugin" ),
					"param_name" => "btn_align",
					"value" => array(
						__( 'Left', 'veso-theme-plugin' ) => 'text-left',
						__( 'Center', 'veso-theme-plugin' ) => 'text-center',
						__( 'Right', 'veso-theme-plugin' ) => 'text-right',
					),
					'std' => 'text-center',
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button size", "veso-theme-plugin" ),
					"param_name" => "btn_size",
					"value" => array(
						__('Large', 'veso-theme-plugin') => 'btn-lg',
						__('Medium', 'veso-theme-plugin') => 'btn-md',
						__('Small', 'veso-theme-plugin') => 'btn-sm',
						__('Extra small', 'veso-theme-plugin') => 'btn-xs',
					),
					'std' => 'btn-md',
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button style", "veso-theme-plugin" ),
					"param_name" => "btn_type",
					"value" => array(
						__( "Solid color", "veso-theme-plugin" ) => 'btn-solid',
						__( "Outline", "veso-theme-plugin" ) => 'btn-outline',
						__( "Underline", "veso-theme-plugin" ) => 'btn-underline',
					),
					'std' => 'btn-solid',
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),

				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Button color scheme", "veso-theme-plugin" ),
					"param_name" => "btn_color",
					"value" => array(
						__('Dark button' , 'veso-theme-plugin')=> 'btn-dark',
						__('Light button', 'veso-theme-plugin') => 'btn-light',
						__('Custom color', 'veso-theme-plugin') => 'custom'
					),
					'std' => 'btn-dark',
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Background color", 'veso-theme-plugin'),
					"param_name" => "bg_btn",		
					"value" => "#fff",
					'description' => __( 'Select background color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'veso-theme-plugin' ),
					
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "text_color",		
					"value" => "#333",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",		
					"value" => "#ffb573",
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),		
					"dependency" => array("element" => "btn_color","value" => 'custom'),	
					'group' => __( 'Button', 'veso-theme-plugin' ),
				),
			),
		));
	}
}