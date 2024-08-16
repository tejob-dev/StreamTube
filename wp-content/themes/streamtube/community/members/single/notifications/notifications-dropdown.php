<?php
/**
 *
 * The header dropdown notification list
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


if( ! function_exists( 'bp_notifications_get_unread_notification_count' ) ){
    return;
}

$count = bp_notifications_get_unread_notification_count( get_current_user_id() );

?>
<div class="header-user__notification">
    <div class="dropdown">
        <button href="" class="btn btn-notification shadow-none px-2 position-relative" data-bs-toggle="dropdown" data-bs-display="static">
            <span class="btn__icon icon-bell-alt"></span>

            <?php 
            if( $count ): ?>
                <?php printf(
                    '<span class="badge bg-danger position-absolute top-0 end-0">%s</span>',
                    number_format_i18n( $count )
                ); ?>
            <?php endif;?>
        </button>

        <div class="dropdown-notification dropdown-menu dropdown-menu-end animate slideIn p-0">

            <div class="widget-title-wrap d-flex m-0 p-0 border-bottom">
                <h2 class="widget-title p-3 m-0"><?php esc_html_e( 'Notifications', 'streamtube' );?></h2>
            </div>

            <?php bp_get_template_part( 'members/single/notifications/notifications-list' ); ?>
        </div>
    </div>
</div>