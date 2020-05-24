<?php get_header();

$class = $full_pages = $full_class = $col_class = '';
$post_id = get_the_ID();
$vc_enabled = get_post_meta($post_id, '_wpb_vc_js_status', true);
$col_class = 'small-12 columns small-centered';

if($vc_enabled != true) {
	$class = 'page-padding';
}
if(function_exists('get_field')) {
	$full_pages = get_field('veso_full_pages');
	if($full_pages == true) {
		$full_class = 'veso-full-pages';
		$col_class = 'full-column';
	}
}


while ( have_posts() ) : the_post(); ?>
<div class="classic page-bg-color <?php echo esc_attr($class);?> <?php echo esc_attr($full_class); ?>">
	<?php if(!$full_pages == true) {
		echo '<div class="row">';
	} ?>
		<div class="<?php echo esc_attr($col_class);?> blog-content">
			<?php if(function_exists('get_field')) :
				if($full_pages == false) :
				$show_title = get_field('veso_show_page_title', 'option');
				if(is_null($show_title)) {
					$show_title = true;
				}
				if(isset($show_title) && $show_title != false) : ?>
					<div class="page-header animate-text loaded-text page-header-title">
						<h1><?php the_title();?></h1>
					</div>
				<?php endif; ?>
				<?php endif; ?>
			<?php else: ?>
				<div class="page-header animate-text loaded-text page-header-title">
					<h1><?php the_title();?></h1>
				</div>
			<?php endif; ?>
			<?php the_content(); ?>
		</div>
		<div class="small-12 columns small-centered blog-content">
			<?php
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		</div>
	<?php if(!$full_pages == true) {
		echo '</div>';
	} ?>
</div>
<?php endwhile; ?>
<?php get_footer();