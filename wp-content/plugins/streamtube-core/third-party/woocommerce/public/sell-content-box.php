<?php
/**
 *
 * The Woocommerce Sell Content template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.3
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

?>
<div class="widget widget-sell-content shadow-sm rounded bg-white border">

    <div class="widget-title-wrap d-flex m-0 p-3 bg-light">
        <h2 class="widget-title no-after m-0">
            <?php esc_html_e( 'Sell Content', 'streamtube-core' );?>
        </h2>
    </div>

    <div class="widget-content p-3">
        <?php
        load_template( trailingslashit( dirname( plugin_dir_path( __FILE__ ) ) ) . 'admin/sell-content-box.php' );
        ?>
    </div>   

</div>