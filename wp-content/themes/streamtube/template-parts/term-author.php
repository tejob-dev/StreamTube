<?php
/**
 *
 * The term author name template file
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      2.2.1
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

global $term;

$user_data = null;

if( "" != $term_author = get_term_meta( $term->term_id, 'user_id', true ) ){
    $user_data = get_userdata( $term_author );
}
if( ! $user_data ){
    return;
}
?>
<div class="post-meta__author">
    <?php printf( 
        '<a href="%s" title="%s">', 
            esc_url( get_author_posts_url( $user_data->ID ) ), 
            esc_attr( $user_data->display_name )
        );
    ?>
        <span class="post-meta__icon icon-user-o"></span>
        <?php printf(
            '<span class="post-meta__text">%s</span>',
            $user_data->display_name
        );?>
    </a>
</div>