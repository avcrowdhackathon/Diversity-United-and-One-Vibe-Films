<?php

$shareData = array();
$shareData = array('title'=>get_the_title(), 'url'=>get_the_permalink());
$shareImage = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
if(isset($shareImage[0])) {
	$shareImage = $shareImage[0];
	$shareData['image'] = $shareImage;
}
$class = $postClass = $gallery = '';
$sidebarActive = false;
if(is_active_sidebar('sidebar-1')) {
	$sidebarActive = true;
}
if($sidebarActive) {
	$class = 'medium-centered large-uncentered';
} else {
	$class = 'small-centered';
}
$postFormat = get_post_format();
if(has_post_format('gallery')) {
	$postClass .= ' post-gallery';
} elseif(has_post_format('video')) {
	$postClass .= ' post-video';
} elseif(has_post_format('quote')) {
	$postClass .= ' post-quote';
} elseif(has_post_format('link')) {
	$postClass .= ' post-link';
} elseif(has_post_format('audio')) {
	$postClass .= ' post-audio';
} else {
	$postFormat = 'image';
}
if(function_exists('get_field')) {
	$gallery = get_field('veso_post_gallery');
	$videoUrl = get_field('veso_post_url'); 
	$link = get_field('veso_post_link'); 
	$quote = get_field('veso_post_quote'); 
}

get_header(); ?>
<?php
	while ( have_posts() ) : the_post();
?>
<div class="content single-post page-padding">
	<div class="row">
		<div class="single-post-content small-12 large-8 columns <?php echo esc_attr($class); ?>">
			<div class="post-header">
				<?php 
					// POST FORMAT - IMAGE
					if($postFormat == 'image' || !function_exists('get_field')) : ?>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-images">
							<?php
							$image = get_post_thumbnail_id();
							$image_url = wp_get_attachment_image_src($image, 'large');
							echo '<img height="'.$image_url[2].'" width="'.$image_url[1].'" src="'.esc_html($image_url[0]).'">';
							?>
						</div>
					<?php endif; ?>
				<?php 
					// POST FORMAT - GALLERY
					elseif ($postFormat == 'gallery' && isset($gallery) && $gallery != '') : ?>
					<div class="veso-post-gallery swiper-container"><div class="swiper-wrapper">
					<?php 
						foreach($gallery as $g) {
							$image = $g['url'];
							$image = 'background-image: url('.esc_url($image).')';
					?>
					<div class="swiper-slide">
						<div style="<?php echo esc_attr($image); ?>;">
						</div>
					</div>
					<?php } ;?>
					
					</div><div class="veso-post-gallery-arrows show-for-large swiper-arrows">
						<div class="arrow-prev arrow">
							<div class="arrow-icon"><i class="fa fa-angle-left text-color"></i></div>
						</div>
						<div class="arrow-next arrow">
							<div class="arrow-icon"><i class="fa fa-angle-right text-color"></i></div>
						</div>
					</div></div>

				<?php 
					// POST FORMAT - VIDEO
					elseif ($postFormat == 'video' && isset($videoUrl) && $videoUrl != '') : ?>
					<?php
					?>
					<div class="veso-post-video">
					<div class="responsive-embed widescreen"><?php echo wp_oembed_get($videoUrl); ?></div>
					</div>
				<?php 
					// POST FORMAT - QUOTE
					elseif ($postFormat == 'quote' && isset($quote) && $quote != '') : ?>
					<div class="veso-post-quote">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="post-images">
								<?php
								$image = get_post_thumbnail_id();
								$image_url = wp_get_attachment_image_src($image, 'large');
								echo '<img class="b-lazy" height="'.$image_url[2].'" width="'.$image_url[1].'" src="'.veso_image_preloader($image_url[1],$image_url[2]).'" data-src="'.esc_html($image_url[0]).'"></a>';
								?>
							</div>
						<?php endif; ?>
						<div class="quote"><div class="quotation-marks">
						<svg version="1.1" class="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 75.999 75.999"  xml:space="preserve">
							<path d="M14.579,5C6.527,5,0,11.716,0,20c0,8.285,6.527,15,14.579,15C29.157,35,19.438,64,0,64v7
									C34.69,71,48.286,5,14.579,5z M56.579,5C48.527,5,42,11.716,42,20c0,8.285,6.527,15,14.579,15C71.157,35,61.438,64,42,64v7
									C76.69,71,90.286,5,56.579,5z"/>
						</svg>
					</div><?php echo apply_filters('the_content', $quote); ?></div>
					</div>
				<?php 
					// POST FORMAT - LINK
					elseif ($postFormat == 'link' && isset($link) && $link != '') : ?>
					<?php 
					$textLink = get_field('veso_post_link_text'); 
					?>
					<div class="veso-post-link">
						<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($textLink); ?></a>
					</div>
				<?php 
					// POST FORMAT - AUDIO
					elseif ($postFormat == 'audio') : ?>
					<?php $audioUrl = get_field('veso_post_url'); 
					?>
					<div class="veso-post-audio">
					<?php echo wp_audio_shortcode( array('src' => $audioUrl, 'autoplay' => '', 'preload'  => 'none')); ?>
					</div>
				<?php endif; ?>
				<div class="post-meta loaded-post">
					<header><h1 class="post-title h3"><?php the_title(); ?></h1></header>
					<ul class="post-cat-meta">
						<?php echo veso_posted_on(); veso_edit_link();?>
					</ul>
					<?php
					if(function_exists('veso_get_share_links')) {
						echo veso_get_share_links($shareData);
					}
					?>
				</div>
			</div>
			<div class="content">
				<?php 
					the_content();
					wp_link_pages( array(
						'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'veso' ),
						'after'       => '</div>',
						'link_before' => '<span class="page-number">',
						'link_after'  => '</span>',
					) );
				; ?>

			</div>
			
			<?php echo veso_categories_tags_list(); ?>
			<div class="single-post-comments">
				<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>
			</div>
		</div>
		<?php if($sidebarActive) : ?>
		<div class="small-12 large-4 columns">
			<div class="single-post-sidebar">
				<?php get_sidebar(); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php
	endwhile;
?>
<?php get_footer();