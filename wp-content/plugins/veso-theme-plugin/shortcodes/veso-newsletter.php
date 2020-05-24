<?php

class Veso_Newsletter {
	function __construct() {
		add_action( 'init', array($this, 'veso_map_vc_params' ));
		add_action( 'rest_api_init', array($this, 'rest_validate_email_endpoint' ));
		add_shortcode( 'veso_newsletter', array($this, 'veso_shortcode' ));
	}


	function rest_validate_email_endpoint() {
		// Declare our namespace
		$namespace = 'wp/v2';

		// Register the route
		register_rest_route( $namespace, '/email/', array(
			'methods'   => 'POST',
			'callback'  => array($this, 'rest_validate_email_handler'),
			'args'      => array(
				'email'  => array( 'required' => true ), // This is where we could do the validation callback
			)
		) );
	}

	// The callback handler for the endpoint
	function rest_validate_email_handler( $request ) {
		$params = $request->get_params();
		$email = $params['email'];
		$list  = $params['newsletter_list'];
		if ( is_email( $params['email']) ) {
			include_once ( plugin_dir_path( __FILE__ ) . 'newsletter/MailChimp.php');
			$apikey = get_field('veso_newsletter_key', 'option');
			
			if ($apikey != '') {
				$MC = new MailChimp($apikey);
				$vars = array();
				$result = $MC->post('lists/'.$list.'/members', array(
					'email_address' => $email,
					'status' => 'subscribed',
				));
			}
			if(!isset($result)) {
				return new WP_REST_Response( array('status' => 'error'), 200 );
			}
			if($result['status'] === 404) {
				return new WP_REST_Response( array('status' => 'error', 'result'=>$result), 200 );
			} else {
				return new WP_REST_Response( array('status' => 'success', 'result'=>$result), 200 );
			}
		}
		return new WP_REST_Response( array('status' => 'error'), 200 );
	}

	function veso_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'newsletter_list' => '',
			'btn_text' => __( "Submit", "veso-theme-plugin" ),
			'btn_size' => 'btn-md',
			'btn_type' => 'btn-solid',
			'btn_color' => 'btn-dark',
			'bg_btn' => '#fff',
			'text_color' => '#333',
			'accent_color' => '#ffb573',
			'css' => ''
		), $atts ) );
		$id = 'id_'.uniqid(true).mt_rand();
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
		
		$button = '<div class="form-submit text-center"><button type="submit" class="btn '.$btn_size.' '.$btn_color.' '.$btn_type.' btn-newsletter text-right" '.$btn_bg.'>
				<span class="btn-text" '.$btn_txt.'>'.$btn_text.'</span>
			</button></div>';
			
		$nonce = wp_create_nonce('newsletter_nonce');

		$output = '';
		$output .= '<div class="form-style newsletter-form ' . vc_shortcode_custom_css_class( $css, ' ' ) . ' '.$id.'">
			<div class="newsletter-form-wrapper">
				<form name="n-form" class="n-form animate-text" id="n-form" action="newsletter_request" method="POST" ><input type="hidden" name="action" value="newsletter_request">
					<div class="newsletter-details form-fieldset">
						<div class="n-text email n-input veso-input">
							<label for="n_email">'.esc_html('E-mail', 'veso-theme-plugin').'</label>
							<input class="input-required" type="text" name="email" id="n_email" value="">
						</div>
					</div>
					'.$button.'
					<input type="hidden" name="newsletter_list" class="newsletter_list" value="'.$newsletter_list.'" />
					<input type="hidden" name="newsletter_nonce" class="newsletter_nonce" value="'.$nonce.'" />
				</form>
			</div>
		</div>
		';
		$output .= '<div class="custom-styles" data-styles=".'.$id.' .btn.btn-solid.custom:after { background-color: '.$accent_color.'; }
			.'.$id.' .btn.btn-outline.custom:after { border-color: '.$accent_color.'; }
			.'.$id.' .btn.custom.btn-underline:hover .btn-text:after { border-color:  '.$accent_color.'; }"></div>';

		return $output;
	}


	function veso_returnMailChimpList() {
		include_once __DIR__.'/newsletter/MailChimp.php';

		$apikey = get_field('veso_newsletter_key', 'option');
		$newsletterLists = array('Select list' => '');

		if ($apikey != '') {
			$MC = new MailChimp($apikey);
			$mailchimp = $MC->get('lists');
			if (is_array($mailchimp['lists'])) {
				foreach ($mailchimp['lists'] as $key => $value) {
					$newsletterLists[ $value['name'] .' (' . $value['id'] . ')'] = $value['id'];
				}
			}
		}

		return $newsletterLists;
	}


	public static function veso_map_vc_params() {
		vc_map( array(
			"name" => __( "Veso Newsletter", "veso-theme-plugin" ),
			"base" => "veso_newsletter",
			"class" => "",
			"category" => __( "Content", "veso-theme-plugin"),
			"icon" => get_template_directory_uri().'/assets/images/logo-icon.png',
			"params" => array(
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Insert your list ID", "veso-theme-plugin" ),
					"param_name" => "newsletter_list",
					'value' => '',
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Button text", "veso-theme-plugin" ),
					"param_name" => "btn_text",
					'value' => __( 'Submit', 'veso-theme-plugin' ),
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

				array(
					'type' => 'css_editor',
					'heading' => __( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group' => __( 'Design Options', 'js_composer' ),
				),

			),
		));
	}

}