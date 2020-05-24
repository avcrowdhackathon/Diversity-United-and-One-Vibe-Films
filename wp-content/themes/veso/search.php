<?php
get_header(); ?>
<div class="content blog blog-classic page-padding">
	<div class="row">
		<div class="blog-post-content small-12 large-9 columns">
		<?php if ( have_posts() ) : ?>
			<header class="page-header">
				<h2 class="page-title"><?php echo esc_html__( 'Search Results for: ', 'veso' ); ?> <span> <?php echo get_search_query(); ?></span></h2>
			</header>
			<?php while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/post/content', 'excerpt' );
				endwhile; ?>
			<?php 
			the_posts_pagination(array(
    			'prev_text' => '<div class="previous-page arrow-prev"><i class="fa fa-angle-left"></i></div>',
    			'next_text' => '<div class="next-page arrow-next"><i class="fa fa-angle-right"></i></div>',
			)); 						

		else : ?>
			<header class="page-header">
				<h2 class="page-title"><?php echo esc_html__( 'Nothing Found', 'veso' ); ?></h2>
			</header>
			<p><?php echo esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'veso' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif;
		?>
		</div>
	</div>
</div>
<?php get_footer();
