<?php if(get_field('veso_portfolio_style') == 'large' || get_field('veso_portfolio_style') == 'fullwidth') : ?>
<div class="small-12 large-12 columns small-centered">
<?php else : ?>
<div class="small-12 large-4 columns small-centered">
<?php endif; ?>
	<div class="single-portfolio-content">
		<div class="single-portfolio-header">
			<h2 class="header-underline"><span><?php the_title(); ?></span></h2>
			<?php 
				$categories_list = '';
				$postCats = get_the_terms( get_the_ID(), 'veso_portfolio_categories' );
				if (is_array($postCats)) : ?>
					<ul class="portfolio-category">
					<?php foreach ($postCats as $c => $category) : ?>
						<li><a class="veso-hover-text" href="<?php echo esc_url(get_term_link($category->term_id)); ?>" title="<?php echo esc_attr($category->name); ?>"><?php echo esc_html($category->name); ?></a></li>
					<?php endforeach; ?>
					</ul>						
				<?php endif; ?>
			<?php if(get_field('veso_portfolio_style') == 'small') : ?>
			<div class="single-portfolio-text">
				<?php echo apply_filters( 'the_content', get_field('veso_portfolio_text') );; ?>
			</div>
			<?php endif; ?>
			<div class="single-gallery-attributes">
				<?php if( have_rows('veso_portfolio_attribiutes') || 'veso_portfolio' === get_post_type()) : ?>
					<div class="attr">
					<?php while( have_rows('veso_portfolio_attribiutes') ) : 
						the_row();
					?>
						<div class="attr-header"><?php echo esc_html(get_sub_field('attr_header')); ?></div>
						<div class="attr-content"><?php echo apply_filters('the_content', get_sub_field('attr_content')); ?></div>
					<?php endwhile; ?>
					<?php 
						$tags_list = get_the_term_list($post->ID,'veso_portfolio_tags','',', ');
							if ( $tags_list ) {	
								echo '<div class="attr-header">'.esc_html__('Tags:', 'veso').'</div><div class="attr-content"><p>' . $tags_list . '</p></div>';
							}
				
					?>
					</div>
				<?php endif; ?>
			</div>
			<?php if( have_rows('veso_portfolio_back_to_page') ) : ?>
				<?php while( have_rows('veso_portfolio_back_to_page') ) : 
					the_row();
				?>
				<div class="single-gallery-back"><a class="text-link" href="<?php echo esc_url(get_sub_field('link_page')); ?>"><?php echo esc_html(get_sub_field('back_text')); ?></a></div>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	<?php if(get_field('veso_portfolio_style') == 'large' || get_field('veso_portfolio_style') == 'fullwidth') : ?>
	<div class="single-portfolio-content-block">
		<div class="single-portfolio-text">
			<?php echo apply_filters( 'the_content', get_field('veso_portfolio_text') ); ?>
		</div>
	</div></div>
	<?php else : ?>
	</div>
	<?php endif; ?>
</div>