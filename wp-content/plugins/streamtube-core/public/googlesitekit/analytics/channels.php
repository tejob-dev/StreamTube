<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$chart_options = array(
	'legend'	=>	array(
		'textStyle'	=>	array(
			'color'	=>	'#aaa'
		)
	),
	'height'	=>	'400',
	'backgroundColor'	=>	'transparent'
);

/**
 *
 * Filter the chart options
 *
 * @param array $chart_options
 * 
 * @since 1.0.8
 */
$chart_options = apply_filters( 'streamtube/core/analytics/reports/channel/chart_options', $chart_options );

?>
<?php printf(
	'<div class="analytics-section section-channels bg-white p-3 shadow-sm mb-4" id="section-report-channels" data-params="%s">',
	esc_attr( json_encode( $params ) )
);?>
	<div class="analytics-section__header border-bottom mb-4 px-4 py-2 d-flex">
		<h2 class="section-title">
			<?php 

			if( $start_date == 'all' ){
				esc_html_e( 'Top acquisition channels', 'streamtube-core' );
			}
			else{

				if( in_array( $start_date, array( 'today', 'yesterday' ) ) ){
					printf(
						esc_html__( 'Top acquisition channels over the %s', 'streamtube-core' ),
						$start_date
					);
				}

				elseif( array_key_exists( $start_date, $start_dates ) ){
					printf(
						esc_html__( 'Top acquisition channels over the %s', 'streamtube-core' ),
						$start_dates[ $start_date ]
					);
				}
				else{
					$diff = (int)date_diff( date_create( $start_date ), date_create( $end_date ))->format("%R%a");
					printf(
						esc_html__( 'Top acquisition channels over the last  %s %s', 'streamtube-core' ),
						in_array( $diff, array( 0, 1 ) ) ? 1 : $diff,
						in_array( $diff, array( 0, 1 ) ) ? esc_html__( 'day', 'streamtube-core' ) : esc_html__( 'days', 'streamtube-core' )
					);					
				}
			}
			?>
		</h2>
	</div>		
	<?php printf(
		'<div class="analytics-section__content position-relative" id="analytics-channels" data-params="%s">',
		esc_attr( json_encode( $params ) )
	);?>
		<?php include( dirname( dirname( __FILE__ ) ) . '/spinner.php' ); ?>

		<div class="row align-items-center">

			<div class="col-12 col-lg-4 col-xl-5">
				<?php printf(
					'<div id="analytics-channel-charts" class="w-100" data-chart-options="%s"></div>',
					esc_attr( json_encode( $chart_options ) )
				);?>
			</div>

			<div class="col-12 col-lg-8 col-xl-7">
				<div id="analytics-channel-table"></div>
			</div>

		</div>

	</div>
</div>