<?php
/**
 * Define the Buy Points widget functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.1
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

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_myCRED_Widget_Buy_Points extends WP_Widget{

    /**
     *
     * Holds an array of amounts
     * 
     * @var array
     */
    public $amounts = array();

    /**
     *
     * Holds the widget instance
     * 
     * @var array
     */
    public $instance = array();

    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'mycred-buy-points-widget' ,
            esc_html__('[StreamTube] myCred - Buy Points', 'streamtube-core' ), 
            array( 
                'classname'     =>  'mycred-buy-points-widget widget-mycred streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] myCred - Buy Points', 'streamtube-core')
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
     * Default widget settings
     * 
     * @return array
     * 
     */
    public function get_defaults(){
        return array(
            'title'             =>  esc_html__( 'Buy Points', 'streamtube-core' ),
            'button'            =>  esc_html__( 'Buy Now', 'streamtube-core' ),
            'button_style'      =>  'danger',
            'button_classes'    =>  array( 'btn', 'btn-block', 'btn-submit', 'w-100' ),
            'gateway'           =>  '',
            'ctype'             =>  'mycred_default',
            'amount'            =>  '',
            'gift_to'           =>  '',
            'gift_by'           =>  '',
            'e_rate'            =>  '',
            'inline'            =>  1,
            'amounts_column'    =>  4,
            'buy_points_url'    =>  ''
        );
    }

    /**
     *
     * Render gateway array
     * 
     * @return array
     */
    public static function get_gateway_options(){

        $options = array();

        global $buycred_instance;

        if( ! $buycred_instance ){
            return  $options;
        }

        $active_gateways    = $buycred_instance->active;

        if( count( $active_gateways ) > 0 ){
            foreach ( $active_gateways as $gateway_id => $info ){
                $options[ $gateway_id ] = $info['title'];
            }
        }

        return $options;
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::widget()
     */
    public function widget( $args, $instance ) {

        $this->instance = wp_parse_args( $instance, $this->get_defaults() );

        if( $this->instance['gift_to'] == 'author' ){
            if( is_singular() ){
                $this->instance['gift_to'] = $GLOBALS['post']->post_author;    
            }else{
                // Reset gift_to param
                $this->instance['gift_to'] = '';    
            }
        }

        do_action( 'streamtube/core/mycred/widget/buy_points', array( &$this ) );

        extract( $this->instance );

        if( $buy_points_url ){
            $buy_points_url = get_permalink( $buy_points_url );
        }else{
            if( is_singular() ){
                $buy_points_url = get_permalink();
            }else{
                $buy_points_url = home_url('/');    
            }
        }

        $_amounts = array_filter( array_map( 'trim' , explode(',', $amount )) );

        if( is_array( $_amounts ) && count( $_amounts ) > 1 ){
            $this->amounts = $_amounts;
        }

        $title = apply_filters( 'widget_title', $title );

        $button_classes[] = 'btn-' . $button_style;

        if( $this->amounts ){
            $button_classes[] = 'disabled';
        }

        echo $args['before_widget'];

            if( $title ){
                echo $args['before_title'] . $title . $args['after_title'];
            }

            if( function_exists( 'mycred_render_buy_form_points' ) ){

                /**
                 *
                 * Fires before rendering form
                 * 
                 */
                do_action( 'streamtube/core/mycred/widget/buy_points/form/before', array( &$this ) );

                $form_classes = array(
                    'myCRED-buy-form',
                    'buy-form-creds'
                );

                if( $this->amounts ){
                    $form_classes[] = 'buy-form-amount';
                }

                if( $gift_to ){
                    $form_classes[] = 'buy-form-gift';
                }

                $output = mycred_render_buy_form_points( array_merge( $this->instance, array(
                    'amount'    =>  $this->amounts ? '' : $amount
                ) ) );

                $output = preg_replace(
                    '/<form(.*?)action=""/', 
                    '<form$1action="'. esc_url( $buy_points_url ) .'"', 
                    $output 
                );

                $output = str_replace( 
                    'myCRED-buy-form', 
                    join( ' ', $form_classes ),
                    $output 
                );
                
                $output = str_replace( 
                    'button btn btn-block btn-lg', 
                    join( ' ', $button_classes), 
                    $output 
                );

                if( $this->amounts ){

                    /**
                     *
                     * Fires before amount list
                     * 
                     */
                    do_action( 'streamtube/core/mycred/widget/buy_points/amounts/before', array( &$this ) );

                    printf(
                        '<div class="amounts mt-4"><div class="row row-cols-2 row-cols-md-%s">',
                        esc_attr( $amounts_column )
                    );

                        $mycred = mycred( $ctype );

                        for ( $i =0; $i < count( $this->amounts ); $i++ ) { 

                            echo '<div class="buy-cred-url text-center mb-3">';
                                printf(
                                    '<button class="mycred-buy-link btn btn-amount btn-outline-success" data-amount="%s">%s</button>',
                                    $this->amounts[$i],
                                    $mycred->format_creds( $this->amounts[$i] )
                                );
                                
                            echo '</div>';
                        }
                    echo '</div></div>';

                    /**
                     *
                     * Fires before amount list
                     * 
                     */
                    do_action( 'streamtube/core/mycred/widget/buy_points/amounts/after', array( &$this ) );                    
                }

                echo $output;

                /**
                 *
                 * Fires before rendering form
                 * 
                 */
                do_action( 'streamtube/core/mycred/widget/buy_points/form/after', array( &$this ) );                
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
        $instance = wp_parse_args( $instance, $this->get_defaults() );
        
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
                esc_attr( $this->get_field_id( 'button' ) ),
                esc_html__( 'Button Text', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'button' ) ),
                esc_attr( $this->get_field_name( 'button' ) ),
                esc_attr( $instance['button'] )
            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'button_style' ) ),
                esc_html__( 'Button Style', 'streamtube-core')

            );?>
            
            <?php printf(
                '<select class="widefat" id="%s" name="%s">',
                esc_attr( $this->get_field_id( 'button_style' ) ),
                esc_attr( $this->get_field_name( 'button_style' ) )
            );?>

                <?php foreach ( streamtube_core_get_button_styles() as $key => $value ): ?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $key ),
                        selected( $instance['button_style'], $key, false ),
                        esc_html( $value )
                    );?>
                    
                <?php endforeach ?>

            </select>
        </div>        

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'gateway' ) ),
                esc_html__( 'Gateway', 'streamtube-core')

            );?>
            
            <?php printf(
                '<select class="widefat" id="%s" name="%s">',
                esc_attr( $this->get_field_id( 'gateway' ) ),
                esc_attr( $this->get_field_name( 'gateway' ) )
            );?>

                <option value=""><?php esc_html_e( 'Select a gateway', 'streamtube-core' );?></option>

                <?php foreach ( self::get_gateway_options() as $key => $value ): ?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $key ),
                        selected( $instance['gateway'], $key, false ),
                        esc_html( $value )
                    );?>
                    
                <?php endforeach ?>

            </select>
        </div>

        <?php if( function_exists( 'mycred_get_types' ) ): ?>
            <div class="field-control">
                <?php printf(
                    '<label for="%s">%s</label>',
                    esc_attr( $this->get_field_id( 'ctype' ) ),
                    esc_html__( 'Point Type', 'streamtube-core')

                );?>
                
                <?php printf(
                    '<select class="widefat" id="%s" name="%s">',
                    esc_attr( $this->get_field_id( 'ctype' ) ),
                    esc_attr( $this->get_field_name( 'ctype' ) )
                );?>

                    <?php foreach ( mycred_get_types() as $key => $value ): ?>

                        <?php printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $key ),
                            selected( $instance['ctype'], $key, false ),
                            esc_html( $value )
                        );?>
                        
                    <?php endforeach ?>

                </select>
            </div>
        <?php endif;?>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'amount' ) ),
                esc_html__( 'Amount', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'amount' ) ),
                esc_attr( $this->get_field_name( 'amount' ) ),
                esc_attr( $instance['amount'] )
            );?>

            <p class="field-help">
                <?php esc_html_e( 'Accepts either a number or a string separated by commas', 'streamtube-core' );?>
            </p>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'amounts_column' ) ),
                esc_html__( 'Amount Columns', 'streamtube-core')
            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'amounts_column' ) ),
                esc_attr( $this->get_field_name( 'amounts_column' ) ),
                esc_attr( $instance['amounts_column'] )
            );?>
        </div>        

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'gift_to' ) ),
                esc_html__( 'Gift To', 'streamtube-core')
            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'gift_to' ) ),
                esc_attr( $this->get_field_name( 'gift_to' ) ),
                esc_attr( $instance['gift_to'] )
            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'gift_by' ) ),
                esc_html__( 'Gift By', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'gift_by' ) ),
                esc_attr( $this->get_field_name( 'gift_by' ) ),
                esc_attr( $instance['gift_by'] )
            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'e_rate' ) ),
                esc_html__( 'Rate', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'e_rate' ) ),
                esc_attr( $this->get_field_name( 'e_rate' ) ),
                esc_attr( $instance['e_rate'] )
            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'buy_points_url' ) ),
                esc_html__( 'Buy Points URL', 'streamtube-core')

            );?>
            
            <?php printf(
                '<select class="widefat" id="%s" name="%s">',
                esc_attr( $this->get_field_id( 'buy_points_url' ) ),
                esc_attr( $this->get_field_name( 'buy_points_url' ) )
            );?>
                <option value=""><?php esc_html_e( 'Select ...', 'streamtube-core' );?></option>

                <?php foreach ( get_pages( array( 'post_status' => 'publish' ) ) as $page ): ?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $page->ID ),
                        selected( $instance['buy_points_url'], $page->ID, false ),
                        esc_html( $page->post_title )
                    );?>
                    
                <?php endforeach ?>

            </select>

            <p class="field-help">
                <?php esc_html_e( 'You can use whatever URL you want', 'streamtube-core' );?>
            </p>
        </div>        
        <?php        
    }    
}