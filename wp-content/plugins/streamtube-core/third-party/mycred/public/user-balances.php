<?php
/**
 *
 * User balance template
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.1
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

$output = '';

$args = wp_parse_args( $args, array(
	'user_id'	=>	get_current_user_id(),
    'columns'   =>  2
) );

$point_types = streamtube_core_get_mycred_public_point_types();

if( ! $point_types ){
    return;
}

$grid_column = 0;

?>
<div class="mycred-balances text-secondary mt-4">
	<?php 

    ob_start();

    foreach ( $point_types as $type => $text ) {

        $balance = mycred_display_users_balance( $args['user_id'], $type );

        if( $balance ){

            $grid_column++;

            printf(
                '<div class="mb-4 point-type point-type-%s"><div class="rounded bg-white text-center border text-center d-flex flex-column gap-1 p-3">',
                esc_attr( $type )
            );
                printf(
                    '<span class="small">%1$s</span><span class="user-balance user-balance-%2$s mycred-balance-%3$s text-success fw-bold m-0">%4$s</span>',
                    $text,
                    $args['user_id'],
                    esc_attr( $type ),
                    $balance
                );
            echo '</div></div>';
        }
    }

    $output = ob_get_clean();

    if( $output ){
        printf(
            '<div class="row row-cols-%s">%s</div>',
            $grid_column == 1 ? '1' : $args['columns'],
            $output
        );
    }

	?>
    
</div>