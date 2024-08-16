<?php
/**
 * The Search Videos modal template file
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

$is_logged_in = is_user_logged_in();

?>
<div class="modal fade" id="modal-search-videos" tabindex="-1" aria-labelledby="modal-search-videos-label" aria-hidden="true">
	
	<?php printf(
		'<div class="modal-dialog modal-%s modal-dialog-centered">',
		$is_logged_in ? 'lg' : 'md'
	);?>

		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-search-videos-label">
					<?php esc_html_e( 'Search videos', 'streamtube-core' ); ?>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

				<form class="form-ajax">
					
					<div class="input-group mb-3">
						<?php printf(
							'<input name="search" type="text" class="form-control rounded-0" placeholder="%s">',
							esc_attr__( 'Search...', 'streamtube-core' )
						);?>
						<button type="submit" class="btn p-2 btn-secondary rounded-0 btn-hide-icon-active">
							<span class="btn__icon icon-search"></span>
						</button>
					</div>

					<div id="video-list" class="search_videos-list bg-light p-3 border">

						<div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1 row-cols-xl-1 row-cols-xxl-1">

						</div>
							
					</div>

					<input type="hidden" name="action" value="search_videos">
					<input type="hidden" name="term_id" value="0">

				</form>

			</div>
		</div>
	</div>
</div>