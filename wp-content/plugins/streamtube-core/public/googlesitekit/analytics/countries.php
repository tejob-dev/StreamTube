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
$chart_options = apply_filters( 'streamtube/core/analytics/reports/countries/chart_options', $chart_options );
?>
<?php printf(
	'<div class="analytics-section section-countries bg-white p-3 shadow-sm mb-4" id="section-report-countries" data-params="%s">',
	esc_attr( json_encode( $params ) )
);?>
	<div class="analytics-section__header border-bottom mb-4 px-4 py-2 d-flex">
		<h2 class="section-title">
			<?php 

			if( $start_date == 'all' ){
				esc_html_e( 'Top countries', 'streamtube-core' );
			}
			else{

				if( in_array( $start_date, array( 'today', 'yesterday' ) ) ){
					printf(
						esc_html__( 'Top countries over the %s', 'streamtube-core' ),
						$start_date
					);
				}

				elseif( array_key_exists( $start_date, $start_dates ) ){
					printf(
						esc_html__( 'Top countries over the %s', 'streamtube-core' ),
						$start_dates[ $start_date ]
					);
				}
				else{
					$diff = (int)date_diff( date_create( $start_date ), date_create( $end_date ))->format("%R%a");
					printf(
						esc_html__( 'Top countries over the last %s %s', 'streamtube-core' ),
						in_array( $diff, array( 0, 1 ) ) ? 1 : $diff,
						in_array( $diff, array( 0, 1 ) ) ? esc_html__( 'day', 'streamtube-core' ) : esc_html__( 'days', 'streamtube-core' )
					);					
				}
			}
			?>
		</h2>
	</div>	
	<?php printf(
		'<div class="analytics-section__content position-relative" id="analytics-countries" data-params="%s">',
		esc_attr( json_encode( $params ) )
	);?>

		<div class="row align-items-center">

			<div class="col-12 col-lg-7 col-xl-7 col-xxl-8">

				<?php printf(
					'<div id="analytics-countries-geo-chart" class="w-100" style="height: 500px;" data-chart-options="%s">',
					esc_attr( json_encode( $chart_options ) )
				);?>
					<?php include( dirname( dirname( __FILE__ ) ) . '/spinner.php' ); ?>
				</div>

			</div>

			<div class="col-12 col-lg-5 col-xl-5 col-xxl-4">

				<?php printf(
					'<div id="analytics-countries-pie-chart" class="w-100" style="height: 500px;" data-chart-options="%s">',
					esc_attr( json_encode( array_merge( $chart_options, array(
						'sliceVisibilityThreshold'	=>	1/20
					) ) ) )
				);?>
					<?php include( dirname( dirname( __FILE__ ) ) . '/spinner.php' ); ?>
				</div>		

			</div>

		</div>

	</div>
</div>