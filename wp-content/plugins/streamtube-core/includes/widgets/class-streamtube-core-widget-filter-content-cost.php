<?php
/**
 * Define the Content Cost filter widget functionality
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
class Streamtube_Core_Widget_Filter_Content_Cost extends WP_Widget{

    protected $query_var = 'content_cost';

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'filter-content-cost-widget' ,
            esc_html__('[StreamTube] Filter - Content Cost', 'streamtube-core' ), 
            array( 
                'classname'     =>  'filter-content-cost-widget widget-filter streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Searching posts by Content Cost.', 'streamtube-core')
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
     * Get current query var
     * 
     */
    private function get_current_query_var(){

        $current = array();

        if( isset( $_REQUEST[ $this->query_var ] ) ){
             $current = wp_unslash( $_REQUEST[ $this->query_var ] );

             if( is_string( $current ) ){
                $current = array( $current );
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
            'title'             =>  esc_html__( 'Content Cost', 'streamtube-core' ),
            'button_search'     =>  '',
            'options'           =>  array_reverse( streamtube_core_get_pmp_level_type_options() ),
            'current'           =>  $this->get_current_query_var(),
            'cap'               =>  '',
            'list_type'         =>  'cloud',
            'fullwidth'         =>  ''
        ) );

        extract( $instance );

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        if( ! empty( $cap ) && ! current_user_can( $cap ) ){
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
                '<div class="content-cost-filter list-filter list-%s">',
                esc_attr( $list_type )
            );

                foreach ( $options as $key => $value ) {

                    printf(
                        '<div class="filter-%s mb-3">',
                        esc_attr( $key )
                    );
                    ?>
                        <label>
                            <?php printf(
                                '<input type="radio" name="%s" value="%s" id="filter-%s" %s>',
                                $this->query_var,
                                esc_attr( $key ),
                                esc_attr( $key ),
                                $current && in_array( $key , $current ) ? 'checked' : ''
                            );?>

                            <?php printf(
                                '<span>%s</span>',
                                esc_html( $value )
                            );?>
                        </label>
                    </div>
                    <?php
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
            'title'             =>  esc_html__( 'Content Cost', 'streamtube-core' ),
            'button_search'     =>  '',
            'cap'               =>  '',
            'fullwidth'         =>  ''
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