<?php
/**
 *
 * The template for displaying term featured image widget
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

$Collection     = streamtube_get_core()->get()->collection;

$term_id        = get_queried_object_id();

$thumbnail_url  = streamtube_get_core()->get()->taxonomy->get_thumbnail_url( $term_id );

$is_onwer       = $Collection->_is_owner( $term_id );

$can_manage     = method_exists( $Collection, '_can_manage' ) ? $Collection->_can_manage() : true;

?>
<div class="widget widget-term-featured-image p-0">
    <div class="thumbnail-group">
        <div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark">
            <?php 
            if( $thumbnail_url ){
                printf(
                    '<img src="%s">',
                    esc_attr( $thumbnail_url )
                );
            }?>
        </div>
        <?php if( $is_onwer && $can_manage ): ?>
            <form class="form-ajax">
                <div class="d-flex gap-0">
                    <label class="d-block w-100">
                        <a class="btn btn-secondary w-100 rounded-0">
                            <span class="icon-file-image"></span>
                        </a>
                        <input type="file" name="featured-image" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" class="d-none">
                    </label>

                    <button type="submit" class="btn btn-primary w-100 rounded-0 d-none btn-hide-icon-active">
                        <span class="btn__icon icon-floppy"></span>
                    </button>
                </div>

                <input type="hidden" name="action" value="upload_collection_thumbnail_image">

                <input type="hidden" name="term_id" value="<?php echo esc_attr( $term_id );?>">
            </form>
        <?php endif;?>
    </div>
</div>