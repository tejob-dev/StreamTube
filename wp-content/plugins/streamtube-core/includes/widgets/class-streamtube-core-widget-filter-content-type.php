<?php
/**
 * Define the default Content Type filter widget functionality
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
class Streamtube_Core_Widget_Filter_Content_Type extends WP_Widget{

    protected $query_var = 'content_type';

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'filter-content-type-widget' ,
            esc_html__('[StreamTube] Filter - Content Type', 'streamtube-core' ), 
            array( 
                'classname'     =>  'filter-content-type-widget widget-filter streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Searching posts by Content Type.', 'streamtube-core')
            )
        );
    }  

    /**
     * Register this widget
     */
    public static function register(){
        register_widget( __CLASS__ );
    }

    /**
     *
     * Get search content type
     * 
     * @return array
     *
     * @since 1.1.9
     * 
     */
    public static function get_content_types(){

        $post_types = array_merge( array(
            'any'   =>  esc_html__( 'Any', 'streamtube-core' ),
            'video' =>  esc_html__( 'Video', 'streamtube-core' )
        ), get_post_types( array(
            'public'                =>  true,
            'exclude_from_search'   =>  false
        ) ) );

        $content_types = array_merge( $post_types, array(
            'video_collection'  =>  esc_html__( 'Collections', 'streamtube-core' ),
            'user'              =>  esc_html__( 'Users', 'streamtube-core' )
        ) );

        /**
         *
         * Filter the content types
         * 
         */
        return apply_filters( 'streamtube/core/widget/filter_content_types', $content_types );
    }

    public static function get_content_type_ids( $content_types ){
        $content_type_ids = array();

        if( $content_types ){
            foreach ( $content_types as $type => $value ) {
                $content_type_ids[] = $type;
            }
        }

        return $content_type_ids;
    }

    public static function get_dependency_taxonomies( $post_type ){

        if( post_type_exists( $post_type ) ){
            return get_object_taxonomies( $post_type );    
        }

        return false;
        
    }

    /**
     *
     * Get current
     * 
     */
    private function get_current_query_var( $default = '' ){

        $current = '';

        if( isset( $_REQUEST[ $this->query_var ] ) ){
             $_current = wp_unslash( $_REQUEST[ $this->query_var ] );

             if( is_array( $_current ) && count( $_current ) > 0 ){
                $current = $_current[0];
             }

             if( is_string( $_current ) ){
                $current = $_current;
             }
        }

        return $current ? $current : $default;
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::widget()
     */
    public function widget( $args, $instance ) {

        $output = '';

        $instance = wp_parse_args( $instance, array(
            'title'             =>  esc_html__( 'Content Types', 'streamtube-core' ),
            'content_types'     =>  array(),
            'all_content_types' =>  self::get_content_types(),
            'button_search'     =>  '',
            'cap'               =>  '',
            'list_type'         =>  'cloud',
            'current'           =>  '',
            'fullwidth'         =>  ''
        ) );

        extract( $instance );

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        if( ! empty( $cap ) && ! current_user_can( $cap ) || ! $all_content_types ){
            return;
        }

        if( ! $content_types ){
            $content_types = self::get_content_type_ids( $all_content_types );
        }

        $GLOBALS['registered_content_types'] = $content_types;

        $current = $this->get_current_query_var( array_keys( $all_content_types )[0] );

        /**
         *
         * Filter widget args
         * 
         */
        $args = apply_filters( 'streamtube/core/widget/filter_widget_args', $args, $instance );     

        echo $args['before_widget'];

            if( $title ){
                echo $args['before_title'] . $title . $args['after_title'];
            }

            ob_start();

            printf(
                '<div class="content-type-filter list-filter list-%s">',
                esc_attr( $list_type )
            );

                for ( $i = 0; $i < count( $content_types ); $i++) {

                    if( array_key_exists( $content_types[$i] , $all_content_types ) ){

                        $text  = $all_content_types[ $content_types[$i] ];

                        if( post_type_exists( $content_types[$i] ) ){
                            $text = get_post_type_object( $content_types[$i] )->label;
                        }

                        $taxonomies = $this->get_dependency_taxonomies( $content_types[$i] );

                        printf(
                            '<div class="filter-%s mb-3">',
                            esc_attr( $content_types[$i] )
                        );
                        ?>
                            <label>
                                <?php printf(
                                    '<input type="radio" name="%s" value="%s" id="filter-content_type-%s" %s data-taxonomies="%s">',
                                    $this->query_var,
                                    esc_attr( $content_types[$i] ),
                                    esc_attr( $content_types[$i] ),
                                    checked( $current, $content_types[$i], false ),
                                    $taxonomies ? esc_attr( join('+', $taxonomies ) ) : ''
                                );?>

                                <?php printf(
                                    '<span>%s</span>',
                                    esc_html( $text )
                                );?>
                            </label>
                        </div>
                        <?php
                    }
                }

            echo '</div>';

            $output = ob_get_clean();

            if( $button_search && $args['id'] != 'advanced-search' ){
                printf(
                    '<form method="GET" action="%s">',
                    esc_url( home_url( '/' ) )
                );

                    printf(
                        '<div class="mb-3">%s</div>',
                        $output
                    );

                    printf(
                        '<input type="hidden" name="s" value="%s">',
                        esc_attr( get_search_query( true ) )
                    );         

                    printf(
                        '<div class="d-flex gap-3"><button type="reset" class="btn btn-secondary btn-sm">%s</button>',
                        esc_html__( 'Reset', 'streamtube-core' )
                    );

                    printf(
                        '<button type="submit" class="btn btn-primary btn-sm">%s</button></div>',
                        esc_html__( 'Search', 'streamtube-core' )
                    );

                echo '</form>';
            }else{
                echo $output;
            }

            if( $current ):
            ?>
            <script type="text/javascript">

                function contentTypeSelect( currentType ){
                    jQuery( '.filter-taxonomy-widget' ).addClass( 'd-none' );

                    var current = jQuery( '#filter-content_type-' + currentType );
                    
                    var taxonomies = current.attr( 'data-taxonomies' );

                    if( taxonomies ){
                        taxonomies = taxonomies.split("+");

                        for ( var i = 0; i < taxonomies.length; i++ ) {
                            jQuery( '.filter-taxonomy-'+ taxonomies[i] +'-widget' ).removeClass( 'd-none' );
                        }
                    }
                }

                jQuery( document ).ready(function() {
                    contentTypeSelect( '<?php echo $current; ?>' );

                    jQuery( 'input[name=content_type]' ).on( 'change', function(e){
                        return contentTypeSelect( jQuery(this).val() );
                    } );

                });
                
            </script>
            <?php
            endif;

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
            'title'             =>  esc_html__( 'Content Types', 'streamtube-core' ),
            'content_types'     =>  array(),
            'button_search'     =>  '',
            'cap'               =>  '',
            'list_type'         =>  'cloud',
            'fullwidth'         =>  '',
            'multiple'          =>  ''
        ) );

        if( is_string( $instance['content_types'] ) ){
            $instance['content_types'] = array_map( 'trim' , explode(',', $instance['content_types']));
        }

        $all_content_types = self::get_content_types();

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

        <div class="field-group" style="max-height: 300px;overflow: scroll;">
            <?php printf(
                '<label>%s</label>',
                esc_html__( 'Content Types', 'streamtube-core')
            );?>

            <span class="field-help">
                <?php esc_html_e( 'Leave default to show all available content types', 'streamtube-core' );?>
            </span>

            <?php foreach ( $all_content_types as $type => $value ): ?>
                <div class="field-control">
                    <label>
                    <?php printf(
                        '<input type="checkbox" class="widefat" name="%s[]" value="%s" %s/> %s',
                        esc_attr( $this->get_field_name( 'content_types' ) ),
                        esc_attr( $type ),
                        in_array( $type , $instance['content_types'] ) ? 'checked' : '',
                        post_type_exists( $type ) ? get_post_type_object( $type )->label : ucwords( $value )
                    );?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>     

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'cap' ) ),
                esc_html__( 'Capability', 'streamtube-core')
            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'cap' ) ),
                esc_attr( $this->get_field_name( 'cap' ) ),
                esc_attr( $instance['cap'] )
            );?>
            <span class="field-help">
                <?php esc_html_e( 'Show this widget if current user has the required capability', 'streamtube-core' );?>
            </span>            
        </div>        

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'list_type' ) ),
                esc_html__( 'List Type', 'streamtube-core')
            );?>
            
            <?php printf(
                '<select class="widefat" id="%s" name="%s">',
                esc_attr( $this->get_field_id( 'list_type' ) ),
                esc_attr( $this->get_field_name( 'list_type' ) )
            );?>

                <?php foreach ( streamtube_core_get_list_types() as $key => $value ): ?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $key ),
                        selected( $instance['list_type'], $key, false ),
                        esc_html( $value )
                    );?>
                    
                <?php endforeach ?>

            </select>
        </div>        

        <div class="field-control">
           
            <?php printf(
                '<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
                esc_attr( $this->get_field_id( 'fullwidth' ) ),
                esc_attr( $this->get_field_name( 'fullwidth' ) ),
                checked( $instance['fullwidth'], 'on', false )
            );?>
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'fullwidth' ) ),
                esc_html__( 'Fullwidth', 'streamtube-core')
            );?>
        </div>         

        <div class="field-control">
           
            <?php printf(
                '<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
                esc_attr( $this->get_field_id( 'button_search' ) ),
                esc_attr( $this->get_field_name( 'button_search' ) ),
                checked( $instance['button_search'], 'on', false )
            );?>
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'button_search' ) ),
                esc_html__( 'Search Button', 'streamtube-core')
            );?>            
        </div>
        <?php
    }    
}