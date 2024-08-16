<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<?php printf(
	'<div class="analytics-section section-topcontent bg-white p-3 shadow-sm mb-4" id="section-report-topcontent" data-params="%s">',
	esc_attr( json_encode( $params ) )
);?>
	<div class="analytics-section__header border-bottom mb-4 px-4 py-2 d-flex">
		<h2 class="section-title">
			<?php 

			if( $start_date == 'all' ){
				esc_html_e( 'Top content', 'streamtube-core' );
			}
			else{
				if( in_array( $start_date, array( 'today', 'yesterday' ) ) ){
					printf(
						esc_html__( 'Top content over the %s', 'streamtube-core' ),
						$start_date
					);
				}

				elseif( array_key_exists( $start_date, $start_dates ) ){
					printf(
						esc_html__( 'Top content over the %s', 'streamtube-core' ),
						$start_dates[ $start_date ]
					);
				}
				else{
					$diff = (int)date_diff( date_create( $start_date ), date_create( $end_date ))->format("%R%a");
					printf(
						esc_html__( 'Top content over the last %s %s', 'streamtube-core' ),
						in_array( $diff, array( 0, 1 ) ) ? 1 : $diff,
						in_array( $diff, array( 0, 1 ) ) ? esc_html__( 'day', 'streamtube-core' ) : esc_html__( 'days', 'streamtube-core' )
					);					
				}
			}
			?>
		</h2>
	</div>		
	<?php printf(
		'<div class="analytics-section__content position-relative" id="analytics-top-topcontent" data-params="%s">',
		esc_attr( json_encode( $params ) )
	);?>
		<?php include( dirname( dirname( __FILE__ ) ) . '/spinner.php' ); ?>
	</div>
</div>