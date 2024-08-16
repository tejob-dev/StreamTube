<?php
/**
 * The Collection button template file
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

global $post, $streamtube;

$is_logged_in = is_user_logged_in();

?>
<div class="modal fade" id="modal-collection" tabindex="-1" aria-labelledby="modal-collection-label" aria-hidden="true">
	
	<?php printf(
		'<div class="modal-dialog modal-%s modal-dialog-centered">',
		$is_logged_in ? 'lg' : 'md'
	);?>

		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-collection-label">
					<?php esc_html_e( 'Save to...', 'streamtube-core' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

				<?php if( $is_logged_in ): ?>

					<?php load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'form-search-collections.php' );?>

					<?php load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'collection-list.php' );?>

					<div id="create-collection-button" class="p-2">
						<a class="text-uppercase text-body text-decoration-none fw-bold" data-bs-toggle="collapse" href="#create-collection-form">
							<span class="icon-plus me-2"></span>
							<?php esc_html_e( 'Create new collection', 'streamtube-core' );?>
						</a>
					</div>

					<?php load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'form-create-collection.php', false, array(
						'collapse'	=>	true
					) );?>

				<?php else:?>
					<div class="need-login text-muted text-center p-4">
						<?php printf(
							esc_html__( 'Please %s to save this video.', 'streamtube-core' ),
							'<strong><a class="fw-bold text-secondary" href="'. esc_url( wp_login_url( get_permalink() ) ) .'">'. esc_html__( 'login', 'streamtube-core' ) .'</a></strong>'
						);?>
					</div>
				<?php endif;?>

			</div>
		</div>
	</div>
</div>