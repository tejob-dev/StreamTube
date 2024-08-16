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
			<th scope="row"><label for="author_notify_publish"><?php esc_html_e( 'Public Notification', 'streamtube-core' );?></label></th>
			<td>
				<label for="author_notify_publish">
					<?php printf(
						'<input name="bunnycdn[author_notify_publish]" type="checkbox" id="author_notify_publish" %s>',
						checked( 'on', $settings['author_notify_publish'], false )
					);?>
					<?php esc_html_e( 'Send a notification to author after publishing video', 'streamtube-core' );?>
				</label>

				<div class="mt-2">
					<label for="author_notify_publish_subject">
						<?php esc_html_e( 'Subject', 'streamtube-core' );?>
					</label>
					<p>
						<?php printf(
							'<input class="regular-text" name="bunnycdn[author_notify_publish_subject]" type="text" id="author_notify_publish_subject" value="%s">',
							esc_attr( $settings['author_notify_publish_subject'] )
						);?>
					</p>
				</div>

				<div class="mt-2">
					<?php wp_editor( $settings['author_notify_publish_content'], 'author_notify_publish_content', array(
						'textarea_name'			=>	'bunnycdn[author_notify_publish_content]',
						'media_buttons'			=>	true,
						'textarea_rows'			=>	10,
						'teeny'					=>	true
					) );?>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="author_notify_fail"><?php esc_html_e( 'Fail Notification', 'streamtube-core' );?></label></th>
			<td>
				<label for="author_notify_fail">
					<?php printf(
						'<input name="bunnycdn[author_notify_fail]" type="checkbox" id="author_notify_fail" %s>',
						checked( 'on', $settings['author_notify_fail'], false )
					);?>
					<?php esc_html_e( 'Send a notification to author after video encoding failed', 'streamtube-core' );?>
				</label>

				<div class="mt-2">
					<label for="author_notify_fail_subject">
						<?php esc_html_e( 'Subject', 'streamtube-core' );?>
					</label>
					<p>
						<?php printf(
							'<input class="regular-text" name="bunnycdn[author_notify_fail_subject]" type="text" id="author_notify_fail_subject" value="%s">',
							esc_attr( $settings['author_notify_fail_subject'] )
						);?>
					</p>
				</div>				

				<div class="mt-2">
					<?php wp_editor( $settings['author_notify_fail_content'], 'author_notify_fail_content', array(
						'textarea_name'			=>	'bunnycdn[author_notify_fail_content]',
						'media_buttons'			=>	true,
						'textarea_rows'			=>	10,
						'teeny'					=>	true
					) );?>
				</div>				
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="shortcodes"><?php esc_html_e( 'Shortcodes', 'streamtube-core' );?></label></th>
			<td>
				<ol>
					<li><?php printf(
						'%s: %s',
						'{user_display_name}',
						esc_html__( 'Author display name', 'streamtube-core' )
					)?></li>

					<li><?php printf(
						'%s: %s',
						'{website_name}',
						get_bloginfo( 'name' )
					)?></li>

					<li><?php printf(
						'%s: %s',
						'{website_url}',
						untrailingslashit( home_url() )
					)?></li>

					<li><?php printf(
						'%s: %s',
						'{post_name}',
						esc_html__( 'Video title', 'streamtube-core' )
					)?></li>

					<li><?php printf(
						'%s: %s',
						'{post_url}',
						esc_html__( 'Video URL', 'streamtube-core' )
					)?></li>					
				</ol>
			</td>
		</tr>
	</tbody>

</table>