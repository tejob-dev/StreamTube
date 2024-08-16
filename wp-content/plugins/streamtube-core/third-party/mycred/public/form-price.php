<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$settings = streamtube_core()->get()->myCRED->sell_content->get_mycred_settings();

if ( $post && ( empty( $settings['filters'][ $post->post_type ] ) || $settings['filters'][ $post->post_type ]['by'] !== 'manual') ){
    return;
}

echo '<div class="widget widget-sell-content shadow-sm rounded bg-white border">';

    echo '<div class="widget-title-wrap d-flex m-0 p-3 bg-light">';
        printf(
            '<h2 class="widget-title no-after m-0">%s</h2>',
            esc_html__( 'Sell Content', 'streamtube-core' )
        );
    echo '</div>';

    echo '<div class="widget-content p-3">';

    /**
     *
     * Fires after box price
     *
     * @since 1.1
     * 
     */
    do_action( 'streamtube/core/post/box_price/before' );

    if ( ! empty( $settings['type'] ) ) {
        foreach ( $settings['type'] as $point_type ) {

            $setup  = mycred_get_option( 'mycred_sell_this_' . $point_type );

            if ( $setup['status'] === 'enabled' ){

                $sale_setup = array();

                $mycred     = mycred( $point_type );

                $suffix     = '_' . $point_type;

                if ( $point_type == MYCRED_DEFAULT_TYPE_KEY ){
                    $suffix = '';
                }

                if( $post ){
                    $sale_setup = (array)mycred_get_post_meta( $post->ID, 'myCRED_sell_content' . $suffix );

                    $sale_setup = empty($sale_setup) ? $sale_setup : $sale_setup[0];
                }

                $sale_setup = wp_parse_args( $sale_setup, array(
                    'status' => 'disabled',
                    'price'  => 0,
                    'expire' => 0 
                ) );

                printf(
                    '<label class="mb-2 fw-bold">%s</label>',
                    sprintf(
                        esc_html__( 'Sell using %s', 'streamtube-core' ),
                        $mycred->plural()
                    )
                )

                ?><div class="row"><div class="col-6"><?php
                streamtube_core_the_field_control( array(
                    'label'         =>  esc_html__( 'Price', 'streamtube-core' ),
                    'type'          =>  'number',
                    'name'          =>  'sell_content['.$point_type.'][price]',
                    'value'         =>  $sale_setup['price']
                ) ); 
                ?></div><div class="col-6"><?php
                streamtube_core_the_field_control( array(
                    'label'         =>  esc_html__( 'Expiration', 'streamtube-core' ),
                    'type'          =>  'number',
                    'name'          =>  'sell_content['.$point_type.'][expire]',
                    'value'         =>  $sale_setup['expire']
                ) ); 
                ?></div></div><?php

            }
        }

        printf(
            '<input type="hidden" name="point_types" value="%s">',
            esc_attr( join( ',', $settings['type'] ) )
        );
    }
    /**
     *
     * Fires after box price
     *
     * @since 1.1
     * 
     */
    do_action( 'streamtube/core/post/box_price/after' );
    echo '</div>';

echo '</div>';