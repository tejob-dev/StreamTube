<?php
/**
 * Post social share
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

if( is_singular() && function_exists( 'meks_ess_share' ) ){

    $settings = get_option( 'meks_ess_settings' );

    if( is_array( $settings ) && array_key_exists( 'location', $settings ) ){
        if( $settings['location'] == 'custom' ){
            ?>
                <div class="social-share mt-3">
                    <?php meks_ess_share(); ?>
                </div>
            <?php
        }
    }
}

do_action( 'streamtube/post/social_share' );

