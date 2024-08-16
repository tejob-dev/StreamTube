<?php
if( ! defined( 'ABSPATH' ) ){
	exit;
}
if( ! array_key_exists( 'post_type', $args ) ){
	$args['post_type'] = 'video';
}

if( is_array( $args['post_type'] ) && count( $args['post_type'] ) == 1 ){
	$args['post_type'] = $args['post_type'][0];
}
?>
<div class="liked-login login-wrap position-relative">
	<div class="top-50 start-50 translate-middle position-absolute text-center">

		<h5 class="text-muted h5 mb-4">
			<?php 

			if( is_string( $args['post_type'] ) ){
				printf(
					esc_html__( 'Sign in to access %s that you’ve liked.', 'wp-post-like' ),
					get_post_type_object( $args['post_type'] )->label
				);	
			}else{
				esc_html_e( 'Sign in to access posts that you’ve liked.', 'wp-post-like' );
			}
			?>
		</h5>

		<?php printf(
			'<a class="btn btn-primary btn-login text-white px-3" href="%s">',
			esc_url( wp_login_url( get_permalink() ) )
		);?>
		    <span class="menu-icon icon-user-circle me-0 me-sm-1"></span>
		    <span class="menu-text small menu-text small">
		    	<?php esc_html_e( 'Log In', 'wp-post-like' ); ?>
		    </span>
		</a>

	</div>
</div>