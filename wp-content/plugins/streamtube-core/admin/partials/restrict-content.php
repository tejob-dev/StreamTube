<?php

if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$restrict_content = streamtube_core()->get()->restrict_content;

$post_data = $restrict_content->get_post_data();
?>
<div class="restrict-content-wrap">
	
	<table class="form-table">
		
		<tbody>
			<tr>
				<th scope="row">
					<label>
						<?php esc_html_e( 'Display For', 'streamtube-core' ); ?>
					</label>
				</th>
				<td>

					<div class="field-section" id="apply_for">
						<select class="regular-text" name="data[apply_for]" id="restrict_content_for">
							<?php foreach ( $restrict_content->apply_for_options() as $key => $value ): ?>
								
								<?php printf(
									'<option %s value="%s">%s</option>',
									selected( $key, $post_data->apply_for, true ),
									esc_attr( $key ),
									esc_html( $value )
								);?>

							<?php endforeach ?>?>
						</select>
					</div>

					<?php printf(
						'<div class="field-section section-apply-for %s" id="section-roles">',
						$post_data->apply_for != 'roles' ? 'hide-me' : ''
					);?>

						<label class="label-title"><?php esc_html_e( 'Roles', 'streamtube-core' );?></label>

						<?php foreach ( $restrict_content->get_editable_roles() as $role => $value ) {
							?>
							
								<p class="role role-<?php echo esc_attr( $role ); ?>">
									<label>
										<?php
										printf(
											'<input type="checkbox" name="data[roles][]" id="role_%1$s" class="regular-text" value="%1$s" %2$s> %3$s',
											$role,
											in_array( $role, $post_data->roles ) ? 'checked' : '',
											$value['name']
										);
										?>	
									</label>
								</p>
							
							<?php
						}?>
					</div>

					<?php printf(
						'<div class="field-section section-apply-for %s" id="section-capabilities">',
						$post_data->apply_for != 'capabilities' ? 'hide-me' : ''
					);?>
						<label class="label-title" for="restrict_content_capabilities"><?php esc_html_e( 'Capabilities', 'streamtube-core' );?></label>
						<p>
							<?php printf(
								'<input id="restrict_content_capabilities" class="regular-text" type="text" name="data[capabilities]" value="%s">',
								join( ',', $post_data->capabilities )
						); ?>
						</p>
						<p class="description">
							<?php printf(
								esc_html__( '%s, separated by commas.', 'streamtube-core' ),
								'<a target="_blank" href="https://wordpress.org/support/article/roles-and-capabilities/#capabilities">'.esc_html__( 'Capabilities', 'streamtube-core' ).'</a>'
							); ?>	
						</p>
					</div>

					<div class="field-section">
						<p>
							<?php printf(
								esc_html__( 'Administrator, Editor and %1$s owner can always view %1$s content without any restriction.', 'streamtube-core' ),
								$post->post_type
							);?>
						</p>
					</div>
				</td>
			</tr>
		</tbody>

	</table>

	<?php wp_nonce_field( $restrict_content::NONCE, $restrict_content::NONCE );?>
</div>