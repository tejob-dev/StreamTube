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

/**
 * Fires before authorbox
 *
 * @since 1.0.8
 */
do_action( 'streamtube/authorbox/before' );
?> 
<div id="item-header" class="post-bottom__author d-flex align-items-center border-bottom p-4">

    <?php
    /**
     * Fires before author avatar
     *
     * @since 1.0.8
     */
    do_action( 'streamtube/authorbox/avatar/before' );
    ?>    

    <div class="author__avatar d-flex align-items-start">

    	<?php if( $args['author_avatar'] && function_exists( 'streamtube_core_get_user_avatar' ) ):?>

            <?php
            streamtube_core_get_user_avatar( array(
                'user_id'       =>  get_the_author_meta( 'ID' ),
                'link'          =>  true,
                'wrap_size'     =>  'xl',
                'before'        =>  '<div class="post-author me-4">',
                'after'         =>  '</div>'
            ) );
            ?> 

    	<?php endif;?>

        <?php if( function_exists( 'streamtube_core_get_user_name' ) ):?>

            <div class="author-info d-flex flex-column">

                <?php streamtube_core_get_user_name( array(
                    'user_id'   =>  get_the_author_meta( 'ID' ),
                    'before'    =>  '<h3 class="author-name h6 mb-2">',
                    'after'     =>  '</h3>'
                ) );?>

                <?php
                /**
                 *
                 * Fires after author name
                 *
                 * @since  1.0.0
                 * 
                 */
                do_action( 'streamtube/single/video/author/name/after' );
                ?>                

                <div class="d-flex gap-3 align-items-center">
                    <?php
                    /**
                     *
                     * Fires after author name
                     *
                     * @since  1.0.0
                     * 
                     */
                    do_action( 'streamtube/single/video/author/after' );
                    ?>
                </div>
            </div>

        <?php endif;?>
    </div>

    <?php
    /**
     * Fires after author avatar
     *
     * @since 1.0.8
     */
    do_action( 'streamtube/authorbox/avatar/after' );
    ?>

</div>
<?php
/**
* Fires after authorbox
*
* @since 1.0.8
*/
do_action( 'streamtube/authorbox/after' );