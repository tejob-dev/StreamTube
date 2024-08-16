<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
global $post, $streamtube;

$is_portrait    = false;

$thumbnail_size = 'streamtube-image-medium';

if( $post && $streamtube->get()->post->is_portrait_video( $post->ID ) ){
    $thumbnail_size = 'full';
    $is_portrait = true;
}
?>
<div class="widget widget-featured-image shadow-sm rounded bg-white border" id="widget-featured-image">
    <div class="widget-title-wrap d-flex m-0 p-3 bg-light">
        <h2 class="widget-title no-after m-0">
            <?php esc_html_e( 'Featured Image', 'streamtube-core' ); ?>
        </h2>
    </div>
    <div class="widget-content">
        <div class="thumbnail-group p-3">

            <?php printf(
                '<div class="post-thumbnail ratio ratio-%s position-relative bg-dark mb-2 shadow rounded">',
                $is_portrait ? '9x16' : '16x9'
            );?>
                <?php
                if( $post ){
                    if( has_post_thumbnail( $post ) ){
                        echo get_the_post_thumbnail( $post, $thumbnail_size );
                    }

                    /**
                     * Fires in post thumbnail container
                     */
                    do_action( 'streamtube/core/post/edit/thumbnail_content', $post );
                }
                ?>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <label>
                    <a class="btn btn-primary btn-sm px-3">
                        <span class="icon-file-image"></span>
                        <?php esc_html_e( 'Upload', 'streamtube-core' ); ?>
                    </a>
                    <input type="file" name="featured-image" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" class="d-none">
                </label>

                <?php if( $post && ! has_post_thumbnail( $post ) ) : ?>

                    <?php printf(
                        '<button id="button-generate-thumb-image" type="button" class="btn btn-secondary btn-sm px-3"><span class="btn__icon icon-flash-outline"></span>%s</button>',
                        esc_html__( 'Generate', 'streamtube-core' )
                    );?>

                <?php endif;?>

            </div>
        </div>
    </div>
</div>