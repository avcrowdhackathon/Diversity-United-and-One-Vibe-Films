<?php

$sticky = '';
if ( is_sticky() ) {
	$sticky = 'sticky-post';
}
$image = '';
if ( has_post_thumbnail() ) {
	$post_thumbnail_id = get_post_thumbnail_id( $post );
	$image = wp_get_attachment_image_src($post_thumbnail_id, 'large');
	if(is_array($image)) {
		$imgUrl = $image[0];
		$width = $image[1];
		$height = $image[2];
	}
	
} 
?>

<div class="post-header <?php echo esc_attr($sticky); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php echo esc_url(get_permalink()); ?>" class="post-images">
			<img class="b-lazy" src="<?php echo veso_image_preloader($width,$height); ?>" height="<?php esc_attr($height);?>" width="<?php echo esc_attr($width); ?>" data-src="<?php echo esc_url($imgUrl); ?>" />
		</a>
		<div class="post-meta loaded-post">
	<?php else : ?>
		<div class="post-meta loaded-post meta-full-width">
	<?php endif; ?>
		<header><h4 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>"><span><?php the_title(); ?></span></a></h4></header>
		<ul class="post-cat-meta">
			<?php echo veso_posted_on();?>
		</ul>
		<?php echo veso_edit_link(); ?>
	<div class="content excerpt">
		<?php 
			the_excerpt();
		?>

	</div>
	<div class="text-right"><a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-read-more btn-underline btn-dark btn-xs">
		<span class="btn-text"><?php esc_html_e('Read more', 'veso'); ?></span>
	</a></div>
	</div>
</div>

