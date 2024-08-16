<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="dropdown">
	<button class="btn p-1 shadow-none" type="button" id="postControlButtons" data-bs-toggle="dropdown" aria-expanded="false">
		<span class="btn__icon icon-ellipsis-vert"></span>
	</button>

	<ul class="dropdown-menu" aria-labelledby="postControlButtons">

		<?php if( get_post_status() != 'trash' ): ?>
			<?php printf(
				'<li><a class="dropdown-item" href="%s"><span class="btn__icon icon-edit"></span> %s</a></li>',
				esc_url( streamtube_core_get_edit_post_url( get_the_ID() ) ),
				esc_html__( 'Edit', 'streamtube-core' )
			);?>

			<?php printf(
				'<li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deletePostModal" data-post-id="%s"><span class="btn__icon icon-trash"></span> %s</a></li>',
				get_the_ID(),
				esc_html__( 'Trash', 'streamtube-core' )
			);?>

			<?php if( current_user_can( 'delete_others_posts' ) ): ?>

				<?php if( ! in_array( get_post_status(), array( 'reject' ) ) ) : ?>
					<?php printf(
						'<li><a class="dropdown-item text-warning" href="#" data-bs-toggle="modal" data-bs-target="#updatePostMessageModal" data-action="reject_post" data-post-id="%s"><span class="btn__icon icon-cancel-circled"></span> %s</a></li>',
						get_the_ID(),
						esc_html__( 'Reject', 'streamtube-core' )
					);?>
				<?php endif; ?>

				<?php if( ! in_array( get_post_status(), array( 'publish', 'private' ) ) ):?>
					<?php printf(
						'<li><a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#updatePostMessageModal" data-action="approve_post" data-post-id="%s"><span class="btn__icon icon-ok-circled"></span> %s</a></li>',
						get_the_ID(),
						esc_html__( 'Approve', 'streamtube-core' )
					);?>
				<?php endif; ?>			

			<?php endif; ?>

		<?php else:?>
			<?php printf(
				'<li><a class="dropdown-item ajax-elm text-info" href="#" data-action="restore_post" data-params="%s" data-method="POST"><span class="btn__icon icon-ccw"></span> %s</a></li>',
				esc_attr( json_encode( array( 'post_id' => get_the_ID() ) ) ),
				esc_html__( 'Restore', 'streamtube-core' )
			);?>			
		<?php endif; ?>

	</ul>
</div>