<?php
/**
 * The General settings template file
 *
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Stream
 * @subpackage Wp_Cloudflare_Stream/admin/settings
 */

?>
<table class="form-table">
	
	<tbody>
		<tr>
			<th scope="row">
				<label for="enable">
					<?php esc_html_e( 'Enable', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label for="enable">
					<?php printf(
						'<input name="wp_cloudflare_stream[enable]" type="checkbox" id="enable" %s>',
						checked( $settings['enable'], 'on', false )
					);?>
					<?php esc_html_e( 'Enable Cloudflare Stream API', 'wp-cloudflare-stream' );?>
				</label>
			</td>
		</tr>		
		<tr>
			<th scope="row">
				<label for="account_id">
					<?php esc_html_e( 'Cloudflare Account ID', 'wp-cloudflare-stream' );?>
					<span style="color: red"><?php esc_html_e( '(required)', 'wp-cloudflare-stream' );?></span>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[account_id]" type="text" id="account_id" value="%s" class="regular-text">',
					esc_attr( $settings['account_id'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="api_token">
					<?php esc_html_e( 'Cloudflare Account API Token', 'wp-cloudflare-stream' );?>
					<span style="color: red"><?php esc_html_e( '(required)', 'wp-cloudflare-stream' );?></span>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[api_token]" type="text" id="api_token" value="%s" class="regular-text">',
					esc_attr( $settings['api_token'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="subdomain">
					<?php esc_html_e( 'Cloudflare Customer Subdomain', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>	
				<?php printf(
					'<input name="wp_cloudflare_stream[subdomain]" type="text" id="subdomain" value="%s" class="regular-text" placeholder="%s">',
					esc_attr( $settings['subdomain'] ),
					esc_attr__( 'e.g: customer-0a4pgsokheys4z14.cloudflarestream.com', 'wp-cloudflare-stream' )
				)?>
			</td>
		</tr>	

		<tr>
			<th scope="row">
				<label for="allowed_origins">
					<?php esc_html_e( 'Allowed Origins', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[allowed_origins]" type="text" id="allowed_origins" class="regular-text" value="%s">',
					is_array( $settings['allowed_origins'] ) ? join( ',', $settings['allowed_origins'] ) : $settings['allowed_origins']
				);?>

				<p class="description">
					<?php esc_html_e( 'e.g: *.domain.com, comma separated list of origins to restrict embedding.', 'wp-cloudflare-stream' );?>	
				</p>				
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="signed_url">
					<?php esc_html_e( 'Signed URLs', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label for="signed_url">
					<?php printf(
						'<input name="wp_cloudflare_stream[signed_url]" type="checkbox" id="signed_url" %s>',
						checked( $settings['signed_url'], 'on', false )
					);?>
					<?php esc_html_e( 'Secure videos with signed URLs', 'wp-cloudflare-stream' );?>
				</label>

				<p class="field-help" style="margin: 1rem 0">
					<?php printf(
						esc_html__( '%s may be necessary after enabling this option.', 'wp-cloudflare-stream' ),
						'<a href="'. esc_url( admin_url( 'options-general.php?page=wp-cloudflare-stream&tab=misc' ) ) .'">'. esc_html__( 'Bulk updating videos', 'wp-cloudflare-stream' ) .'</a>'
					);?>
				</p>

				<?php if( get_option( 'wp_cloudflare_stream_key' ) ): ?>
					<div>
						<button class="button button-secondary" type="button" id="cloudflare-revoke-tokens">
							<?php esc_html_e( 'Revoke Tokens', 'wp-cloudflare-stream' );?>
						</button>
					</div>
				<?php endif;?>
			</td>
		</tr>

	</tbody>
</table>