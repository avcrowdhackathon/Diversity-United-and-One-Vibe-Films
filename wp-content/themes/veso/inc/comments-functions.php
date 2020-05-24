<?php

class Veso_Walker_Comment extends Walker_Comment {

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				break;
			case 'ol':
				$output .= '<ol class="comment-list">' . "\n";
				break;
			case 'ul':
			default:
				$output .= '<ul class="comment-list">' . "\n";
				break;
		}
	}

	protected function comment( $comment, $depth, $args ) {
		if ( 'div' == $args['style'] ) {
			$tag = 'div ';
			$add_below = 'comment';
		} else {
			$tag = 'li ';
			$add_below = 'div-comment';
		}
?>
		<?php echo '<'.$tag; comment_class( $this->has_children ? 'parent' : '', $comment ); ?> id="comment-<?php comment_ID(); ?>" <?php echo '>'; ?>
		
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-author vcard">
		
			<div class="comment-author-avatar">
			<?php
			if ( 0 != $args['avatar_size'] ) {
				echo get_avatar( $comment, $args['avatar_size'] );
			}
			?>
			</div>
			<div class="comment-header comment-content">
				<div class="comment-author-link"><?php echo get_comment_author_link( $comment );?></div>
				<div class="comment-date comment-meta commentmetadata">
					<a class="veso-hover-text" href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
						<?php printf( __( '%1$s at %2$s' , 'veso'), get_comment_date( '', $comment ),  get_comment_time() ); ?>
					</a>
					<?php edit_comment_link( esc_html__( '(Edit)', 'veso')); ?>
					<?php if ( '0' == $comment->comment_approved ) : ?>
					<span class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'veso' ) ?></span>
					
					<?php endif; ?>
				</div>
				<article class="comment-body">
					<div id="comment-content">
						<?php comment_text( $comment, array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						<?php
						comment_reply_link( array_merge( $args, array(
							'add_below' => $add_below,
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply"><span class="link-hover comment-reply-link">',
							'after'     => '</span></div>'
						) ) );
						?>
					</div>
				</article>
			</div>
			
		
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
<?php
	}
}

function veso_comment_form_submit_button($button) {
	$button = '<button name="submit" type="submit" class="btn btn-md btn-dark btn-comment btn-solid" tabindex="5" id="submit"><span class="btn-text">'.esc_html__('Comment', 'veso').'</span></button>';
	return $button;
}
add_filter('comment_form_submit_button', 'veso_comment_form_submit_button');

function veso_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter( 'comment_form_fields', 'veso_move_comment_field_to_bottom' );

 
