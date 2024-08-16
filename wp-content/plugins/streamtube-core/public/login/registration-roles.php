<?php
/**
 *
 * Add role fields to default WP Registration form
 * 
 * @since 2.1.6
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

$roles = streamtube_core()->get()->user->get_registration_roles();

if( $roles ):

	$default_role = false;

	if( isset( $_REQUEST['user_role'] ) ){
		if( array_key_exists( $_POST['user_role'], $roles ) ){
			$default_role = sanitize_text_field( trim( $_REQUEST['user_role'] ) );
		}
	}

	?>
		<div class="registration-fields registration-roles choose-role my-3">
			<label for="user_role"><?php esc_html_e( 'Choose a role', 'streamtube-core' ); ?></label>

			<div class="d-flex">
				<?php foreach ( $roles as $role => $value ) {
					if( ! $default_role && $value['default'] ){
						$default_role = $role;
					}
					?>
					<div class="flex-fill">
						<div class="form-check">
							<?php printf(
								'<input %1$s class="form-check-input" type="radio" name="user_role" id="role-%2$s" value="%2$s">',
								$default_role == $role ? 'checked' : '',
								esc_attr( $role )
							);?>

							<?php printf(
								'<label class="form-check-label" for="role-%s">%s</label>',
								esc_attr( $role ),
								$value['label']
							);?>
						</div>
					</div>
					<?php
				}?>
			</div>
		</div>
     <?php 
endif;