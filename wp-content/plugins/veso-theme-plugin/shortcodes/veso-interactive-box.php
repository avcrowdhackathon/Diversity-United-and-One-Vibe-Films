<?php

class Veso_Interactive_Box {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_interactive_box', array($this, 'veso_shortcode' ));
	}

	protected $overlay_color;

	function veso_shortcode( $atts , $content ) {
		extract( shortcode_atts( array(
			'box_type' => 'box_standard',
			'blog_category' => '',
			'post_per_page' => '4',
			'image' => '',
			'sm_height' => '',
			'lg_height' => '',
			'link' => '',
			'lightbox' => '1',
			'video_link' => '',
			'title' => '',
			'text_color' => '#ffffff',
			'overlay_color' => 'rgba(0,0,0,.5)',
			'overlay_gradient' => '1',
			'css' => '',
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();

		$output = $box_link = "";

		if($content !== '') { 
			$content = '<div class="box-text">'.wpautop($content).'</div>';
		}
		if($title !== '') {
			$title = '<h4>'.$title.'</h4>';
		} else {
			$title = '';
		}
		if ( $link !== '' ) {
			if ( strpos( $link, 'url' ) !== false ) {
				$href = vc_build_link( $link );
				if ( $href['url'] !== "" ) {
					$target = ( isset( $href['target'] ) && $href['target'] !== '' ) ? "target='". trim($href['target']). "'" : '';
					$box_link = '<a class="box-link" href="'. $href['url'] .'" '. $target .' ></a>';
				}
			} 
		} 

		preg_match("/([0-9]+)([a-zA-Z%]+)/", $sm_height, $sm_height_array );
		if ( !empty($sm_height_array) ) {
			$sm_height = $sm_height_array[1];
		}
		preg_match("/([0-9]+)([a-zA-Z%]+)/", $lg_height, $lg_height_array );
		if ( !empty($lg_height_array) ) {
			$lg_height = $lg_height_array[1];
		}

		$image = wp_get_attachment_image_src( $image, 'large');
		if(is_array($image) && isset($image[0])) {
			$image = $image[0];
		} else {
			$image = '';
		}

		$lightboxClass = '';
		if($lightbox == '1' && $link == '') {
			$lightboxClass = '<a class="lightbox" href="'.$image.'"></a>';
		}

		$bg_color = 'background: transparent';
		if($overlay_gradient == '1') {
			sscanf($overlay_color, 'rgba(%d,%d,%d,%f)', $r, $g, $b, $alpha);
			$bg_color = "background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(".$r.",".$g.",".$b.",0.85) 100%); background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,rgba(".$r.",".$g.",".$b.",0.85) 100%); background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,rgba(".$r.",".$g.",".$b.",0.85) 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#a6000000',GradientType=0 ); position: absolute; top: 0; left: 0; width: 100%; height: 100%; ";
		} else {
			if($overlay_color != '') {
				$bg_color = 'background: '.$overlay_color;
			}
		}

		if($box_type == 'box_blog') {
			$terms = get_term( $blog_category, 'category' );
			$params = array('post_type'=>'post', 'posts_per_page'=>$post_per_page, 'order'=>'DESC', 'orderby'=>'meta_value_num');
			if(!$terms instanceof WP_Error) {
				$params['tax_query'] = array(array('taxonomy'=>'category','terms'=>$terms->term_id));
			}
			$the_query = new WP_Query($params);
		
			$output = '<div class="interactive-box blog-box animate-text ' . vc_shortcode_custom_css_class( $css, ' ' ) . '  active-hover" data-sm-height="'.$sm_height.'" data-lg-height="'.$lg_height.'">
					
					<div class="box-slider owl-carousel">';
					if ($the_query->have_posts()) {
						while ($the_query->have_posts()) {
							$the_query->the_post();
							$post = $the_query->post;
							if(has_post_thumbnail($post->ID )) {
								$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
								if ( $post_thumbnail_id ) {
									$post_img = wp_get_attachment_image_url( $post_thumbnail_id, 'veso-food');
								}
							} else {
								$post_img = $image;
							}
							$output .=' <div class="item" style="text-align: center;"><div class="interactive-img" style="background-image: url('.$post_img.');"></div><div class="box-overlay" style="'.$bg_color.'"></div>';
							$output .= '<div class="box-content"><h4 style="color: '.$text_color.'">'.$post->post_title.'</h4></div>';
							$output .= '<a class="box-link" href="'.get_permalink($post->ID).'"></a>';
							$output .= '</div>';				
						}
						wp_reset_postdata();
					}
			$output .=	'</div></div>';
		} elseif($box_type == 'box_standard') {
			$output = '<div class="interactive-box standard-box animate-text ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' active-hover open-lightbox" data-sm-height="'.$sm_height.'" data-lg-height="'.$lg_height.'">
					<div class="interactive-img" style="background-image: url('.$image.');"></div>
					<div class="box-overlay" style="'.$bg_color.'"></div>	
					<div class="box-content" style="color: '.$text_color.';">
						'.$title.'
						'.apply_filters('the_content',$content).'
					</div>		
					'.$box_link.'
					'.$lightboxClass.'
				</div>';
		} else {
			$output = '<div class="interactive-box video-box animate-text ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' active-hover" data-sm-height="'.$sm_height.'" data-lg-height="'.$lg_height.'">
					<div class="interactive-img" style="background-image: url('.$image.');"></div>
					<div class="box-overlay" style="'.$bg_color.'"></div>	
					<div class="video-icon"><a href="'.$video_link.'" class="video-link"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" version="1.1" class="svg-triangle"><polygon points="20,18 32,25 20,32"/></polygon></svg></a></div>
				</div>';
		}

		return $output; 

	}

	public static function veso_map_vc_params() {
		$taxonomy = 'category';
		$terms = get_terms( array( 
		    'taxonomy' => 'category',
		) );
		$categories = array(__('select category', 'veso_theme_plugin') => '');
		foreach($terms as $term) {
			$name = $term->name;
			$id = $term->term_id;
			$categories[$name] = $id;
		}

		vc_map( array(
			"name" => __( "Veso Interactive Box", "veso-theme-plugin" ),
			"base" => "veso_interactive_box",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array( 
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Box type", "veso-theme-plugin" ),
					"param_name" => "box_type",
					"value" => array(
						__('Standard','veso-theme-plugin') => 'box_standard',
						__('Latest post','veso-theme-plugin') => 'box_blog',
						__('Video','veso-theme-plugin') => 'box_video',
					),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Select category", "veso-theme-plugin" ),
					"param_name" => "blog_category",
					"value" => $categories,
					"dependency" => array("element" => "box_type","value" => "box_blog"),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Count of posts", "veso-theme-plugin" ),
					"param_name" => "post_per_page",
					"value" => '4',
					"dependency" => array("element" => "box_type","value" => "box_blog"),
				),
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Box image", "veso-theme-plugin" ),
					"param_name" => "image",
					"value" => '',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Image height on small screen", "veso-theme-plugin" ),
					"param_name" => "sm_height",
					"value" => '',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Image height on large screen", "veso-theme-plugin" ),
					"param_name" => "lg_height",
					"value" => '',
				),
				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
					'param_name' => 'link',
					'description' => __( 'Add link to page.', 'veso-theme-plugin' ),
					"dependency" => array("element" => "box_type","value" => array('box_standard')),
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Show image on lightbox", 'veso-theme-plugin'),
					"param_name" => "lightbox",	
					"value" => array(
						'on' => '1',
					),
					'std' => '1',
					"dependency" => array("element" => "link","is_empty" => true),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'YouTube/Vimeo url', 'veso-theme-plugin' ),
					'param_name' => 'video_link',
					'description' => __( 'Add YouTube or Vimeo video url.', 'veso-theme-plugin' ),
					"dependency" => array("element" => "box_type","value" => 'box_video'),	
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Title", "veso-theme-plugin" ),
					"param_name" => "title",
					"admin_label" => true,
					"value" => '',
					"dependency" => array("element" => "box_type","value" => 'box_standard'),	
				),
				array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __( "Short text", "veso-theme-plugin" ),
					"param_name" => "content",
					"admin_label" => false,
					"value" => '',
					"dependency" => array("element" => "box_type","value" => 'box_standard'),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "text_color",		
					"value" => "#ffffff",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
					'group' => __( 'Colors', 'veso-theme-plugin' ),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Overlay color", 'veso-theme-plugin'),
					"param_name" => "overlay_color",	
					"value" => "rgba(0,0,0,.5)",	
					'description' => __( 'Select color for overlay.', 'veso-theme-plugin' ),			
					'group' => __( 'Colors', 'veso-theme-plugin' ),	
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __("Gradient", 'veso-theme-plugin'),
					"param_name" => "overlay_gradient",	
					"value" => array(
						'on' => '1',
					),
					'std' => '1',
					'description' => __( 'Set overlay color as gradient.', 'veso-theme-plugin' ),			
					'group' => __( 'Colors', 'veso-theme-plugin' ),	
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