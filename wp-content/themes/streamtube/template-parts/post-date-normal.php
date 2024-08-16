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
?>

<div class="post-meta__date">

    <span class="icon-calendar-empty"></span>
    
    <a title="<?php echo esc_attr( get_the_title() )?>" href="<?php echo esc_url( get_permalink() )?>">
        <?php printf(
            esc_html__( 'on %s', 'streamtube' ),
            '<time datetime="'. get_the_date( 'Y-m-d H:i:s' ) .'" class="date">'. get_the_date() .'</time>'
        ) ?>
    </a>
</div>