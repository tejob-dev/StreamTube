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

$active_sidebars = array();

$footer_widgets = absint( get_option( 'footer_widgets', 4 ) );

if( $footer_widgets == 0 ){
    return;
}

for ( $i=1; $i <= $footer_widgets ; $i++) {
    if( is_active_sidebar( 'footer-' . $i ) ){
        $active_sidebars[] = 'footer-' . $i;
    }
}

if( ! $active_sidebars ){
    return;
}
?>

<?php if( apply_filters( 'footer_sidebar', true ) === true ): ?>
    <div class="footer-sidebar">

        <div class="<?php echo esc_attr( streamtube_get_container_footer_classes() ); ?>">

            <div class="row">

            <?php 
            for ( $i = 0; $i < count( $active_sidebars ) ; $i++) {

                printf(
                    '<div class="col-lg-%1$s col-12"><div id="footer-sidebar-%2$s">',
                    12/count( $active_sidebars ),
                    $i
                );

                    dynamic_sidebar( $active_sidebars[$i] );

                echo '</div></div>';
            }
            ?>

            </div>

        </div>

    </div>
<?php endif;?>