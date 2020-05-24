		</div>
	<?php if(function_exists('get_field')) : ?>
	<?php if(get_field('veso_back_top', 'option') == true) : ?>
		<a id="scroll-up" class="scroll-up show-for-medium">
			<div class="arrow">
				<i class="fa fa-angle-up"></i>
				<span></span>
			</div>
			<span><?php echo esc_html__('Top', 'veso'); ?></span>
		</a>
	<?php endif; ?>
	<?php endif; ?>
<?php 

	if(function_exists('get_field')) :
		if(get_field('veso_enable_footer', 'option') == true) :
			$footer = get_field('veso_default_footer', 'option');
			if(get_field('veso_select_footer') && get_field('veso_show_footer') != 'disabled') {
				$footer = get_field('veso_select_footer');
			}
			if($footer && get_field('veso_show_footer') != 'disabled' ) : ?>
			<div class="footer-row-wrapper">
				<div class="row">
					<div class="small-12 columns small-centered">
						<footer>
							<?php echo apply_filters('the_content', $footer->post_content); ?>
						</footer>
					</div>
				</div>
			</div>
			<?php endif; 
		endif;
	endif; ?>

<?php if(class_exists('WooCommerce')) : ?>
	<div class="cart-offcanvas">
		<div class="cart-overlay"></div>
		<div class="shopping-cart">
		<?php
			echo veso_woocommerce_get_cart_overlay();
		?>
		</div>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>