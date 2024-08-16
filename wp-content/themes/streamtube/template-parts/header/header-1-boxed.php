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
<header id="site-header" class="site-header header-boxed-1 d-flex align-items-center shadow-sm border-bottom py-4 px-2">
    <div class="<?php echo esc_attr( streamtube_get_container_header_classes() ); ?>">
        
        <div class="row align-items-center">
            <div class="col-5 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                <div class="logo-lg">
                    <?php get_template_part( 'template-parts/logo' );?>
                </div>
            </div>

            <div class="col-center col-2 col-sm-4 col-md-6 col-lg-6 col-xl-6 col-xxl-6">   

                <div id="site-search" class="site-search search-form-wrap d-none d-lg-block">
                    <div class="container mx-auto p-0">
                        <?php get_template_part( 'template-parts/search-form' );?>
                    </div>
                </div>
            </div>

            <div class="col-5 col-sm-4 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
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

<?php get_template_part( 'template-parts/nav' ); ?>
<div id="site-main" class="site-main">