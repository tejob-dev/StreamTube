<?php
/**
 * Define the default Paid Membership Pro filter widget functionality
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
class Streamtube_Core_Widget_Filter_Paid_Membership_Pro extends WP_Widget{

    protected $query_var = 'pmp_level';

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'filter-pmp-widget' ,
            esc_html__('[StreamTube] Filter - Paid Membership Pro', 'streamtube-core' ), 
            array( 
                'classname'     =>  'filter-pmp-widget widget-filter streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Searching posts by Membership Levels.', 'streamtube-core')
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
    public static function get_levels(){

        if( ! function_exists( 'pmpro_sort_levels_by_order' ) ||
            ! function_exists( 'pmpro_getAllLevels' )
         ){
            return false;
        }

        return pmpro_sort_levels_by_order( pmpro_getAllLevels( true, true ));
    }

    /**
     *
     * Get level ids from all available levels
     * 
     * @param  array $levels
     * @return array
     * 
     */
    public static function get_level_ids( $levels ){
        $level_ids = array();

        if( $levels ){
            foreach ( $levels as $level ) {
                $level_ids[] = $level->id;
            }
        }

        return $level_ids;
    }

    /**
     *
     * Get current
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
            'title'             =>  esc_html__( 'Membership Levels', 'streamtube-core' ),
            'levels'            =>  array(),
            'all_levels'        =>  self::get_levels(),
            'button_search'     =>  '',
            'cap'               =>  '',
            'list_type'         =>  'cloud',
            'current'           =>  $this->get_current_query_var(),
            'fullwidth'         =>  ''
        ) );

        if( is_string( $instance['levels'] ) ){
            $instance['levels'] = array_map( 'trim' , explode(',', $instance['levels']));
        }        

        extract( $instance );

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        if( ! empty( $cap ) && ! current_user_can( $cap ) || ! $all_levels ){
            return;
        }        

        if( ! $levels ){
            $levels = self::get_level_ids( $all_levels );
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
                '<div class="post-date-filter list-filter list-%s">',
                esc_attr( $list_type )
            );

                for ( $i = 0; $i < count( $levels ); $i++) {

                    if( array_key_exists( $levels[$i] , $all_levels ) ){
                        $level = $all_levels[ $levels[$i] ];

                        printf(
                            '<div class="filter-%s mb-3">',
                            esc_attr( $level->id )
                        );
                        ?>
                        
                            <label>
                                <?php printf(
                                    '<input type="checkbox" name="%s[]" value="%s" id="filter-%s" %s>',
                                    $this->query_var,
                                    esc_attr( $level->id ),
                                    esc_attr( $level->id ),
                                    $current && in_array( $level->id , $current ) ? 'checked' : ''
                                );?>

                                <?php printf(
                                    '<span>%s</span>',
                                    esc_html( $level->name )
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
            'title'             =>  esc_html__( 'Membership Levels', 'streamtube-core' ),
            'levels'            =>  array(),
            'button_search'     =>  '',
            'cap'               =>  '',
            'list_type'         =>  'cloud',
            'fullwidth'         =>  '',
            'multiple'          =>  ''
        ) );

        if( is_string( $instance['levels'] ) ){
            $instance['levels'] = array_map( 'trim' , explode(',', $instance['levels']));
        }

        $membership_levels = self::get_levels();

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

        <?php if( $membership_levels ): ?>

            <div class="field-group" style="max-height: 300px;overflow: scroll;">
                <?php printf(
                    '<label>%s</label>',
                    esc_html__( 'Membership Levels', 'streamtube-core')
                );?>
                <span class="field-help">
                    <?php esc_html_e( 'Leave empty to show all available membership levels', 'streamtube-core' );?>
                </span>
                <?php foreach ( $membership_levels as $level ): ?>
                    <div class="field-control">
                        <label>
                        <?php printf(
                            '<input type="checkbox" class="widefat" name="%s[]" value="%s" %s/> %s',
                            esc_attr( $this->get_field_name( 'levels' ) ),
                            esc_attr( $level->id ),
                            in_array( $level->id, $instance['levels'] ) ? 'checked' : '',
                            esc_html( $level->name )
                        );?>
                        </label>
                    </div>
                <?php endforeach ?> 
            </div>
        <?php endif;?>

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