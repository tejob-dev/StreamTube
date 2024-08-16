<?php
/**
 *
 * Text tracks template file
 * 
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $post;

$wpmedia = ( current_user_can( 'administrator' ) || apply_filters( 'streamtube/core/altsource/wpmedia', false ) === true ) ? true : false;

if( $wpmedia ){
    wp_enqueue_media();
}
?>
<form class="form-ajax">

    <div class="widget-title-wrap d-flex bg-white sticky-top border p-3">

        <div class="d-none d-sm-block group-title flex-grow-1">
            <h2 class="page-title">
                <?php esc_html_e( 'Alternative Sources', 'streamtube-core' ); ?>
            </h2>
        </div>

        <div class="ms-md-auto">
            <button type="submit" name="update" class="btn btn-primary px-3">
                <span class="btn__icon icon-floppy"></span>
                <span class="btn__text">
                    <?php esc_html_e( 'Update', 'streamtube-core' ); ?>
                </span>
            </button>
        </div>
    </div>    

    <input type="hidden" name="action" value="update_altsources">

    <?php printf(
        '<input type="hidden" name="post_ID" value="%s">',
        $post ? $post->ID : '0'
    );?>

    <?php load_template( STREAMTUBE_CORE_ADMIN_PARTIALS . '/altsources.php' ); ?>    

</form>

<?php if( $wpmedia ) : ?>
<script type="text/javascript">

    jQuery( document ).on( 'click', '.button-upload', function(e){

        var button = jQuery(this);

        var frame;
        
        // If the media frame already exists, reopen it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create a new media frame
        frame = wp.media({  
            library: { type: 'video' },
            multiple: false
        });

         // Finally, open the modal on click
        frame.open();

         // When an video is selected in the media frame...
        frame.on( 'select', function() {
            
            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();

            var attachment_id   =   attachment.id;

            button.closest( '.field-group' ).find( '.input-field' ).val( attachment_id );
        });

    } );

</script>
<?php endif;?>