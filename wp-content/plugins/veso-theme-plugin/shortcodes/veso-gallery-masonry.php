<?php

class Veso_Gallery_Masonry {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_gallery_masonry', array($this, 'veso_shortcode' ));
	}

	public function veso_shortcode( $atts ) {
		extract(shortcode_atts(array(
			"images" => '', 
			'type' => 'veso-pinterest veso-1-col',
			'show_gutter' => '',
			'layout_type' => 'default',
			'custom_layout'=>'', 
			'bg_hover_color' => '#fff',
			'extra_class' => ''
		), $atts));

		$output = $default_layout = $figcaption = '';
		$layout_index1 = 0;
		$layout = 0;
	
		$gridClass = (strpos($type, 'grid')) ? true : false;
		$imgAsBg = (strpos($type, 'pinterest') == false) ? true : false;
		$masonryClass = (strpos($type, 'masonry')) ? true : false;	
		if($gridClass) {
			$default_layout = array('w1-h1');	
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
	
		if($images != '') {
			$images = explode(',', $images);
		}

		$output = '<div class="veso-portfolio veso-gallery-element"><div class="veso-portfolio-masonry '.$type.' '.$show_gutter.' '.$extra_class.' animate-text" data-ismasonry="'.(int)$masonryClass.'" data-imgbg="'.(int)$imgAsBg.'"><div class="grid-sizer"></div><div class="gutter-sizer"></div>';

		if(is_array($images)) {
			foreach ($images as $image) {
				$caption = get_the_excerpt($image );
				if($caption != '') {
					$figcaption = '<figure><figcaption class="hide">'.$caption.'</figcaption></figure>';
				}
				if($masonryClass || $gridClass) {
					$layout = $layout_index1;
					$layout = $layouts[$layout];
					if ( $layout == 'w1-h1' ) {
						$image_url = wp_get_attachment_image_src($image, 'blog_thumb');
					} elseif ( $layout == 'w2-h1' ) {
						$image_url = wp_get_attachment_image_src($image, 'blog_thumb');
					} elseif ( $layout == 'w2-h2' ) {
						$image_url = wp_get_attachment_image_src($image, 'large_bg');
					} else {
						$image_url = wp_get_attachment_image_src($image, 'large_bg');
					}
					if($gridClass) {
						$image_url = wp_get_attachment_image_src($image, 'blog_thumb');
					}
					$image_full = wp_get_attachment_image_src($image, 'full');
					$output .= '<article class="'.$layout.'"><div class="layer"><div class="img-bg" data-depth="0.50" style="background-image: url('.$image_url[0].') "></div></div>
						<div class="veso-portfolio-item open-lightbox_images"><a class="portfolio-link" href="'.$image_full[0].'" data-size="'.$image_url[1].'x'.$image_url[2].'">'.$figcaption.'</a><div class="image-wrapper"><div class="portfolio-hover-img" style="background: '.$bg_hover_color.'"></div><span class="portfolio-img b-lazy" data-src="'.esc_html($image_url[0]).'"></span></div></div>';
					$layout_index1++;
					if ( !isset($layouts[$layout_index1]) ) {
						$layout_index1 = 0;
					}
				} else {
					$image_url = wp_get_attachment_image_src($image, 'full');
					$output .= '<article>';
					$output .= '<div class="veso-portfolio-item open-lightbox_images"><a class="portfolio-link" href="'.$image_url[0].'" data-size="'.$image_url[1].'x'.$image_url[2].'">'.$figcaption.'</a><div class="image-wrapper"><div class="portfolio-hover-img" style="background: '.$bg_hover_color.'"></div><img class="b-lazy" alt="" height="'.$image_url[2].'" width="'.$image_url[1].'" src="'.veso_image_preloader($image_url[1],$image_url[2]).'" data-src="'.esc_html($image_url[0]).'"></div></div>';
				}
				$output .= '</article>';

				
			}

		}
		$output .= '</div></div>';
		return $output; 
	}

	public function veso_map_vc_params() {
		vc_map(array(
			"name" => __("Veso Gallery", "veso-theme-plugin"),
			"base" => "veso_gallery_masonry",
			"class" => "",
			"description" => __('Add a gallery', 'veso-theme-plugin'),
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "attach_images",
					"heading" => __("Images", "veso-theme-plugin"),
					"param_name" => "images",
					"admin_label" => false,
					"description" => __("Please images to display in gallery", "veso-theme-plugin")
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Gallery type', 'veso-theme-plugin'),
					"param_name" => "type",
					"value" => array(
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
					"description" => __("Please select your gallery type.", "veso-theme-plugin")	  
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Masonry layout', 'veso-theme-plugin'),
					"param_name" => "layout_type",
					"value" => array(
						__('Default') => 'default',
						__('Custom masonry') => 'custom_masonry',
					),
					'dependency' => array('element' => 'type', 'value' => array('veso-masonry veso-3-col', 'veso-masonry veso-4-col', 'veso-masonry veso-5-col', 'veso-masonry veso-6-col', 'veso-masonry-sd veso-3-col', 'veso-masonry-sd veso-4-col')),
				),
				array(
					'type' => 'textarea',
					'heading' => __('Custom masonry string', 'veso-theme-plugin'),
					"param_name" => "custom_layout",
					"description" => __("Write your custom layout loop. Use values: 1 - square, 2 - wide rectangle, 3 - tall rectangle, 4 - large square. Separate values with comma, i.e. 1,1,2,1,1,3", "veso-theme-plugin"),
					'dependency' => array('element' => 'layout_type', 'value' => 'custom_masonry'),
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
					"heading" => __("Hover background color", 'veso-theme-plugin'),
					"param_name" => "bg_hover_color",		
					"value" => "#fff",
					'description' => __( 'Select background color for hover.', 'veso-theme-plugin' ),	
				),

				array(
					"type" => "textfield",
					"heading" => __("Extra class", "veso-theme-plugin"),
					"param_name" => "extra_class",
					"admin_label" => false,
				),
			)
		));
	}
}
