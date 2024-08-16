<?php
/**
 * The Edit Collection modal template file
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
<div class="modal fade" id="modal-edit-collection" tabindex="-1" aria-labelledby="modal-edit-collection-label" aria-hidden="true">
	
	<?php printf(
		'<div class="modal-dialog modal-%s modal-dialog-centered">',
		is_user_logged_in() ? 'lg' : 'md'
	);?>

		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-edit-collection-label">
					<?php esc_html_e( 'Edit Collection', 'streamtube-core' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'form-create-collection.php', false, array(
					'collapse'	=>	false
				) );?>
			</div>
		</div>
	</div>
</div>