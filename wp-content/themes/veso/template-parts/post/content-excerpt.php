<?php
	$class = array('post-item');
	$class[] = 'no-thumbnail';
	$sticky = '';
	if ( is_sticky() ) {
		$sticky = 'sticky-post';
	}
?>


<div <?php post_class($class); ?>>
	<div class="post-content-wrapper">
		<div class="post-header <?php echo esc_attr($sticky); ?>">
			<div class="post-meta meta-full-width loaded-post">
				<header><h4 class="post-title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h4></header>
				<ul class="post-cat-meta">
					<?php echo veso_posted_on();?>
				</ul>
				<?php echo veso_edit_link(); ?>
			<div class="content excerpt">
				<?php 
					the_excerpt();
				?>
			</div>
			<div class="text-right "><a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-read-more btn-underline btn-dark btn-xs">
				<span class="btn-text veso-header"><?php esc_html_e('Read more', 'veso'); ?></span>
			</a></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>