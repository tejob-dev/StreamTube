<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$user_socials = streamtube_core()->get()->user->get_social_profiles( get_queried_object_id() );

if( ! $user_socials ){
    return;
}

$output = '';

ob_start();

foreach ( $user_socials as $social_id => $social_name ):
    
    if( array_key_exists( $social_id , $user_socials ) && ! empty( $user_socials[ $social_id ] ) ){
        $output .= sprintf(
            '<li class="social__%1$s">
                <a rel="nofollow" target="_blank" href="%2$s">
                    <span class="icon-%1$s icon-%1$s-circled"></span>
                </a>
            </li>',
            esc_attr( $social_id ),
            esc_url( $user_socials[ $social_id ] )
        );
    }

endforeach;

$output .= ob_get_clean();

/**
 * @since 2.2.1
 */
$output = apply_filters( 'streamtube/core/user/social_profiles', $output );

if( $output ){
    printf(
        '<div class="social-profiles d-flex text-center mt-3">
            <ul class="social-list social-list-sm list-unstyled mx-auto mb-0">%s</ul>
        </div>',
        $output
    );
}
