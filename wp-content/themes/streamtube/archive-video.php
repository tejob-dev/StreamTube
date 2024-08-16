<?php
/**
 * The template for displaying video archive
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
    
$template = streamtube_get_archive_template_settings();

extract( $template );

get_header();
?>
    <div class="page-main">   

        <div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?>"> 

            <?php streamtube_breadcrumbs(); ?>
   
            <?php
            /**
             *
             * Fires before page header
             *
             * @since 1.0.0
             * 
             */
            do_action( 'streamtube/archive/video/page_header/before' );
            ?>

            <div class="page-header d-flex align-items-center justify-content-between py-4">

                <?php
                /**
                 *
                 * Fires before page title
                 * 
                 */
                do_action( 'streamtube/archive/video/page_title/before' );
                ?>

                <?php the_archive_title( '<h1 class="page-title widget-title h5">', '</h1>' )?>

                <?php
                /**
                 *
                 * Fires after page title
                 * 
                 */                
                do_action( 'streamtube/archive/video/page_title/after' );
                ?>

                <div class="post-sort">                    
                    <?php get_template_part( 'template-parts/sortby' ); ?>
                </div>
            </div>

            <?php
            /**
             *
             * Fires before page header
             *
             * @since 1.0.0
             * 
             */
            do_action( 'streamtube/archive/video/page_header/after' );
            ?>

            <?php

            $query_args = array_merge( $GLOBALS['wp_query']->query_vars, array(
                'show_post_date'        =>  $post_date,
                'show_post_comment'     =>  $post_comment,
                'show_author_name'      =>  $author_name,
                'show_post_view'        =>  streamtube_is_google_analytics_connected(),
                'hide_empty_thumbnail'  =>  true,
                'thumbnail_size'        =>  $thumbnail_size,
                'posts_per_page'        =>  (int)$posts_per_column * (int)$rows_per_page,
                'paged'                 =>  get_query_var( 'page' ),
                'layout'                =>  'grid',
                'col_xxl'               =>  (int)$posts_per_column,
                'col_xl'                =>  (int)$col_xl,
                'col_lg'                =>  (int)$col_lg,
                'col_md'                =>  (int)$col_md,
                'col_sm'                =>  (int)$col_sm,
                'col'                   =>  (int)$col,
                'author_avatar'         =>  $author_avatar,
                'avatar_size'           =>  'md',
                'margin_bottom'         =>  4,
                'pagination'            =>  $pagination,
                'not_found_text'        =>  esc_html__( 'No posts were found', 'streamtube' ),
                'verified_users_only'   =>  get_option( 'verified_users_only' )
            ) );

            $query_args['tax_query'] = $GLOBALS['wp_query']->tax_query->queries;

            /**
             *
             * Filter the query_args
             * 
             * @param  array $query_args
             *
             * @since  1.0.0
             * 
             */
            $query_args = apply_filters( 'streamtube/archive/video/query_args', $query_args );
            ?>

            <?php
            /**
             *
             * Fires before archive content
             *
             * @since 1.0.0
             * 
             */
            do_action( 'streamtube/archive/video/before' );
            ?>              

            <div class="archive-video">
                <?php the_widget( 'Streamtube_Core_Widget_Posts', $query_args, array() ); ?>
            </div>

            <?php
            /**
             *
             * Fires after archive content
             *
             * @since 1.0.0
             * 
             */
            do_action( 'streamtube/archive/video/after' );
            ?>             

    	</div>
    </div>
<?php 
get_footer();