<?php
/*
Template Name: Blog List 
*/
get_header(); 

if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
else { $paged = 1; }
$categories = '';
if($categories && $categories != '') {
	$categories = explode(',', $categories);
} else {
	$categories = '';
}
$perpage = '4';

$query_params = array(
	'post_type' => 'post',
	'category__in' => $categories,
	'paged' => $paged,
	'posts_per_page' => $perpage,
	'ignore_sticky_posts' => true

);


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
	$blog_categories = get_field('veso_blog_category');
	$perpage = get_field('veso_blog_per_page');
	if($blog_categories !== false) {
		$query_params = array(
			'post_type' => 'post',
			'category__in' => $blog_categories,
			'paged' => $paged,
			'posts_per_page' => $perpage,
			'ignore_sticky_posts' => true

		);
	}
}
$wp_query = new WP_Query( $query_params );
?>

<div class="content blog blog-rows page-padding">
	<div class="row">
		<div class="blog-post-content small-12 <?php echo esc_attr( $blog_class ); ?> columns">
			<?php if ( $wp_query->have_posts() ) : ?>
				<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
					get_template_part( 'template-parts/post/content', 'rows' );
				endwhile; ?>
				<?php the_posts_pagination(array(
    			'prev_text' => '<div class="previous-page arrow arrow-prev"><div class="arrow-icon"><i class="fa fa-angle-left"></i></div></div>',
    			'next_text' => '<div class="next-page arrow arrow-next"><div class="arrow-icon"><i class="fa fa-angle-right"></i></div></div>',
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