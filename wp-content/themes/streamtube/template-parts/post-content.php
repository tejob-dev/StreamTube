<?php
/**
 * The post content template file
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

/**
 *
 * Fires before post content box
 *
 * @since 1.0.0
 * 
 */
do_action( 'streamtube/single/post_content/before' );
?>

<div class="post-bottom__content p-4">
    <div class="post-content">
        <?php the_content(); ?>
        <?php get_template_part( 'template-parts/post-tags' );?>
    </div>
</div>

<?php
/**
 *
 * Fires before post content box
 *
 * @since 1.0.0
 * 
 */
do_action( 'streamtube/single/post_content/after' );
