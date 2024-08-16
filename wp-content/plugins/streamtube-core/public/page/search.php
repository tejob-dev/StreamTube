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

$not_found_text     = esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.','streamtube-core' );

$template = streamtube_get_search_template_settings();

extract( $template );

?>
<?php get_header();?>

    <div class="page-header bg-white px-2 border-bottom pt-4 mb-3">
        <div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?>">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="page-title h5"><?php printf( esc_html__( 'Search result for "%s"', 'streamtube-core' ), get_search_query() ); ?></h1>

                <div class="ms-auto">
                    <?php get_template_part( 'template-parts/sortby' ); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="page-main py-3">

        <div class="<?php echo esc_attr( join( ' ', streamtube_get_container_classes( $content_width ) ) ); ?>">

            <?php

            $content_types = array_keys( Streamtube_Core_Widget_Filter_Content_Type::get_content_types() );

            global $registered_content_types;

            if( $registered_content_types ){
                $content_types = $registered_content_types;
            }

            $content_type = isset( $_REQUEST['content_type'] ) ? wp_unslash( $_REQUEST['content_type'] ) : 'any';

            if( is_array( $content_type ) ){
                $content_type = $content_type[0];
            }

            if( is_string( $content_type ) && $content_type != 'any' ){
                if( ! in_array( $content_type, $content_types ) ){
                    $content_type = $content_types[0];
                }
            }

            $query_args = array_merge( $GLOBALS['wp_query']->query_vars, array(
                'margin_bottom'         =>  4,
                'show_post_date'        =>  $post_date,
                'show_post_comment'     =>  $post_comment,
                'show_author_name'      =>  $author_name,         
                'hide_empty_thumbnail'  =>  $hide_empty_thumbnail,
                'thumbnail_size'        =>  $thumbnail_size,
                'thumbnail_ratio'       =>  $thumbnail_ratio,
                'posts_per_page'        =>  (int)$posts_per_column * (int)$rows_per_page,
                'paged'                 =>  get_query_var( 'page' ),
                'layout'                =>  $layout,
                'col_xxl'               =>  (int)$posts_per_column,
                'col_xl'                =>  (int)$col_xl,
                'col_lg'                =>  (int)$col_lg,
                'col_md'                =>  (int)$col_md,
                'col_sm'                =>  (int)$col_sm,
                'col'                   =>  (int)$col,
                'author_avatar'         =>  $author_avatar,
                'avatar_size'           =>  $layout != 'grid' ? 'sm' : 'md',
                'post_excerpt_length'   =>  $post_excerpt_length,
                'pagination'            =>  $pagination,
                'not_found_text'        =>  $not_found_text,
                'date'                  =>  isset( $_REQUEST['date'] ) ? wp_unslash( $_REQUEST['date'] ) : '',
                'content_cost'          =>  isset( $_REQUEST['content_cost'] ) ? wp_unslash( $_REQUEST['content_cost'] ) : '',
                'level__in'             =>  isset( $_REQUEST['pmp_level'] ) ? wp_unslash( $_REQUEST['pmp_level'] ) : '',
                'post_type'             =>  $content_type,
                'auto_tax_query'        =>  true,
                'verified_users_only'   =>  get_option( 'verified_users_only' )
            ) );

            /**
             *
             * Filter the query_args
             * 
             * @param  array $query_args
             *
             * @since  1.0.0
             * 
             */
            $query_args = apply_filters( "streamtube/archive/search/query_args", $query_args, $content_types );            

            /**
             *
             * Filter the query_args
             * 
             * @param  array $query_args
             *
             * @since  1.0.0
             * 
             */
            $query_args = apply_filters( "streamtube/archive/search/{$content_type}/query_args", $query_args );

            switch ( $query_args['post_type'] ) {

                case 'product':
                    the_widget( 'Streamtube_Core_Widget_Posts', array_merge( $query_args, array(
                        'thumbnail_ratio'   =>  'default'
                    ) ), array() );
                break;

                case 'topic':
                case 'reply':
                    ?>
                    <div class="container bbp-search-container p-0">
                        <?php 
                        if( get_search_query() ){
                            bbp_get_template_part( 'content', 'search' );    
                        }else{
                        ?>
                            <div class="widget widget-not-found">
                                <div class="not-found p-3 text-center text-muted fw-normal h6">
                                    <p>
                                        <?php echo $not_found_text; ?>
                                    </p>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                break;

                case 'video_collection':

                    $query_args = array(
                        'taxonomy'              =>  array( Streamtube_Core_Collection::TAX_COLLECTION ),
                        'number'                =>  $query_args['posts_per_page'],
                        'public_only'           =>  true,
                        'searchable'            =>  true,
                        'search'                =>  get_search_query(),
                        'thumbnail_size'        =>  $thumbnail_size,
                        'thumbnail_ratio'       =>  $thumbnail_ratio,
                        'layout'                =>  'playlist',
                        'pagination'            =>  $pagination,
                        'hide_empty_thumbnail'  =>  true,
                        'term_author'           =>  true,
                        'term_status'           =>  false,                        
                        'col_xxl'               =>  (int)$posts_per_column,
                        'col_xl'                =>  (int)$col_xl,
                        'col_lg'                =>  (int)$col_lg,
                        'col_md'                =>  (int)$col_md,
                        'col_sm'                =>  (int)$col_sm,
                        'col'                   =>  (int)$col,
                        'hide_if_empty'         =>  true
                    );

                    /**
                     *
                     * Filter the query_args
                     * 
                     * @param  array $query_args
                     *
                     * @since  1.0.0
                     * 
                     */
                    $query_args = apply_filters( 'streamtube/archive/search/collection/query_args', $query_args );                    
                    ob_start();

                    the_widget( 'Streamtube_Core_Widget_Term_Grid', $query_args, array() );

                    $output = trim(ob_get_clean());

                    if( empty( $output ) ){
                        ?>
                        <div class="widget widget-not-found">
                            <div class="not-found p-3 text-center text-muted fw-normal h6">
                                <p>
                                    <?php echo $not_found_text; ?>
                                </p>
                            </div>
                        </div>
                        <?php
                    }else{
                        echo $output;
                    }

                break;

                case 'user':

                    the_widget( 'Streamtube_Core_Widget_User_Grid', array(
                        'search'        =>  get_search_query(),
                        'search_form'   =>  false,
                        'col_xxl'       =>  (int)$posts_per_column,
                        'col_xl'        =>  (int)$col_xl,
                        'col_lg'        =>  (int)$col_lg,
                        'col_md'        =>  (int)$col_md,
                        'col_sm'        =>  (int)$col_sm,
                        'col'           =>  (int)$col
                    ), array() );
                break;
                
                default:
                    the_widget( 'Streamtube_Core_Widget_Posts', $query_args, array() );
                break;
            }
            
            ?>

    	</div>
    </div>

<?php get_footer();?>