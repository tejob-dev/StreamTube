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

?>
<form class="form-ajax">

    <div class="widget-title-wrap d-flex bg-white sticky-top border p-3">

        <div class="d-none d-sm-block group-title flex-grow-1">
            <h2 class="page-title">
                <?php esc_html_e( 'Subtitles', 'streamtube-core' ); ?>
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

    <input type="hidden" name="action" value="update_text_tracks">

    <?php printf(
        '<input type="hidden" name="post_ID" value="%s">',
        $post ? $post->ID : '0'
    );?>

    <?php load_template( STREAMTUBE_CORE_ADMIN_PARTIALS . '/text-tracks.php' ); ?>    

</form>

