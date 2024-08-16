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

global $streamtube;

extract( $args );
?>
<div class="video-watch-later">
    <?php printf(
        '<button type="button" class="%s" data-params="%s" data-action="set_post_watch_later" title="%s">',
        esc_attr( join( ' ', $args['classes'] ) ),
        esc_attr( json_encode( compact( 'post_id', 'term_id' ) ) ),
        $icon == 'icon-clock' ? esc_attr__( 'Add to Watch Later', 'streamtube-core' ) : esc_attr__( 'Remove from Watch Later', 'streamtube-core' )
    );?>
        <?php printf(
            '<span class="btn__icon %s"></span>',
            esc_attr( $icon )
        );?>
    </button>
</div>