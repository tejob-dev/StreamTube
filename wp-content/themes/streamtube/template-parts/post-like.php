<?php
/**
 * The post like meta template
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

if( ! function_exists( 'wppl_get_count' ) ){
    return;
}

$like_count = wppl_get_count();
?>

<div class="post-meta__like">
    <?php if( function_exists( 'wppl_button_like' ) ): ?>
        <?php wppl_button_like(); ?>
    <?php else:?>
        <a title="<?php echo esc_attr( get_the_title() )?>" href="<?php echo esc_url( get_permalink() )?>">
            <span class="post-meta__icon icon-heart-empty"></span>
            <span class="post-meta__text">
                <?php echo number_format_i18n( $like_count ); ?>
            </span>
        </a>
    <?php endif;?>
</div>