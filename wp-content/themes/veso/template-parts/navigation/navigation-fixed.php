<?php
	$logo = $profiles = $social_list = $social_name = $social_url = $type = $additional_nav = '';

	if(function_exists('get_field')) :
		$logo = get_field('veso_navigation_fixed_logo', 'option');
		if(isset($logo['url']) && $logo ['url'] != '' ) {
			$logo = $logo['url'];
		} else {
			$logo = '';
		}
		$height = get_field('veso_navigation_fixed_height', 'option');

	if(get_page_template_slug() == 'page-templates/home.php') {
		$selectedNav = get_post_meta(get_the_ID(), 'veso_home_navigation', true);
	} else {
		$selectedNav = get_post_meta(get_the_ID(), 'veso_navigation', true);
	}
	if(!$selectedNav) {
		$selectedNav = 1;
	}
	if(function_exists('get_field')) {
		if(function_exists('have_rows')) {
			if( have_rows('veso_navigation_bars', 'option') ) {
				$i = 0;
				while( have_rows('veso_navigation_bars', 'option') ) {
					$i++;
					the_row();
					$value = $i;
					if($i == $selectedNav) {
						$type = get_sub_field('type');
						$position = get_sub_field('position');
						$custom_text = get_sub_field('custom_text');
						$additional_nav = get_sub_field('additional_nav');
						if($position == false) {
							$position = 'nav-solid';
						} else {
							$position = 'nav-transparent';
						}
					}
				}
				
			}

			if($type == 'nav-burger') {
				$mobile_class = '';
			} else {
				$mobile_class = 'hide-for-large';
			}
			$nav_pos_ver = 'nav-top';
		}
	} else {
		$type = 'nav-center';
		$nav_pos_ver = 'nav-top';
		$position = 'nav-solid';
	}
?>
<?php if(get_field('veso_navigation_fixed', 'option') == true) : ?>
<div id="fixed-nav" class="page-header-nav veso-nav fixed-nav nav-top <?php echo esc_attr($type); ?>" data-height="<?php echo esc_attr($height); ?>">
	<div class="row">
		<div class="medium-12 columns">
		<div class="nav">
				<?php if($logo != '') : ?>
				<div class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="show-in-viewport">
						<figure class="static-logo">
							<img src="<?php echo esc_url($logo); ?>" alt="<?php esc_attr(get_bloginfo( 'name' )); ?>">
						</figure>
					</a>
				</div>
				<?php else : ?>
				<div class="logo logo-text">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="show-in-viewport">
						<h1><?php bloginfo( 'name' ); ?></h1>
					</a>
				</div>
				<?php endif;?>
				<?php if($type != 'nav-burger') : ?>
					<?php if ( has_nav_menu( 'top' ) ) : ?>
					<?php wp_nav_menu( array(
					'theme_location' => 'top',
					'menu_id'        => 'fixed-top-menu',
					'container_class'=> 'main-nav nav-items',
					'menu_class'     => 'dropdown menu desktop-menu menu-main-menu',
					'items_wrap' => '<ul id="%1$s" class="%2$s show-for-large">%3$s</ul>',
					'walker' => new Veso_Nav_Walker,
					) ); ?>
					<?php endif; ?>
				<?php endif; ?>
				<div class="nav-additional">
					<ul class="desktop-menu">
						<?php if(($additional_nav == 'top_nav' && $type == 'nav-burger') || $type != 'nav-burger') : ?>
						<?php echo veso_get_nav_socials(); ?>
						<?php endif; ?>
						<?php if(class_exists('WooCommerce')) : ?>
						<li class="open-cart"><a href="#"><svg version="1.1" id="icon-cart" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 407.453 407.453" style="enable-background:new 0 0 407.453 407.453;" xml:space="preserve">
	<path d="M255.099,116.515c4.487,0,8.129-3.633,8.129-8.129c0-4.495-3.642-8.129-8.129-8.129H143.486
		c-4.487,0-8.129,3.633-8.129,8.129c0,4.495,3.642,8.129,8.129,8.129H255.099z"/>
	<path d="M367.062,100.258H311.69c-4.487,0-8.129,3.633-8.129,8.129c0,4.495,3.642,8.129,8.129,8.129h47.243
		v274.681H48.519V116.515h44.536c4.487,0,8.129-3.633,8.129-8.129c0-4.495-3.642-8.129-8.129-8.129H40.391
		c-4.487,0-8.129,3.633-8.129,8.129v290.938c0,4.495,3.642,8.129,8.129,8.129h326.671c4.487,0,8.129-3.633,8.129-8.129V108.386
		C375.191,103.891,371.557,100.258,367.062,100.258z"/>
	<path d="M282.59,134.796c4.487,0,8.129-3.633,8.129-8.129V67.394C290.718,30.238,250.604,0,201.101,0
		c-49.308,0-89.414,30.238-89.414,67.394v59.274c0,4.495,3.642,8.129,8.129,8.129s8.129-3.633,8.129-8.129V67.394
		c0-28.198,32.823-51.137,73.36-51.137c40.334,0,73.157,22.939,73.157,51.137v59.274
		C274.461,131.163,278.095,134.796,282.59,134.796z"/>
	<path d="M98.892,147.566c0,11.526,9.389,20.907,20.923,20.907c11.534,0,20.923-9.38,20.923-20.907
		c0-4.495-3.642-8.129-8.129-8.129s-8.129,3.633-8.129,8.129c0,2.561-2.089,4.65-4.666,4.65c-2.569,0-4.666-2.089-4.666-4.65
		c0-4.495-3.642-8.129-8.129-8.129S98.892,143.071,98.892,147.566z"/>
	<path d="M282.59,168.473c11.534,0,20.923-9.38,20.923-20.907c0-4.495-3.642-8.129-8.129-8.129
		c-4.487,0-8.129,3.633-8.129,8.129c0,2.561-2.089,4.65-4.666,4.65c-2.577,0-4.666-2.089-4.666-4.65
		c0-4.495-3.642-8.129-8.129-8.129c-4.487,0-8.129,3.633-8.129,8.129C261.667,159.092,271.055,168.473,282.59,168.473z"/>
</svg><span class="woo-cart-count"></span></a></li>
						<?php endif; ?>
						<?php do_action('wpml_add_language_selector'); ?>
					</ul>
				</div>
				<div class="veso-nav-burger <?php echo esc_attr( $mobile_class ); ?>">
					<span class="top"></span>
					<span class="center"></span>
					<span class="bottom"></span>
				</div>
			</div>
		</div>
	</div>
</div>



<?php endif; ?>

<?php endif; ?>