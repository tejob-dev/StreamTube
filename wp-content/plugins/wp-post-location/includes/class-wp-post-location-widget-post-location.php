<?php
/**
 * Define the post location widget
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
 */

/**
 *
 * @since      1.0.0
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location_Widget_Post_Location extends WP_Widget{
    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct(
            'post-location-widget' ,
            esc_html__('[WP Post Location] Post Location', 'wp-post-location' ), 
            array( 
                'classname'     =>  'post-location-widget streamtube-widget', 
                'description'   =>  esc_html__( 'Display location of current post', 'wp-post-location' )
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
            'zoom'                  =>  0,
            'height'                =>  '400px',
            'post_id'               =>  get_the_ID(),
            'hide_empty_thumbnail'  =>  false,
        ) );

        $instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        if( ! array_key_exists( 'hide_empty_thumbnail', $instance ) ){
            $instance['hide_empty_thumbnail'] = false;
        }

        extract( $instance );

        if( ! $post_id ){
            return;
        }

        $location = WP_Post_Location_Post::get_post_locations( compact( 'post_id', 'hide_empty_thumbnail' ) );

        if( ! $location || ! $location[0]['lat'] || ! $location[0]['lng'] ){
            return;
        }

        echo $args['before_widget'];

            if( $title ){
                echo $args['before_title'] . $title . $args['after_title'];
            }

            echo WP_Post_Location_Shortcode::_the_map( array(
                'locations'             =>  WP_Post_Location_Post::get_post_locations( compact( 'post_id' ) ),
                'search_location'       =>  false,
                'find_my_location'      =>  false,
                'height'                =>  $height
            ) ); 

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
            'title'     =>  '',
            'zoom'      =>  0,
            'height'    =>  '400px',
        ) );

        ?>
        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'title' ) ),
                esc_html__( 'Title', 'wp-post-location')

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
                esc_attr( $this->get_field_id( 'zoom' ) ),
                esc_html__( 'Zoom', 'wp-post-location')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'zoom' ) ),
                esc_attr( $this->get_field_name( 'zoom' ) ),
                esc_attr( $instance['zoom'] )
            );?>

            <p>
                <?php esc_html_e( 'Set a custom zoom number or leave 0 for default', 'wp-post-location' );?>
            </p>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'height' ) ),
                esc_html__( 'Map Height', 'wp-post-location')
            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'height' ) ),
                esc_attr( $this->get_field_name( 'height' ) ),
                esc_attr( $instance['height'] )

            );?>
        </div>
        <?php
    }
}