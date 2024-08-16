<?php
/**
 * The post author meta template
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
<?php if( has_category() ):?>
    <div class="post-meta__categories post-category">
        <?php the_category( '<span class="sep mx-2">/</span>' );?>
    </div>
<?php endif;