<?php
/**
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

$length = streamtube_core()->get()->post->get_length( get_the_ID() );

if( empty( $length ) ){
    return;
}

?>
<div class="video-length badge">
    <?php echo streamtube_seconds_to_length( $length ); ?>
</div>