<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

global $post;

$post_id = $post->ID;

$is_admin = is_admin() ? true : false;

$location = array_merge( WP_Post_Location_Post::get_location( $post_id ), array(
    'max_zoom'  =>  19
) );

?>

<?php if( ! $is_admin ) : ?>  
<form class="form-ajax">
    <button type="submit" name="update" class="btn btn-primary button button-primary">
        <span class="btn__icon icon-floppy"></span>
        <span class="btn__text">
            <?php esc_html_e( 'Update', 'wp-post-location' );?>
        </span>
    </button>
    <input type="hidden" name="action" value="update_location">
<?php endif;?>

    <?php printf(
        '<input type="hidden" name="wp_post_location[post_ID]" id="post_id" value="%s">',
        esc_attr( $post_id )
    );?>

    <?php printf(
        '<input type="hidden" name="wp_post_location[lng]" id="post-longitude" value="%s">',
        esc_attr( $location['lng'] )
    );?>    

    <?php printf(
        '<input type="hidden" name="wp_post_location[lat]" id="post-latitude" value="%s">',
        esc_attr( $location['lat'] )
    );?>

    <?php printf(
        '<input type="hidden" name="wp_post_location[address]" id="post-address" value="%s">',
        esc_attr( $location['address'] )
    );?>

    <?php printf(
        '<input type="hidden" name="wp_post_location[country]" id="post-country" value="%s">',
        esc_attr( $location['country'] )
    );?>    

    <?php printf(
        '<input type="hidden" name="wp_post_location[country_code]" id="post-country-code" value="%s">',
        esc_attr( $location['country_code'] )
    );?>    
     
    <?php printf(
        '<input type="hidden" name="wp_post_location[zoom]" id="post-zoom" value="%s">',
        esc_attr( $location['zoom'] )
    );?>

<?php if( ! $is_admin ) : ?>  
</form>
<?php endif;