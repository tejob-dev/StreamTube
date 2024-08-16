<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="modal-edit-comment" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php esc_html_e( 'Edit comment', 'streamtube-core' ); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body position-relative">
				<div class="position-relative p-5 spinner-wrap">
					<div class="position-absolute top-50 start-50 translate-middle">
						<?php get_template_part( 'template-parts/spinner', '', array(
							'type'  =>  'secondary'
						) );?>
					</div>
				</div>
				<?php streamtube_core_load_template( 'form/edit-comment.php', false ); ?>
			</div>
		</div>
	</div>
</div>