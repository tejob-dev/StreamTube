<?php
/**
 *
 * The General template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      2.1
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

?>
		
<table class="form-table">
		
	<tbody>

		<tr>
			<th scope="row"><label for="enable"><?php esc_html_e( 'Enable', 'streamtube-core' );?></label></th>
			<td>
				<label for="enable">
					<?php printf(
						'<input name="bunnycdn[enable]" type="checkbox" id="enable" %s>',
						checked( 'on', $settings['enable'], false )
					);?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="upload_type"><?php esc_html_e( 'Upload Type', 'streamtube-core' );?></label></th>
			<td>
				<label for="upload_type">
					<select name="bunnycdn[upload_type]" id="upload_type" class="regular-text">
							
						<?php foreach ( $bunnycdn->get_upload_types() as $key => $value): ?>
							
							<?php printf(
								'<option %s value="%s">%s</option>',
								selected( $settings['upload_type'], $key, false ),
								esc_attr( $key ),
								esc_html( $value )
							);?>

						<?php endforeach ?>

					</select>
				</label>
			</td>
		</tr>		

		<tr>
			<th scope="row"><label for="AccessKey"><?php esc_html_e( 'Access (API) Key', 'streamtube-core' );?></label></th>
			<td>
				<?php printf(
					'<input name="bunnycdn[AccessKey]" type="text" id="AccessKey" value="%s" class="regular-text">',
					esc_attr( $settings['AccessKey'] )

				);?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="libraryId"><?php esc_html_e( 'Library ID', 'streamtube-core' );?></label></th>
			<td>
				<?php printf(
					'<input name="bunnycdn[libraryId]" type="text" id="libraryId" value="%s" class="regular-text">',
					esc_attr( $settings['libraryId'] )

				);?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="cdn_hostname"><?php esc_html_e( 'CDN Hostname', 'streamtube-core' );?></label></th>
			<td>
				<?php printf(
					'<input name="bunnycdn[cdn_hostname]" type="text" id="cdn_hostname" value="%s" class="regular-text">',
					esc_attr( $settings['cdn_hostname'] )

				);?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="webhook_url"><?php esc_html_e( 'Webhook URL', 'streamtube-core' );?></label></th>
			<td>
				<?php printf(
					'<input onclick="javascript:this.select()" readonly name="webhook_url" type="text" id="webhook_url" value="%s" class="regular-text">',
					$settings['is_connected'] ? esc_attr( $bunnycdn->get_webhook_url() ) : ''

				);?>

				<?php if( $settings['is_connected'] ): ?>
					<p class="description">
						<?php printf(
							esc_html__( 'Click %s to set up Webhook URL', 'streamtube-core' ),
							sprintf(
								'<a target="_blank" href="%s">'. esc_html__( 'here', 'streamtube-core' ) .'</a>',
								esc_url( "https://panel.bunny.net/stream/library/manage/{$settings['libraryId']}#config-content-api" )
							)
						);?>
					</p>
				<?php endif;?>

				<?php if( ! $settings['is_connected'] ): ?>
					<p class="description" style="color: red">
						<?php esc_html_e( 'Webhook URL will appear after connecting to Bunny successfully.', 'streamtube-core' ); ?>
					</p>
				<?php endif;?>

			</td>
		</tr>

		<tr>
			<th scope="row"><label for="allow_formats"><?php esc_html_e( 'Allow Formats', 'streamtube-core' );?></label></th>
			<td>
				<?php printf(
					'<input name="bunnycdn[allow_formats]" type="text" id="allow_formats" value="%s" class="regular-text">',
					esc_attr( $settings['allow_formats'] )

				);?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="sync_type"><?php esc_html_e( 'Sync Type', 'streamtube-core' );?></label></th>
			<td>
				<label for="sync_type">

					<select name="bunnycdn[sync_type]" id="sync_type" class="regular-text">
							
						<?php foreach ( $bunnycdn->get_sync_types() as $key => $value): ?>
							
							<?php printf(
								'<option %s value="%s">%s</option>',
								selected( $settings['sync_type'], $key, false ),
								esc_attr( $key ),
								esc_html( $value )
							);?>

						<?php endforeach ?>

					</select>

				</label>

				<ol>

					<li>
						<?php printf(
							esc_html__( 'Selecting %s requires %s installed on your server.', 'streamtube-core' ),
							'<strong>'. esc_html__( 'Shell Curl', 'streamtube-core' ) .'</strong>',
							'<a target="_blank" href="http://manpages.ubuntu.com/manpages/trusty/man1/curl.1.html">'. esc_html__( 'CURL', 'streamtube-core' ) .'</a>'								
						);?>
					</li>

					<li>
						<?php printf(
							esc_html__( 'If you already installed CURL, set the CURL application path from %s', 'streamtube-core' ),
							'<a href="'. esc_url( admin_url( '/customize.php?autofocus[section]=system' ) ) .'">'. esc_html__( 'Theme Options > System', 'streamtube-core' ) .'</a>'
						)?>
					</li>

				</ol>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="tsp"><?php esc_html_e( 'Task Spooler', 'streamtube-core' );?></label></th>
			<td>
				<label for="tsp">
					<?php printf(
						'<input name="bunnycdn[tsp]" type="checkbox" id="tsp" %s>',
						checked( 'on', $settings['tsp'], false )
					);?>
					<?php printf(
						esc_html__( 'Queue Upload Job using %s tool', 'streamtube-core' ),
						'<strong>'. esc_html__( 'Task Spooler (TSP)', 'streamtube-core' ) .'</strong>'
					);?>
				</label>

				<ol>

					<li>
						<?php printf(
							esc_html__( 'Enabling %s option requires %s installed on your server.', 'streamtube-core' ),
							'<strong>'. esc_html__( 'Task Spooler', 'streamtube-core' ) .'</strong>',
							'<a target="_blank" href="https://manpages.ubuntu.com/manpages/xenial/man1/tsp.1.html">'. esc_html__( 'Task Spooler', 'streamtube-core' ) .'</a>'
						);?>
					</li>

					<li>
						<?php printf(
							esc_html__( 'If you already installed Task Spooler, set the Task Spooler application path from %s', 'streamtube-core' ),
							'<a href="'. esc_url( admin_url( '/customize.php?autofocus[section]=system' ) ) .'">'. esc_html__( 'Theme Options > System', 'streamtube-core' ) .'</a>'
						)?>
					</li>

				</ol>						
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="delete_original"><?php esc_html_e( 'Delete Original File', 'streamtube-core' );?></label></th>
			<td>
				<label for="delete_original">
					<?php printf(
						'<input name="bunnycdn[delete_original]" type="checkbox" id="delete_original" %s>',
						checked( 'on', $settings['delete_original'], false )
					);?>
					<?php esc_html_e( 'Delete the original file after syncing successfully', 'streamtube-core' );?>
				</label>
				<p class="description" style="color: red">
					<?php esc_html_e( 'Warning: Original files will be permanently deleted from the WordPress media library.', 'streamtube-core' );?>
				</p>				
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="auto_import_thumbnail"><?php esc_html_e( 'Thumbnail Image', 'streamtube-core' );?></label></th>
			<td>
				<label for="auto_import_thumbnail">
					<?php printf(
						'<input name="bunnycdn[auto_import_thumbnail]" type="checkbox" id="auto_import_thumbnail" %s>',
						checked( 'on', $settings['auto_import_thumbnail'], false )
					);?>
					<?php esc_html_e( 'Auto Import Thumbnail Image', 'streamtube-core' );?>
				</label>
			</td>
		</tr>		

		<tr>
			<th scope="row"><label for="animation_image"><?php esc_html_e( 'Animation (WebP) Image', 'streamtube-core' );?></label></th>
			<td>
				<label for="animation_image">
					<?php printf(
						'<input name="bunnycdn[animation_image]" type="checkbox" id="animation_image" %s>',
						checked( 'on', $settings['animation_image'], false )
					);?>
					<?php esc_html_e( 'Auto Import Animation (WebP) Image', 'streamtube-core' );?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="file_organize"><?php esc_html_e( 'File Organization', 'streamtube-core' );?></label></th>
			<td>
				<label for="file_organize">
					<?php printf(
						'<input name="bunnycdn[file_organize]" type="checkbox" id="file_organize" %s>',
						checked( 'on', $settings['file_organize'], false )
					);?>
					<?php esc_html_e( 'Organize my uploads into Author name folders', 'streamtube-core' );?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="auto_publish"><?php esc_html_e( 'Auto Publish', 'streamtube-core' );?></label></th>
			<td>
				<label for="auto_publish">
					<?php printf(
						'<input name="bunnycdn[auto_publish]" type="checkbox" id="auto_publish" %s>',
						checked( 'on', $settings['auto_publish'], false )
					);?>
					<?php esc_html_e( 'Auto publish video after syncing successfully', 'streamtube-core' );?>
				</label>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="bunny_player"><?php esc_html_e( 'Bunny Player', 'streamtube-core' );?></label></th>
			<td>
				<label for="bunny_player">
					<?php printf(
						'<input name="bunnycdn[bunny_player]" type="checkbox" id="bunny_player" %s>',
						checked( 'on', $settings['bunny_player'], false )
					);?>
					<?php esc_html_e( 'Load Bunny Player instead of the built-in Videojs player', 'streamtube-core' );?>
				</label>
			</td>
		</tr>

	</tbody>

</table>