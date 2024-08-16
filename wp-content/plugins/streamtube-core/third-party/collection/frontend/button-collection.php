<?php
/**
 * The Collection button template file
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
    'classes'       =>  '',
    'title'         =>  esc_html__( 'Add to Collections', 'streamtube-core' ),
    'label'         =>  '',
    'modal'         =>  ! is_user_logged_in() ? 'modal-login' : 'modal-collection',
    'icon'          =>  'icon-folder-open'
) );

?>
<div class="button-group button-group-collection">
    <?php printf(
        '<button type="button" class="btn shadow-none px-1 %s" data-bs-toggle="modal" data-bs-target="#%s" title="%s">',
        esc_attr( $args['classes'] ),
        esc_attr( $args['modal'] ),
        esc_attr( $args['title'] )
    );?>
        <?php printf(
            '<span class="btn__icon %s"></span>',
            $args['icon']
        );?>

        <?php if( $args['label'] ){
            printf(
                '<span class="btn__text text-secondary">%s</span>',
                $args['label']
            );
        }?>
    </button>
</div>
<?php
/**
 *
 * Fires after button loaded
 * 
 */
do_action( 'streamtube/core/collection_button_loaded' );