<?php
/**
 * The post large image template file
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
$image_url = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), $args['thumbnail_size'] ) : '';

printf(
    '<div class="post-img fullwidth bg-cover %1$s" style="background-image: url(%2$s)">',
    $image_url ? 'has-image' : 'no-image',
    $image_url ? $image_url : ''
);?>

    <div class="post-header py-4">

        <div class="container">

            <article>

                <div class="post-meta">   
                    
                    <?php if( get_option( 'blog_post_category', 'on' ) ){
                        get_template_part( 'template-parts/post-category' );
                    }?>

                    <?php streamtube_breadcrumbs(); ?>
                    
                    <?php
                         the_title(
                            '<h1 class="post-meta__title post-title post-title-xxl py-2">', '</h1>'
                        ); 
                    ?>

                    <?php 
                    if( get_post_type() != 'page' ){
                        get_template_part( 'template-parts/blog', 'meta', $args );
                    }?>

                </div>

            </article>

        </div>
    </div>

    <div class="bg-overlay"></div>

</div><!--.post-img fullwidth-->
