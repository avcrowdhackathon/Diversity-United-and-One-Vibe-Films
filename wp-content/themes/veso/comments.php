<?php
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php
	if ( have_comments() ) : ?>
		<h5 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				printf( esc_html_x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'veso' ), get_the_title() );
			} else {
				printf(
					_nx(
						'%1$s Reply to &ldquo;%2$s&rdquo;',
						'%1$s Replies to &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'veso'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
		</h5>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'walker'      => new Veso_Walker_Comment(),
					'avatar_size' => 60,
					'style'       => 'ul',
					'short_ping'  => true,
					'type'        => 'comment',
				) );
			?>
		</ol>
		<ul class="veso-pings">
			<?php
				wp_list_comments( array(
					'walker'      => new Veso_Walker_Comment(),
					'avatar_size' => 60,
					'style'       => 'ul',
					'short_ping'  => true,
					'type'        => 'pings',
				) );
			?>
		</ul>

		<?php if(isset($commentNavigation)) {
			the_comments_navigation();
		} else {
			the_comments_pagination( array(
				'prev_text' => '<div class="previous-page arrow-prev"><i class="fa fa-angle-left"></i></div>',
				'next_text' => '<div class="next-page arrow-next"><i class="fa fa-angle-right"></i></div>',
			) );
		}

	endif;
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'veso' ); ?></p>
	<?php
	endif;

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(

	'author' =>'<div class="comment-details form-fieldset">
		<div class="comment-text name comment-input veso-input input-required">
			<label for="comment_author">'.esc_html__('Name', 'veso').'</label>
			<input type="text" name="author" id="comment_author" value="'.esc_attr( $commenter['comment_author'] ).'">
		</div> ',

	'email' =>'<div class="comment-text email comment-input veso-input input-required">
		<label for="comment_email">'.esc_html__('Email', 'veso').'</label>
			<input type="text" name="email" id="comment_email" value="'.esc_attr(  $commenter['comment_author_email'] ).'">
		</div>',

	'url' =>'<div class="comment-text url comment-input veso-input">
  		<label for="comment_url">'.esc_html__('Website', 'veso').'</label>
			<input type="text" name="url" id="comment_url" value="'.esc_attr( $commenter['comment_author_url'] ).'">
		</div>  
	</div>',
);

	$args = array(
		'comment_notes_before' => '',
		'cancel_reply_before' => '',
		'cancel_reply_after' => '',
		'title_reply_before' => '<h5 id="reply-title" class="comment-reply-title">',
		'title_reply_after' => '</h5>',
		'class_form' => 'veso-form form-style comment-form darken-bg-color',
		'class_submit'  => 'btn btn-md btn-comment btn-dark btn-solid',
		'comment_field' => '<div class="message form-fieldset">
							<div class="comment-textarea message comment-input veso-input">
								<label for="comment_message">'.esc_html__('Comment', 'veso').'</label>
								<textarea rows="7" name="comment" id="comment_message"></textarea>
							</div>
						</div>',
		'fields' => apply_filters( 'comment_form_default_fields', $fields ),
	);
	?>
	
	<?php comment_form($args); ?>

</div>