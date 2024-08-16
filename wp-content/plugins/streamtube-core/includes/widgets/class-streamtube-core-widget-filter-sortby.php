<?php
/**
 * Define the default sortby filter widget functionality
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
class Streamtube_Core_Widget_Filter_Sortby extends WP_Widget{

    protected $query_var = 'orderby';

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'filter-sortby-widget' ,
            esc_html__('[StreamTube] Filter - Sort By', 'streamtube-core' ), 
            array( 
                'classname'     =>  'filter-sortby-widget widget-filter streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Sorting posts options.', 'streamtube-core')
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
     * Get options
     * 
     */
    public static function get_options(){
        $options = array(
            'relevance'     =>  esc_html__( 'Relevance', 'streamtube-core' ),
            'date'          =>  esc_html__( 'Date', 'streamtube-core' ),
            'name'          =>  esc_html__( 'Name', 'streamtube-core' ),
            'comment_count' =>  esc_html__( 'Comment Count', 'streamtube-core' ),
        );

        if( class_exists( 'Streamtube_Core_GoogleSiteKit_Analytics' ) ){
            $googleSitekitAnalytics = new Streamtube_Core_GoogleSiteKit_Analytics();

            if( $googleSitekitAnalytics->is_connected() ){
                $options['post_view']   = esc_html__( 'View count', 'streamtube-core' );
            }    
        }

        return apply_filters( 'streamtube/core/widget/filter_sortby', $options );
    }

    /**
     *
     * Get options IDs
     * 
     */
    public static function get_options_ids( $options ){
        $options_ids = array();

        if( $options ){
            foreach ( $options as $option => $value ) {
                $options_ids[] = $option;
            }
        }

        return $options_ids;
    }    

    /**
     *
     * Get current
     * 
     */
    private function get_current_query_var(){

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

        return $current;
    }   

    /**
     * {@inheritDoc}
     * @see WP_Widget::widget()
     */
    public function widget( $args, $instance ) {

        $output = '';

        $instance = wp_parse_args( $instance, array(
            'title'             =>  esc_html__( 'Sort by', 'streamtube-core' ),
            'cap'               =>  '',
            'list_type'         =>  'cloud',
            'fullwidth'         =>  '',    
            'button_search'     =>  '',
            'options'           =>  array(),
            'all_options'       =>  self::get_options()
        ) );

        $instance['current'] = $this->get_current_query_var();

        extract( $instance );

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        $options = $options ? $options : self::get_options_ids( $all_options );

        if( ! empty( $cap ) && ! current_user_can( $cap ) || ! $options ){
            return;
        }

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
                '<div class="sortby-filter list-filter list-%s">',
                esc_attr( $list_type )
            );

                for ( $i = 0; $i < count( $options ); $i++) {

                    if( array_key_exists( $options[$i] , $all_options ) ){

                        printf(
                            '<div class="filter-%s mb-3">',
                            esc_attr( $options[$i] )
                        );
                        ?>
                            <label>
                                <?php printf(
                                    '<input type="radio" name="%s" value="%s" id="filter-%s" %s>',
                                    $this->query_var,
                                    esc_attr( $options[$i]),
                                    esc_attr( $options[$i] ),
                                    checked( $current, $options[$i], false )
                                );?>

                                <?php printf(
                                    '<span>%s</span>',
                                    esc_html( $all_options[ $options[$i] ] )
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
            'title'             =>  esc_html__( 'Sort by', 'streamtube-core' ),
            'options'           =>  '',
            'button_search'     =>  '',
            'cap'               =>  '',
            'list_type'         =>  'cloud',
            'fullwidth'         =>  ''
        ) );

        if( is_string( $instance['options'] ) ){
            $instance['options'] = array_map( 'trim', explode(',', $instance['options'] ) );
        }

        $all_options = self::get_options();        

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
                esc_html__( 'Options', 'streamtube-core')
            );?>

            <span class="field-help">
                <?php esc_html_e( 'Leave default to show all available options', 'streamtube-core' );?>
            </span>

            <?php foreach ( $all_options as $option => $value ): ?>
                <div class="field-control">
                    <label>
                    <?php printf(
                        '<input type="checkbox" class="widefat" name="%s[]" value="%s" %s/> %s',
                        esc_attr( $this->get_field_name( 'options' ) ),
                        esc_attr( $option ),
                        in_array( $option , $instance['options'] ) ? 'checked' : '',
                        $value
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