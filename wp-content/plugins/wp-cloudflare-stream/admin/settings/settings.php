<?php
/**
 * The settings template file
 *
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Stream
 * @subpackage Wp_Cloudflare_Stream/admin/settings
 */

$wp_cloudflare_stream 	= wp_cloudflare_stream()->get();

$tabs 					= WP_Cloudflare_Stream_Settings::get_settings_tabs();

$current 				= WP_Cloudflare_Stream_Settings::get_current_tab();

$is_updated 			= false;
	
if( in_array( $current , array( 'webhook', 'watermark' )) ){
	$submit_button = sprintf(
		'%s %s',
		esc_html__( 'Install', 'wp-cloudflare-stream' ),
		$tabs[$current]
	);
}
else{
	$submit_button 	= esc_html__( 'Save Changes', 'wp-cloudflare-stream' );

	$is_updated 	= true;
}
?>

<div class="wrap">

	<h1><?php esc_html_e( 'WP Cloudflare Stream', 'wp-cloudflare-stream' );?></h1>

	<?php
	if( isset( $_POST['submit'] ) && current_user_can( 'administrator' ) ){

		$data = wp_unslash( $_POST['wp_cloudflare_stream'] );

		WP_Cloudflare_Stream_Settings::update_settings( $data );

		if( $is_updated ){
	        load_template( plugin_dir_path( __FILE__ ) . 'alert.php', false, array(
	            'type'      =>  'success',
	            'message'   =>  esc_html__( 'Settings saved.', 'wp-cloudflare-stream' )
	        ) );

	        if( array_key_exists( 'signed_url', $data ) && ! get_option( 'wp_cloudflare_stream_key' ) ){

	        	$cloudflare_api = new WP_Cloudflare_Stream_API(
	        		array(
			            'account_id'    => $data['account_id'],
			            'api_token'     => $data['api_token'],
			            'subdomain'     => $data['subdomain']
	        		)
	        	);

	        	$streamkey = $wp_cloudflare_stream->post->generate_stream_key();

	        	if( is_wp_error( $streamkey ) ){
			        load_template( plugin_dir_path( __FILE__ ) . 'alert.php', false, array(
			            'type'      =>  'error',
			            'message'   =>  $streamkey->get_error_message()
			        ) );	        		
	        	}
	        }
    	}
	}

	$settings 	= WP_Cloudflare_Stream_Settings::get_settings();
	?>

	<nav class="nav-tab-wrapper wp-clearfix">

		<?php foreach ( $tabs as $tab => $text ): ?>

			<?php printf(
				'<a href="%s" class="nav-tab nav-tab-%s">%s</a>',
				add_query_arg( array( 'tab' => $tab ) ),
				$current == $tab ? 'active' : 'inactive',
				$text
			);?>
			
		<?php endforeach ?>

	</nav>

	<form method="post">

		<div class="widget-tab-content">

			<?php foreach ( $tabs as $tab => $text ): ?>

				<?php printf(
					'<div class="tab-pane tab-content tab-content-%s %s">',
					esc_attr( $tab ),
					$current == $tab ? 'active' : 'inactive',
				);?>

					<?php include( plugin_dir_path( __FILE__ ) . sanitize_file_name( $tab ) . '.php' ); ?>

				</div>
				
			<?php endforeach ?>			

		</div>

		<div class="submit">

			<?php printf(
				'<input type="hidden" name="page" value="%s">',
				esc_attr( $_GET['page'])
			);?>

			<?php printf(
				'<input type="hidden" name="tab" value="%s">',
				esc_attr( $current )
			);?>

			<?php printf(
				'<input type="hidden" name="wp_cloudflare_stream[webhook_key]" value="%s">',
				esc_attr( $settings['webhook_key'] )
			);?>
			
			<?php printf(
				'<input type="submit" name="submit" id="submit" class="button button-primary" value="%s">',
				$submit_button
			);?>

		</div>
		
	</form>
</div>	