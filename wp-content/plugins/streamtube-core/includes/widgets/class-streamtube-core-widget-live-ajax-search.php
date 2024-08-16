<?php
/**
 * Define the default search widget functionality
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
class Streamtube_Core_Widget_Live_Ajax_Search extends WP_Widget{

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'live-ajax-search-widget' ,
            esc_html__('[StreamTube] Live AJAX Search', 'streamtube-core' ), 
            array( 
                'classname'     =>  'live-ajax-search-widget streamtube-widget widget_search', 
                'description'   =>  esc_html__('[StreamTube] Live AJAX Search', 'streamtube-core')
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
     * {@inheritDoc}
     * @see WP_Widget::widget()
     */
    public function widget( $args, $instance ) {

        $instance = wp_parse_args( $instance, array(
            'title'                 =>  '',
            'ajax_live_search'      =>  ''
        ) );

        extract( $instance );

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        echo $args['before_widget'];

            if( $title ){
                echo $args['before_title'] . $title . $args['after_title'];
            }

            if( $ajax_live_search ){
                $GLOBALS['ajax_live_search'] = true;
            }

            get_search_form();

            $GLOBALS['ajax_live_search'] = false;

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
            'ajax_live_search'      =>  ''
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
                '<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
                esc_attr( $this->get_field_id( 'ajax_live_search' ) ),
                esc_attr( $this->get_field_name( 'ajax_live_search' ) ),
                checked( 'on', $instance['ajax_live_search'], false )

            );?>

            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'ajax_live_search' ) ),
                esc_html__( 'Live Ajax Search', 'streamtube-core')

            );?>
            
        </div>          
        <?php
    }

}