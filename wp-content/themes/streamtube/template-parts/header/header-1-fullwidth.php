<?php
/**
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
    '<header id="site-header" class="%s header-fw-1 d-flex align-items-center shadow-sm border-bottom py-4 fixed-top">',
    get_option( 'header_headroom', 'on' ) ? 'site-header site-header-headroom' : 'site-header'
)?>
    <div class="<?php echo esc_attr( streamtube_get_container_header_classes() ); ?>">
        
        <div class="row align-items-center">
            <?php printf(
                '<div class="col-%s col-sm-4 col-md-4 col-lg-3 col-xl-3 col-xxl-3">',
                ! streamtube_is_login_page() ? '1' : '6'
            );?>

                <div class="d-flex align-items-center">

                    <?php if( ! streamtube_is_login_page() ) :?>
                        <div class="navbar-toggler-btn navbar-light d-xl-none">
                            <button id="toggle-nav" class="btn border-0 navbar-toggler shadow-none" type="button">
                                <span class="btn__icon icon-menu"></span>
                            </button>
                        </div>
                    <?php endif;?>

                    <?php printf(
                        '<div class="logo-lg %s">',
                        ! streamtube_is_login_page() ? 'd-none d-lg-block' : 'd-block'
                    );?>
                        <?php get_template_part( 'template-parts/logo' );?>
                    </div>
                </div>
            </div>

            <?php printf(
                '<div class="col-center col-%s col-sm-4 col-md-4 col-lg-6 col-xl-6 col-xxl-6 top-0">',
                ! streamtube_is_login_page() ? '6' : '1'
            );?>

                <div class="d-flex">
                    <?php printf(
                        '<div class="logo-sm mx-md-auto me-sm-auto %s">',
                        ! streamtube_is_login_page() ? 'd-block d-lg-none' : 'd-none'
                    );?>
                        <?php get_template_part( 'template-parts/logo' );?>
                    </div>
                </div>

                <div id="site-search" class="site-search search-form-wrap d-none d-lg-block">
                    <?php get_template_part( 'template-parts/search-form' );?>
                </div>
            </div>

            <?php printf(
                '<div class="col-%s col-sm-4 col-md-4 col-lg-3 col-xl-3 col-xxl-3">',
                ! streamtube_is_login_page() ? '5' : '5'
            );?>
                <div class="header-user d-flex align-items-center">

                    <div class="ms-auto d-flex align-items-center gap-1 gap-sm-1 gap-lg-2">

                        <div class="header-user__search d-lg-none">
                            <button type="button" class="toggle-search btn btn-sm border-0 shadow-none p-1">
                                <span class="btn__icon icon-search"></span>
                            </button>
                        </div>

                        <?php get_template_part( 'template-parts/header/profile-dropdown' );?>
                        
                    </div>
                    
                </div>
            </div>

        </div>
    </div>   
</header>

<?php
if( apply_filters( 'sidebar_float', true ) === true ){
    get_sidebar( 'secondary' );    
}  
?>
<div id="site-main" class="site-main">