<?php
/**
 * The post views meta template
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.8
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

if( ! function_exists( 'streamtube_core' ) ){
    return;
}

$pageviews = streamtube_core()->get()->post->get_post_views();

if( ! $pageviews ){
    return;
}
?>
<div class="post-meta__views">
    <span class="icon-eye"></span>
    <?php printf(_n( '%s view', '%s views', $pageviews, 'streamtube' ), streamtube_core_format_page_views( $pageviews )); ?>
</div>