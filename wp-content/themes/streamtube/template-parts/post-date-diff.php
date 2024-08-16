<?php
/**
 * The post date meta template
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

$modified_time = apply_filters( 'streamtube/post/show_modified_time', false );

if( $modified_time ){
    $modified_time = get_the_modified_time( 'U' );
}
else{
    $modified_time = get_the_time( 'U' );
}
?>

<div class="post-meta__date">
    <span class="icon-calendar-empty"></span>
    <a title="<?php echo esc_attr( get_the_title() )?>" href="<?php echo esc_url( get_permalink() )?>">
        <?php printf(
            esc_html__( '%s ago', 'streamtube' ),
            '<time datetime="'. get_the_date( 'Y-m-d H:i:s' ) .'" class="date">'. human_time_diff( $modified_time, current_time('timestamp') ) .'</time>'
        );?>
    </a>
</div>