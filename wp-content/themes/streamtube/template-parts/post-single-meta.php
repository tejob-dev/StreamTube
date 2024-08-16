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

<div class="post-options d-flex pt-4">
    <div class="d-flex mx-auto gap-4">
        <?php
        /**
         *
         * @since  1.0.0
         * 
         */
        do_action( 'streamtube/single/video/control' );
        ?>

        <?php if ( current_user_can( 'edit_post', get_the_ID() ) ) :?>
            <div class="button-group button-group-edit">
                <a href="<?php echo esc_url( streamtube_get_edit_post_link( get_the_ID() ) ); ?>" class="btn shadow-none">
                    <span class="btn__icon icon-edit"></span>
                </a>
            </div>
        <?php endif;?>
    </div>
</div>