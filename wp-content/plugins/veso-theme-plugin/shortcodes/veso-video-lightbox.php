<?php 

class Veso_Video_Lightbox {
	public function __construct() {
		add_action('init', array($this, 'map'));
		add_shortcode( 'veso_video_lightbox', array( $this, 'shortcode' ) );
	}

	function shortcode($atts) {
		extract(shortcode_atts(array("link_style" => "play_button", 'hover_effect' => 'default', "video_url" => '#', "link_text" => "", "play_button_color" => "", 'background_color'=>'', 'text_color'=>'', 'textalign'=>'text-left' , 'css'=>''), $atts));

		$output = '';
		$extra_attrs = ($link_style == 'veso-button') ? 'data-color-override="false"': null;
		$the_link_text = ($link_style == 'veso-button') ? $link_text : '<span class="play"><span class="inner-wrap" style="background-color: '.$background_color.'"><svg version="1.1" style="fill: '.$play_button_color.'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="600px" height="800px" x="0px" y="0px" viewBox="0 0 600 800" enable-background="new 0 0 600 800" xml:space="preserve"><path fill="none" d="M0-1.79v800L600,395L0-1.79z"></path> </svg></span></span>';
		$the_color = $play_button_color;

		if($link_style == 'play_button_with_text') {
			$the_color = $play_button_color;
		}

		$pbwt = ($link_style == 'play_button_with_text') ? '<span class="link-text"><span style="color: '.$text_color.'">'.$link_text.'</span></span>' : null;
		$output .= '<div class="'.$textalign.' animate-text"><a href="'.$video_url.'" '.$extra_attrs.' style="color: '.$play_button_color.'"  class="'.$link_style.' single-image-lightbox large veso_video_lightbox nohover '.vc_shortcode_custom_css_class( $css, ' ' ).'">'.$the_link_text .$pbwt.'</a></div>';

		return $output;
	}

	function map() {
		vc_map( array(
			"name" => __("Veso Video Lightbox Button", "veso"),
			"base" => "veso_video_lightbox",
			"description" => __('Add a video lightbox link', 'veso'),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "dropdown",
					"heading" => __("Link Style", "veso"),
					"param_name" => "link_style",
					"value" => array(
						"Play Button" => "play_button",
						"Play Button With text" => "play_button_with_text",
					),
					'save_always' => true,
					"admin_label" => true,
					"description" => __("Please select your link style", "veso")	  
					),
				array(
					"type" => "textfield",
					"heading" => __("Video URL", "veso"),
					"param_name" => "video_url",
					"admin_label" => false,
					"description" => __("The URL to your video on Youtube or Vimeo.", "veso")
					),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Video icon alignment", "veso-theme-plugin" ),
					"param_name" => "textalign",
					"value" => array(
						__( 'Left', 'veso-theme-plugin' ) => 'text-left',
						__( 'Center', 'veso-theme-plugin' ) => 'text-center',
						__( 'Right', 'veso-theme-plugin' ) => 'text-right',
					),
					'std' => 'text-left',
				),
				array(
					"type" => "colorpicker",
					"heading" => __("Play Arrow Color", "veso"),
					"param_name" => "play_button_color",
					'save_always' => true,
					"description" => __("Please select the color you desire", "veso")
					),
				array(
					"type" => "colorpicker",
					"heading" => __("Play Background Color", "veso"),
					"param_name" => "background_color",
					'save_always' => true,
					"dependency" => array('element' => "link_style", 'value' => array("play_button_with_text")),
					"description" => __("Please select the color you desire", "veso")
					),
				array(
					"type" => "colorpicker",
					"heading" => __("Text Color", "veso"),
					"param_name" => "text_color",
					'save_always' => true,
					"dependency" => array('element' => "link_style", 'value' => array("play_button_with_text")),
					"description" => __("Please select the color you desire", "veso")
					),
				array(
					"type" => "textfield",
					"heading" => __("Link Text", "veso"),
					"param_name" => "link_text",
					"admin_label" => false,
					"dependency" => array('element' => "link_style", 'value' => array("play_button_with_text")),
					"description" => __("The text that will be displayed for your link", "veso")
					),
				array(
					'type' => 'css_editor',
					'heading' => __( 'CSS box', 'veso-theme-plugin' ),
					'param_name' => 'css',
					'group' => __( 'Design Options', 'veso-theme-plugin' ),
				),
				)
			)
		);
	}
}

