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

class StreamTube_Core_Youtube_Importer_Admin{

    /**
     *
     * Define advertising admin menu slug
     *
     * @since 1.3
     * 
     */
    const ADMIN_MENU_SLUG   = 'edit.php?post_type=video';

    /**
     *
     * Unregistered Menu
     * 
     */
    public function unregistered(){
        add_submenu_page( 
            self::ADMIN_MENU_SLUG, 
            esc_html__( 'YouTube Importers', 'streamtube-core' ), 
            esc_html__( 'YouTube Importers', 'streamtube-core' ), 
            'administrator', 
            'youtube-importers', 
            array( 'Streamtube_Core_License' , 'unregistered_template' ), 
            50
        );
    }

    /**
     *
     * @see add_meta_box()
     *
     * @since 2.0
     * 
     */
    public function add_meta_boxes(){
        add_meta_box( 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE . '_settings', 
            esc_html__( 'Search Settings', 'streamtube-core' ), 
            array( $this , 'search_settings_box' ), 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE,
            'advanced',
            'core'
        );

        add_meta_box( 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE . '_post_settings', 
            esc_html__( 'Video Settings', 'streamtube-core' ), 
            array( $this , 'video_settings_box' ), 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE,
            'advanced',
            'core'
        );        

        add_meta_box( 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE . '_search', 
            esc_html__( 'Search Results', 'streamtube-core' ), 
            array( $this , 'search_box' ), 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE,
            'advanced',
            'core'
        );

        add_meta_box( 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE . '_update_frequency', 
            esc_html__( 'Update Frequency', 'streamtube-core' ), 
            array( $this , 'update_frequency_box' ), 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE,
            'side',
            'core'
        );         

        add_meta_box( 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE . '_videos', 
            esc_html__( 'Latest Imported Items', 'streamtube-core' ), 
            array( $this , 'imported_box' ), 
            StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE,
            'side',
            'core'
        );        

        $taxonomies = get_object_taxonomies( 'video', 'object' );

        foreach ( $taxonomies as $tax => $object ){
            add_meta_box( 
                StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE . '_' . $tax, 
                $object->label, 
                array( $this , 'taxonomies_box' ), 
                StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE,
                'side',
                'core',
                compact( 'tax' )
            );
        }      
    }    

    /**
     *
     * The settings metabox
     * 
     * @param  object $post
     *
     * @since 2.0
     *
     */
    public function search_settings_box( $post ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/search-settings.php' );
    }

    /**
     *
     * The box settings metabox
     * 
     * @param  object $post
     *
     * @since 2.0
     *
     */
    public function video_settings_box( $post ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/video-settings.php' );
    }

    /**
     *
     * The search metabox
     * 
     * @param  object $post
     *
     * @since 2.0
     *
     */
    public function search_box( $post ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/search.php' );
    }    

    public function update_frequency_box( $post ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/update-frequency.php' );
    }

    /**
     *
     * The imported metabox
     * 
     * @param  object $post
     *
     * @since 2.0
     *
     */
    public function imported_box( $post ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/imported-videos.php' );
    } 

    /**
     *
     * The taxonomies metabox
     * 
     * @param  object $post
     *
     * @since 2.0
     * 
     */
    public function taxonomies_box( $post, $args ){
        load_template( plugin_dir_path( __FILE__ ) . 'admin/taxonomy.php', false, $args['args'] );
    }

    /**
     *
     * Get default settings;
     * 
     * @return array
     *
     * @since 2.0
     * 
     */
    private function get_default(){
        return array(
            'apikey'                =>  get_option( 'youtube_api_key' ),
            'enable'                =>  '',
            'cron_tag_key'          =>  uniqid(),
            'q'                     =>  '',
            'searchIn'              =>  'channel',
            'channelId'             =>  '',
            'maxResults'            =>  10,
            'publishedAfter'        =>  '',
            'publishedBefore'       =>  '',
            'regionCode'            =>  '',
            'relevanceLanguage'     =>  '',
            'type'                  =>  'video',
            'videoType'             =>  'any',
            'safeSearch'            =>  'moderate',
            'eventType'             =>  '',
            'videoDefinition'       =>  'any',
            'videoDimension'        =>  'any',
            'videoDuration'         =>  'any',
            'videoEmbeddable'       =>  true,
            'videoLicense'          =>  'any',
            'order'                 =>  'date',
            'post_status'           =>  'pending',
            'post_type'             =>  'video',
            'post_meta_field'       =>  '',
            'post_author'           =>  '',
            'post_tags'             =>  '',
            'update_number'         =>  5,
            'update_frequency'      =>  5,
            'update_frequency_unit' =>  'minutes'
        );  
    }

