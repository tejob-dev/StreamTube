<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="modal-gift" tabindex="-1" aria-labelledby="modal-donate-label" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header bg-light">
				<h5 class="modal-title" id="modal-donate-label">
					<?php echo $args['modal_title']; ?>
				</h5>
				<?php printf(
					'<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="%s"></button>',
					esc_attr__( 'Close','streamtube-core' )
				);?>
			</div>
			<div class="modal-body bg-white">
				<?php

					/**
					 *
					 * Fires before widget
					 *
					 * @param $args
					 * 
					 */
					do_action( 'streamtube/core/mycred/modal/gift/widget/before', $args );

					/**
					 *
					 *
					 * @param $args
					 * 
					 */
					do_action( 'streamtube/core/mycred/modal/gift/widget', $args );

					/**
					 *
					 * Fires after widget
					 *
					 * @param $args
					 * 
					 */
					do_action( 'streamtube/core/mycred/modal/gift/widget/after', $args );					
				?>
			</div>
		</div>
	</div>
</div>