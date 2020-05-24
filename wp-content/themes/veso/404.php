<?php

get_header(); ?>

<div class="content blog blog-classic page-padding">
	<div class="row">
		<div class="blog-post-content small-12 large-9 columns">
			<section class="error-404 not-found">
				<h1 class="header-404"><?php esc_html_e('404', 'veso');?></h1>
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'veso' ); ?></h1>
				</header>
				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'veso' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</section>
		</div>
	</div>
</div>
<?php get_footer();
