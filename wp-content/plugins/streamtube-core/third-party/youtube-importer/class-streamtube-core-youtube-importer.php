<?php
/**
 * Define the Youtube Importer functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class StreamTube_Core_Youtube_Importer{

    /**
     *
     * Holds the admin object
     * 
     * @var object
     *
     * @since 2.0
     * 
     */
    public $admin;

    /**
     *
     * Holds the post_type object
     * 
     * @var object
     *
     * @since 2.0
     * 
     */
    public $post_type;

    /**
     *
     * Holds the options object
     * 
     * @var object
     *
     * @since 2.0
     * 
     */
    public $options;

    /**
     *
     * Holds the Youtube API object
     * 
     * @var object
     *
     * @since 2.0
     * 
     */
    public $api;

    /**
     *
     * Class contructor
     *
     * @since 2.0
     * 
     */
    public function __construct(){

        $this->api = new stdClass();

        $this->load_dependencies();
    }

    /**
     *
     * Include file
     * 
     * @param  string $file
     *
     * @since 2.0
     * 
     */
    private function include_file( $file ){
        require_once plugin_dir_path( __FILE__ ) . $file;
    }

    /**
     *
     * Load dependencies
     *
     * @since 2.0
     * 
     */
    public function load_dependencies(){

        $this->include_file( 'class-streamtube-core-youtube-api.php' );

        $this->include_file( 'class-streamtube-core-youtube-api-search.php' );

        $this->include_file( 'class-streamtube-core-youtube-api-videos.php' );

        $this->api->search = new StreamTube_Core_Youtube_API_Search();

        $this->api->video = new StreamTube_Core_Youtube_API_Videos();

        $this->include_file( 'class-streamtube-core-youtube-admin.php' );

        $this->admin = new StreamTube_Core_Youtube_Importer_Admin();

        $this->include_file( 'class-streamtube-core-youtube-post-type.php' );

        $this->post_type = new StreamTube_Core_Youtube_Importer_Post_Type();

        $this->include_file( 'class-streamtube-core-youtube-options.php' );

        $this->options = new StreamTube_Core_Youtube_Importer_Options();
    }

    /**
     *
     * Set video thumbnail
     * 
     * @param int $post_id
     * @param array $item
     *
     * @since 2.0
     * 
     */
    private function set_post_thumbnail( $post_id, $thumbnail_url ){
        if( ! function_exists( 'media_sideload_image' ) ){
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');              
        }

        $thumbnail_id = media_sideload_image( $thumbnail_url, $post_id, null, 'id' );

        if( is_int( $thumbnail_id ) ){
            set_post_thumbnail( $post_id, $thumbnail_id );
        }

        return $thumbnail_id;
    }

    /**
     *
     * Set video terms
     * 
     * @param int $post_id
     * @param int $importer_id
     *
     * @since 2.0
     * 
     */
    private function set_post_terms( $post_id, $importer_id ){
        $taxonomies = get_object_taxonomies( Streamtube_Core_Post::CPT_VIDEO, 'object' );

        foreach ( $taxonomies as $tax => $object ){

            $fields = is_taxonomy_hierarchical( $tax ) ? 'ids' : 'slugs';

            $terms = wp_get_post_terms( $importer_id, $tax, array(
                'fields' => $fields
            ) );

            if( $terms ){
                wp_set_post_terms( $post_id, $terms, $tax, true );
            }
        }
    }

    /**
     *
     * Check if video imported
     * 
     * @param  string  $yt_id
     * @return true|false
     *
     * @since 2.0
     * 
     */
    public function is_existed( $yt_id, $importer_id = '' ){
        global $wpdb;

        $settings = $this->admin->get_settings( $importer_id );

        if( empty( $settings['post_type'] ) ){
            $settings['post_type'] = Streamtube_Core_Post::CPT_VIDEO;
        }

        $sql = "
            SELECT * FROM $wpdb->postmeta AS meta
            INNER JOIN $wpdb->posts AS posts ON posts.ID = meta.post_id
            WHERE posts.post_type = %s AND meta_value LIKE '%s' AND meta_key = 'video_url'
        ";

        $results = $wpdb->query( $wpdb->prepare( $sql, $settings['post_type'], "%{$yt_id}%" ) );

        return $results;
    }

    /**
     *
     * Search Youtube content
     * 
     * @param  integer $importer_id
     * @param  array   $settings
     * @return Wp_Error|Array
     *
     * @since 2.0
     * 
     */
    public function search_content( $importer_id = 0, $settings = array() ){

        $settings = wp_parse_args( $settings, $this->admin->get_settings( $importer_id ) );

        if( $settings['publishedAfter'] ){
            $settings['publishedAfter'] = date( 'Y-m-d\TH:i:s\Z', strtotime( $settings['publishedAfter'] ));
        }

        if( $settings['publishedBefore'] ){
            $settings['publishedBefore'] = date( 'Y-m-d\TH:i:s\Z', strtotime( $settings['publishedBefore'] ));
        }

        if( ! array_key_exists( 'searchIn', $settings ) ){
            $settings['searchIn'] = 'channel';
        }

        if( $settings['searchIn'] == 'playlist' ){
            $settings['playlistId'] = $settings['channelId'];
            unset( $settings['channelId'] );

            $this->api->search->set_api_endpoint( '/playlistItems' );
        }

        foreach ( $settings as $key => $value ) {
            if( ! $value || empty( $value ) ){
                unset( $settings[ $key ] );
            }
        };

        $response = $this->api->search->get_data( $settings['apikey'], $settings ); 

        if( ! is_wp_error( $response ) ){
            update_post_meta( $importer_id, '_total', $this->api->search->get_total_results( $response ) );
        }

        return $response;
    }

    /**
     *
     * Import Youtube content
     * 
     * @param  array $yt_video_ids
     * @param  int   $importer_id
     * @return Wp_Error|Array
     *
     * @since 2.0
     * 
     */
    public function import_content( $yt_video_ids, $importer_id = 0 ){

        error_reporting(0);
        set_time_limit(0);

        $posts = array();

        $settings = $this->admin->get_settings( $importer_id );

        if( ! $yt_video_ids ){
            return new WP_Error( 
                'no_yt_video_id',
                esc_html__( 'No Youtube Video ID', 'streamtube-core' )
            );
        }

        $response = $this->api->video->get_data( $settings['apikey'], array(
            'id'    =>  is_array( $yt_video_ids ) ? join( ',', $yt_video_ids ) : $yt_video_ids
        ) );

        if( is_wp_error( $response ) ){
           return $response;
        }

        if( $response['items'] && count( $response['items'] ) > 0 ){
            for ( $i=0; $i < count( $response['items'] ); $i++) {

                if( ! $this->is_existed( $this->api->video->get_item_id( $response['items'][$i] ), $importer_id ) ){

                    $video_url          = $this->api->video->get_item_url( $response['items'][$i] );

                    $statistics         = $this->api->video->get_item_statistics( $response['items'][$i] );
                    $content_details    = $this->api->video->get_item_content_details( $response['items'][$i] );

                    if( array_key_exists( 'duration', $content_details ) ){
                        $content_details['_length'] = streamtube_convert_youtube_duration( $content_details['duration'] );
                    }

                    $post_args = array(
                        'post_title'        =>  $this->api->video->get_item_title( $response['items'][$i] ),
                        'post_content'      =>  $this->api->video->get_item_description( $response['items'][$i] ),
                        'post_status'       =>  $settings['post_status'],
                        'post_author'       =>  $settings['post_author'],
                        'meta_input'        =>  array_merge( $statistics, $content_details, compact( 'video_url' ) )
                    );

                    if( empty( $settings['post_type'] ) ){
                        $settings['post_type'] = Streamtube_Core_Post::CPT_VIDEO;
                    }

                    if( is_post_type_viewable( $settings['post_type'] ) ){
                        $post_args['post_type'] = trim( $settings['post_type'] );
                    }

                    if( $post_args['post_type'] != Streamtube_Core_Post::CPT_VIDEO ){
                        if( ! empty( $settings['post_meta_field'] ) ){
                            $meta_key = trim( sanitize_key( $settings['post_meta_field'] ) );
                            $post_args['meta_input'] = array_merge( $post_args['meta_input'], array(
                                $meta_key => $video_url
                            ) );                            
                        }else{
                            $post_args['post_content'] = $video_url . '<br/>' . $post_args['post_content'];
                        }
                    }

                    /**
                     *
                     * Filter post args
                     * 
                     */
                    $post_args = apply_filters( 'streamtube/core/youtube_importer/post_args', $post_args, $response['items'][$i], $response, $settings );

                    $post_id = wp_insert_post( $post_args, true );

                    if( ! is_wp_error( $post_id ) ){

                        if( $maybe_thumbnail_url = $this->api->video->get_item_thumbnail_url( $response['items'][$i] ) ){
                            $this->set_post_thumbnail( $post_id, $maybe_thumbnail_url );
                        }

                        $this->set_post_terms( $post_id, $importer_id );

                        if( $settings['post_tags'] ){
                            $tags = $this->api->video->get_item_tags( $response['items'][$i] );

                            if( $tags ){

                                if( $post_args['post_type'] == Streamtube_Core_Post::CPT_VIDEO ){
                                    wp_set_post_terms( $post_id, $tags, 'video_tag', true );
                                }
                                elseif( $post_args['post_type'] == 'post' ){
                                    wp_set_post_terms( $post_id, $tags, 'post_tag', true );
                                }else{
                                    $taxonomy_tag = apply_filters( 'streamtube/core/youtube_importer/taxonomy_tag', false, $importer_id, $response['items'][$i], $response, $settings );

                                    if( taxonomy_exists( $taxonomy_tag ) ){
                                        wp_set_post_terms( $post_id, $tags, $taxonomy_tag, true );
                                    }
                                }                               
                            }
                        }

                        if( $importer_id ){
                            update_post_meta( $post_id, 'yt_importer_id', $importer_id );
                        }

                        /**
                         *
                         * Fires after video imported
                         *
                         * @since 2.0
                         * 
                         */
                        do_action( 
                            'streamtube/core/youtube_importer/imported', 
                            $post_id, 
                            $importer_id, 
                            $response['items'][$i], 
                            $response, 
                            $settings 
                        );

                        $posts[$this->api->video->get_item_id( $response['items'][$i] )] = get_permalink( $post_id );
                    }
                }
            }
        }

        return compact( 'response', 'posts' );
    }

    /**
     *
     * Get imported videos from given importer ID
     * 
     * @param  int $importer_id
     * @return false|array
     *
     * @since 2.0
     * 
     */
    public function get_imported_videos( $importer_id, $number = 5 ){
        global $wpdb;

        $results = $wpdb->get_results(
            $wpdb->prepare( 
                "SELECT post_id FROM $wpdb->postmeta where meta_key = %s and meta_value = %d ORDER BY post_id DESC LIMIT %d",
                'yt_importer_id',
                $importer_id,
                $number
            ),
            OBJECT
        );

        return $results;
    }

    /**
     * AJAX search content
     *
     * @since 2.0
     */
    public function ajax_search_content(){

        if( ! current_user_can( 'administrator' ) || ! is_array( $_POST['yt_importer'] ) ){
            exit;
        }

        $params = $_POST['yt_importer'];

        if( isset( $_POST['next_page_token'] ) ){
            $params['pageToken'] = wp_unslash( $_POST['next_page_token'] );
        }

        $response = $this->search_content( $_POST['post_ID'], $params );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        if( $this->api->search->get_item_ids( $response ) ){

            $items = $response['items'];

            ob_start();

            for ( $i=0; $i < count( $items ); $i++) { 
                load_template( plugin_dir_path( __FILE__ ) . 'admin/item-loop.php', false, array_merge( $items[$i], array(
                    'importer_id'   =>  $_POST['post_ID']
                ) ) );
            }

            $li = ob_get_clean();

            $output = sprintf(
                '<ul class="yt-video-list">%s</ul>',
                $li
            );

            if( $this->api->search->get_next_page_token( $response ) ){
                $output .= sprintf(
                    '<button type="button" class="d-block w-100 button button-primary button-search-youtube button-yt-next-page" data-next-page-token="%s">%s</button>',
                    esc_attr( $this->api->search->get_next_page_token( $response ) ),
                    esc_html__( 'Load more', 'streamtube-core' )
                );
            }

            wp_send_json_success( $output );
            
        }

        wp_send_json_error( new WP_Error(
            'no_content',
            esc_html__( 'No content was found', 'streamtube-core' )
        ) ); 
    }

    /**
     * AJAX import content
     *
     * @since 2.0
     */
    public function ajax_import_content(){

        if( ! current_user_can( 'administrator' ) || ! isset( $_POST['item_id'] ) ){
            exit;
        }

        $item_id        = isset( $_POST['item_id'] )        ? $_POST['item_id']             : '';
        $importer_id    = isset( $_POST['importer_id'] )    ? (int)$_POST['importer_id']    : 0;

        $response       = $this->import_content( $item_id, $importer_id );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( array_merge( $response, array(
            'message'   =>  esc_html__( 'Imported', 'streamtube-core' )
        ) ) );
    }

    /**
     *
     * AJAX bulk import
     * 
     */
    public function ajax_bulk_import_content(){

        if( ! current_user_can( 'administrator' ) ){
            exit;
        }        

        if( ! isset( $_POST['yt_ids'] ) || count( $_POST['yt_ids'] ) == 0 ){
            wp_send_json_error( new WP_Error(
                'no_content',
                esc_html__( 'No content were submitted', 'streamtube-core' )
            ) );
        }

        $response       = $this->import_content( $_POST['yt_ids'], $_POST['post_ID'] );

        if( is_wp_error( $response ) ){
            wp_send_json_error( $response );
        }

        wp_send_json_success( array_merge( $response, array(
            'message'   =>  esc_html__( 'Imported', 'streamtube-core' )
        ) ) );
    }

    /**
     *
     * Run cron job bulk import content
     * 
     * @since 2.0
     */
    public function run_bulk_import_content( $importer_id, $key = '' ){

        $last_check = current_time('timestamp');

        $settings = $this->admin->get_settings( $importer_id );

        if( ! $settings['enable'] ){
            return new WP_Error(
                'disabled_importer',
                esc_html__( 'This importer is disabled', 'streamtube-core' )
            );            
        }

        if( ! $key || $key != $settings['cron_tag_key'] ){
            return new WP_Error(
                'invalid_key',
                esc_html__( 'Invalid Cron Tab Key', 'streamtube-core' )
            );
        }

        if( (int)$settings['update_number'] == 0 ){
            return new WP_Error(
                'invalid_number',
                esc_html__( 'Invalid Number', 'streamtube-core' )
            );
        }

        $settings['maxResults'] = (int)$settings['update_number'];

        if( "" != $next_page_token = get_post_meta( $importer_id, 'next_page_token', true ) ){
            $settings['pageToken'] = $next_page_token;
        }

        $response = $this->search_content( $importer_id, $settings );

        if( is_wp_error( $response ) ){
            return $response;
        }

        update_post_meta( $importer_id, 'last_check', $last_check );        

        $item_ids = $this->api->search->get_item_ids( $response );

        if( ! $item_ids ){
            return new WP_Error(
                'no_content',
                esc_html__( 'No content was found', 'streamtube-core' )
            );
        }

        update_post_meta( $importer_id, 'next_page_token', $this->api->search->get_next_page_token( $response ) );

        $results = $this->import_content( $item_ids, $importer_id );

        if( is_wp_error( $results ) ){
            return $results;
        }

        /**
         *
         * Fires after importing completed
         *
         * @param int $importer_id
         * @param array $results
         *
         * @since 2.0
         * 
         */
        do_action( 'streamtube/core/youtube_importer/bulk_imported', $importer_id, $results );

        return compact( 'response', 'results', 'last_check' );
    }

    /**
     *
     * Cron job task
     *
     * @since 2.0
     * 
     */
    public function template_run_bulk_import_content( $template ){

        if( ! is_singular( StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE ) ){
            return $template;
        }

        if( ! isset( $_GET['key'] ) ){
            return locate_template( array( '404.php' ) );
        }

        $results = $this->run_bulk_import_content( get_the_ID(), $_GET['key'] );

        if( is_wp_error( $results ) ){
            echo $results->get_error_message();
            exit;
        }

        $count = count( $results['results']['posts'] );

        printf( 
            _n( '%s post has been imported.', '%s posts have been imported.', $count, 'streamtube-core' ), 
            number_format_i18n( $count ) 
        ) . '<br/>';

        printf(
            esc_html__( 'Next Page: %s', 'streamtube-core' ),
            get_post_meta( get_the_ID(), 'next_page_token', true )
        )  . '<br/>';

        echo '<ol>';

            foreach ( $results['results']['posts'] as $key => $value ) {
                printf(
                    '<li><a target="_blank" href="%s"><strong>%s</strong> ==> %s</a></li>',
                    esc_url( $value ),
                    $key,
                    $value
                );
            }        

        echo '</ol>';
        exit;

    }

    /**
     *
     * AJAX bulk import button handler
     * 
     * @since 2.0
     */
    public function ajax_run_bulk_import_content(){

        if( ! current_user_can( 'administrator' ) ){
            exit;
        }

        $results = $this->run_bulk_import_content( $_POST['importer_id'], $_POST['key'] );

        if( is_wp_error( $results ) ){
            wp_send_json_error( $results );
        }

        $count = count( $results['results']['posts'] );

        $message = sprintf( 
            _n( '%s post has been imported.', '%s posts have been imported.', $count, 'streamtube-core' ), 
            number_format_i18n( $count ) 
        );

        $last_check = sprintf(
            esc_html__( '%s ago', 'streamtube-core' ),
            human_time_diff( $results['last_check'], current_time('timestamp') )
        );

        wp_send_json_success( compact( 'message', 'last_check' ) );
    }

    public function ajax_get_tax_terms(){

        $results = array();

        if( ! current_user_can( 'administrator' ) ){
            exit;
        }

        $data = wp_parse_args( $_GET, array(
            'search'    =>  '',
            'tax'       =>  ''
        ) );

        if( ! $data['search'] || ! $data['tax'] ){
            exit;
        }

        $terms = get_terms( array(
            'taxonomy'      =>  $data['tax'],
            'hide_empty'    =>  false,
            'name__like'    =>  sanitize_text_field( $data['search'] ),
            'number'        =>  20
        ) );

        if( $terms ){
            foreach( $terms as $term ){
                $results[] = array(
                    'id'    =>  $term->slug,
                    'text'  =>  $term->name
                );
            }
        }

        wp_send_json_success( compact( 'results' ) );
    }

    /**
     * Import YouTube Embed using API
     *
     * @param int|WP_Post $post
     * @param string $source
     * 
     */
    public function import_youtube_embed( $post, $source ){

        $maybe_youtube_id = Streamtube_Core_oEmbed::get_youtube_id( $source );

        if( ! $maybe_youtube_id ){
            return $post;
        }

        $response = $this->api->video->get_data( get_option( 'youtube_api_key' ), array(
            'id'    =>  $maybe_youtube_id
        ) );

        if( is_wp_error( $response ) ){
            return $response;
        }

        if( ! is_object( $post ) ){
            $post = get_post( $post );
        }

        $statistics         = $this->api->video->get_item_statistics( $response['items'][0] );
        $content_details    = $this->api->video->get_item_content_details( $response['items'][0] );
        $tags               = $this->api->video->get_item_tags( $response['items'][0] );

        $metadata = array_merge( $statistics, $content_details );

        if( array_key_exists( 'duration', $metadata ) ){
            $metadata['_length'] = streamtube_convert_youtube_duration( $metadata['duration'] );
        }

        foreach ( $metadata as $key => $value ) {
            update_post_meta( $post->ID, $key, $value );
        }

        if( $tags && apply_filters( 'streamtube/core/import_embed_youtube_tags', true ) === true ){
            wp_set_post_terms( $post->ID, $tags, 'video_tag' );
        }

        wp_update_post( array(
            'ID'            => $post->ID,
            'post_content'  => wpautop( $this->api->video->get_item_description( $response['items'][0] ) )
        ) );

        /**
         *
         * Fires after importing youtube url
         *
         * @param object WP_Post $post
         * @param string $source
         * 
         */
        do_action( 'streamtube/core/youtube_importer/imported_youtube_embed', $post, $source );
    }
}