    /**
     *
     * Save settings metabox
     * 
     * @param  int $post_id
     *
     * @since 2.0
     * 
     */
    public function save_settings( $importer_id ){

        if( ! current_user_can( 'administrator' ) ){
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if( ! isset( $_POST['yt_importer'] ) ){
            return;
        }

        $settings = wp_parse_args( $_POST['yt_importer'], $this->get_default() );

        foreach ( $settings as $key => $value) {
            update_post_meta( $importer_id, $key, $value );
        }
    }

    /**
     *
     * Get settings
     * 
     * @param  integer $importer_id
     * 
     * @since 2.0
     * 
     */
    public function get_settings( $importer_id = 0 ){

        $settings = array();

        $defaults = $this->get_default();

        foreach ( $defaults as $key => $value ) {
            $_meta = get_post_meta( $importer_id, $key, true );

            if( ! $_meta ){
                $_meta = $value;
            }

            $settings[ $key ] = $_meta;
        }

        return $settings;
    }

    /**
     * Add custom fields to the Video table
     *
     * @param array $columns
     */
    public function post_table( $columns ){
        unset( $columns['date'] );
        unset( $columns['title'] );

        $new_columns = array(
            'run'           =>  esc_html__( 'Run', 'streamtube-core' ),
            'title'         =>  esc_html__( 'Importer', 'streamtube-core' ),
            'last_check'    =>  esc_html__( 'Last Check', 'streamtube-core' ),
            'next_page'     =>  esc_html__( 'Next Page Token', 'streamtube-core' ),
            'status'        =>  esc_html__( 'Status', 'streamtube-core' ),
            'date'          =>  esc_html__( 'Date', 'streamtube-core' )
        );

        return array_merge( $columns, $new_columns );
    }

    /**
     *
     * Custom Columns callback
     * 
     * @param  string $column
     * @param  int $post_id
     * 
     */
    public function post_table_columns( $column, $importer_id ){

        switch ( $column ) {

            case 'run':
                ?>
                <?php printf(
                    '<button data-text-run="%s" data-text-running="%s" type="button" class="button button-yt-bulk-import" data-importer-id="%s" data-key="%s">',
                    esc_html__( 'Run', 'streamtube-core' ),
                    esc_html__( 'Running', 'streamtube-core' ),
                    esc_attr( $importer_id ),
                    esc_attr( get_post_meta( $importer_id, 'cron_tag_key', true ) )
                );?>
                    <?php esc_html_e( 'Run', 'streamtube-core' );?>
                </button>
                <?php
            break;

            case 'last_check':

                $last_check = get_post_meta( $importer_id, 'last_check', true );

                if( $last_check ){
                    printf(
                        esc_html__( '%s ago', 'streamtube-core' ),
                        human_time_diff( get_post_meta( $importer_id, 'last_check', true ), current_time('timestamp') )
                    );
                }
            break;

            case 'next_page':

                $maybe_next_page = get_post_meta( $importer_id, 'next_page_token', true );

                if( $maybe_next_page ){
                    echo $maybe_next_page;
                }
            break;

            case 'status':

                $count = 0;

                $imported_videos = streamtube_core()->get()->yt_importer->get_imported_videos( $importer_id, 10000000000 );

                if( $imported_videos ){
                    $count = count( $imported_videos );
                }

                printf(
                    esc_html__( '%s items has been imported', 'streamtube-core' ),
                    number_format_i18n( $count )
                );
            break;
        } 
    } 

    public function pre_get_posts( $query ){
        if( is_admin() ){
            if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'video' ){
                if( isset( $_GET['importer_id'] ) && get_post_type( $_GET['importer_id'] ) == StreamTube_Core_Youtube_Importer_Post_Type::POST_TYPE ){

                    $query->set( 'meta_query', array(
                        array(
                            'key'   =>  'yt_importer_id',
                            'value' =>  (int)$_GET['importer_id']
                        )
                    ) );
                }
            }
        }
    }
}