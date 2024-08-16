<?php
/**
 *
 * The preloader template file
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
<div class="preloader" id="preloader">
    <div class="position-absolute top-50 start-50 translate-middle">
        <?php get_template_part( 'template-parts/spinner', '', array(
            'type'  =>  'danger'
        ) ); ?>
    </div>
</div>