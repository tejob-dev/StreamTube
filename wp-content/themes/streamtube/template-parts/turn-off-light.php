<?php
/**
 *
 * The Turn Off Light template file
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
?>
<?php printf(
    '<button id="turn-off-light" class="btn p-1 rounded-1 bg-light border" title="%1$s" data-on-title="%1$s" data-off-title="%2$s">',
    esc_attr__( 'Turn off light', 'streamtube' ),
    esc_attr__( 'Turn on light', 'streamtube' ),
);?>
    <span class="text-secondary icon-lightbulb"></span>
</button>