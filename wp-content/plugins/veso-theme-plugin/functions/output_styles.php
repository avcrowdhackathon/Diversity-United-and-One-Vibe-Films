<?php 
	function veso_darken_color($rgb, $darker=1.1) {
	    $hash = (strpos($rgb, '#') !== false) ? '#' : '';
	    $rgb = (strlen($rgb) == 7) ? str_replace('#', '', $rgb) : ((strlen($rgb) == 6) ? $rgb : false);
	    if(strlen($rgb) != 6) return $hash.'000000';

	    list($R16,$G16,$B16) = str_split($rgb,2);

	    $R = sprintf("%02X", floor(hexdec($R16)/$darker));
	    $G = sprintf("%02X", floor(hexdec($G16)/$darker));
	    $B = sprintf("%02X", floor(hexdec($B16)/$darker));
	
	    return $hash.$R.$G.$B;
	}

	function veso_output_styles() {
		$css = '';

		$body_bg_color = get_field('veso_background_color', 'option');
		$body_text_color = get_field('veso_text_color', 'option');
		$body_accent_color = get_field('veso_accent_color', 'option');
		$body_page_bg = get_field('veso_page_bg');
		if($body_page_bg != '') {
			$body_bg_color = $body_page_bg;
		} else {
			$body_bg_color = $body_bg_color; 
		}
		
		if($body_bg_color == '#000000') {
			$body_bg_darken = '#222222';
			$body_bg_darken1 = '#555555';
		} else {
			$body_bg_darken = veso_darken_color($body_bg_color, $darker=1.04);
			$body_bg_darken1 = veso_darken_color($body_bg_color, $darker=1.15);
		}

		if( have_rows('veso_button_dark_color', 'option') ) {
			$i = 0;

			// while has rows
			while( have_rows('veso_button_dark_color', 'option') ) {
				$i++;
				the_row();

				$btn_bg_color = get_sub_field('background_color');
				$btn_color = get_sub_field('text_color');
				$btn_accent_color = get_sub_field('accent_color');
				$css .= '.btn.btn-solid.btn-dark, .woocommerce-mini-cart__buttons a.button.wc-forward.checkout { background: '.$btn_bg_color.'; } .btn.btn-underline.btn-dark, .btn.btn-outline.btn-dark { color: '.$btn_bg_color.'} .btn.btn-solid.btn-dark .btn-text { color: '.$btn_color.'} .btn.btn-solid.btn-dark:after, .btn.btn-outline.btn-dark:after { background: '.$btn_accent_color.'} .btn.btn-underline .btn-text:after { border-color: '.$btn_accent_color.'}';
			}
			
		}
		if(get_field('veso_full_pages') == true) {
			$full_page_pagination_color = get_field('veso_full_pages_pagination_color');
			$css .= '#fp-nav li a span { background: '.$full_page_pagination_color.'} #fp-nav li .fp-tooltip { color: '.$full_page_pagination_color.'}'; 
		}
		if( have_rows('veso_button_light_color', 'option') ) {
			$i = 0;

			// while has rows
			while( have_rows('veso_button_light_color', 'option') ) {
				$i++;
				the_row();

				$btn_bg_color1 = get_sub_field('background_color');
				$btn_color1 = get_sub_field('text_color');
				$btn_accent_color1 = get_sub_field('accent_color');
				$css .= '.btn.btn-solid.btn-light, .woocommerce-mini-cart__buttons a.button.wc-forward { background: '.$btn_bg_color1.'; } .btn.btn-underline.btn-light, .btn.btn-outline.btn-light { color: '.$btn_bg_color1.'} .btn.btn-solid.btn-light .btn-text, .woocommerce-mini-cart__buttons a.button.wc-forward.checkout { color: '.$btn_color1.'} .btn.btn-solid.btn-light:after, .btn.btn-outline.btn-light:after { background: '.$btn_accent_color1.'} .btn.btn-underline .btn-text:after { border-color: '.$btn_accent_color1.'}';
			}
			
		}

		$css .= 'body, body h1, body h2, body h3, body h4, body h5, body h6, tfoot, thead, .wp-caption-text, cite, abbr, .nav-links .arrow .arrow-icon i, .woocommerce span.onsale, mark, .woocommerce-MyAccount-navigation-link.is-active { color: '.$body_text_color.'} body .content p a:hover, body .text-link:hover { border-color: '.$body_text_color.'} .vc_toggle.vc_toggle_default .vc_toggle_title .vc_toggle_icon:before, .vc_toggle.vc_toggle_default .vc_toggle_title .vc_toggle_icon:after, .vc_toggle.vc_toggle_default .vc_toggle_title .vc_toggle_icon, .veso-blog-carousel .veso-carousel-pagination span, .nav-links .arrow .arrow-icon, .wpb_wrapper .vc_progress_bar .vc_single_bar .vc_bar, .cart-offcanvas-close:before, .cart-offcanvas-close:after, .cart-offcanvas-close > div, .cart-offcanvas .show-cart .list-product .remove-product .icon-close:before, .cart-offcanvas .show-cart .list-product .remove-product .icon-close:after, .sticky-post:after { background: '.$body_text_color.'} .search-submit #zoom-icon path { fill: '.$body_text_color.' }';

		$css .= 'body, .select2-dropdown, #add_payment_method #payment div.payment_box, .woocommerce-cart #payment div.payment_box, .woocommerce-checkout #payment div.payment_box, .cart-offcanvas .shopping-cart, .widget-title span { background-color: '.$body_bg_color.'; } #payment div.payment_box::before, #payment ul.payment_methods { border-bottom-color: '.$body_bg_color.' !important} .woocommerce div.product .woocommerce-tabs ul.tabs li, .woocommerce-info, .woocommerce-error, .woocommerce-message, tbody tr:nth-child(even), pre { background: '.$body_bg_darken.'} .comments-area > .comment-list, .woocommerce table.shop_table td, .woocommerce form.checkout_coupon, .woocommerce form.login, .woocommerce form.register, .woocommerce-MyAccount-navigation li, .woocommerce #reviews #comments ol.commentlist li .comment-text, .woocommerce div.product .woocommerce-tabs ul.tabs li, #wp-calendar tbody tr, .woocommerce div.product .woocommerce-tabs ul.tabs li.active, .woocommerce div.product .woocommerce-tabs ul.tabs::before, #comments .pingback { border-color: '.$body_bg_darken1.'} .comment-list .border-list, .comments-area > .comment-list .comment-list .comment-author-avatar:first-of-type:before, #wp-calendar thead, .select2-container--default .select2-results__option[aria-selected=true], #add_payment_method #payment, .woocommerce-cart #payment, .woocommerce-checkout #payment, .woocommerce div.product .woocommerce-tabs ul.tabs li.active { background: '.$body_bg_darken.'}';

		$blockquote = base64_encode('<svg version="1.1" class="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="60px" width="60px" viewBox="0 0 75.999 75.999" style="enable-background:new 0 0 75.999 75.999;" xml:space="preserve"><path style="fill:'.$body_accent_color.';" d="M14.579,5C6.527,5,0,11.716,0,20c0,8.285,6.527,15,14.579,15C29.157,35,19.438,64,0,64v7C34.69,71,48.286,5,14.579,5z M56.579,5C48.527,5,42,11.716,42,20c0,8.285,6.527,15,14.579,15C71.157,35,61.438,64,42,64v7C76.69,71,90.286,5,56.579,5z"/></svg>');

		$css .= '.single-post-sidebar .widget-title:after, .shop-sidebar .widget-title:after, .single-portfolio .single-portfolio-header h2:after, .veso-pricing-table .desc li:before, .wpb_wrapper .vc_progress_bar .vc_single_bar, .team-member .team-header .team-name:after, body .veso-highlight, .portfolio-text h3.header-outline:after, .veso-portfolio-item.text-below.hover3 .image-wrapper:after, .woocommerce span.onsale, .woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content, mark, .woocommerce-MyAccount-navigation-link.is-active, .woocommerce-mini-cart__buttons a.button.wc-forward:after, .veso-carousel-container .swiper-pagination-bullet-active, .veso-split-slider-container .split-slider-btn a:after, body .veso-highlight.highlight-accent, .scroll-up .arrow span { background: '.$body_accent_color.'; } .veso-pricing-table .price, .product .price ins, .scroll-up, .scroll-up.show-arrow:hover, .veso-hover-text:hover, .widget_archive li a:hover, .widget_categories li a:hover, body .dropcap.dropcap-accent, .scroll-up i, .scroll-up > span { color: '.$body_accent_color.' } .veso-hover-text:before, .veso-hover-text:after, .nav-links a:hover, .woocommerce-info, .woocommerce-error, .woocommerce-message, .woocommerce .woocommerce-pagination .page-numbers li:hover, .wpb-js-composer .vc_tta.vc_general .vc_tta-panel, body .content p a:after, body .text-link:after, body .wpb_text_column p a:after, body .team-member .desc p a:after, body .attr-content p a:after { border-color: '.$body_accent_color.'} .veso-portfolio-item.text-on-hover.hover2 h3 > span, .woocommerce .widget-area li .product-title, .single-portfolio .single-portfolio-header h2 > span, a.hover-underline { background-image: linear-gradient(transparent 96%, '.$body_accent_color.' 0%)} input:focus, textarea:focus, select:focus, .woocommerce .widget-area .cat-item a:before, .woocommerce .widget-area .cat-item a:after, .woocommerce .widget-area .wc-layered-nav-term a:before, .woocommerce .widget-area .wc-layered-nav-term a:after, .widget_nav_menu li a:after, .widget_meta a:after, .veso-nav-footer > .item-footer-wrapper a:after { border-bottom-color: '.$body_accent_color.' !important} body blockquote:before { content: url(\'data:image/svg+xml;base64,'.$blockquote.'\') !important } .veso-nav .dropdown > li > a:after, .veso-nav .dropdown > li > a:before, .veso-nav .dropdown > li.current-menu-item > a:after, .veso-nav .dropdown > li.current-menu-item > a:before {border-color: '.$body_accent_color.'} #icon-cart:hover path { fill: '.$body_accent_color.' !important}';


		$css .= '.woocommerce .widget_price_filter .ui-slider .ui-slider-range, .woocommerce .widget_price_filter .ui-slider .ui-slider-handle { background: '.$body_text_color.'; }';

		$selectedNav = get_post_meta(get_the_ID(), 'veso_navigation', true);
		if(!$selectedNav) {
			$selectedNav = 1;
		}
		if(is_search()) {
			$selectedNav = 1;
		}
		if( have_rows('veso_navigation_bars', 'option') ) {
			$i = 0;

			// while has rows
			while( have_rows('veso_navigation_bars', 'option') ) {
				$i++;
				the_row();
				$value = $i;
				if($i == $selectedNav) {
					$color = get_sub_field('link_colors');
					$dropdown_bg = get_sub_field('dropdown_bg_color');
					$dropdown_color = get_sub_field('dropdown_items_color');
					$css .= '.veso-nav:not(.fixed-nav) .menu-item-has-children .sub-menu { background: '.$dropdown_bg.'; } .veso-nav:not(.fixed-nav) .nav-items .menu-item .sub-menu li a, .veso-nav:not(.fixed-nav) .mega-menu-inner li h6, .veso-nav:not(.fixed-nav) .mega-menu-inner h4 { color: '.$dropdown_color.' !important; } .nav-dark-text .menu-item-has-children .sub-menu { border-color: '.$color.' !important; } .veso-nav:not(.fixed-nav) .nav-items a, .veso-nav:not(.fixed-nav) .nav-additional a, .nav-logo h1, body .logo h1 { color: '.$color.'; } .nav-additional .sub-menu a { color: '.$dropdown_color.' !important; } .veso-nav:not(.fixed-nav) #icon-cart path { fill: '.$color.'; } .veso-nav:not(.fixed-nav) .veso-nav-burger span { background: '.$color.'}';

					// $css .= '.veso-nav:not(.fixed-nav) .menu-item-has-children .sub-menu, .veso-nav:not(.fixed-nav) .menu-item-has-children .sub-menu, .veso-nav:not(.fixed-nav) .menu-item-object-veso_mega_menu .sub-menu { background: '.$dropdown_bg.'; } .veso-nav:not(.fixed-nav) .nav-items .menu-item .sub-menu li a, .veso-nav:not(.fixed-nav) .mega-menu-inner li h6, .veso-nav:not(.fixed-nav) .mega-menu-inner h4, .veso-nav:not(.fixed-nav) .nav-additional .sub-menu a { color: '.$dropdown_color.' !important; } .nav-dark-text .menu-item-has-children .sub-menu { border-color: '.$color.' !important; } .veso-nav:not(.fixed-nav) .nav-items a, .veso-nav:not(.fixed-nav) .nav-additional a, .nav-logo h1, body .logo h1 { color: '.$color.'; } .veso-nav:not(.fixed-nav) #icon-cart path { fill: '.$color.'; } .veso-nav-burger { color: '.$color.'}';

				}		
			}
			
		}

		$mobile_bg = get_field('veso_mobile_bg_color', 'option');
		$mobile_text = get_field('veso_mobile_text_color', 'option');

		$css .= '.mobile-menu-content, .mobile-menu-content:after { background: '.$mobile_bg.' } .mobile-menu-content, .mobile-nav .text-logo h1, .mobile-cart {color: '.$mobile_text.'} #mobile-burger:before, #mobile-burger > div, #mobile-burger:after, #mobile-burger.close-btn:before, #mobile-burger.close-btn > div, #mobile-burger.close-btn:after, .mobile-menu .menu-item-has-children > a:before, .mobile-menu .menu-item-has-children > a:after, .mobile-menu .overlay-back a:before, .mobile-menu .overlay-back a:after, .mobile-nav-overlay .mobile-overlay { background: '.$mobile_text.'; } .mobile-nav #icon-cart-mobile path { fill: '.$mobile_text.'; }';

		$off_canvas_bg = get_field('veso_bg_off_canvas', 'option');
		$off_canvas_color = get_field('veso_color_off_canvas', 'option');
		$css .= '.veso-nav-overlay { color: '.$off_canvas_color.'} .veso-nav-overlay .veso-overlay { background: '.$off_canvas_bg.'; }';

		$nav_height = get_field('veso_navigation_height', 'option');
		$nav_mobile_height = get_field('veso_navigation_height_mobile', 'option');
		$nav_padding = get_field('veso_navigation_logo_padding', 'option');
		$nav_fixed_padding = get_field('veso_navigation_fixed_logo_padding', 'option');
		$double_nav_padding = $nav_padding * 2;
		$nav_mobile_padding = get_field('veso_navigation_mobile_logo_padding', 'option');
		$double_nav_mobile_padding = $nav_mobile_padding * 2;
		$half_nav_height = $nav_height/2; 
		$half_nav_mobile_height = $nav_mobile_height/2; 

		$css .= '.veso-nav .nav { height: '.$nav_height.'px; }
			.nav-solid.nav-top ~ .page-wrapper { padding-top: '.$nav_height.'px; }
			.veso-nav:not(.fixed-nav) .nav > div.logo .static-logo img { padding: '.$nav_padding.'px 0; }
			.veso-nav.fixed-nav .nav > div.logo .static-logo img { padding: '.$nav_fixed_padding.'px 0; }';
			// .veso-split-slider-container .veso-split-slider { height: calc(100vh - '.$nav_mobile_height.'px) !important; }
			// .veso-split-slider-container .slide { height: calc(100vh - '.($nav_mobile_height+60).'px) !important; }
		// $css .= '@media screen and (max-width: 64em) { 
		// 	#main-navbar-home, #main-navbar-home .nav { height: '.$nav_mobile_height.'px; }
		// 	.veso-full-pages .fp-section .row .columns > .vc_row { padding-top: calc('.$nav_mobile_height.'px + 30px); }
		// 	.veso-split-slider-pagination { top: calc(50% + '.$half_nav_mobile_height.'px) !important; }
		// 	.mobile-nav-overlay .mobile-menu { margin: '.$nav_mobile_height.'px 0 60px 0}
		// 	.nav-solid ~ .page-wrapper { padding-top: '.$nav_mobile_height.'px; }
		// 	.mobile-nav .logo { padding: '.$nav_mobile_padding.'px; }
		// 	#mobile-burger { top: calc('.$half_nav_mobile_height.'px - 10px)}
		// 	.mobile-nav .logo img { max-height: calc('.$nav_mobile_height.'px - '.$double_nav_mobile_padding.'px) !important; }
		// }';

		if(get_field('veso_navigation_fixed', 'option') == true) {
			$fixed_bg = get_field('veso_navigation_fixed_bg', 'option');
			$fixed_color = get_field('veso_navigation_fixed_color', 'option');
			$fixed_dropdown_bg = get_field('veso_navigation_fixed_dropdown_bg', 'option');
			$fixed_dropdown_color = get_field('veso_navigation_fixed_dropdown_color', 'option');
			// $css .= '.veso-nav.fixed-nav { background: '.$fixed_bg.'; } .veso-nav.fixed-nav .nav > div > ul > li > a, .veso-nav.fixed-nav .profile a, .veso-nav.fixed-nav .logo h1 { color: '.$fixed_color.'; } .veso-nav.fixed-nav #icon-cart path { fill: '.$fixed_color.'} .veso-nav.fixed-nav > ul > li > a:after, .veso-nav.fixed-nav .veso-nav-burger span { background: '.$fixed_color.'; } .veso-nav.fixed-nav .desktop-menu .menu-item-has-children .sub-menu { background: '.$fixed_dropdown_bg.'; } .veso-nav.fixed-nav .menu-item-has-children .sub-menu a { color: '.$fixed_dropdown_color.'; }';

			$css .= '.veso-nav.fixed-nav { background: '.$fixed_bg.'; } .veso-nav.fixed-nav .menu-item-has-children .sub-menu, .veso-nav.fixed-nav .menu-item-has-children .sub-menu, .veso-nav.fixed-nav .menu-item-object-veso_mega_menu .sub-menu { background: '.$fixed_dropdown_bg.'; } .veso-nav.fixed-nav .nav-items .menu-item .sub-menu li a, .veso-nav.fixed-nav .mega-menu-inner li h6, .veso-nav.fixed-nav .mega-menu-inner h4, .veso-nav.fixed-nav .nav-additional .sub-menu a { color: '.$fixed_dropdown_color.' !important; } .nav-dark-text .menu-item-has-children .sub-menu { border-color: '.$fixed_color.' !important; } .veso-nav.fixed-nav .nav-items a, .veso-nav.fixed-nav .nav-additional a, .nav-logo h1, body .logo h1 { color: '.$fixed_color.'; } .veso-nav.fixed-nav #icon-cart path { fill: '.$fixed_color.'; }';
		}
	
		if(get_field('veso_custom_css', 'option')) {
			$custom_css = get_field('veso_custom_css', 'option');
			$css .= $custom_css;
		}
		$css = trim(preg_replace('/\s+/', ' ', $css));

		wp_add_inline_style( 'veso-main-stylesheet', $css );
	}

	add_action( 'wp_enqueue_scripts', 'veso_output_styles', 21);