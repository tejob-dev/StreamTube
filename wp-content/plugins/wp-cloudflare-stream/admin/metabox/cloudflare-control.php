<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$code 				= '';
$status 			= '';

$cloudflare 		= wp_cloudflare_stream()->get()->post;
$uid 				= $cloudflare->get_stream_uid( $args['attachment_id'] );
$is_ready_stream 	= false;

if( $uid ){

	$is_ready_stream = $cloudflare->is_ready_to_stream( $args['attachment_id'] );

	if( is_wp_error( $is_ready_stream ) ){
		$status = $is_ready_stream->get_error_code();
	}else{
		$status = esc_html__( 'Ready', 'wp-cloudflare-stream' );
	}
}

printf(
	'<div id="status-attachment-%1$s" class="status-attachment" data-attachment-id="%1$s" data-status="%2$s">',
	$args['attachment_id'],
	sanitize_html_class( strtolower($status) )
);

	if( $uid ){

		printf(
			'<p><span class="badge bg-%1$s">%2$s</span></p>',
			sanitize_html_class( strtolower($status) ),
			ucwords( $status )
		);

        printf(
            '<a class="thickbox button button-small" href="%s">%s</a>',
            esc_url( add_query_arg( array(
                'action'        =>  'admin_get_cloudflare_error',
                'attachment_id' =>  $args['attachment_id'],
                'TB_iframe'     =>  true,
                'width'         =>  700,
                'height'        =>  400
            ), admin_url( 'admin-ajax.php' ) ) ),
            esc_html__( 'Log', 'streamtube-core' )
        );
	}else{
	    printf(
	        '<button type="button" class="button button-small button-cloudflare-sync button-primary" data-attachment-id="%s">%s</button>',
	        esc_attr( $args['attachment_id'] ),
	        esc_html__( 'Sync', 'streamtube-core' )
	    );		
	}

echo '</div>';