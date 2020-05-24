<?php
class Veso_Typography {
	function __construct() {
		add_action( 'wp_enqueue_scripts', array($this, 'google_link'), 22);
		add_filter('the_content', array($this, 'remove_empty_p'), 20, 1);
		add_action( 'acf/save_post', array($this, 'save_typography'), 10, 4 );
	}
	protected $config = array(
		'veso_font_body' => 'body',
		'veso_font_headers' => 'h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6,.btn,.veso-header,.woocommerce ul.products li.product .price, .woocommerce .button, .woocommerce .product-title, .woocommerce .product-name, .woocommerce table.shop_table th, .single-product.woocommerce .amount, .veso-fullscreen-links-list .title',
		'veso_font_navigation' => '.mobile-nav-overlay .mobile-menu-content,.veso-nav a, .fixed-nav a',
	);

	function remove_empty_p( $content ) {
		$content = force_balance_tags( $content );
		$content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
		$content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
		return $content;
	}

	function google_link() {
		$typography = get_option('veso_typography');
		if($typography) {
			wp_enqueue_style( 'veso-google-fonts', $typography[0], array(), null );
			wp_add_inline_style( 'veso-google-fonts', $typography[1]);		
			return;
		}
		
		$fonts = array();
		$families = '';

		$css = '';
		$subsetsString = '';
		$subsets = get_field('veso_font_subsets', 'options');
		
		if($subsets) {
			foreach($subsets as $key => $sub) {
				if($key > 0) {
					$subsetsString .= ',';
				}
				$subsetsString .= $sub;
			}
			$subsetsString = '&amp;subset='.$subsetsString;
		}

		$font_scraper = array();
		$i = -1;

		foreach ($this->config as $key => $selector) {
			$i++;
			$value = get_field($key, 'options');
			if($value) {
			$family = urlencode($value['font-family']);
			// var_dump($value);
			if(isset($value['font-weight'])) {
				$weight = urlencode($value['font-weight']);
			} else {
				$weight = urlencode('400,400i');
			}

			if(isset($value['font-weight-multi'])) {
				$weight .= urlencode($value['font-weight-multi']);
			}
			$style = '';

			if(!isset($value['font_style'])) {
				$value['font_style'] = 'normal';
			}

			$css .= $this->generate_styles($key, $selector, $value);
			if(isset($value['font_style']) && $value['font_style'] == 'italic') {
				$style = 'i';
			}

			if ( !array_search($family, $font_scraper)) {
				$family = str_replace(' ', '+', $family);
				if ( !empty($weight)) {
					$font_scraper[$i] = $family . ':' . $weight.$style;
				} else {
					$font_scraper[$i] = $family;
				}
			}
			}
		}

		// var_dump($font_scraper);
		// var_dump($subsetsString);
		if(!empty($font_scraper)) {
			foreach ($font_scraper as $font) {
				$fonts = implode("%7C", $font_scraper);
			}

			if ( $fonts ) {
				$string = '//fonts.googleapis.com/css?family=' . $fonts.$subsetsString;
				update_option('veso_typography', array($string, $css), true);
			}
		}
	}

	function generate_styles($type, $selector, $value) {
		$css = '';
		if(isset($value['font-family'])) {
			$backup = 'Arial, Helvetica, sans-serif';
			$css .= "font-family:'".$value['font-family']."',".$backup.";";
		}

		if(isset($value['font-weight']) && $value['font-weight'] != '') {
			$css .= 'font-weight:'.$value['font-weight'].';';
		}

		if(isset($value['font_style']) && $value['font_style'] != '') {
			$css .= 'font-style:'.$value['font_style'].';';
		}

		if(isset($value['letter_spacing']) && $value['letter_spacing'] != '') {
			$css .= 'letter-spacing:'.$value['letter_spacing'].'px;';
		}

		if(isset($value['font_size']) && $value['font_size'] != '') {
			$css .= 'font-size:'.$value['font_size'].'px;';
		}

		return $selector .'{'.$css.'}';
	}

	function save_typography( $post_id) {
		$screen = get_current_screen();
		if($screen->base == 'theme-settings_page_acf-options-typography') {
			delete_option('veso_typography');
			$this->google_link();
		}
	}
}

new Veso_Typography;