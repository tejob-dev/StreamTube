<?php
/**
 * The template for displaying video collection
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
    
$template   = streamtube_get_archive_template_settings();

extract( $template );

$el_width           = apply_filters( 'streamtube_main_content_size', 9 );

$custom_thumbnail   = apply_filters( 'streamtube/collection/custom_thumbnail', true );

$Collection         = streamtube_get_core()->get()->collection;

$term_id            = get_queried_object_id();
$can_view           = $Collection->_can_view( $term_id );
$is_onwer           = $Collection->_is_owner( $term_id );

get_header();
?>
    <div class="page-main pt-4">

        <?php if( is_wp_error( $can_view ) && ! $is_onwer ): ?>

            <div class="login-wrap position-relative">
                <div class="top-50 start-50 translate-middle position-absolute text-center">
                    <?php
                    /**
                     * 
                     * Fires before error message
                     *
                     * @param int $term_id
                     * @param WP_Error
                     * 
                     */
                    do_action( 'streamtube/collection/archive/error/before', $term_id, $can_view );
                    ?>                    
                    <h5 class="text-muted h5 mb-4">
                        <?php

                        $icon_class = 'icon-lock';

                        switch ( $can_view->get_error_code() ) {
                            
                            case 'empty_posts':
                                $icon_class = 'icon-folder-open-empty';
                            break;
                        }
                        ?>

                         <?php printf(
                            '<span class="%s"></span>',
                            esc_attr( $icon_class )
                         );?>
                        
                        <?php printf(
                            '<span class="error-message">%s</span>',
                            $can_view->get_error_message()
                        ); ?>
                    </h5>

                    <?php
                    /**
                     * 
                     * Fires after error message
                     *
                     * @param int $term_id
                     * @param WP_Error
                     * 
                     */
                    do_action( 'streamtube/collection/archive/error/after', $term_id, $can_view );
                    ?>
                </div>
            </div>
            
        <?php else:?>  

            <div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?>">

                <div class="row">

                    <?php if( $custom_thumbnail ): ?>

                        <?php printf(
                            '<div class="col-xxl-%1$s col-xl-%1$s col-lg-4 col-md-4 col-12">',
                            12-(int)$el_width
                        )?>

                            <div class="sticky-top mb-4">
                                <?php get_sidebar( 'term' ); ?>
                            </div>
                            
                        </div>

                    <?php endif;?>

                    <?php
                    printf(
                        '<div class="col-xxl-%1$s col-xl-%1$s col-lg-%2$s col-md-%2$s col-12">',
                        $custom_thumbnail ? $el_width : '12',
                        $custom_thumbnail ? '8' : '12'
                    );
                    ?>

                        <?php

                        /**
                         *
                         * Fires before collection archive
                         *
                         * @since 1.0.0
                         * 
                         */
                        do_action( 'streamtube/collection/archive/before' );

                        the_widget( 'Streamtube_Core_Widget_Playlist_Content', array(
                            'term_id'       =>  $term_id,
                            'max_height'    =>  '80vh',
                            'author_name'   =>  'on',
                            'status'        =>  'on',
                            'search_form'   =>  'on'
                        ));

                        /**
                         *
                         * Fires after collection archive
                         *
                         * @since 1.0.0
                         * 
                         */
                        do_action( 'streamtube/collection/archive/after' );                    
                        ?>

                    </div>         

                </div><!--.row-->

            </div>

        <?php endif;?>
    </div>
<?php 
get_footer();