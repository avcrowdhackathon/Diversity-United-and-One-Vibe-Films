<?php
if ( ! function_exists( 'veso_posted_on' ) ) :
	function veso_posted_on() {
		$byline = sprintf( wp_kses(
			__( '%s', 'veso' ), array('span'=>array('class'=>array()), 'a'=>array('href'=>array(), 'class'=>array()))),
			'<li class="byline post-cat-author">'.__( 'By', 'veso' ).' <a class="url fn n veso-hover-text" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></li>'

		);
		return '' . $byline . '<li class="posted-on post-cat-date">' . veso_time_link() . '</li>';
	}
endif;
if ( ! function_exists( 'veso_time_link' ) ) :
	function veso_time_link() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		$time_string = sprintf( $time_string,
			get_the_date( DATE_W3C ),
			get_the_date()
		);
		return '<a class="accent-color veso-hover-text" href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
	}
endif;
if ( ! function_exists( 'veso_categories_tags_list' ) ) :
	function veso_categories_tags_list() {
		$categories_list = '';
		$postCats = get_the_category(); 
		if (is_array($postCats)) {
			foreach ($postCats as $c => $category) {
				$categories_list .= '<li><a class="veso-hover-text" href="' . esc_url(get_category_link($category->term_id)) . '" title="' . esc_attr($category->name) . '">' . esc_html($category->name) . '</a><span class="el-icon-spacing"></span></li>';
			}
		} 
		$tags_list = get_the_tag_list('<li>', '<span class="el-icon-spacing"></span></li><li>', '<span class="el-icon-spacing"></span></li>');
		if ( 'post' === get_post_type() ) {
			if ( ( $categories_list && veso_categorized_blog() ) || $tags_list ) {
				echo '<div class="post-cat-tags"><div class="cat-tags-links">';
				if ( $categories_list && veso_categorized_blog() ) {
					echo '<ul class="post-tags post-cat"><li>'.esc_html__('Categories:', 'veso').'</li>' . $categories_list . '</ul>';
				}
				if ( $tags_list ) {
					echo '<ul class="post-tags"><li>'.esc_html__('Tags:', 'veso').'</li>' . $tags_list . '</ul>';
				}
				echo '</div></div>';
			}
		}
	}
endif;
function veso_categorized_blog() {
	$category_count = get_transient( 'veso_categories' );
	if ( false === $category_count ) {
		$categories = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			'number'     => 2,
		) );
		$category_count = count( $categories );
		set_transient( 'veso_categories', $category_count );
	}
	return $category_count >= 1;
}
function veso_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	delete_transient( 'veso_categories' );
}
add_action( 'edit_category', 'veso_category_transient_flusher' );
add_action( 'save_post',     'veso_category_transient_flusher' );
if ( ! function_exists( 'veso_entry_footer' ) ) :
	function veso_entry_footer() {
		$separate_meta = esc_html__( ', ', 'veso' );
		$categories_list = get_the_category_list( $separate_meta );
		$tags_list = get_the_tag_list( '', $separate_meta );
		if ( ( ( veso_categorized_blog() && $categories_list ) || $tags_list ) || get_edit_post_link() ) {
			echo '<footer class="entry-footer">';
				if ( 'post' === get_post_type() ) {
					if ( ( $categories_list && veso_categorized_blog() ) || $tags_list ) {
						echo '<span class="cat-tags-links">';
							if ( $categories_list && veso_categorized_blog() ) {
								echo '<span class="cat-links"><span class="screen-reader-text">' . esc_html__( 'Categories', 'veso' ) . '</span>' . $categories_list . '</span>';
							}
							if ( $tags_list ) {
								echo '<span class="tags-links"><span class="screen-reader-text">' . esc_html__( 'Tags', 'veso' ) . '</span>' . $tags_list . '</span>';
							}
						echo '</span>';
					}
				}
				veso_edit_link();
			echo '</footer>';
		}
	}
endif;
if ( ! function_exists( 'veso_edit_link' ) ) :
	function veso_edit_link() {
		$link = edit_post_link(
				esc_html__( 'Edit', 'veso' ),
			' <span class="edit-link accent-color">',
			'</span>'
		);
		return $link;
	}
endif;
function veso_front_page_section( $partial = null, $id = 0 ) {
	if ( is_a( $partial, 'WP_Customize_Partial' ) ) {
		global $vesocounter;
		$id = str_replace( 'panel_', '', $partial->id );
		$vesocounter = $id;
	}
	global $post;
	if ( get_theme_mod( 'panel_' . $id ) ) {
		global $post;
		$post = get_post( get_theme_mod( 'panel_' . $id ) );
		setup_postdata( $post );
		set_query_var( 'panel', $id );
		get_template_part( 'template-parts/page/content', 'front-page-panels' );
		wp_reset_postdata();
	} elseif ( is_customize_preview() ) {
		echo '<article class="panel-placeholder panel veso-panel veso-panel' . esc_attr($id) . '" id="panel' . esc_attr($id) . '"><span class="veso-panel-title">' . sprintf( esc_html__( 'Front Page Section %1$s Placeholder', 'veso' ), $id ) . '</span></article>';
	}
}

function veso_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<div class="form-container"><div class="form-style veso-form text-right"><form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post" class="post-password-form"><p class="form-password-txt text-left">
    ' . esc_html__( "To view this protected post, enter the password below:", 'veso' ) . '</p><div class="form-fieldset text-left"><div class="veso-input"><label for="' . $label . '">' . esc_html__( "Password", 'veso' ) . '</label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" /></div></div><button type="submit" class="btn btn-md btn-solid btn-dark"><span class="btn-text">' . esc_html__("Enter", 'veso') . '</span></button></form></div></div>
    ';
    return $o;
}
add_filter( 'the_password_form', 'veso_password_form' );