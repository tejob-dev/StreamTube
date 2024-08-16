<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

wp_enqueue_script( 'google-chart', '//www.gstatic.com/charts/loader.js' );
wp_enqueue_script( 'streamtube-reports' );

$googlesitekit = streamtube_core()->get()->googlesitekit;

$args = wp_parse_args( $args, array(
	'page_title'	=>	'<span class="icon-chart-area me-2"></span>' . esc_html__( 'Analytics', 'streamtube-core' ),
	'page_path'		=>	'',
	'start_date'	=>	isset( $_GET['start_date'] ) ? $_GET['start_date'] :  '7daysAgo',
	'end_date'		=>	isset( $_GET['end_date'] ) ? $_GET['end_date'] : 'yesterday'
) );

extract( $args );

$params = compact(
	'start_date', 'end_date', 'page_path'
);

$overview_metrics 			= $googlesitekit->analytics_rest_api->pre_get_overview_metrics();
$overview_video_metrics 	= $googlesitekit->analytics_rest_api->pre_get_overview_video_metrics();

?>

<?php printf(
	'<div class="googlesitekit-reports" id="googlesitekit-reports" data-linechart-options="%s" data-overview-metrics="%s" data-overview-video-metrics="%s">',
	esc_attr( json_encode( streamtube_core_get_linechart_options() ) ),
	esc_attr( json_encode( $overview_metrics ) ),
	esc_attr( json_encode( $overview_video_metrics ) )

);?>
	<div class="page-head mb-3 d-flex align-items-center">

		<?php if( $page_title ): ?>
			<?php
			printf(
				'<h1 class="page-title h3">%s</h1>',
				$page_title
			);
			?>
		<?php endif;?>

		<?php include_once( 'start-date.php' ); ?>
	</div>

	<?php

	/**
	 *
	 * Fires before reports
	 *
	 *@since 1.0.8 
	 * 
	 */
	do_action( 'streamtube/core/googlesitekit/reports/before', $params );
	?>

	<?php 

	if( $googlesitekit->analytics->is_connected() ){
		include_once( 'analytics/analytics.php' );	
	}

	?>

	<?php 
	if( $googlesitekit->search_console->is_connected() ){
		include_once( 'search-console/search-console.php' );
	}
	?>

	<?php
	/**
	 *
	 * Fires before reports
	 *
	 *@since 1.0.8 
	 * 
	 */
	do_action( 'streamtube/core/googlesitekit/reports/after', $params );
	?>	
</div>