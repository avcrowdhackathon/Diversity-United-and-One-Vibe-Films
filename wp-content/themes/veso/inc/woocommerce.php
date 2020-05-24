<?php

add_action('woocommerce_before_main_content', 'veso_woocommerce_before_main_content', 9);
function veso_woocommerce_before_main_content() {
	echo '<div class="shop-products page-padding"><div class="row">';
	if(is_active_sidebar('shop') && !is_product()) {
		$class = 'large-9 medium-centered large-uncentered';
	} else {
		$class = 'large-12 small-centered';
	}
	echo '<div class="small-12 '.esc_attr($class).' columns">';
}

add_action('woocommerce_after_main_content', 'veso_woocommerce_after_main_content',13);
function veso_woocommerce_after_main_content() {

	echo '</div>';
}

add_action('woocommerce_sidebar', 'veso_woocommerce_sidebar_before', 9);
function veso_woocommerce_sidebar_before() {
	if(is_active_sidebar('shop') && !is_product()) {
		echo '<div class="small-12 medium-10 medium-offset-1 large-offset-0 large-3 end columns"><div class="shop-sidebar">';
	}
}

add_action('woocommerce_sidebar', 'veso_woocommerce_sidebar_after', 11);
function veso_woocommerce_sidebar_after() {
	if(is_active_sidebar('shop') && !is_product()) {
		echo '</div></div>';
	}
	echo '</div></div>';
}

if(class_exists('WooCommerce')) {
	add_action('template_redirect', 'remove_sidebar_shop');
	function remove_sidebar_shop() {
		if ( is_product() ) {
	   		remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar');
	    }
	}
}

function veso_woocommerce_shop_loop_item_wrapper_start() {
	echo '<div class="veso-single-product">';
}

function veso_woocommerce_shop_loop_item_wrapper_end() {
	echo '</div>';
}

function veso_woocommerce_template_loop_product_title() {
	echo '<h3><a href="'.esc_url(get_permalink()).'" title="'.esc_attr(get_the_title()).'">' . get_the_title() . '</a></h3>';
}

add_filter( 'woocommerce_pagination_args' , 'veso_custom_pagination_args' );

function veso_custom_pagination_args( $args ) {
	return $args;
}

add_filter( 'woocommerce_comment_pagination_args' , 'veso_custom_pagination_reviews' );
function veso_custom_pagination_reviews( $args ) {
	return $args;

}

add_filter('woocommerce_cross_sells_columns', 'veso_cross_sells');
if (!function_exists('veso_cross_sells')) {
	function veso_cross_sells() {
		return 4; 
	}
}

function veso_woocommerce_header_add_to_cart_fragment($fragments) { 
	global $woocommerce;
	$shop_page_url = get_permalink(wc_get_page_id('shop'));
	$cart_url = wc_get_cart_url();
	$checkout_url = wc_get_checkout_url();
	$count = $woocommerce->cart->cart_contents_count;
	if($count == '0') {
		$count = '';
	}
	// $summation = '';
	$fragments['span.woo-cart-count'] = '<span class="woo-cart-count">'.$count.'</span>';

	$cart_contents = '<ul class="show-cart">';
	
	if (sizeof($woocommerce->cart->cart_contents) == 0) {

	} else {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ($_product->is_sold_individually()) {
				$product_quantity = "1";
			} else {
				$product_quantity = $cart_item['quantity'];
			}

			$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($_product->get_id()), 'menu_thumb' );
			
			if(empty($thumb_url)) {
				$thumb_url = '/img/placeholder.png';
			} else {
				$thumb_url = $thumb_url[0];
			}
			$cart_contents .= '<li class="product">
				<a href="'.$_product->get_permalink().'" class="img-product">
					<figure><img src="'.$thumb_url.'" alt="" /></figure>
				</a>

				<div class="list-product">
					<a class="remove-product" title="'.__( 'Remove this item', 'veso' ).'" href="'.esc_url( WC()->cart->get_remove_url( $cart_item_key ) ).'"><div class="icon-close"></div></a>
					<a href="' . $_product->get_permalink() . '"><h5 class="h6">' . $_product->get_title() . '</h5></a>
					<div class="quantity buttons_added header-font-family">' . $product_quantity . '</div>
					<div class="price-product header-font-family">' . strip_tags(WC()->cart->get_product_price( $_product )) . '</div>
				</div>
			</li>';		
		}

		// subtotal


		$cart_contents .= '</ul>';
		$summation = '<div class="summation">
			<div class="summation-subtotal">
				<span>'.__('Subtotal', 'veso').':</span>
				<span class="amount header-font-family">' . WC()->cart->get_cart_subtotal() . '</span>
			</div>
			<div class="btn-cart">
				<a class="btn btn-dark btn-sm btn-outline" href="'.esc_url($shop_page_url).'"><div class="btn-text">'.esc_html__('Go to shop', 'veso').'</div></a>
				<a class="btn btn-dark btn-sm btn-outline" href="'.add_query_arg('empty-cart','', wc_get_cart_url()).'"><div class="btn-text">'.esc_html__('Empty cart', 'veso').'</div></a>
				<a class="btn btn-light btn-sm btn-solid btn-view-cart" href="'.esc_url($cart_url).'"><div class="btn-text">'.esc_html__('View cart', 'veso').'</div></a>
				<a class="btn btn-dark btn-sm btn-solid btn-checkout" href="'.esc_url($checkout_url).'"><div class="btn-text">'.esc_html__('Checkout', 'veso').'</div></a>
			</div>
		</div>';
	}
	
	$fragments['ul.show-cart'] = $cart_contents;
	$fragments['div.summation'] = $summation;

	return $fragments;
}

