<?php
/**
 *
 * The Plans template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      2.2
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

if( ! defined( 'IS_MEMBERSHIP_LEVELS' ) ){
	return;
}

global $wpdb, $pmpro_msg, $pmpro_msgt, $current_user;

$args = wp_parse_args( $args, array(
	'mb'				=>	4,
	'shadow'			=>	'sm',
	'custom_levels'		=>	array(),
	'min_height'		=>	'',
	'plan_description'	=>	''
) );

$pmpro_levels = pmpro_sort_levels_by_order( pmpro_getAllLevels(false, true) );
$pmpro_levels = apply_filters( 'pmpro_levels_array', $pmpro_levels );

?>
<div class="pmpro-plans-wrap">

	<?php if( $args['heading'] ):?>

		<div class="section-title text-center mb-4">

			<?php printf(
				'<%1$s class="text-body">%2$s</%s1$>',
				$args['heading_tag'],
				$args['heading']
			);?>
		</div>

	<?php endif;?>

	<?php
	if($pmpro_msg){
		printf(
			'<div class="%s">%s</div>',
			pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ),
			$pmpro_msg
		);
	}?>

	<div class="<?php echo esc_attr( join( ' ', $args['classes'] ) ); ?>">
		
		<?php
		$count 			= 0;
		$has_any_level	= false;
		foreach( $pmpro_levels as $level ):

			if( ! $args['custom_levels'] || ( is_array( $args['custom_levels'] ) && in_array( $level->id, $args['custom_levels'] ) ) ):

				$user_level 		= pmpro_getSpecificMembershipLevelForUser( $current_user->ID, $level->id );
				$has_level 			= ! empty( $user_level )	? true 	: false;
				$has_any_level 		= $has_level 				? : $has_any_level;

				$cost_text 			= pmpro_getLevelCost($level, true, true); 
				$expiration_text 	= pmpro_getLevelExpiration($level);					

				$style = '';

				if( $args['min_height'] ){
					$style .= sprintf( 'min-height:%spx;', esc_attr( $args['min_height'] ) );
				}

				printf(
					'<div class="col mb-%s">',
					$args['mb']
				);

					printf(
						'<div class="shadow-%s plan-item plan-id-%s plan-%s bg-white d-flex flex-column p-4" style="%s">',
						sanitize_html_class( $args['shadow'] ),
						sanitize_html_class( $level->id ),
						sanitize_html_class( strtolower( $level->name ) ),
						esc_attr( $style )
					);

						?>
						<div class="pmpro-plan-name text-center border-bottom pb-3">
							<?php printf(
								'<h4 class="text-body">%s</h4>',
								$level->name
							);?>
						</div>

						<div class="pmpro-plan-price text-center my-4">
							<?php printf(
								'<h4  class="text-info m-0">%s</h4>',
								$cost_text
							);?>
						</div>						

						<?php
						if( $level->description && $args['plan_description'] ):
						printf(
							'<div class="pmpro-plan-description mb-4">%s</div>',
							do_shortcode( $level->description )
						);
						endif;
						?>

						<div class="pmpro-plan-button text-center mt-auto">
							<?php

							if ( ! $has_level ):

								printf(
									'<a class="%s" href="%s">%s</a>',
									pmpro_get_element_class( 'btn btn-'. sanitize_html_class( $args['button_size'] ) .' btn-'. sanitize_html_class($args['select_button']) .' d-block text-white', 'pmpro_btn-select' ),
									esc_url( pmpro_url( "checkout", "?level=" . $level->id, "https" ) ),
									esc_html__( 'Select', 'streamtube-core' ),
								);

							else:

								if( pmpro_isLevelExpiringSoon( $user_level ) && $level->allow_signups ) {

									printf(
										'<a class="%s" href="%s">%s</a>',
										pmpro_get_element_class( 'btn btn-'. sanitize_html_class( $args['button_size'] ) .' btn-'. sanitize_html_class($args['renew_button']) .' d-block', 'pmpro_btn-select' ),
										esc_url( pmpro_url( "checkout", "?level=" . $level->id, "https" ) ),
										esc_html__( 'Renew', 'streamtube-core' )
									);

								} else {

									printf(
										'<a class="%s" href="%s">%s</a>',
										pmpro_get_element_class( 'btn btn-'. sanitize_html_class( $args['button_size'] ) .' btn-'. sanitize_html_class($args['your_level_button']) .' d-block disabled', 'pmpro_btn' ),
										esc_url( pmpro_url( "account" ) ),
										esc_html__('Your&nbsp;Level', 'streamtube-core' )
									);

								}

							endif;
						echo '</div>';// pmpro-plan-button
					echo '</div>';

				echo '</div>';

			endif;

		endforeach;
		?>
	</div>

</div>
