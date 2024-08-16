<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
/**
 *
 * The user balance template file
 * 
 */

$args = wp_parse_args( $args, array(
    'user_id'       =>  get_current_user_id(),
    'ctype'         =>  MYCRED_DEFAULT_TYPE_KEY,
    'text'          =>  esc_html__( 'Your current balance is', 'streamtube-core' )
) );

extract( $args );

?>

<div class="user-balance-wrap text-secondary mb-4 text-center p-3 border">

    <?php if( $text ){
        printf(
            '<span class="text-balance">%s</span>',
            $text
        );
    }?>

    <?php printf(
        '<span class="text-success fw-bold h5 user-balance user-balance-%s mycred-balance-%s">%s</span>',
        $user_id,
        esc_attr( $ctype ),
        mycred_display_users_balance( $user_id, $ctype )
    ); ?>

</div>