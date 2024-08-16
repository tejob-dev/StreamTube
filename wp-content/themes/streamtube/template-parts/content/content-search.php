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

$args = wp_parse_args( $args, array(
    'post_author'   =>  'on',
    'post_date'     =>  'normal',
    'post_category' =>  'on',
    'post_comment'  =>  'on'
) );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="post-body single-body">

        <?php if( has_post_thumbnail() ) : ?>
            <div class="post-main">
                <a title="<?php echo esc_attr( get_the_title() )?>" href="<?php echo esc_url( get_permalink() )?>">
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail( 'post-thumbnails', array(
                            'class' =>  'img-fluid'
                        ) );?>
                    </div>
                </a>
            </div>
        <?php endif;?>

        <div class="post-bottom p-4 p-sm-5">

            <div class="post-meta">
                <?php get_template_part( 'template-parts/post-category' ); ?> 
                <?php 

                if( ! is_singular() ){
                    the_title(
                        '<h2 class="post-meta__title post-title post-title-xxl"><a class="text-body" title="'. esc_attr( get_the_title() ) .'" href="'. esc_url( get_permalink() ) .'">', '</a></h2>'
                    );
                }
                else{
                     the_title(
                        '<h1 class="post-meta__title post-title post-title-xxl py-2">', '</h1>'
                    );                   
                }
                ?>

                <div class="post-meta__items">

                    <?php get_template_part( 'template-parts/post-author' ); ?>

                    <?php if( $args['post_date'] ){
                       get_template_part( 'template-parts/post-date', $args['post_date'] ); 
                    }?>

                    <?php if( get_comments_number() ): ?>
                        <?php get_template_part( 'template-parts/post-comment', null, array( 'text' => true ) );?>
                    <?php endif;?>
                    
                </div><!--.post-meta__items-->

            </div>

            <div class="post-content mt-4">
                <?php the_excerpt(); ?>            
            </div>

            <?php if( has_tag() && is_singular() ):?>
                <div class="post-tags mt-4">
                    <?php the_tags( '<span class="tagged me-2">'. esc_html__( 'Tagged:', 'streamtube' ) .'</span>', ' ' );?>
                </div>
            <?php endif;?>

            <?php get_template_part( 'template-parts/social', 'share' ); ?>

        </div>        
    </div>

</article>