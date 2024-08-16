<?php
/**
 * The post author meta template
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

$args = wp_parse_args( $args, array(
    'icon'  =>  true
) );

?>
<div class="post-meta__author">
    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
        <?php if( $args['icon'] ):?>
            <span class="post-meta__icon icon-user-o"></span>
        <?php endif;?>

        <span class="post-meta__text"><?php echo get_the_author(); ?></span>
    </a>
</div>