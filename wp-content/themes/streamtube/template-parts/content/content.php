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

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="post-body <?php echo is_singular() ? 'single-body' : 'part-body'; ?>">

        <?php if( has_post_thumbnail() ) : ?>
            <div class="post-main">
                <a title="<?php echo esc_attr( get_the_title() )?>" href="<?php echo esc_url( get_permalink() )?>">
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail( $args['thumbnail_size'], array(
                            'class' =>  'img-fluid'
                        ) );?>
                    </div>
                </a>
            </div>
        <?php endif;?>

        <div class="post-bottom p-4">

            <div class="post-meta">

                <?php if( is_singular() ){
                    streamtube_breadcrumbs();
                } ?>
                
                <?php get_template_part( 'template-parts/post-category' ); ?> 
                <?php 

                if( ! is_singular() ){
                    the_title(
                        '<h2 class="post-meta__title post-title post-title-xxl py-2"><a class="text-body" title="'. esc_attr( get_the_title() ) .'" href="'. esc_url( get_permalink() ) .'">', '</a></h2>'
                    );
                }
                else{
                     the_title(
                        '<h1 class="post-meta__title post-title post-title-xxl py-2">', '</h1>'
                    );                   
                }
                ?>

                <?php get_template_part( 'template-parts/blog', 'meta', $args );?>

            </div>

            <div class="post-content mt-2">

                <?php if( ! is_single() ):?>

                    <?php if( get_option( 'blog_post_excerpt', 'on' ) ) :?>

                        <?php the_excerpt();?>

                    <?php else:?>

                        <?php the_content( esc_html__( 'Continue reading', 'streamtube' ) );?>

                    <?php endif;?>

                <?php else:?>
                    <?php the_content();?>
                <?php endif;?>

                <div class="clearfix"></div>

                <?php
                wp_link_pages( array(
                    'before'    => sprintf(
                        '<div class="post-nav-links"><span class="post-nav-label me-2">%s</span>',
                        esc_html__( 'Pages', 'streamtube' )
                    ),
                    'after'     => '</div>',                        
                    'type'      => 'list'
                ) );
                ?>                
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