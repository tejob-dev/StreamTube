<?php

$bunnycdn 	= streamtube_core()->get()->bunnycdn;

$statuses   = $bunnycdn->bunnyAPI->get_webhook_video_statuses();
$status     = get_post_meta( $args['attachment_id'], '_bunnycdn_status', true );

if( get_post_meta( $args['attachment_id'], 'live_status', true ) ){
	return;
}

printf(
	'<div id="status-attachment-%1$s" class="status-attachment" data-attachment-id="%1$s" data-status="%2$s">',
	$args['attachment_id'],
	$status
);

	if( array_key_exists( $status, $statuses ) ){

	    printf(
	        '<p><span class="badge badge-%1$s bg-%1$s">%2$s</span></p>',
	        $statuses[ $status ][0],
	        ucfirst( $statuses[ $status ][0] )
	    );

	    if( in_array( $statuses[ $status ][0], array( 'uploading', 'failed' ) ) ){
	        printf(
	            '<span class="alert-warning">' . esc_html__( 'Not uploading or failed?', 'streamtube-core' ) . '</span>',
	        );

	        echo '<div>';

		        printf(
		            '<button style="margin-right: 1rem" class="button button-small button-bunnycdn-retry" data-attachment-id="%s">%s</button>',
		            esc_attr( $args['attachment_id'] ),
		            esc_html__( 'Retry', 'streamtube-core' )
		        );

	    	echo '</div>';
	    }

        if( $bunnycdn->settings['sync_type'] != 'fetching' ){
	        printf(
	            '<a class="thickbox button button-small" href="%s">%s</a>',
	            esc_url( add_query_arg( array(
	                'action'        =>  'read_file_log_content',
	                'attachment_id' =>  $args['attachment_id'],
	                'TB_iframe'     =>  true,
	                'width'         =>  700,
	                'height'        =>  400
	            ), admin_url( 'admin-ajax.php' ) ) ),
	            esc_html__( 'Log', 'streamtube-core' )
	        );
    	}	    
	}else{
	    printf(
	        '<button type="button" class="button button-small button-bunnycdn-sync button-primary" data-attachment-id="%s">%s</button>',
	        esc_attr( $args['attachment_id'] ),
	        esc_html__( 'Sync', 'streamtube-core' )
	    );
	}

echo '</div>';