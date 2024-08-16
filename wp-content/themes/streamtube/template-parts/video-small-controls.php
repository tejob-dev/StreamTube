<?php
/**
 *
 * The Video Small Controls template file
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

?>
<div class="video-small-controls d-flex gap-2 ms-lg-auto justify-content-center my-sm-3">

    <?php
    do_action( 'streamtube/video/small_controls/before' );
    ?>

    <?php 
    if( get_option( 'button_turn_off_light', 'on' ) ){
        get_template_part( 'template-parts/turn-off-light' );
    }?>

    <?php 
    if( get_option( 'button_upnext', 'on' ) ){
        get_template_part( 'template-parts/up-next' );
    }?>

    <?php 
    if( get_option( 'button_video_navigator', 'on' ) ){
        get_template_part( 'template-parts/next-prev' );
    }?>

    <?php
    do_action( 'streamtube/video/small_controls/after' );
    ?>    
</div>