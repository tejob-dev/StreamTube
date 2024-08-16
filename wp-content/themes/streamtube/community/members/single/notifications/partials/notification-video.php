<?php
/**
 *
 * The video notification content template part
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
<div class="list-body d-flex gap-3 align-items-start">

    <?php streamtube_bp_the_notification_avatar(); ?>

    <div class="notification-content text-body">

        <div class="notification-description">
            <?php bp_the_notification_description(); ?>
        </div>

        <div class="notification-since text-muted my-2"><?php bp_the_notification_time_since(); ?></div>
    </div>

    <?php 
    if( has_post_thumbnail( bp_get_the_notification_item_id() ) ) :
        ?>
        <div class="ms-auto notification-thumbnail position-relative">
            <a href="<?php the_permalink( bp_get_the_notification_item_id())?>">
                <?php
                printf(
                    '<div class="ratio ratio-16x9 rounded overflow-hidden bg-light">%s</div>',
                    get_the_post_thumbnail( bp_get_the_notification_item_id(), 'medium', array(
                        'class' =>  'bg-cover'
                    ) )
                );
                ?>
                <span class="icon-play-circled2 h5 position-absolute top-50 start-50 w-auto h-auto translate-middle text-white"></span>
            </a>
        </div>
        <?php
    endif;
    ?>
</div>