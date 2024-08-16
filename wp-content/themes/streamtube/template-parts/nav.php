<?php
/**
 * Primary menu template file
 *
 * Bootstrap menu
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

$el_classes = array( 'container' );

$el_classes[] = streamtube_get_site_content_width();

if( has_nav_menu( 'primary' ) ): ?>

	<?php printf(
		'<nav class="py-3 m-lg-0 p-lg-0 nav-top navbar narbar-fw navbar-expand-lg navbar-%s %s">',
        get_option( 'menu_style', 'dark' ),
		get_option( 'menu_sticky' ) ? 'sticky-top' : '' 
	);?>
		<div class="<?php echo esc_attr( join( ' ',array_unique( $el_classes ) ) ); ?>">

			<button class="btn border-0 navbar-toggler shadow-none py-3 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-primary">
				<span class="btn__icon icon-menu text-white"></span>
			</button>
			
            <div id="navbar-primary" class="collapse navbar-collapse">
                <?php
                wp_nav_menu( array(
                    'theme_location'    => 'primary',
                    'container'         => false,
                    'menu_class'        => 'main-menu navbar-nav mb-3 mb-lg-0',
                    'echo'              => true,
                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'            => new WP_Bootstrap_Navwalker(),
                ) );
                ?>                  
            </div>
		</div>
	</nav>

<?php endif;