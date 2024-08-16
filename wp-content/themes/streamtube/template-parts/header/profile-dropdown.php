<?php
/**
 *
 * Profile Dropdown menu template
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

$settings = get_option( 'custom_registration', array(
    'login_button'      =>  'on'
) );

if( ! array_key_exists( 'login_button' , $settings ) ){
    $settings['login_button'] = 'on';
}

$is_verified = false;

if( function_exists( 'streamtube_core' ) ){
    if( method_exists( streamtube_core()->get()->user, 'is_verified' ) ){
        $is_verified = streamtube_core()->get()->user->is_verified();
    }
}

/**
 *
 * Fires before user profile button
 *
 * @since 1.0.0
 * 
 */
do_action( 'streamtube/header/profile/before' );
?>

<div class="header-user__dropdown ms-0 ms-lg-2">

    <?php if( ! is_user_logged_in() ):?>

        <?php if( $settings['login_button'] ): ?>

            <?php printf(
                '<a class="btn btn-login px-lg-3 d-flex align-items-center btn-sm" href="%s">
                    <span class="btn__icon icon-user-circle"></span>
                    <span class="btn__text text-white d-lg-block d-none ms-2">%s</span>
                </a>',
                esc_url( wp_login_url() ),
                esc_html__( 'Sign In', 'streamtube' )
            );?>

        <?php endif;?>

    <?php else:?>

        <div class="dropdown">

            <div class="avatar-dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static">
                <?php
                /**
                 *
                 * Fires before avatar dropdown image
                 *
                 * @since 1.1.7.2
                 * 
                 */
                do_action( 'streamtube/avatar_dropdown/before' );
                ?>                
                <?php printf(
                    '<div class="user-avatar avatar-btn %s">%s</div>',
                    $is_verified ? 'is-verified' : '',
                    get_avatar( get_current_user_id(), 96, null, null, array(
                        'class' =>  'img-thumbnail avatar'
                    ) )
                )?>
                <?php
                /**
                 *
                 * Fires after avatar dropdown image
                 *
                 * @since 1.1.7.2
                 * 
                 */
                do_action( 'streamtube/avatar_dropdown/after' );
                ?>
            </div>

            <ul class="dropdown-menu dropdown-menu-end animate slideIn">
                <?php get_template_part( 'template-parts/header/profile-menu' );?>
            </ul>
        </div>

    <?php endif;?>

</div>

<?php if( function_exists( 'streamtube_core' ) && get_option( 'custom_theme_mode' ) && ! is_user_logged_in() ): ?>
    <?php get_template_part( 'template-parts/theme-mode-switcher' );?>
<?php endif;
/**
 *
 * Fires before user profile button
 *
 * @since 1.0.0
 * 
 */
do_action( 'streamtube/header/profile/after' );