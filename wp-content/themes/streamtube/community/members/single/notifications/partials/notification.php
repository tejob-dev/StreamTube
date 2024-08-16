<?php
/**
 *
 * The regular notification content template part
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
</div>