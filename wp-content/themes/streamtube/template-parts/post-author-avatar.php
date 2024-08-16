<?php
/**
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

if( ! function_exists( 'streamtube_core_get_user_avatar' ) ){
    return;
}

?>
<?php printf(
    '<div class="post-avatar %s">',
    $args['avatar_name'] ? 'd-flex align-items-center' : ''
);?>
    <?php
    streamtube_core_get_user_avatar( array(
        'user_id'       =>  get_the_author_meta( 'ID' ),
        'link'          =>  true,
        'wrap_size'     =>  $args['avatar_size'],
        'name'          =>  $args['avatar_name']
    ) );
    ?>
</div>