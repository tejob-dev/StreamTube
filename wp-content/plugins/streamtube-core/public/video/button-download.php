<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="button-group button-group-download">
    <?php printf(
        '<a download="%s" target="_blank" href="%s" class="button-download px-1 btn shadow-none position-relative" title="%s">',
        esc_attr( $args['filename'] ),
        esc_url( $args['file_url'] ),
        esc_attr( $args['button_label'] )
    );?>

        <?php if( $args['button_icon'] ): ?>

            <?php printf(
                '<span class="btn__icon %s"></span>',
                esc_attr( $args['button_icon'] )
            );?>

        <?php endif;?>

        <?php if( (int)$args['count'] > 0 ):?>
            <span class="btn__badge badge bg-secondary position-absolute">
                <?php echo number_format_i18n( $args['count'] );?>
            </span>
        <?php endif;?>
    </a>
</div>