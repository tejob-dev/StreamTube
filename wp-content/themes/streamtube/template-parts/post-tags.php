<?php
/**
 * The post tags template file
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

if( has_term( null, 'video_tag', get_the_ID()  ) ): ?>
    <div class="post-tags mb-3">
        <span class="icon-tags text-muted mr-2"></span>
        <?php the_terms( get_the_ID(), 'video_tag', null, ' ' );?>
    </div>
<?php endif;