add_filter('add_to_cart_fragments', 'veso_woocommerce_header_add_to_cart_fragment');

add_action( 'init', 'veso_woocommerce_clear_cart_url' );
function veso_woocommerce_clear_cart_url() {
	global $woocommerce;
	if ( isset( $_GET['empty-cart'] ) ) {
		$woocommerce->cart->empty_cart();
	}
}

function veso_woocommerce_get_cart_overlay() {
	global $woocommerce;
	$empty_cart = '';
	$shop_page_url = get_permalink(wc_get_page_id('shop'));
	$cart_url = wc_get_cart_url();
	$checkout_url = wc_get_checkout_url();
	if (sizeof($woocommerce->cart->cart_contents) == 0) {
		$empty_cart = '<p class="veso-empty-cart text-center">'.esc_html__('Cart is empty','veso').'</p>';
	} 
	$after = '';
	$after .= '<div class="cart-offcanvas-close"><div class="cart-icon-close"></div></div><ul class="show-cart">'.$empty_cart.'';
				
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ($_product->is_sold_individually()) {
				$product_quantity = "1";
			} else {
				$product_quantity = $cart_item['quantity'];
			}

			$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id($_product->get_id()), 'menu_thumb' );
			
			if(empty($thumb_url)) {
				$thumb_url = '';
			} else {
				$thumb_url = $thumb_url[0];
			}
			$after .= '<li class="product">
				<a href="'.$_product->get_permalink().'" class="img-product">
					<figure><img src="'.$thumb_url.'" alt="" /></figure>
				</a>

				<div class="list-product">
					<a class="remove-product" title="'.esc_html__( 'Remove this item', 'veso').'" href="'.esc_url( WC()->cart->get_remove_url( $cart_item_key ) ).'"><div class="icon-close"></div></a>
					<a href="' . $_product->get_permalink() . '"><h5 class="h6">' . $_product->get_title() . '</h5></a>
					<div class="quantity buttons_added header-font-family">' . $product_quantity . '</div>
					<div class="price-product header-font-family">' . strip_tags(WC()->cart->get_product_price( $_product )) . '</div>
				</div>
			</li>';
		}

		$after .= '</ul>';
		// subtotal

		$after .= '<div class="summation">
			<div class="summation-subtotal">
				<span>'.esc_html__('Subtotal', 'veso').':</span>
				<span class="amount header-font-family">' . WC()->cart->get_cart_subtotal() . '</span>
			</div>
			<div class="btn-cart">
				<a class="btn btn-dark btn-sm btn-outline" href="'.esc_url($shop_page_url).'"><div class="btn-text">'.esc_html__('Go to shop', 'veso').'</div></a>
				<a class="btn btn-dark btn-sm btn-outline" href="'.add_query_arg('empty-cart','', wc_get_cart_url()).'"><div class="btn-text">'.esc_html__('Empty cart', 'veso').'</div></a>
				<a class="btn btn-light btn-sm btn-solid btn-view-cart" href="'.esc_url($cart_url).'"><div class="btn-text">'.esc_html__('View cart', 'veso').'</div></a>
				<a class="btn btn-dark btn-sm btn-solid btn-checkout" href="'.esc_url($checkout_url).'"><div class="btn-text">'.esc_html__('Checkout', 'veso').'</div></a>
			</div>
		</div>';
	return $after;
}

add_theme_support( 'veso' );
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );



	function veso_woocommerce_template_loop_add_to_cart( $args = array() ) {
		global $product;

		if ( $product ) {
			$defaults = array(
				'quantity' => 1,
				'class'    => implode( ' ', array_filter( array(
						'btn btn-xs btn-solid btn-dark',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
				) ) ),
			);
			$args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $product );
			wc_get_template( 'loop/add-to-cart.php', $args );
		}
	}


remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
add_action('woocommerce_after_shop_loop_item', 'veso_woocommerce_template_loop_add_to_cart');
