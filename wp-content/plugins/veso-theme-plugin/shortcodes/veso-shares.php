<?php

class Veso_Shares {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_shortcode( 'veso_shares', array($this, 'veso_shortcode' ));
	}

	function veso_shortcode( $atts, $content ) {
		extract( shortcode_atts( array(
			'textalign' => 'text-center',
			'share_size' => '16px',
			'text_color' => '',
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();
		
		preg_match("/([0-9]+)([a-zA-Z%]+)/", $share_size, $share_size_array );
		if ( !empty($share_size_array) ) {
			$share_size = $share_size_array[1];
		}
		$shareData = array();
		$shareData = array('title'=>get_the_title(), 'url'=>get_the_permalink());
		$shareImage = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_id(), 'full' ));
		if(isset($shareImage[0])) {
			$shareImage = $shareImage[0];
			$shareData['image'] = $shareImage;
		}
		$output = '<div class="veso-shares '.$textalign.' '.$id.' accent-color animate-text" style="font-size: '.$share_size.'px; color: '.$text_color.'">';
		$output .= veso_get_share_links($shareData);
		$output .= '</div>';
		return $output; 
	}

	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Shares", "veso-theme-plugin" ),
			"base" => "veso_shares",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Shares alignment", "veso-theme-plugin" ),
					"param_name" => "textalign",
					"value" => array(
						__( 'Left', 'veso-theme-plugin' ) => 'text-left',
						__( 'Center', 'veso-theme-plugin' ) => 'text-center',
						__( 'Right', 'veso-theme-plugin' ) => 'text-right',
					),
					'std' => 'text-center',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Shares size", "veso-theme-plugin" ),
					"param_name" => "share_size",
					"value" => '16px',
				),
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => __("Shares color", 'veso-theme-plugin'),
					"param_name" => "text_color",		
					"value" => "",
					'description' => __( 'Select color for shares.', 'veso-theme-plugin' ),		
				),
			),
		));
	}

}