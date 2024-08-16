<?php
/**
 * The Collection Confirm Delete template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}
?>
<div class="modal fade" id="modal-delete-collection" tabindex="-1" aria-labelledby="modal-delete-collection-label" aria-hidden="true">
	
	<div class="modal-dialog modal-md modal-dialog-centered">

		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-delete-collection-label">
					<?php esc_html_e( 'Confirm Delete', 'streamtube-core' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form class="form-ajax">
				<div class="modal-body">

					<p>
						<?php esc_html_e( 'Are you sure you want to delete this collection?', 'streamtube-core' );?>
					</p>

					<p>
						<?php
						printf(
							esc_html__( 'After you press %s, this collection will be deleted permanently', 'streamtube-core' ),
							'<strong class="text-danger">'. esc_html__( 'DELETE', 'streamtube-core' ) .'</strong>'
						);
						?>
					</p>

				</div>

				<div class="modal-footer bg-light d-flex justify-content-center gap-3">
					<button type="button" class="btn btn-sm px-3 btn-secondary" data-bs-dismiss="modal">
						<?php esc_html_e( 'Cancel', 'streamtube-core' );?>
					</button>					
					<button type="submit" class="btn btn-sm px-3 btn-danger">
						<?php esc_html_e( 'Delete', 'streamtube-core' );?>
					</button>
				</div>
				<input type="hidden" name="action" value="delete_collection">
				<input type="hidden" name="redirect_url" value="<?php echo esc_attr( home_url('/') ); ?>">
				<input type="hidden" name="data" value="0">
			</form>
		</div>
	</div>
</div>