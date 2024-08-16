<?php
/**
 * Define the default submit date filter widget functionality
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
class Streamtube_Core_Widget_Filter_Taxonomy extends WP_Widget{

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'filter-taxonomy-widget' ,
            esc_html__('[StreamTube] Filter - Taxonomy', 'streamtube-core' ), 
            array( 
                'classname'     =>  'filter-taxonomy-widget-class widget-filter streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Searching posts by custom taxonomy.', 'streamtube-core')
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
     * Field name
     * 
     */
    private function get_taxonomy_query_var( $taxonomy ){
        return $taxonomy;
    }

    /**
     *
     * Get current
     * 
     */
    private function get_current_query_var( $taxonomy ){

        $current = array();

        $query_name = $this->get_taxonomy_query_var( $taxonomy );

        if( isset( $_REQUEST[ $query_name ] ) ){
             $current = wp_unslash( $_REQUEST[ $query_name ] );

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
            'title'             =>  esc_html__( 'Taxonomy', 'streamtube-core' ),
            'taxonomy'          =>  'categories',
            'hide_empty'        =>  true,
            'orderby'           =>  'count',
            'order'             =>  'DESC',            
            'number'            =>  5,
            'include'           =>  '',
            'multiple'          =>  '',
            'button_search'     =>  '',
            'current'           =>  '',
            'cap'               =>  '',
            'list_type'         =>  'list',
            'fullwidth'         =>  '',
            'count'             =>  ''
        ) );

        extract( $instance );

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        if( ! empty( $cap ) && ! current_user_can( $cap ) ){
            return;
        }

        $taxonomy_hierarchical  = is_taxonomy_hierarchical( $taxonomy );
        $field_name             = $this->get_taxonomy_query_var( $taxonomy );

        $current = $this->get_current_query_var( $taxonomy );

        $terms = get_terms( compact(
            'taxonomy',
            'hide_empty',
            'include',
            'number',
            'orderby',
            'order'
        ) );

        if( ! $terms ){
            return;
        }

        /**
         *
         * Filter widget args
         * 
         */
        $args           = apply_filters( 'streamtube/core/widget/filter_widget_args', $args, $instance );

        $find           = "filter-taxonomy-widget-class";
        $replaceWith    = "filter-taxonomy-widget-class filter-taxonomy-{$taxonomy}-widget";

        $args['before_widget'] = str_replace( $find, $replaceWith, $args['before_widget'] );

        echo $args['before_widget'];

            if( $title ){
                echo $args['before_title'] . $title . $args['after_title'];
            }

            ob_start();

            printf(
                '<div class="submit-date-filter list-filter list-%s">',
                esc_attr( $list_type )
            );

                foreach ( $terms as $term ) {

                    $field_id       = $this->id . $term->slug;

                    $field_value    = $term->slug;

                    printf(
                        '<div class="term-%s mb-3">',
                        esc_attr( $field_value )
                    );
                    ?>
                        <label>
                            <?php printf(
                                '<input type="%s" name="%s" value="%s" id="filter-%s" %s>',
                                $multiple ? 'checkbox' : 'radio',
                                $field_name . ( $multiple ? '[]' : '' ),
                                esc_attr( $field_value ),
                                esc_attr( $field_id ),
                                $current && in_array( $field_value , $current ) ? 'checked' : ''
                            );?>

                            <?php printf(
                                '<span>%s %s</span>',
                                esc_html( ucwords($term->name) ),
                                $count ? '('. number_format_i18n( $term->count ) .')' : ''
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
            'title'             =>  esc_html__( 'Taxonomy', 'streamtube-core' ),
            'taxonomy'          =>  'categories',
            'orderby'           =>  'count',
            'order'             =>  'DESC',
            'include'           =>  '',
            'multiple'          =>  '',
            'button_search'     =>  '',
            'post_type'         =>  array_keys( Streamtube_Core_Widget_Filter_Content_Type::get_content_types() ),
            'count'             =>  '',
            'number'            =>  5,
            'cap'               =>  '',
            'fullwidth'         =>  '',
            'list_type'         =>  'list' // cloud
        ) );

        if( is_array( $instance['include'] ) ){
            $instance['include'] = implode(',', $instance['include'] );
        }

        $taxonomies = get_object_taxonomies( $instance['post_type'], 'object' );
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
                esc_attr( $this->get_field_id( 'taxonomy' ) ),
                esc_html__( 'Taxonomy', 'streamtube-core')
            );?>
            
            <?php printf(
                '<select class="widefat" name="%s">',
                esc_attr( $this->get_field_name( 'taxonomy' ) )
            );?>

                <?php if( $taxonomies ): ?>

                    <?php foreach ( $taxonomies as $tax => $object ):?>

                        <?php printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $tax ),
                            selected( $tax, $instance['taxonomy'], false ),
                            esc_html( $object->label )
                        );?>

                    <?php endforeach; ?>

                <?php endif;?>

            </select>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'include' ) ),
                esc_html__( 'Term IDs', 'streamtube-core')
            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'include' ) ),
                esc_attr( $this->get_field_name( 'include' ) ),
                esc_attr( $instance['include'] )
            );?>

            <span class="field-help">
                <?php esc_html_e( 'Specific term IDs, separated by commas', 'streamtube-core' );?>
            </span>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'number' ) ),
                esc_html__( 'Number', 'streamtube-core')
            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'number' ) ),
                esc_attr( $this->get_field_name( 'number' ) ),
                esc_attr( $instance['number'] )
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
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'orderby' ) ),
                esc_html__( 'Order by', 'streamtube-core')

            );?>

            <?php printf(
                '<select class="widefat" id="%s" name="%s" />',
                esc_attr( $this->get_field_id( 'orderby' ) ),
                esc_attr( $this->get_field_name( 'orderby' ) )

            );?>

                <?php foreach( streamtube_core_get_term_orderby_options() as $orderby => $orderby_text ):?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $orderby ),
                        selected( $instance['orderby'], $orderby, false ),
                        esc_html( $orderby_text )
                    );?>

                <?php endforeach;?>

            </select><!-- end <?php echo $this->get_field_id( 'orderby' );?> -->
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'order' ) ),
                esc_html__( 'Order', 'streamtube-core')

            );?>

            <?php printf(
                '<select class="widefat" id="%s" name="%s" />',
                esc_attr( $this->get_field_id( 'order' ) ),
                esc_attr( $this->get_field_name( 'order' ) )

            );?>

                <?php foreach( streamtube_core_get_order_options() as $order => $order_text ):?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $order ),
                        selected( $instance['order'], $order, false ),
                        esc_html( $order_text )
                    );?>

                <?php endforeach;?>

            </select><!-- end <?php echo $this->get_field_id( 'order' );?> -->
        </div>        

        <div class="field-control">
           
            <?php printf(
                '<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
                esc_attr( $this->get_field_id( 'multiple' ) ),
                esc_attr( $this->get_field_name( 'multiple' ) ),
                checked( $instance['multiple'], 'on', false )
            );?>
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'multiple' ) ),
                esc_html__( 'Multiple selection', 'streamtube-core')
            );?>            
        </div>

        <div class="field-control">
           
            <?php printf(
                '<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
                esc_attr( $this->get_field_id( 'count' ) ),
                esc_attr( $this->get_field_name( 'count' ) ),
                checked( $instance['count'], 'on', false )
            );?>
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'count' ) ),
                esc_html__( 'Show count', 'streamtube-core')
            );?>            
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