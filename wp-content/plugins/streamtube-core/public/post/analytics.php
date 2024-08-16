<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$args = array(
	'post_id'	=>	streamtube_core()->get()->post->get_edit_post_id()
);

add_filter( 'streamtube/core/analytics/start_dates', function( $dates ){
	return array_merge( $dates, array(
		'all'	=>	esc_html__( 'All', 'streamtube-core' )
	) );
}, 10, 1 );


$page_path = str_replace( streamtube_core_get_hostname( true ), '', get_permalink( $args['post_id'] ) );

streamtube_core_load_template( 'googlesitekit/reports.php', true, array(
	'page_title'	=>	false,
	'page_path'		=>	$page_path,
	'page_type'		=>	get_post_type()
) );