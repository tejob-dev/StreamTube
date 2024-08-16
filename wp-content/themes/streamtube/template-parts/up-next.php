<?php
/**
 *
 * The Up Next template file
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

$is_auto_upnext = true;

if( ! isset( $_COOKIE['upnext'] ) || $_COOKIE['upnext'] == 'off' ){
    $is_auto_upnext = false;
}

?>
<?php printf(
    '<button id="btn-up-next" class="btn p-1 rounded-1 bg-light border btn-upnext %1$s" title="%2$s" data-on-title="%2$s" data-off-title="%3$s">',
    $is_auto_upnext ? 'auto-next' : '',
    esc_attr__( 'Turn on Up Next', 'streamtube' ),
    esc_attr__( 'Turn off Up Next', 'streamtube' )
);?>
    <span class="text-secondary icon-toggle-off"></span>
</button>