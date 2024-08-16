<?php
/**
 *
 * myCred plugin compatiblity file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 *
 * Get total user balance including custom point types
 * 
 * @param  integer $user_id
 * @return false|int
 * 
 */
function streamtube_mycred_get_total_user_points( $user_id = 0 ){

    if( ! function_exists( 'mycred' ) || ! function_exists( 'mycred_get_types' ) ){
        return false;
    }

    $point_types = mycred_get_types();

    /**
     *
     * Filter the public point types
     * 
     */
    $point_types = apply_filters( 'streamtube/mycred/public_point_types', $point_types );    

    $total_balance = 0;

    foreach ( $point_types as $point_type => $value) {

        $mycred  = mycred( $point_type );

        $total_balance += $mycred->get_users_balance( $user_id, $point_type );
    }

    /**
     *
     * Filter total balance
     *
     * @param string formatted balance
     * @param int balance number
     * 
     */
    return apply_filters( 'streamtube/mycred/user/total_balance', $mycred->format_creds( $total_balance ), $total_balance );
}

/**
 *
 * Load the followers count
 * 
 * @param  object WP_User $user
 *
 * @since 1.0.0
 * 
 */
function streamtube_mycred_load_user_card_points_count( $user ){

    $user_id = is_object( $user ) ? $user->ID : ( is_int( $user ) ? $user : 0 );

    $total_balance = streamtube_mycred_get_total_user_points( $user_id );

    if( $total_balance === false ){
        return;
    }

	?>
    <div class="member-info__item flex-fill">
        <div class="member-info__item__count">
        	<?php
            printf(
                '<div class="member-info__item__count">%s</div>',
                $total_balance ? $total_balance : 0
            );
            ?>
        </div>
        <div class="member-info__item__label">
        	<?php if( $total_balance == 1 ){
                esc_html_e( 'point', 'streamtube' );
            }else{
                esc_html_e( 'points', 'streamtube' );
            }?>
        </div>
    </div>
	<?php
}
add_action( 'streamtube/core/user/card/info/item', 'streamtube_mycred_load_user_card_points_count', 20, 1 );