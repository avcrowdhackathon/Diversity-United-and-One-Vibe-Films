<?php

if ( ! function_exists( 'veso_enqueue_scripts' ) ) :
	function veso_enqueue_scripts() {
		wp_enqueue_style( 'veso-main-stylesheet', get_template_directory_uri() . '/assets/css/app.css', array(), '1.0.0', 'all' );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_enqueue_script( 'velocity', get_theme_file_uri( '/assets/js/libs/velocity.min.js' ), array( 'jquery' ), '1.5.0', true );
		wp_enqueue_script( 'owl-carousel', get_theme_file_uri( '/assets/js/libs/owl.carousel.min.js' ), array( 'jquery' ), '2.2.1', true );
		wp_enqueue_script( 'veso-waypoints', get_theme_file_uri( '/assets/js/libs/jquery.waypoints.min.js' ), array( 'jquery' ), '4.0.1', true );
		wp_enqueue_script( 'magnific-popup', get_theme_file_uri( '/assets/js/libs/jquery.magnific-popup.min.js' ), array( 'jquery' ), '1.1.0', true );
		wp_enqueue_script( 'jarallax', get_theme_file_uri( '/assets/js/libs/jarallax.min.js' ), array( 'jquery' ), '1.8.0', true );
		wp_enqueue_script( 'jarallax-video', get_theme_file_uri( '/assets/js/libs/jarallax-video.min.js' ), array( 'jquery' ), '1.2.1', true );
		wp_enqueue_script( 'swiper', get_theme_file_uri( '/assets/js/libs/swiper.jquery.min.js' ), array( 'jquery' ), '3.4.2', true );
		wp_enqueue_script( 'blazy', get_theme_file_uri( '/assets/js/libs/blazy.min.js' ), array( 'jquery' ), '1.8.2', true );
		wp_enqueue_script( 'hoverintent', get_theme_file_uri( '/assets/js/libs/jquery.hoverintent.min.js' ), array( 'jquery' ), '2.0.0', true );
		wp_enqueue_script( 'imagesloaded' );
		wp_enqueue_script( 'isotope', get_theme_file_uri( '/assets/js/libs/isotope.pkgd.min.js' ), array( 'jquery' ), '3.0.4', true );
		wp_enqueue_script( 'sticky-kit', get_theme_file_uri( '/assets/js/libs/sticky-kit.min.js' ), array( 'jquery' ), '1.1.3', true );
		wp_enqueue_script( 'granim', get_theme_file_uri( '/assets/js/libs/granim.min.js' ), array( 'jquery' ), '1.0.6', true );
		wp_enqueue_script( 'blast', get_theme_file_uri( '/assets/js/libs/jquery.blast.min.js' ), array( 'jquery' ), '2.0.0', true );
		wp_enqueue_script( 'iScroll', get_theme_file_uri( '/assets/js/libs/scrolloverflow.min.js' ), array( 'jquery' ), '0.0.1', true );
		wp_enqueue_script( 'fullPage', get_theme_file_uri( '/assets/js/libs/jquery.fullPage.min.js' ), array( 'jquery' ), '2.9.4', true );

		if(function_exists('get_field')) {
			$smoothScroll = get_field('veso_smooth_scroll', 'options');
			if($smoothScroll && $smoothScroll == true) {
				wp_enqueue_script( 'veso-smooth-scroll', get_theme_file_uri( '/assets/js/smooth-scroll.js' ), array( 'jquery' ), '1.0', true );
			}

			if(get_field('veso_enable_footer', 'option') == true) {
				if(get_field('veso_select_footer')) {
					$footer = get_field('veso_select_footer');
					wp_add_inline_style('veso-main-stylesheet', get_post_meta($footer->ID, '_wpb_shortcodes_custom_css', true));
				} else {
					$footer = get_field('veso_default_footer', 'option');
					if($footer) {
						wp_add_inline_style('veso-main-stylesheet', get_post_meta($footer->ID, '_wpb_shortcodes_custom_css', true));
					}
				}
			}
		} else {
			wp_enqueue_style('veso-basic-font', 'http://fonts.googleapis.com/css?family=Nunito:400%7CNunito:800800%7CNunito:600%2C400%2C600&#038;subset=latin', array(), '1.0.0', 'all');
			wp_enqueue_style('veso-basic-typography', get_template_directory_uri() . '/assets/css/typography.css', array(), '1.0.0', 'all' );

		}
		wp_enqueue_script( 'veso-global', get_theme_file_uri( '/assets/js/app.js' ), array( 'jquery' ), '1.0', true );
		$script = "var ajaxurl = '".admin_url('admin-ajax.php')."';
		var pageId = '".get_the_id()."';
		var vesoBackWord = '".esc_html('back', 'veso')."'";
		wp_add_inline_script( 'veso-global', $script, 'before' );

	    wp_localize_script( 'veso-global', 'rest_object',
	        array(
	            'api_nonce' => wp_create_nonce( 'wp_rest' ),
	            'api_url'   => site_url('/wp-json/wp/v2/')
	        )
	    );
	}

	add_action( 'wp_enqueue_scripts', 'veso_enqueue_scripts');
endif;