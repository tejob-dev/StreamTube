<?php

global $post;

$importer = streamtube_core()->get()->yt_importer;

$settings = $importer->admin->get_settings( $post->ID );
?>
<table class="form-table">
	
	<tbody>

		<tr>
			<th scope="row">
				<label for="post_type">
					<?php esc_html_e( 'Post Type', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[post_type]" id="post_type" class="regular-text">

					<?php foreach ( $importer->options->get_post_types() as $key => $value ) {
						if( is_post_type_viewable( $key ) ){
							printf(
								'<option %s value="%s">%s</option>',
								selected( $key, $settings['post_type'], false ),
								esc_attr( $key ),
								esc_html( $value )
							);
						}
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="post_meta_field">
					<?php esc_html_e( 'Post Meta Field', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input type="text" name="yt_importer[post_meta_field]" id="post_meta_field" class="regular-text" value="%s">',
					esc_attr( sanitize_key( $settings['post_meta_field'] ) )
				);?>

				<p>
					<?php
					esc_html_e( 'Videos will be imported into the post content if no post meta field is specified', 'streamtube-core' )
					?>
				</p>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="post_status">
					<?php esc_html_e( 'Post Status', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<select name="yt_importer[post_status]" id="post_status" class="regular-text">

					<?php foreach ( $importer->options->get_post_statuses() as $key => $value ) {
						printf(
							'<option %s value="%s">%s</option>',
							selected( $key, $settings['post_status'], false ),
							esc_attr( $key ),
							esc_html( $value )
						);
					}?>

				</select>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="post_author">
					<?php esc_html_e( 'Post Author', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php wp_dropdown_users( array(
					'role__not_in'	=>	array(
						'subscriber'
					),
					'class'			=>	'regular-text',
					'name'			=>	'yt_importer[post_author]',
					'selected'		=>	$settings['post_author']
				) );?>
			</td>			
		</tr>

		<tr>
			<th scope="row">
				<label for="post_tags">
					<?php esc_html_e( 'Import Tags', 'streamtube-core' );?>
				</label>
			</th>

			<td>
				<?php printf(
					'<input type="checkbox" name="yt_importer[post_tags]" %s>',
					checked( $settings['post_tags'], 'on', false )
				);?>
			</td>			
		</tr>		

	</tbody>

</table>