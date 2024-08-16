<?php

$comment = $args;

$comment_classes = array( 'row-comment', 'bg-white' );
$comment_classes[] = 'comment-' . sanitize_html_class(  $comment->comment_ID );

if( $comment->comment_approved == 0 ){
	$comment_classes[] = 'table-warning';
}

?>
<tr class="<?php echo esc_attr( join(' ', $comment_classes ) ); ?>" id="row-comment-<?php echo $comment->comment_ID; ?>">

	<th scope="row" class="col-id">
		<?php printf(
			'<input class="form-check-input row-id-input mt-0" type="checkbox" name="entry_ids[]" value="%s">',
			esc_attr( $comment->comment_ID )
		);?>
	</th>

	<td class="col-author" data-title="<?php esc_attr_e( 'User', 'streamtube-core' ); ?>">
		<div class="comment-author d-flex align-items-center">
			<?php 
			if( $comment->user_id ):
				streamtube_core_get_user_avatar( array(
					'user_id'	=>	$comment->user_id,
					'name'		=>	true
				) );
			else:
				?><div class="user-avatar">
					<a href="<?php echo esc_url( $comment->comment_author_url ); ?>">
						<?php echo get_avatar( $comment->comment_author_email, 64 );?>
					</a>
				</div><?php
				printf(
					'<span class="ms-2 user-name text-body">%s</span>',
					esc_html( $comment->comment_author )
				);
			endif;
			?>
		</div>
	</td>

	<td class="col-comment" data-title="<?php esc_attr_e( 'Content', 'streamtube-core' ); ?>">

		<div class="d-flex flex-column">

			<div class="comment-content comment-text">
				<?php echo force_balance_tags( wpautop( wp_trim_words( $comment->comment_content, 20 ) ) ); ?>
			</div>

			<?php streamtube_core_load_template( 'comment/table/row-buttons.php', false, $comment );?>

		</div>

	</td>

	<?php if( ! streamtube_core_is_edit_post_screen() ):?>
		<td class="col-response-to" data-title="<?php esc_attr_e( 'Response To', 'streamtube-core' ); ?>">

			<div class="d-sm-flex gap-3">

			<a title="<?php echo esc_attr( get_the_title( $comment->comment_post_ID ) ); ?>" href="<?php the_permalink( $comment->comment_post_ID ) ?>">
	            <div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark mb-2 mb-sm-0">
	                <?php if( has_post_thumbnail( $comment->comment_post_ID ) ):?>
	                    <?php echo get_the_post_thumbnail( $comment->comment_post_ID, 'post-thumbnails', array(
	                        'class' =>  'img-fluid'
	                    ) );?>
	                <?php endif;?>
	            </div>
	    	</a>

			<?php printf(
				'<a class="post-title text-body fw-bold text-decoration-none" href="%s">%s</a>',
				esc_url( get_permalink( $comment->comment_post_ID ) ),
				get_the_title( $comment->comment_post_ID )
			);?>

			</div>
		</td>
	<?php endif;?>

	<td class="col-date" data-title="<?php esc_attr_e( 'Date', 'streamtube-core' ); ?>">
		<?php echo get_comment_date( '', $comment ); ?>
	</td>
</tr>
<?php