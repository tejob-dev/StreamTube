<?php
/**
 * Define the custom comments template widget functionality
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
class Streamtube_Core_Widget_Comments_Template extends WP_Widget{

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'comments-template-widget' ,
            esc_html__('[StreamTube] Comments Template', 'streamtube-core' ), 
            array( 
                'classname'     =>  'comments-template-widget streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] Comments Template', 'streamtube-core')
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

        if( ! is_singular( 'video' ) ){
            return;
        }

        $instance = wp_parse_args( $instance, array(
            'max_height'     =>  ''
        ) );        

        echo $args['before_widget'];

            printf(
                '<div class="comments-widget-container" data-max-height="%s">',
                esc_attr( $instance['max_height'] )
            );

                comments_template();

            echo '</div>';

            ?>
            <script type="text/javascript">

                var widget      = '#<?php echo $this->id; ?>';
                
                var maxHeight   = jQuery( widget ).find( '.comments-widget-container' ).attr( 'data-max-height' );

                if( maxHeight ){
                    jQuery( widget ).find( 'ul#comments-list' )
                    .css( 'max-height', maxHeight )
                    .css( 'overflow-y', 'scroll' );
                }

            </script>
            <?php

        echo $args['after_widget'];

        do_action( 'streamtube/core/widget/comments_template/loaded' );

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
            'max_height'    =>  ''
        ) );
        ?>
        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'max_height' ) ),
                esc_html__( 'Max Height', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'max_height' ) ),
                esc_attr( $this->get_field_name( 'max_height' ) ),
                esc_attr( $instance['max_height'] )
            );?>

            <p>
                <?php esc_html_e( 'Max Height of Comment List in Pixel, e.g: 500px', 'streamtube-core' );?>
            </p>
        </div>
        <?php
    }
}