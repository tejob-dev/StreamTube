<?php
/**
 * The proccess thumbnail template file
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

global $post;

?>
<div class="spinner-wrap">
    <div class="top-50 start-50 translate-middle position-absolute text-center">
        <div class="spinner-border text-info" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>

        <p class="text-white"><?php esc_html_e( 'Processing', 'streamtube-core' );?></p>
    </div>
</div>

<?php if ( ! defined('DOING_AJAX') || ! DOING_AJAX ): ?>
    <script type="text/javascript">
        var getPostThumbnailInterval = setInterval( function(){
            var ajaxUrl         = '<?php echo add_query_arg( array(
                'action'        =>  'get_post_thumbnail',
                'post_id'       =>  $post ? $post->ID : '',
                '_wpnonce'      =>  wp_create_nonce( '_wpnonce' )
            ),  admin_url( 'admin-ajax.php' ) );?>';

            jQuery.get( ajaxUrl, function( response ){
                if( response.success == true ){
                    jQuery( '.thumbnail-group .post-thumbnail' ).html( response.data );

                    clearInterval( getPostThumbnailInterval );

                    jQuery( '#button-generate-thumb-image' ).remove();
                }
            } );
        }, 3000 );
    </script>
<?php endif; ?>