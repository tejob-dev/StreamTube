<?php
/**
 *
 * The next/prev post navigation
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
    'in_same_term'      =>  true,
    'excluded_terms'    =>  '',
    'taxonomy'          =>  'categories'
) );

/**
 *
 * Filter the args
 * 
 */
$args = apply_filters( 'streamtube/next_prev_posts', $args );

extract( $args );
?>
<div class="next-prev-nav d-flex gap-2">
    <?php if( $previous_post = get_previous_post( $in_same_term, $excluded_terms, $taxonomy ) ): ?>
        <?php printf(
            '<a id="previous-post-link" class="btn p-1 rounded-1 bg-light border" href="%s" title="%s">',
            esc_url( get_permalink( $previous_post ) ),
            esc_attr( $previous_post->post_title )
        );?>
            <span class="text-secondary icon-left-open"></span>
        </a>
    <?php endif;?>

    <?php if( $next_post = get_next_post( $in_same_term, $excluded_terms, $taxonomy ) ): ?>
        <?php printf(
            '<a id="next-post-link" class="btn p-1 rounded-1 bg-light border" href="%s" title="%s">',
            esc_url( get_permalink( $next_post ) ),
            esc_attr( $next_post->post_title )
        );?>
            <span class="text-secondary icon-right-open"></span>
        </a>
    <?php endif;?>
</div>