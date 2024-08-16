<?php
/**
 * The Watermark settings template file
 *
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Stream
 * @subpackage Wp_Cloudflare_Stream/admin/settings
 */

wp_enqueue_media();

if( isset( $_POST['tab'] ) && $_POST['tab'] == 'watermark' ){

	$response = wp_cloudflare_stream()->get()->post->add_watermark();

	if( is_wp_error( $response ) ){

		WP_Cloudflare_Stream_Settings::delete_setting( 'watermark' );
		
		load_template( plugin_dir_path( __FILE__ ) . 'alert.php', false, array(
			'type'		=>	'error',
			'message'	=>	$response->get_error_message()
		) );
	}else{
		WP_Cloudflare_Stream_Settings::update_setting( 'watermark', $response );

		load_template( plugin_dir_path( __FILE__ ) . 'alert.php', false, array(
			'type'		=>	'success',
			'message'	=>	esc_html__( 'Watermark has been added successfully.', 'wp-cloudflare-stream' )
		) );		
	}
	$settings 	= WP_Cloudflare_Stream_Settings::get_settings();
}
?>
<table class="form-table">
	
	<tbody>

		<tr>
			<th scope="row">
				<label for="watermark_enable">
					<?php esc_html_e( 'Enable', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<label>
					<?php printf(
						'<input name="wp_cloudflare_stream[watermark_enable]" type="checkbox" id="watermark_enable" %s class="regular-text">',
						checked( $settings['watermark_enable'], 'on', false )
					)?>
					<?php esc_html_e( 'Enable Watermark', 'wp-cloudflare-stream' );?>
				</label>
			</td>
		</tr>		
		
		<tr>
			<th scope="row">
				<label for="watermark_url">
					<?php esc_html_e( 'Image URL', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<div class="display: flex; gap: 1rem;">
					<?php printf(
						'<input name="wp_cloudflare_stream[watermark_url]" type="text" id="watermark_url" value="%s" class="regular-text">',
						esc_attr( $settings['watermark_url'] )
					)?>
					<button type="button" class="button button-secondary button-upload-image">
						<?php esc_html_e( 'Upload', 'wp-cloudflare-stream' );?>
					</button>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="watermark_name">
					<?php esc_html_e( 'Name', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[watermark_name]" type="text" id="watermark_name" value="%s" class="regular-text">',
					esc_attr( $settings['watermark_name'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="watermark_opacity">
					<?php esc_html_e( 'Opacity', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[watermark_opacity]" type="text" id="watermark_opacity" value="%s" class="regular-text">',
					esc_attr( $settings['watermark_opacity'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="watermark_padding">
					<?php esc_html_e( 'Padding', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[watermark_padding]" type="text" id="watermark_padding" value="%s" class="regular-text">',
					esc_attr( $settings['watermark_padding'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="watermark_scale">
					<?php esc_html_e( 'Scale', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<?php printf(
					'<input name="wp_cloudflare_stream[watermark_scale]" type="text" id="watermark_scale" value="%s" class="regular-text">',
					esc_attr( $settings['watermark_scale'] )
				)?>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="watermark_position">
					<?php esc_html_e( 'Upload Type', 'wp-cloudflare-stream' );?>
				</label>
			</th>
			<td>
				<select name="wp_cloudflare_stream[watermark_position]" class="regular-text">
					
					<?php foreach ( WP_Cloudflare_Stream_Settings::get_watermark_positions() as $key => $value ): ?>

						<?php printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $key ),
							selected( $settings['watermark_position'], $key, false ),
							esc_html( $value )
						)?>			

					<?php endforeach ?>

				</select>
			</td>
		</tr>

        <?php if( "" != $watermark_uid = $wp_cloudflare_stream->post->get_watermark_uid() ): ?>
            <tr>
                <th scope="row">
                    <label for="watermark-uid">
                        <?php esc_html_e( 'UID', 'wp-cloudflare-stream' );?>
                    </label>
                </th>
                <td>
                    <?php printf(
                        '<input type="text" id="watermark-uid" readonly value="%s" class="regular-text">',
                        esc_attr( $watermark_uid )
                    )?>
                </td>
            </tr>
        <?php endif;?>		

	</tbody>

</table>