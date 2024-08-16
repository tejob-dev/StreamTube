<?php
/**
 *
 * Add First and Last name fields to default WP Registration form
 * 
 * @since 2.1.6
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

$data = wp_parse_args( $_REQUEST, array(
	'first_name'	=>	'',
	'last_name'		=>	''
) );
?>
<div class="registration-fields registration-display-name">
	<div class="d-flex gap-3">

		<?php
		/**
		 *
		 * Fires before first name field
		 *
		 * @param array $data
		 *
		 * @since 2.1.6
		 * 
		 */
		do_action( 'streamtube/core/form/registration/first_name/before', $data );
		?>    		
		<p>
			<label for="first_name"><?php esc_html_e( 'First Name', 'streamtube-core' ); ?></label>
			<?php printf(
				'<input type="text" name="first_name" id="first_name" class="input" value="%s" />',
				esc_attr( $data['first_name'] )
			)?>
		</p>

		<?php
		/**
		 *
		 * Fires after first name field
		 *
		 * @param array $data
		 *
		 * @since 2.1.6
		 * 
		 */
		do_action( 'streamtube/core/form/registration/first_name/after', $data );
		?>

		<p>
			<label for="last_name"><?php esc_html_e( 'Last Name', 'streamtube-core' ); ?></label>
			<?php printf(
				'<input type="text" name="last_name" id="last_name" class="input" value="%s" />',
				esc_attr( $data['last_name'] )
			)?>
		</p>

		<?php
		/**
		 *
		 * Fires after last_name field
		 *
		 * @param array $data
		 *
		 * @since 2.1.6
		 * 
		 */
		do_action( 'streamtube/core/form/registration/last_name/after', $data );
		?>			
	</div>
</div>

<style type="text/css">
body.login #login form .form-check input {
    margin-top: 4px;
}	
</style>
<?php