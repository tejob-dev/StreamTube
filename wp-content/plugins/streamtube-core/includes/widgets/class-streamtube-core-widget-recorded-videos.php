<?php
/**
 * Define the recorded videos widget functionality
 * Require WP Cloudflare Stream plugin activated
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
class Streamtube_Core_Widget_Recorded_Videos extends WP_Widget{

    private $instance = array();

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'recorded-videos-widget' ,
            esc_html__('[StreamTube] Recorded Videos', 'streamtube-core' ), 
            array( 
                'classname'     =>  'recorded-videos streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Display Recorded Videos of current/given Live Stream', 'streamtube-core')
            )
        );
    }

    /**
     * Register this widget
     */
    public static function register(){
        if( function_exists( 'wp_cloudflare_stream' ) ){
            register_widget( __CLASS__ );    
        }
    }

    /**
     *
     * Check if recorded is ready to stream
     * 
     * @param  array  $recorded
     * @return boolean
     *
     * @since 2.4
     * 
     */
    private function is_ready_to_stream( $recorded ){
        return $recorded['readyToStream'] ? true : false;
    }

    /**
     * Check if video is require signed URL
     * 
     */
    private function require_signed_url( $recorded ){
        return $recorded['requireSignedURLs'] ? true : false;
    }

    /**
     *
     * Get stream status
     * 
     * @param  array  $recorded
     * @return boolean
     *
     * @since 2.4
     * 
     */
    private function get_stream_status( $recorded ){
        // ready, live-inprogress
        return $recorded['status']['state'];
    }

    /**
     *
     * Get recorded name
     * 
     * @param  array  $recorded
     * @return false|string
     *
     * @since 2.4
     * 
     */    
    private function get_stream_name( $recorded ){
        if( array_key_exists( 'meta', $recorded ) ){
            if( array_key_exists( 'name', $recorded['meta'] ) && $recorded['meta']['name'] ){
                return apply_filters( 'streamtube/core/widget/recorded_videos/title', $recorded['meta']['name'], $recorded );
            }
        }

        return false;
    }

    /**
     *
     * Get recorded UID
     * 
     * @param  array  $recorded
     * @return false|string
     *
     * @since 2.4
     * 
     */    
    private function get_stream_uid( $recorded ){
        if( array_key_exists( 'uid', $recorded ) ){
            return $recorded['uid'];
        }

        return false;
    }    

    /**
     *
     * Get recorded date
     * 
     * @param  array  $recorded
     * @return false|string
     *
     * @since 2.4
     * 
     */    
    private function get_stream_date( $recorded ){
        if( array_key_exists( 'uploaded', $recorded ) ){

            $time = strtotime( $recorded['uploaded']);

            if( $this->instance['date_format'] == 'diff' ){
                return sprintf(
                    esc_html__( '%s ago', 'streamtube-core' ),
                    human_time_diff( $time, current_time( 'timestamp', true ) )
                );
            }else{
                return date( get_option( 'date_format' ), $time );    
            }
        }

        return false;
    }    

    /**
     *
     * Get recorded duration
     * 
     * @param  array  $recorded
     * @return false|string
     *
     * @since 2.4
     * 
     */    
    private function get_stream_duration( $recorded ){
        if( array_key_exists( 'duration', $recorded ) && (int)$recorded['duration'] != -1 ){
            return $recorded['duration'];
        }

        return false;
    }

    /**
     *
     * Get recorded thumbnail
     * 
     * @param  array  $recorded
     * @return false|string
     *
     * @since 2.4
     * 
     */    
    private function get_stream_thumbnail( $recorded ){

        $thumbnail_url = '';

        if( array_key_exists( 'thumbnail', $recorded ) ){
            $thumbnail_url = $recorded['thumbnail'];
        }

        /**
         *
         * Filter the thumbnail URL
         * 
         */
        return apply_filters( 'streamtube/core/widget/recorded_videos/thumbnail_url', $thumbnail_url, $recorded );
    }    

    /**
     *
     * Get recorded URL
     * 
     * @param  array  $recorded
     * @return false|string
     *
     * @since 2.4
     * 
     */    
    private function get_stream_url( $recorded ){
        return add_query_arg( 
            array( 
                'uid' => $recorded['uid'] 
            ) 
        );
    }

    /**
     *
     * Check if current recorded
     * 
     * @param  array  $recorded
     * @return false|true
     *
     * @since 2.4
     * 
     */   
    private function is_current_stream( $recorded ){
        if( isset( $_GET['uid'] ) && $_GET['uid'] == $this->get_stream_uid( $recorded ) ){
            return true;
        }

        return false;
    }

    /**
     *
     * Get classes
     * 
     * @param  array  $recorded
     * @return array
     *
     * @since 2.4
     * 
     */  
    private function get_stream_classes( $recorded ){
        $classes = array( 'type-video', 'recorded-item' );

        $classes[] = sanitize_html_class( $this->get_stream_uid( $recorded ) );

        if( $this->is_current_stream( $recorded ) ){
            $classes[] = 'current';
        }

        if( $this->require_signed_url( $recorded ) ){
            $classes[] = 'is-signed-url';
        }

        return $classes;        
    }

    /**
     *
     * The stream label
     * 
     * @param  array $recorded
     * @return string
     *
     * @since 2.4
     * 
     */
    private function get_stream_status_label( $recorded ){
        $output = '';

        if( $this->get_stream_status( $recorded ) == 'live-inprogress' ){
            $output = sprintf(
                '<span class="badge badge-stream badge-live-inprogress bg-danger">%s</span>',
                esc_html__( 'Live', 'streamtube-core' )
            );            
        }

        /**
         * @since 2.4
         */
        $output = apply_filters( 'streamtube/core/widget/recorded_videos/badge', $output, $recorded );

        return $output;
    }

    /**
     *
     * Get Date block
     * 
     * @param  array $recorded
     * @return string
     *
     * @since 2.4
     * 
     */
    private function get_block_date( $recorded ){

        if( ! $this->instance['date_format']  ){
            return;
        }

        if( false == ( $date = $this->get_stream_date( $recorded )) ){
            return;
        }

        ?>
        <div class="post-meta__date">

            <span class="icon-calendar-empty"></span>

            <?php if( $this->instance['date_format'] == 'diff' ): ?>
                <a href="<?php echo esc_url( $this->get_stream_url( $recorded )  )?>">
                    <?php echo '<time datetime="'. $date .'" class="date">'. $date .'</time>'; ?>
                </a>
            <?php else: ?>
                <a href="<?php echo esc_url( $this->get_stream_url( $recorded )  )?>">
                    <?php printf(
                        esc_html__( 'on %s', 'streamtube' ),
                        '<time datetime="'. $date .'" class="date">'. $date .'</time>'
                    ) ?>
                </a>
            <?php endif;?>
            
        </div>
        <?php
    }

    /**
     *
     * The grid part
     * 
     * @param  array $recorded
     * @return output html
     *
     * @since 2.4
     * 
     */
    private function get_content_part( $recorded ){
        ?>
        <article class="<?php echo join( ' ', $this->get_stream_classes( $recorded ) )?>">
            
            <?php printf(
                '<div class="post-body %s">',
                $this->instance['layout'] == 'grid' ? 'position-relative' : 'd-flex align-items-start'
            )?>

                <?php printf(
                    '<div class="post-main %s">',
                    $this->instance['layout'] == 'grid' ? 'position-relative rounded overflow-hidden' : 'me-3'
                )?>            

                    <a class="post-permalink" href="<?php echo esc_url( $this->get_stream_url( $recorded ) );?>">
                        <div class="post-thumbnail ratio ratio-16x9 rounded overflow-hidden bg-dark">
                            <?php printf(
                                '<img src="%s">',
                                $this->get_stream_thumbnail( $recorded )
                            );?>
                            <div class="video-hover">
                                <span class="icon-play top-50 start-50 translate-middle position-absolute"></span>
                            </div>

                            <?php if( false != $duration = $this->get_stream_duration( $recorded ) ): ?>
                                <div class="video-length badge">
                                    <?php echo streamtube_seconds_to_length( $duration ); ?>
                                </div>
                            <?php endif;?>

                            <?php echo $this->get_stream_status_label( $recorded ); ?>
                        </div>
                    </a>
                </div>

                <?php printf(
                    '<div class="post-bottom %s">',
                    $this->instance['layout'] == 'grid' ? 'mt-3 d-flex align-items-start' : 'w-100 clearfix'
                )?>
                    <div class="post-meta w-100">

                        <?php if( false != $name = $this->get_stream_name( $recorded ) ): ?>

                            <?php printf(
                                '<h2 class="post-meta__title post-title"><a title="%s" href="%s">%s</a></h2>',
                                esc_attr( $name ),
                                esc_url( $this->get_stream_url( $recorded ) ),
                                $name
                            );?>

                        <?php endif;?>

                        <div class="post-meta__items d-flex flex-column">
                            <?php $this->get_block_date( $recorded ); ?>

                            <?php if( $this->is_current_stream( $recorded ) ): ?>
                                <div class="post-current d-block text-uppercase">
                                    <?php printf(
                                        '<span class="badge bg-danger">%s</span>',
                                        esc_html__( 'Watching', 'streamtube-core' )
                                    );?>
                                </div>
                            <?php endif; ?>                            
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <?php
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::widget()
     */
    public function widget( $args, $instance ) {

        $instance = wp_parse_args( $instance, array(
            'id'                    =>  '',
            'id_base'               =>  '',
            'title'                 =>  '',
            'posts_per_page'        =>  5,
            'date_format'           =>  'diff',
            'layout'                =>  'grid',
            'margin_bottom'         =>  4,
            'col_xxl'               =>  4,
            'col_xl'                =>  4,
            'col_lg'                =>  2,
            'col_md'                =>  2,
            'col_sm'                =>  1,
            'col'                   =>  1
        ) );

        if( ! is_singular() ){
            return;
        }

        $recorded_videos = get_post_meta( get_post_meta( get_the_ID(), 'video_url', true ), '_recorded_videos', true );

        if( ! $recorded_videos || ! is_array( $recorded_videos ) ){
            return;
        }        

        $instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        if( (int)$instance['col_xxl'] == 1 ){
            $instance['col_xl'] = $instance['col_lg'] = $instance['col_md'] = $instance['col_sm'] = $instance['col'] = 1;
        }        

        $this->instance = $instance;

        extract( $instance );

        /**
         *
         * Assign current widget ID into instance
         * 
         */
        if( $this->id ){
            $id = $this->id;
        }

        if( $this->id_base ){
            $id_base = $this->id_base;
        }

        $per_page = min( count( $recorded_videos ), (int)$posts_per_page );

        echo $args['before_widget'];

            if( $title ){
                echo $args['before_title'] . $title . $args['after_title'];
            } 

            $_layout = $layout == 'grid' ? 'grid' : 'list';

            $wrap_classes = array( 'post-grid' );

            $wrap_classes[] = 'post-grid-' . sanitize_html_class( $_layout );
            $wrap_classes[] = 'post-grid-' . sanitize_html_class( $layout );            

            $row_classes = array( 'row' );

            $row_classes[] = 'row-cols-' . $col;
            $row_classes[] = 'row-cols-sm-' . $col_sm;
            $row_classes[] = 'row-cols-md-' . $col_md;
            $row_classes[] = 'row-cols-lg-' . $col_lg;
            $row_classes[] = 'row-cols-xl-' . $col_xl;
            $row_classes[] = 'row-cols-xxl-' . $col_xxl;

            printf(
                '<div class="%s">',
                esc_attr( join( ' ', array_unique( $wrap_classes ) ) )
            );            

                printf( '<div class="%s">', join(' ', $row_classes ) );

                    for ( $i = 0; $i < $per_page; $i++ ) {

                        printf(
                            '<div class="post-item mb-%s">',
                            (int)$margin_bottom
                        );

                            $this->get_content_part( $recorded_videos[$i] );
                            
                        echo '</div>';
                    }

                echo '</div>';

            echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::update()
     */
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::form()
     */
    public function form( $instance ){
        $instance = wp_parse_args( $instance, array(
            'title'                 =>  '',
            'posts_per_page'        =>  5,
            'date_format'           =>  'diff',
            'layout'                =>  'grid',
            'margin_bottom'         =>  4,
            'col_xxl'               =>  4,
            'col_xl'                =>  4,
            'col_lg'                =>  2,
            'col_md'                =>  2,
            'col_sm'                =>  1,
            'col'                   =>  1       
        ) );
        ?>
        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'title' ) ),
                esc_html__( 'Title', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'title' ) ),
                esc_attr( $this->get_field_name( 'title' ) ),
                esc_attr( $instance['title'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'posts_per_page' ) ),
                esc_html__( 'Posts per page', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'posts_per_page' ) ),
                esc_attr( $this->get_field_name( 'posts_per_page' ) ),
                esc_attr( $instance['posts_per_page'] )

            );?>
        </div>        

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'date_format' ) ),
                esc_html__( 'Date Format', 'date_format-core')

            );?>
            
            <?php printf(
                '<select class="widefat" id="%s" name="%s" />',
                esc_attr( $this->get_field_id( 'date_format' ) ),
                esc_attr( $this->get_field_name( 'date_format' ) )
            );?>

            <?php foreach ( Streamtube_Core_Widget_Posts::get_date_formats() as $key => $value ) {
                printf(
                    '<option %s value="%s">%s</option>',
                    selected( $instance['date_format'], $key, false ),
                    esc_attr( $key ),
                    esc_html( $value )
                );
            }?>

            </select>
        </div>

        <div class="field-control">

            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'layout' ) ),
                esc_html__( 'Layout', 'streamtube-core')

            );?>
            <?php printf(
                '<select class="widefat" id="%s" name="%s"/>',
                esc_attr( $this->get_field_id( 'layout' ) ),
                esc_attr( $this->get_field_name( 'layout' ) )
            );?>

                <?php foreach( Streamtube_Core_Widget_Posts::get_layouts() as $layout => $text ): ?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $layout ),
                        selected( $instance['layout'], $layout, false ),
                        esc_html( $text )
                    );?>

                <?php endforeach;?>
                
            </select>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'margin_bottom' ) ),
                esc_html__( 'Margin Bottom', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'margin_bottom' ) ),
                esc_attr( $this->get_field_name( 'margin_bottom' ) ),
                esc_attr( $instance['margin_bottom'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_xxl' ) ),
                esc_html__( 'Extra extra large ≥1400px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_xxl' ) ),
                esc_attr( $this->get_field_name( 'col_xxl' ) ),
                esc_attr( $instance['col_xxl'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_xl' ) ),
                esc_html__( 'Extra large ≥1200px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_xl' ) ),
                esc_attr( $this->get_field_name( 'col_xl' ) ),
                esc_attr( $instance['col_xl'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_lg' ) ),
                esc_html__( 'Large ≥992px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_lg' ) ),
                esc_attr( $this->get_field_name( 'col_lg' ) ),
                esc_attr( $instance['col_lg'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_md' ) ),
                esc_html__( 'Medium ≥768px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_md' ) ),
                esc_attr( $this->get_field_name( 'col_md' ) ),
                esc_attr( $instance['col_md'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_sm' ) ),
                esc_html__( 'Small ≥576px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_sm' ) ),
                esc_attr( $this->get_field_name( 'col_sm' ) ),
                esc_attr( $instance['col_sm'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col' ) ),
                esc_html__( 'Extra small <576px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col' ) ),
                esc_attr( $this->get_field_name( 'col' ) ),
                esc_attr( $instance['col'] )

            );?>
        </div>
        <?php
    }            
}