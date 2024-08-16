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

$classes = array(
    'd-inline-block',
    'post-terms',
    'post-categories'
);

$classes[] = 'post-' . $args['taxonomy'];

?>
<?php if( has_term( null, $args['taxonomy'], get_the_ID()  ) ): ?>
    <?php printf(
        '<div class="%s">',
        esc_attr( implode(' ', array_unique( $classes ) ) )
    );?>
        <span class="btn__icon icon-folder-open-empty"></span>
        <?php the_terms( get_the_ID(), $args['taxonomy'], null, '<span class="sep mx-2">/</span>' );?>
    </div>
<?php endif;