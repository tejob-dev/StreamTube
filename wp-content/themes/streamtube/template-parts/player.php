<?php
/**
 * The player template file
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

if( ! defined( 'STREAMTUBE_IS_PLAYER' ) ){
    define( 'STREAMTUBE_IS_PLAYER' , true );    
}

$streamtube = streamtube_get_core();

if( ! $streamtube ){
    return;
}

$args = wp_parse_args( $args, array(
    'player'        =>  'videojs',
    'post_id'       =>  get_the_ID(),
    'mediaid'       =>  '',
    'uniqueid'      =>  uniqid(),
    'poster'        =>  '',
    'poster2'       =>  '',
    'poster_size'   =>  'large',
    'source'        =>  '',
    'ratio'         =>  '',
    'is_embed'      =>  is_embed() ? true : false,
    'trailer'       =>  false,
    'muted'         =>  get_option( 'player_mute' ) ? true : false,
    'loop'          =>  get_option( 'player_loop' ) ? true : false,
    'controls'      =>  true,
    'youtube'       =>  get_option( 'override_main_youtube', 'on' )
) );

$args['mediaid']        = $args['post_id'] ? $args['post_id'] : $args['uniqueid'];

if( ! $args['source'] ){
    $args['source']     =   $streamtube->get()->post->get_source( $args['post_id'] );
}

if( ! $args['ratio'] ){
    $args['ratio']      =   $streamtube->get()->post->get_aspect_ratio( $args['post_id'] );
}

if( method_exists( $streamtube->get()->post , 'get_thumbnail_url' ) ){
    $args['poster'] = $streamtube->get()->post->get_thumbnail_url( $args['post_id'] );
}
else{
    $args['poster'] = has_post_thumbnail($args['post_id']) ? wp_get_attachment_image_url( get_post_thumbnail_id($args['post_id']), 'large' ) : '';
}

if( method_exists( $streamtube->get()->post , 'get_thumbnail_image_url_2' ) ){
    $args['poster2'] = $streamtube->get()->post->get_thumbnail_image_url_2( $args['post_id'] );
}

if( is_attachment() && wp_attachment_is( 'video', $args['post_id'] ) ){
    $args['source'] = $args['post_id'];
}

$args['muted']      = isset( $_GET['muted'] )       ? wp_validate_boolean( $_GET['muted'] )     : $args['muted'];
$args['loop']       = isset( $_GET['loop'] )        ? wp_validate_boolean( $_GET['loop'] )      : $args['loop'];
$args['controls']   = isset( $_GET['controls'] )    ? wp_validate_boolean( $_GET['controls'] )  : $args['controls'];
$args['logo']       = isset( $_GET['logo'] )        ? wp_validate_boolean( $_GET['logo'] )      : true;
$args['share']      = isset( $_GET['share'] )       ? wp_validate_boolean( $_GET['share'] )     : ( get_option( 'player_share', 'on' ) ? true : false );

$args['autoplay']   = isset( $_GET['autoplay'] )    ? wp_validate_boolean( $_GET['autoplay'] )  : ( get_option( 'player_autoplay', 'on' ) ? true : false );

if( is_embed() && ! isset( $_GET['autoplay'] ) ){
    $args['autoplay'] = false;
}

if( isset( $_GET['ratio'] ) && in_array( $_GET['ratio'] , array( '21x9', '16x9', '4x3', '1x1' )) ){
    $args['ratio'] = $_GET['ratio'];
}

if( isset( $_GET['view_trailer'] ) 
    && wp_validate_boolean( $_GET['view_trailer'] )
    && "" != ($trailer = $streamtube->get()->post->get_video_trailer( $args['post_id'] ))
    && ! defined( 'STREAMTUBE_IS_PLAYER_SHORTCODE' ) ){

    $args['trailer']    = true;
    $args['source']     = $trailer;
}

/**

 * Pre filter player args.
 */
$args = apply_filters( 'streamtube_pre_player_args', $args );

get_template_part( 'template-parts/player', $args['player'], $args );