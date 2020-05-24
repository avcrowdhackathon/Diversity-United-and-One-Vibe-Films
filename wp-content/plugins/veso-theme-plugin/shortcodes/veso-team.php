<?php

class Veso_Team {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_team', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode( $atts , $content, $team_list ) {
		extract( shortcode_atts( array(
			'image' => '',
			'name' => '',
			'team_pos' => '',
			'team_img_pos' => 'team-img-top',
			'team_link' => '',
			'social_links' => '',
			'text_color' => '',
			'accent_color' => '',
			'align' => 'text-left',
			'css' => '',
		), $atts ) );

		$id = 'id_'.uniqid(true).mt_rand();		
		$image = wp_get_attachment_image_src( $image, 'large');
		if(is_array($image) && isset($image[0])) {
			$imageWidth = $image[1];
			$imageHeight = $image[2];
			$image = $image[0];
		}
		if($content !== '') {
			$content = '<div class="desc">'.wpautop($content).'</div>';
		}
		$social = "";
		if($social_links !== "") {
			$social = '<ul class="member-socials">';
			$social_links = explode( ',', $social_links );
			foreach ($social_links as $value) {
				if($value != "") {
					$social_links = explode( '|', $value );
					if(strpos($social_links[0], 'fa-') !== false) {
						$social_links[0] = '<i class="fa '.$social_links[0].'"></i>';
						$link_class = '';
					} else {
						$link_class = 'link-hover';
					}
					$social .= '<li><a href="'.$social_links[1].'" class="'.$link_class.'">'.$social_links[0].'</a></li>';
				}
			}
			$social .= '</ul>';
		}

		if($team_link != '') {
			$url = vc_build_link( $team_link );
			$target = (isset($url['target']) && $url['target'] !== '') ? 'target="'. esc_attr( trim($url['target']) ). '"' : '';
			$img = '<a href="' . esc_attr( $url['url'] ) . '" '.$target.'>
				<img width="'.$imageWidth.'" height="'.$imageHeight.'" src="'.veso_image_preloader($imageWidth,$imageHeight).'" data-src="'.$image.'" alt="team-member-img" class="b-lazy"/>
			</a>';
		} else {
			$img = '<img width="'.$imageWidth.'" height="'.$imageHeight.'" src="'.veso_image_preloader($imageWidth,$imageHeight).'" data-src="'.$image.'" alt="team-member-img" class="b-lazy"/></a>';
		}
		$output = '<div class="team-member '.$team_img_pos.' '.$id.' ' . vc_shortcode_custom_css_class( $css, ' ' ) . '">';
			if(isset($image) && $image !== '') {
				$output .= '<div class="team-image animate-text">
					<div class="team-image-wrapper">
						<span class="img-wrapper">
							'.$img.'
						</span>
					</div>
				</div>';
			}
				$output .= '<div class="team-info animate-text '.$align.'" style="color: '.$text_color.'">
						<div class="team-header">
							<div class="team-name">
								<h4 class="name">'.$name.'</h4>
								<div class="position">'.$team_pos.'</div>
							</div>
						</div>
						'.$content.'
						'.$social.'
					</div>
				</div>';
			$output .= '<div class="custom-styles" data-styles=".'.$id.'.team-member .team-header .team-name:after { background-color: '.$accent_color.'; }"></div>';
		return $output; 

	}
	
	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Team", "veso-theme-plugin" ),
			"base" => "veso_team",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Team member image", "veso-theme-plugin" ),
					"param_name" => "image",
					"value" => '',
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Image position", "veso-theme-plugin" ),
					"param_name" => "team_img_pos",
					"value" => array(
						__("Top", "veso-theme-plugin") => "team-img-top",
						__("Right", "veso-theme-plugin") => "team-img-right",
						__("Left", "veso-theme-plugin") => "team-img-left",
					),
					'std' => 'team-img-top',
				),
				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'veso-theme-plugin' ),
					'param_name' => 'team_link',
					'description' => __( 'Add custom link to image.', 'veso-theme-plugin' ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Team member name", "veso-theme-plugin" ),
					"param_name" => "name",
					"admin_label" => true,
					"value" => '',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Team member position", "veso-theme-plugin" ),
					"param_name" => "team_pos",
					"value" => '',
				),
				array(
					"type" => "textarea_html",
					"class" => "",
					"heading" => __( "Description", "veso-theme-plugin" ),
					"param_name" => "content",
					"admin_label" => false,
					"value" => '',
				),
				array(
					"type" => "exploded_textarea",
					"heading" => __( "Social profiles", "veso-theme-plugin" ),
					"description" =>__('Enter social profile and links. If you want to show social icon visit FontAwesome site (<a href="http://fontawesome.io/icons/">link here</a>) and add icon name with prefix "fa-" (Note: divide links with linebreaks (Enter)). For example: "Facebook| https://facebook.com" or "fa-facebook| https://facebook.com"', 'veso-theme-plugin' ),
					"param_name" => "social_links",
					"value" => '',
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Text alignment", "veso-theme-plugin" ),
					"param_name" => "align",
					"value" => array(
						__("Left", "veso-theme-plugin") => "text-left",
						__("Center", "veso-theme-plugin") => "text-center",
						__("Right", "veso-theme-plugin") => "text-right",
					),
					'std' => 'text-left',
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Text color", 'veso-theme-plugin'),
					"param_name" => "text_color",		
					"value" => "",
					'description' => __( 'Select text color.', 'veso-theme-plugin' ),		
					'group' => __( 'Colors', 'veso-theme-plugin' ),	
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Accent color", 'veso-theme-plugin'),
					"param_name" => "accent_color",	
					"value" => "",	
					'description' => __( 'Select accent color.', 'veso-theme-plugin' ),			
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