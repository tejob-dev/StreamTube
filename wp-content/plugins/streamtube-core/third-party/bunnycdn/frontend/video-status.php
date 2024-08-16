<?php
/**
 * The video status template file
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

$args = wp_parse_args( $args, array(
    'message'           =>  '',
    'spinner'           =>  true,
    'attachment_id'     =>  0
) );
?>

<div class="progress-wrap" id="progress-wrap-<?php echo $args['attachment_id']; ?>">
    <div class="w-50 top-50 start-50 translate-middle position-absolute">
        <h4 class="text-white h5 mb-4 fw-normal text-center" style="text-align: center">

            <?php if( $args['spinner'] ): ?>

                <div class="d-flex justify-content-center mb-3">
                    <div class="spinner-grow text-danger" role="status"></div>
                </div>

            <?php endif;?>

            <?php echo $args['message']; ?>
        </h4>
    </div>
</div>

<?php if ( ! defined('DOING_AJAX') || ! DOING_AJAX ): ?>
    <script type="text/javascript">
        setInterval( function(){
            var attachmentId    = '<?php echo $args['attachment_id']; ?>';
            var ajaxUrl         = '<?php echo add_query_arg( array(
                'action'        =>  'get_bunnycdn_video_status',
                'attachment_id' =>  $args['attachment_id'],
                '_wpnonce'      =>  wp_create_nonce( '_wpnonce' )
            ),  admin_url( 'admin-ajax.php' ) );?>';

            jQuery.get( ajaxUrl, function( response ){
                if( response.data ){
                    jQuery( '#progress-wrap-<?php echo $args['attachment_id']?>' ).replaceWith( response.data );
                }
                else{
                    window.location.href = '<?php echo get_permalink(); ?>';
                }
            } );
        }, 3000 );
    </script>
<?php endif; ?>