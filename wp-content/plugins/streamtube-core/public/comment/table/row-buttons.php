<?php

global $streamtube;

$comment = $args;

$params = json_encode( array(
	'comment_id'		=>	$comment->comment_ID
) );
?>
<div class="row-buttons invisible d-lg-flex gap-2 mt-auto">

	<?php printf(
		'<button type="button" class="btn-hide-icon-active btn btn-sm shadow-none outline-none fw-bold btn-edit-comment text-danger p-1" data-params="%s" data-bs-toggle="modal" data-bs-target="#modal-edit-comment">%s</button>',
		esc_attr( $params ),
		esc_html__( 'Edit', 'streamtube-core' )
	);?>

	<?php printf(
		'<button type="button" class="btn-hide-icon-active btn btn-sm shadow-none outline-none fw-bold p-1 ajax-elm %s" data-action="moderate_comment" data-params="%s" data-method="POST">%s</button>',
		$comment->comment_approved == 0 ? 'text-success' : 'text-warning',
		esc_attr( $params ),
		$comment->comment_approved == 0 ? esc_html__( 'Approve', 'streamtube-core' ) : esc_html__( 'Unapprove', 'streamtube-core' )
	);?>

	<?php printf(
		'<button type="button" class="btn-hide-icon-active btn btn-sm shadow-none outline-none fw-bold p-1 ajax-elm text-danger" data-action="trash_comment" data-params="%s" data-method="POST">%s</button>',
		esc_attr( $params ),
		esc_html__( 'Delete', 'streamtube-core' )
	);?>

	<?php if( $streamtube->get()->comment->comment_reported( $comment ) ): ?>
	<?php printf(
		'<button type="button" class="btn-hide-icon-active btn btn-sm shadow-none outline-none fw-bold p-1 ajax-elm text-secondary" data-action="remove_comment_report" data-params="%s" data-method="POST">%s</button>',
		esc_attr( $params ),
		esc_html__( 'Remove Report', 'streamtube-core' )
	);?>
	<?php endif;?>
</div>