<?php
/**
 *
 * Header 2 template
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
<?php printf(
    '<header id="site-header" class="%s header-boxed-2 d-flex align-items-center shadow-sm border-bottom backdropblur sticky-top py-4">',
    get_option( 'header_headroom', 'on' ) ? 'site-header site-header-headroom' : 'site-header'
)?>
    <div class="<?php echo esc_attr( streamtube_get_container_header_classes() ); ?>">

        <div class="d-flex align-items-center">

            <div class="navbar-light d-lg-none">
                <button data-bs-toggle="collapse" data-bs-target="#navbar-primary" type="button" class="btn border-0 navbar-toggler shadow-none">
                    <span class="btn__icon icon-menu"></span>
                </button>
            </div>            
            <?php get_template_part( 'template-parts/logo' );?>

            <?php if( has_nav_menu( 'primary' ) ): ?>

                <nav class="navbar-boxed main-navbar navbar navbar-expand-lg ms-lg-3">
                    <div class="container px-sm-3 px-lg-0">
                        <div id="navbar-primary" class="collapse navbar-collapse">
                            <?php
                            wp_nav_menu( array(
                                'theme_location'    => 'primary',
                                'container'         => false,
                                'menu_class'        => 'main-menu navbar-nav',
                                'echo'              => true,
                                'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                                'walker'            => new WP_Bootstrap_Navwalker(),
                            ) );
                            ?>                  
                        </div>
                    </div>
                </nav>

            <?php endif;?>

            <?php printf(
                '<div class="header-user d-flex align-items-center gap-1 gap-sm-1 gap-lg-2 %s">',
                ! is_rtl() ? 'ms-auto' : 'me-auto'
            );?>

                <div class="header-user__search dropdown position-relative">

                    <button type="button" data-bs-toggle="dropdown" data-bs-display="static" class="toggle-search btn btn-sm border-0 shadow-none p-1">
                        <span class="btn__icon icon-search"></span>
                    </button>

                    <div id="site-search" class="dropdown-menu dropdown-menu2 dropdown-menu-end animate slideIn">
                        <div class="site-search search-form-wrap">
                            <?php get_template_part( 'template-parts/search-form' );?>
                        </div>
                    </div>                        

                </div>

                <?php get_template_part( 'template-parts/header/profile-dropdown' );?>
                
            </div>

        </div>
    </div>   
</header>
<div id="site-main" class="site-main">