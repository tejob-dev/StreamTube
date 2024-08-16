<?php
/**
 *
 * Theme update template
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

$is_verified = StreamTube_Theme_License()->is_verified();

?>
<div class="wrap">
	
	<div class="license-verification">

		<h1><?php esc_html_e( 'Verify License', 'streamtube' ); ?></h1>

		<?php
		do_action( 'license_verification' );
		?>

		<form method="post">

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="access_token">
								<?php esc_html_e( 'Access Token', 'streamtube' );?>
								<?php printf(
									'<span class="required">%s</span>',
									esc_html__( '(required)', 'streamtube' )
								);?>
							</label></th>
						<td>	
							<?php printf(
								'<input name="access_token" type="text" id="access_token" value="%s" class="regular-text">',
								esc_attr( StreamTube_Theme_License()->get_access_token() )
							);?>
							<p class="description">
								<?php printf(
									'<a target="_blank" href="https://build.envato.com/create-token/">%s</a>',
									esc_html__( 'Create your personal access token key', 'streamtube' )
								);?>
							</p>
						</td>
					</tr>

					<tr>
						<th scope="row">
							<label for="purchase_code">
								<?php esc_html_e( 'Purchase Code', 'streamtube' );?>
								<?php printf(
									'<span class="required">%s</span>',
									esc_html__( '(required)', 'streamtube' )
								);?>								
							</label></th>
						<td>	
							<?php printf(
								'<input name="purchase_code" type="text" id="purchase_code" value="%s" class="regular-text">',
								esc_attr( StreamTube_Theme_License()->get_purchase_code() )
							);?>
						</td>
					</tr>

				</tbody>
			</table>

			<?php if( $is_verified ): ?>
				<p style="color: red">
					<?php esc_html_e( 'IMPORTANT: do not share these two keys with anyone.', 'streamtube' );?>
				</p>
			<?php endif; ?>

			<p class="submit">
				<input type="hidden" name="page" value="theme-update">

				<?php wp_nonce_field( 'verify_form_check', 'verify_form_check' );?>

				<?php printf(
					'<button name="submit" type="submit" class="button button-primary button-large button-block" value="%s">%s</button>',
					'verify',
					$is_verified ? esc_attr__( 'Verified, check for update', 'streamtube' ) : esc_attr__( 'Verify Purchase', 'streamtube' )
				);?>

				<?php if( $is_verified ): ?>

					<?php printf(
						'<button name="submit" type="submit" class="button-deregister button button-secondary button-large button-block" value="%s">%s</button>',
						'deregister',
						esc_html__( 'Deregister', 'streamtube' )
					)?>

				<?php endif;?>

			</p>

			<p class="documentation">
				<?php printf(
					'<a target="_blank" href="https://streamtube.marstheme.com/documentation/#verify-purchase">%s</a>',
					esc_html__( 'How to verfy purchase?', 'streamtube' )
				)?>
			</p>
		</form>
	</div>
</div>