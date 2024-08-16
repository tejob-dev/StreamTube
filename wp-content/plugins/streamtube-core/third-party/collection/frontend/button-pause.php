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

printf(
    '<button class="%s" data-action="%s" data-params="%s">',
    esc_attr( $args['classes'] ),
    'set_collection_activity',
    esc_attr( $args['term_id'] )
);?>
    <?php printf(
        '<span class="btn__icon %s text-secondary h6 m-0 me-3"></span>',
        $args['icon']
    );?>
    <span class="btn__text text-body">
        <?php echo $args['text']; ?>
    </span>
</button>