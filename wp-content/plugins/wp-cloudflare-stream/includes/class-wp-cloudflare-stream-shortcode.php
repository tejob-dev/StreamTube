<?php
/**
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 */

/**
 *
 * @package    WP_Cloudflare_Stream
 * @subpackage WP_Cloudflare_Stream/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class WP_Cloudflare_Stream_Shortcode {

    /**
     *
     * The golive form shortcode
     * 
     * @param  array  $args
     */
    public function _form_golive( $args = array() ){

        if( is_admin() || ! function_exists( 'streamtube_core_the_field_control' ) ){
            return;
        }

        ob_start();
        ?>
        <form class="form-ajax form-live-stream">

            <?php
            /**
             * @since 2.1.7
             */
            do_action( 'streamtube/core/form/live_stream/before' );
            ?>      

            <div class="upload-form__group">            

                <div class="row">

                    <div class="col-12 col-lg-4">

                        <div class="thumbnail-group mb-4">

                            <div class="post-thumbnail ratio ratio-16x9 position-relative bg-dark mb-2 shadow rounded">
                            </div>

                            <label class="text-center w-100 mt-3">
                                <a class="btn btn-secondary btn-sm">
                                    <span class="icon-file-image"></span>
                                    <?php esc_html_e( 'Upload Image', 'wp-cloudflare-stream' ); ?>
                                </a>
                                <input type="file" name="featured-image" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" class="d-none">
                            </label>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <?php streamtube_core_the_field_control( array(
                            'label'         =>  esc_html__( 'Title', 'wp-cloudflare-stream' ),
                            'name'          =>  'name',
                            'type'          =>  'text',
                            'required'      =>  true,
                            'description'   =>  esc_html__( 'Add a title that describes your stream', 'wp-cloudflare-stream' )
                        ) );
                        ?>

                        <?php streamtube_core_the_field_control( array(
                            'label'         =>  esc_html__( 'Description', 'wp-cloudflare-stream' ),
                            'name'          =>  'description',
                            'type'          =>  'textarea',
                            'required'      =>  false,
                            'description'   =>  esc_html__( 'Tell viewers more about your stream', 'wp-cloudflare-stream' )
                        ) );
                        ?>

                    </div>
                </div>

            </div>

            <div class="form-submit d-flex">

                <button type="submit" class="btn btn-danger px-4 text-white btn-next ms-auto">
                    <span class="icon-plus"></span>
                    <?php esc_html_e( 'Start', 'wp-cloudflare-stream' ); ?>
                </button>

            </div>            

            <input type="hidden" name="action" value="live_stream">
            <input type="hidden" name="quick_update" value="1">

            <?php
            /**
             * @since 2.1.7
             */
            do_action( 'streamtube/core/form/live_stream/after' );
            ?>                  
        </form>
        <?php
        return apply_filters( 'wp_cloudflare_stream/form_golive', ob_get_clean() );
    }

    public function form_golive(){
        add_shortcode( 'form_golive', array( $this , '_form_golive' ) );
    }

}