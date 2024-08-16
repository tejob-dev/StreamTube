<?php
/**
 * Define the metabox functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
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

class Streamtube_Core_MetaBox {

    /**
     *
     * Holds the nonce name
     * 
     * @var string
     */
    private $nonce = 'nonce';

    protected $Post;

    protected $oEmbed;

    public function __construct(){
        $this->Post     = new Streamtube_Core_Post();

        $this->oEmbed   = new Streamtube_Core_oEmbed();
    }

    private function generate_video_image( $post_id, $source = '' ){

        $thumbnail_id = 0;

        if( has_post_thumbnail( $post_id ) ){
            return;
        }

        if( empty( $source ) ){
            $source = $this->Post->get_source( $post_id );
        }

        if( empty( $source ) ){
            return;
        }

        $this->oEmbed->generate_image( $post_id, $source );
    }

    /**
     *
     * Add metaboxes
     *
     * @since 1.0.0
     * 
     */
    public function add_meta_boxes(){

        add_meta_box(
            'streamtube-video-main-source',
            esc_html__( 'Main Video Source', 'streamtube-core' ),
            array( $this , 'video_data_html' ),
            Streamtube_Core_Post::CPT_VIDEO,
            'advanced',
            'core'
        );

        add_meta_box(
            'streamtube-video-embedding',
            esc_html__( 'Embedding', 'streamtube-core' ),
            array( $this , 'embedding_html' ),
            Streamtube_Core_Post::CPT_VIDEO,
            'advanced',
            'core'
        );        

        add_meta_box(
            'streamtube-altsources-data',
            esc_html__( 'Alternative Sources', 'streamtube-core' ),
            array( $this , 'altsources_html' ),
            Streamtube_Core_Post::CPT_VIDEO,
            'advanced',
            'core'
        );        

        add_meta_box(
            'streamtube-video-text_tracks',
            esc_html__( 'Subtitles', 'streamtube-core' ),
            array( $this , 'video_text_tracks_html' ),
            Streamtube_Core_Post::CPT_VIDEO,
            'advanced',
            'core'
        );

        add_meta_box(
            'streamtube-featured-image-2',
            esc_html__( 'Featured Image 2', 'streamtube-core' ),
            array( $this , 'featured_image_2_html' ),
            Streamtube_Core_Post::CPT_VIDEO,
            'side',
            'core'
        );

        add_meta_box(
            'streamtube-template-options',
            esc_html__( 'Additional Options', 'streamtube-core' ),
            array( $this , 'template_options_template' ),
            array( 'page', 'post', 'video' ),
            'side',
            'core'
        );        
    }

    /**
     *
     * The video data box
     * 
     * @param  object $post
     * @since 1.0.0
     * 
     */
    public function video_data_html( $post ){
        include plugin_dir_path( __FILE__ ) . 'partials/video-data.php';
    }

    /**
     *
     * Embedding metabox
     * 
     * @param  object $post
     * 
     */
    public function embedding_html( $post ){
        include plugin_dir_path( __FILE__ ) . 'partials/embedding.php';
    }

    /**
     *
     * The Altsources box
     * 
     * @param  object $post
     * @since 1.0.0
     * 
     */
    public function altsources_html( $post ){
        include plugin_dir_path( __FILE__ ) . 'partials/altsources.php';
    }

    /**
     *
     * Save video data
     * 
     * @param  int $post_id
     * @since 1.0.0
     * 
     */
    public function video_data_save( $post_id ){

        if ( ! isset( $_POST[ $this->nonce ] ) || ! wp_verify_nonce( $_POST[ $this->nonce ], $this->nonce ) ){
            return;
        }

        if( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( get_post_type( $post_id ) != Streamtube_Core_Post::CPT_VIDEO ) {
            return;
        }

        $source = isset( $_POST['video_url'] ) ? $_POST['video_url'] : '';

        if( isset( $_POST['video_trailer'] ) ){
            $this->Post->update_video_trailer( $post_id, $_POST['video_trailer'] );
        }

        if( $source ){
            $this->Post->update_source( $post_id, $source );
        }

        if( isset( $_POST['thumbnail_image_url_2'] ) ){
            $this->Post->update_thumbnail_image_url_2( $post_id, $_POST['thumbnail_image_url_2'] );
        }

        if( isset( $_POST['_upcoming_date'] ) ){
            $this->Post->update_upcoming_date( $post_id, $_POST['_upcoming_date'] );
        }

        if( isset( $_POST['length'] ) ){
            $this->Post->update_length( $post_id, $_POST['length'] );
        }        

        if( isset( $_POST['aspect_ratio'] ) ){
            $this->Post->update_aspect_ratio( $post_id, $_POST['aspect_ratio'] );
        }

        $this->Post->update_video_vr( $post_id, isset( $_POST['vr'] ) ? true : false );

        if( isset( $_POST['disable_ad'] ) ){
           $this->Post->disable_ad( $post_id );
        }
        else{
            $this->Post->enable_ad( $post_id );
        }

        if( isset( $_POST['ad_schedules'] ) ){
            $this->Post->update_ad_schedules( $post_id, $_POST['ad_schedules'] );
        }else{
            $this->Post->update_ad_schedules( $post_id, array() );
        }

        // Update tracks
        if( isset( $_POST['text_tracks'] ) ){
            $this->Post->update_text_tracks();
        }

        if( isset( $_POST['altsources'] ) ){
            $this->Post->update_altsources();
        }

        if( isset( $_POST['embed_privacy'] ) ){
            $this->Post->update_embed_privacy();
        }

        $this->generate_video_image( $post_id, $source );
    }

    /**
     *
     * Text Tracks Template
     * 
     * @param  WP_Post $post
     * 
     */
    public function video_text_tracks_html( $post ){
        include plugin_dir_path( __FILE__ ) . 'partials/text-tracks.php';
    }

    /**
     *
     * Load featured image 2 HTML
     * 
     * @param  object $post
     * @since 1.0.0
     * 
     */
    public function featured_image_2_html( $post ){
        include plugin_dir_path( __FILE__ ) . 'partials/featured-image-2.php';
    }

    public function _get_options_alignment(){
        return array(
            'default'   =>  esc_html__( 'Default', 'streamtube-core' ),
            'center'    =>  esc_html__( 'Center', 'streamtube-core' )
        );
    }

    /**
     *
     * Get template options
     * 
     * @param  int $post_id
     * @return array
     *
     * @since 2.2
     * 
     */
    public function get_template_options( $post_id = 0 ){

        $default = array(
            'disable_title'                 =>  '',
            'disable_thumbnail'             =>  '',
            'header_alignment'              =>  'default',
            'header_padding'                =>  '5',
            'remove_content_box'            =>  '',
            'disable_content_padding'       =>  '',
            'disable_primary_sidebar'       =>  '',
            'disable_bottom_sidebar'        =>  '',
            'disable_comment_box'           =>  ''
        );

        $options = wp_parse_args( (array)get_post_meta( $post_id, 'template_options', true ), $default );

        /**
         *
         * Filter template options
         *
         * @param array $options
         * @param int $post_id
         * 
         */
        return apply_filters( 'streamtube/core/single_template_options', $options, $post_id );
    }

    /**
     *
     * Page Template options
     * 
     * @param  WP_Post $post
     *
     * @since 2.2
     * 
     */
    public function template_options_template( $post ){
        load_template( plugin_dir_path( __FILE__ ) . 'partials/template-options.php' );
    }

    /**
     *
     * Save Template Options data
     * 
     * @param  int $post_id
     * @since 2.2
     * 
     */
    public function template_options_save( $post_id ){

        if( ! isset( $_POST ) || ! isset( $_POST['template_options'] ) ){
            return;
        }

        if( ! current_user_can( 'edit_post', $post_id ) ){
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! in_array( get_post_type( $post_id ), array( 'page', 'post', 'video' ) )) {
            return;
        }

        $options = wp_unslash( $_POST['template_options'] );

        if( ! isset( $options['content_padding'] ) ){
            $options['content_padding'] = '';
        }

        return update_post_meta( $post_id, 'template_options', $options );
    }
}