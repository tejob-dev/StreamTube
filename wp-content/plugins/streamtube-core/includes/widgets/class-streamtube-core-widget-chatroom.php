<?php
/**
 * Define the Live Chat Room template widget functionality
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
class Streamtube_Core_Widget_LiveChat extends WP_Widget{

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'livechatroom-widget' ,
            esc_html__('[StreamTube] Live Chat Room', 'streamtube-core' ), 
            array( 
                'classname'     =>  'livechatroom-widget streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Live Chat Room', 'streamtube-core')
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

        global $streamtube;

        $instance = wp_parse_args( $instance, array(
            'title'     =>  '',
            'post_id'   =>  ''
        ) );

        if( ! $instance['post_id'] ){
            return;
        }

        $instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );        

        echo $args['before_widget'];

            if( $instance['title'] ){
                echo $args['before_title'] . $instance['title'] . $args['after_title'];
            }

            $streamtube->get()->better_messages->get_chat_room_output( $instance['post_id'], true );

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
            'post_id'   =>  ''
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
                esc_attr( $this->get_field_id( 'post_id' ) ),
                esc_html__( 'Chat Room (Post) ID', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'post_id' ) ),
                esc_attr( $this->get_field_name( 'post_id' ) ),
                esc_attr( $instance['post_id'] )

            );?>
        </div>
        <?php
    }
}