<?php
get_header(); 
$sidebar = 'show';
$blog_class = 'large-8';
if(function_exists('get_field')) {
	if(get_field('veso_archive_sidebar', 'option') == true) {
		$sidebar = 'show';
		$blog_class .= ' sidebar-on';
	} else {
		$sidebar = 'hide';
		$blog_class = 'large-offset-1 large-10';
	}
}
?>

<div class="content blog blog-rows page-padding">
	<div class="row">
		<div class="blog-post-content small-12 <?php echo esc_attr( $blog_class ); ?> columns">
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<?php
					the_archive_title( '<h2 class="page-title">', '</h2>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header>
				<?php while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/post/content', get_post_format() );
				endwhile; ?>
			<?php the_posts_pagination(array(
    			'prev_text' => '<div class="previous-page arrow-prev"><i class="fa fa-angle-left"></i></div>',
    			'next_text' => '<div class="next-page arrow-next"><i class="fa fa-angle-right"></i></div>',
			)); ?>			
			<?php else :
			get_template_part( 'template-parts/post/content', 'none' );
			endif; ?>
		</div>
		<div class="small-12 large-4 columns">
			<?php if(is_active_sidebar('sidebar-1') && $sidebar == 'show') : ?>
			<div class="single-post-sidebar">
				<?php get_sidebar(); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php get_footer();