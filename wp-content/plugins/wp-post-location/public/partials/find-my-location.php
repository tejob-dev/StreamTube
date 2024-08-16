<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

global $post;
?>
<button id="find-my-location" type="button" class="btn btn-danger button button-secondary">
    <span class="btn__icon icon-location"></span>
    <span class="btn__text">
        <?php esc_html_e( 'My location', 'wp-post-marker' );?>
    </span>
</button> 

<?php if( is_object( $post ) && $post->post_type == 'video' ): ?>

    <?php printf(
        '<button type="button" name="reset" class="btn btn-danger button button-secondary ajax-elm" data-action="reset_location" data-params="%s">',
        esc_attr( $post->ID )
    );?>
        <span class="btn__icon icon-ccw"></span>
        <span class="btn__text">
            <?php esc_html_e( 'Reset', 'wp-post-location' );?>
        </span>
    </button>

    <?php if( is_admin() ) : ?>

        <script type="text/javascript">
            jQuery( document ).ready(function() {

                jQuery( document ).on( 'click', 'button[data-action=reset_location]', function(e){

                    var button = jQuery(this);
                    var postId = parseInt( button.attr( 'data-params' ) );

                    jQuery.ajax({
                        url : '<?php echo admin_url( 'admin-ajax.php' )?>',
                        method: 'POST',
                        data: {
                            action : 'reset_location',
                            data : postId,
                            _wpnonce : '<?php echo wp_create_nonce('_wpnonce'); ?>'
                        },
                        beforeSend: function( jqXHR ) {
                            button.attr( 'disabled', 'disabled' );
                        }
                    }).done( function( data, textStatus, jqXHR ){
                        button.removeAttr( 'disabled' );
                    });

                } );

            });
        </script>

    <?php endif;

endif;