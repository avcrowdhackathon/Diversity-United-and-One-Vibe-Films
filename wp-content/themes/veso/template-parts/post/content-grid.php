<?php 
$image = '';
if ( has_post_thumbnail() ) {
	$image = get_the_post_thumbnail_url( '', 'large');
} 
$sticky = '';
if ( is_sticky() ) {
	$sticky = 'sticky-post';
}
; ?>

<div class="post-header <?php echo esc_attr($sticky); ?>">
	<a href="<?php echo esc_url(get_permalink()); ?>" class="post-images">
		<div class="b-lazy" data-src="<?php echo esc_url($image); ?>"></div>
	</a>
	<div class="post-meta loaded-post">
		<header><h5 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>"><span><?php the_title(); ?></span></a></h5></header>
		<ul class="post-cat-meta">
			<?php echo veso_posted_on();?>
		</ul>
		<?php echo veso_edit_link(); ?>
	</div>
</div>