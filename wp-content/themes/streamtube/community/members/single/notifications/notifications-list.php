<?php
/**
 *
 * The header notification list
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

// Need to check logged in user before loading this template

if ( bp_has_notifications( streamtube_bp_get_notifications_query_args() ) ) : ?>

    <ul class="notification-list list-unstyled list-items border-bottom bg-white m-0 p-0">

        <?php while ( bp_the_notifications() ) : bp_the_notification(); ?>
            <li <?php streamtube_bp_the_notification_classes(); ?> id="notification-<?php bp_the_notification_id(); ?>">
                <?php bp_get_template_part( 'members/single/notifications/partials/notification', bp_get_the_notification_component_name() ); ?>
            </li>
        <?php endwhile;?>

    </ul>

    <?php printf(
        '<a class="text-body text-decoration-none text-center d-block p-3" href="%s">%s</a>',
        esc_url( bp_get_notifications_permalink( get_current_user_id() ) ),
        esc_html__( 'All notifications', 'streamtube' )
    )?>

<?php else:?>
    <p class="p-3 m-0 text-muted"><?php esc_html_e( 'No notifications', 'streamtube' );?></p>
<?php endif; ?>
