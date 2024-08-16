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
$is_verified = false;

if( function_exists( 'streamtube_core' ) ){
    if( method_exists( streamtube_core()->get()->user, 'is_verified' ) ){
        $is_verified = streamtube_core()->get()->user->is_verified();
    }
}
?>

<li>
    <div class="text-center p-4">
        <button type="button" class="btn-close position-absolute top-0 end-0 p-3 shadow-none d-block d-sm-none d-md-none" aria-label="Close"></button>
        <?php
        /**
         * Fires before avatar
         * @since 1.1
         */
        do_action( 'streamtube/user/profile_dropdown/avatar/before' );
        ?>                         
        <div class="post-author d-inline-block">
            <?php printf(
                '<a href="%s"><div class="user-avatar user-avatar-lg %s">%s</div></a>',
                get_author_posts_url( get_current_user_id() ),
                $is_verified ? 'is-verified' : '',
                get_avatar( get_current_user_id(), 96, null, null, array(
                    'class' =>  'img-thumbnail avatar'
                ) )
            );?>
        </div>

        <div class="author-name-wrap">
            <?php printf(
                '<h3 class="author-name my-2"><a href="%s">%s</a></h3>',
                get_author_posts_url( get_current_user_id() ),
                get_the_author_meta( 'display_name', get_current_user_id() )
            );?>
        </div>
        <?php
        /**
         * Fires after avatar
         * @since 1.1
         */
        do_action( 'streamtube/user/profile_dropdown/avatar/after' );
        ?>
    </div>
</li>

<?php if( function_exists( 'streamtube_core_the_user_profile_menu' ) ):?>

    <li><hr class="dropdown-divider"></li>

    <?php
    /**
     * @since 1.1
     */
    do_action( 'streamtube/user/profile_dropdown/menu_item/dashboard/before' );
    ?>  

    <li class="nav-item nav-item-dashboard">
        <?php printf(
            '<a class="dropdown-item %s" href="%s">',
            isset( $GLOBALS['wp_query']->query_vars['dashboard'] ) && empty( $GLOBALS['wp_query']->query_vars['dashboard'] ) ? 'active' : '',
            esc_url( streamtube_core_get_user_dashboard_url( get_current_user_id() ) )
        );?>
            <span class="menu-icon icon-gauge me-3"></span>
            <span class="menu-text"><?php esc_html_e( 'Dashboard', 'streamtube' )?></span>
        </a>
    </li>

    <?php
    /**
     * @since 1.1
     */
    do_action( 'streamtube/user/profile_dropdown/menu_item/dashboard/after' );
    ?>

    <li><hr class="dropdown-divider"></li>

    <li class="nav-item nav-item-main-menu">
        <?php streamtube_core_the_user_profile_menu( array(
            'user_id'           =>  get_current_user_id(),
            'menu_classes'      =>  'user-navbar navbar-nav nav flex-column',
            'item_classes'      =>  'dropdown-item',
            'icon'              =>  true
        ) );?>
    </li>                    

<?php endif;?>

<?php if( function_exists( 'streamtube_core' ) && get_option( 'custom_theme_mode' ) ): ?>
    <li class="nav-item nav-item-theme-switcher">
        <div id="theme-switcher" class="dropdown-item theme-switcher" href="#">
            <span class="btn__icon icon-moon me-3"></span>
            <span class="menu-text"><?php printf(
                esc_html__( '%s mode', 'streamtube' ),
                streamtube_get_theme_mode() == 'dark' ? 'light' : 'dark'
            );?></span>
        </div>
    </li>
<?php endif;?>

<?php if( current_user_can( 'administrator' ) ): ?>
    <li class="nav-item nav-item-divider"><hr class="dropdown-divider"></li>

    <li class="nav-item nav-item-customizer">
        <a class="nav-link dropdown-item" href="<?php echo esc_url( streamtube_get_customize_url() );?>">
            <span class="menu-icon icon-cog me-3"></span>
            <span class="menu-text"><?php esc_html_e( 'Customizer', 'streamtube' )?></span>
        </a>
    </li>                                             

    <li class="nav-item nav-item-backend">
        <a class="nav-link dropdown-item" href="<?php echo esc_url( admin_url( '/' ) );?>">
            <span class="menu-icon icon-wordpress me-3"></span>
            <span class="menu-text"><?php esc_html_e( 'Backend', 'streamtube' )?></span>
        </a>
    </li>                                        

<?php endif;?>

<li class="nav-item nav-item-divider"><hr class="dropdown-divider"></li>
<li class="nav-item nav-item-logout">
    <a class="nav-link dropdown-item" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) );?>">
        <span class="menu-icon icon-logout me-3"></span>
        <span class="menu-text"><?php esc_html_e( 'Log Out', 'streamtube' )?></span>
    </a>
</li>
<?php
/**
 * @since 1.1
 */
do_action( 'streamtube/user/profile_dropdown/menu_item' );
?>