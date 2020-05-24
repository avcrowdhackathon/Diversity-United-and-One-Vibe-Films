<?php
if(function_exists('get_field')) :
if(get_field('veso_portfolio_style') == 'fullwidth') {
	$style = 'large';
} else {
	$style = get_field('veso_portfolio_style');
}
$portfolio_class = 'single-portfolio-'.$style;
$gallery_images = get_field('veso_portfolio_gallery');
if(get_field('veso_portfolio_style') == 'large' || get_field('veso_portfolio_style') == 'fullwidth') {
	$column_class = "large-12";
} else {
	$column_class = "large-8";
}
get_header(); 

while ( have_posts() ) : the_post();
if(get_field('veso_portfolio_style') == 'blank') :
?>
<div class="single-portfolio <?php echo esc_attr($portfolio_class); ?> classic page-bg-color">
	<div class="row">
		<div class="small-12 columns small-centered">
			<?php the_content(); ?>
		</div>
	</div>
</div>
<?php else : ?>

<div class="single-portfolio veso-single-portfolio <?php echo esc_attr($portfolio_class); ?> classic page-bg-color page-padding <?php echo esc_attr('content-'.get_field('veso_portfolio_position')); ?>">
	<div class="row">
		<?php if(get_field('veso_portfolio_position') == 'bottom' && (get_field('veso_portfolio_style') == 'large' || get_field('veso_portfolio_style') == 'fullwidth') ) {
			get_template_part('template-parts/portfolio/portfolio', 'content');
		} ?>

		<?php if(get_field('veso_portfolio_style') == 'fullwidth') {
			echo '</div><div class="row-fluid">';
		} ?>
		<div class="small-12 <?php echo esc_attr( $column_class ); ?> columns small-centered">
			<?php if($gallery_images && $gallery_images !== '') : ?>
				<?php
					// IMAGES  
					if(get_field('veso_portfolio_gallery_view') == 'standard') : ?>
					<div class="veso-single-gallery">
					<?php foreach ($gallery_images as $gallery_image) : ?>

						<?php
						if($gallery_image['type'] != 'video') :
						?>
							<a href="<?php echo esc_url($gallery_image['url']); ?>" class="original-size-img fade-only" title="<?php echo esc_attr($gallery_image['title']); ?>"><img src="<?php echo veso_image_preloader($gallery_image['width'], $gallery_image['height']);?>" data-src="<?php echo esc_url($gallery_image['url']); ?>" class="b-lazy" /></a>

						<?php else : ?>
							<div class="original-size-img">
							<?php echo do_shortcode('[video src="'.$gallery_image['url'].'"]'); ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php

					?>
					</div>

				<?php 
					// SLIDER
					elseif(get_field('veso_portfolio_gallery_view') == 'slider') : ?>
					<div class="single-gallery-slider veso-single-gallery swiper-container">
						<div class="swiper-wrapper">
						<?php foreach ($gallery_images as $gallery_image) : ?>
							<div class="swiper-slide"><div class="slide"><a href="<?php echo esc_url($gallery_image['url']); ?>" title="<?php echo esc_attr($gallery_image['title']); ?>"><img src="<?php echo esc_url($gallery_image['url']); ?>" /></a></div></div>
						<?php endforeach; ?>
						</div>
						<div class="single-gallery-arrows show-for-large swiper-arrows">
							<div class="arrow-prev gallery-arrow">
								<div class="arrow-icon"><i class="fa fa-angle-left text-color"></i></div>
							</div>
							<div class="arrow-next gallery-arrow">
								<div class="arrow-icon"><i class="fa fa-angle-right text-color"></i></div>
							</div>
						</div>
					</div>
				<?php
					// GRID
					elseif(get_field('veso_portfolio_gallery_view') == 'grid') : ?>
					<div class="single-gallery-grid veso-single-gallery veso-single-gallery-grid">
						<div class="grid-sizer"></div>
						<?php foreach ($gallery_images as $gallery_image) : ?>
							<?php $image = wp_get_attachment_image_src($gallery_image['ID'], 'large'); ?>
							<div class="veso-gallery-image"><a href="<?php echo esc_url($gallery_image['url']); ?>" title="<?php echo esc_attr($gallery_image['title']); ?>"><img class="b-lazy" src="<?php echo veso_image_preloader($image[1], $image[2]); ?>" data-src="<?php echo esc_url($gallery_image['url']); ?>" width="<?php echo esc_attr( $image[1] ); ?>" height="<?php echo esc_attr( $image[2] ); ?>" /></a></div>
						<?php endforeach; ?>
					</div>
				<?php 
					// MASONRY
					elseif(get_field('veso_portfolio_gallery_view') == 'masonry') : ?>
					<?php $default_layout = array(
							'w2-h2', 'w2-h1', 'w1-h1', 'w1-h1',
							'w1-h1', 'w1-h2', 'w1-h1', 'w1-h1', 'w1-h1', 'w2-h1',
							'w1-h1', 'w1-h1', 'w2-h2', 'w1-h1', 'w1-h1',
							'w2-h1', 'w1-h1', 'w1-h1',
							'w2-h1', 'w1-h2', 'w1-h1', 'w1-h1', 'w1-h1', 'w1-h1',
							'w2-h1', 'w1-h1', 'w1-h1', 'w1-h1', 'w1-h1', 'w2-h1',
							'w1-h1', 'w2-h1', 'w1-h1',
							'w1-h1', 'w1-h1', 'w2-h1',
							'w1-h1', 'w2-h2', 'w1-h1', 'w1-h1', 'w1-h1',
						); 

						if(get_field('veso_portfolio_style') == 'small') {
							$default_layout = array(
								'w2-h2', 'w1-h1', 'w1-h1',
								'w1-h2', 'w2-h1', 'w1-h1', 'w1-h1',
								'w2-h1', 'w1-h1', 'w1-h1', 'w2-h1',
							);
						}
						$layout_index1 = $layout = 0;
						$layouts = $default_layout;

						?>
					<div class="single-gallery-masonry veso-single-gallery"><div class="grid-sizer"></div>
						<?php foreach ($gallery_images as $gallery_image) : ?>
							<?php 
								$layout = $layout_index1;
								$layout = $layouts[$layout];
								$image_url = wp_get_attachment_image_src($gallery_image['ID'], 'large');
								$image_full = wp_get_attachment_image_src($gallery_image['ID'], 'full'); ?>
							<article class="veso-gallery-image <?php echo esc_attr( $layout ); ?>"><a href="<?php echo esc_url( $image_full[0] ); ?>" title="<?php echo esc_attr($gallery_image['title']); ?>"></a><div class="layer"><div class="img-bg b-lazy" data-src="<?php echo esc_url($image_url[0]); ?>"><img src="<?php echo esc_url($image_url[0]); ?>"></div></div></article>
							<?php $layout_index1++;
								if ( !isset($layouts[$layout_index1]) ) {
									$layout_index1 = 0;
								} 
							?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php if((get_field('veso_portfolio_position') == 'top' && (get_field('veso_portfolio_style') == 'large' || get_field('veso_portfolio_style') == 'fullwidth')) || get_field('veso_portfolio_style') == 'small' )  {
			get_template_part('template-parts/portfolio/portfolio', 'content');
		} ?>
	</div>
</div>
<?php 
	endif;
	endwhile;
?>
<?php else : ?>
<div class="single-portfolio veso-single-portfolio classic page-bg-color page-padding">
	<div class="row">
		<div class="small-12 columns small-centered">
			<?php // if plugin is off; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php get_footer(); ?>