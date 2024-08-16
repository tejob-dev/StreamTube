<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$post_id = streamtube_core()->get()->post->get_edit_post_id();

?>
<div class="analytics-section section-overview bg-white p-3 shadow-sm mb-4">

	<div class="analytics-section__header border-bottom mb-4 px-4 py-2 d-flex">
		<h2 class="section-title">
			<?php 


			if( $start_date == 'all' ){
				esc_html_e( 'Audience overview', 'streamtube-core' );
			}
			else{

				if( in_array( $start_date, array( 'today', 'yesterday' ) ) ){
					printf(
						esc_html__( 'Audience overview for %s', 'streamtube-core' ),
						$start_date
					);
				}

				elseif( array_key_exists( $start_date, $start_dates ) ){
					printf(
						esc_html__( 'Audience overview for the %s', 'streamtube-core' ),
						$start_dates[ $start_date ]
					);
				}
				else{
					$diff = (int)date_diff( date_create( $start_date ), date_create( $end_date ))->format("%R%a");
					printf(
						esc_html__( 'Audience overview for the last %s %s', 'streamtube-core' ),
						in_array( $diff, array( 0, 1 ) ) ? 1 : $diff,
						in_array( $diff, array( 0, 1 ) ) ? esc_html__( 'day', 'streamtube-core' ) : esc_html__( 'days', 'streamtube-core' )
					);					
				}
			}
			?>
		</h2>

	</div>

	<?php if( $overview_metrics ): ?>
		<?php printf(
			'<div class="analytics-section__content position-relative" id="analytics-overview" data-params="%s">',
			esc_attr( json_encode( $params ) )
		);?>
			<?php include( dirname( dirname( __FILE__ ) ) . '/spinner.php' ); ?>
		</div>
	<?php endif;?>

	<?php if( defined( 'STREAMTUBE_CORE_IS_DASHBOARD' ) || ($post_id && get_post_type( $post_id ) == 'video' )) : ?>

		<?php if( $overview_video_metrics ): ?>
			<?php printf(
				'<div class="analytics-section__content position-relative" id="analytics-overview-videos" data-params="%s">',
				esc_attr( json_encode( $params ) )
			);?>
				<?php include( dirname( dirname( __FILE__ ) ) . '/spinner.php' ); ?>
			</div>	
		<?php endif;?>

	<?php endif;?>
</div>