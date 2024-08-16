<?php
/**
 * Define the Like Post hook functionality
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

if( ! class_exists( 'myCRED_Hook' ) ){
    return;
}

class Streamtube_Core_myCRED_Hook_Like_Post extends myCRED_Hook{

    /**
     *
     * Holds the recipient ID
     * 
     * @var integer
     */
    public $recipient_id   =   0;

    /**
     *
     * Holds the did_action
     * 
     * @var string
     */
    public $did_action     =   '';

    /**
     *
     * Holds the Post object 
     * 
     * @var WP_Post
     */
    public $post;

    /**
    * Construct
    * Used to set the hook id and default settings.
    */
   
    function __construct( $hook_prefs, $type ) {

        parent::__construct( array(
            'id'        => 'streamtube_mycred_like_post',
            'defaults'  => array(
                'creds_like'        =>  1,
                'creds_dislike'     =>  1,
                'creds_unlike'      =>  1,
                'creds_undislike'   =>  1,                
                'log_like'          =>  esc_html__( 'Award %plural% for liking post', 'streamtube-core' ),
                'log_dislike'       =>  esc_html__( 'Award %plural% for disliking post', 'streamtube-core' ),
                'log_unlike'        =>  esc_html__( 'Award %plural% for unliking post', 'streamtube-core' ),
                'log_undislike'     =>  esc_html__( 'Award %plural% for undisliking post', 'streamtube-core' )                
            )
        ), $hook_prefs, $type );
    }

    /**
     *
     * Register `streamtube_mycred_like_post`
     * 
     */
    public static function register( $installed, $point_type ){
        $installed['streamtube_mycred_like_post'] = array(
            'title'        => esc_html__( '[StreamTube] %_plural% for liking post' , 'streamtube-core' ),
            'description'  => esc_html__( 'Award %_plural% for liking post', 'streamtube-core' ),
            'callback'     => array( __CLASS__ )
        );

        return $installed;
    }

    /**
    * Run
    * Fires by myCRED when the hook is loaded.
    * Used to hook into any instance needed for this hook
    * to work.
    */
    public function run() {

        add_filter( 'mycred_all_references', array( $this , 'filter_references' ), 10, 1 );

        add_action( 'wp_post_like_action', array( $this , 'do_like_action' ), 10, 4 );
    }

    /**
     *
     * Filter $references
     * 
     * @param  array $references
     * 
     */
    public function filter_references( $references ){

        return array_merge( $references, array(
            'like_post' =>  esc_html__( 'Like Post', 'streamtube-core' ),
            'dislike_post' =>  esc_html__( 'Dislike Post', 'streamtube-core' )
        ) );
    }

    /**
     *
     * Log
     * 
     */
    public function _get_log(){

        $log = $this->prefs[ "log_{$this->did_action}" ];

        $log = sprintf(
            '%s (%s)',
            $log,
            '<a href="'. esc_url( get_permalink( $this->post->ID ) ) .'">'. $this->post->post_title .'</a>'
        );

        return $log;
    }

    /**
     *
     * Get references
     * 
     */
    public function _get_ref(){
        return $this->did_action . '_post';
    }

    /**
     *
     * has_entry query
     * 
     * @return boolean [description]
     */
    public function _has_entry(){
        return $this->has_entry( 
            $this->_get_ref(), 
            $this->post->ID, 
            $this->recipient_id, 
            $this->post->post_type, 
            $this->mycred_type 
        );
    }

    /**
     *
     * Do action
     * 
     */
    public function do_like_action( $did_action, $action, $results, $post_id = 0 ){

        if( ! get_post_status( $post_id ) ){
            return;
        }

        $this->post         = get_post( $post_id );

        $this->recipient_id = get_current_user_id();

        if( $this->post->post_author == $this->recipient_id ){
            return false;
        }

        $this->did_action   = $did_action;

        $actions = array();

        if( $this->prefs['creds_like'] != "0" ){
            $actions[] = 'like';
        }

        if( $this->prefs['creds_unlike'] != "0" ){
            $actions[] = 'unlike';
        }        

        if( $this->prefs['creds_dislike'] != "0" ){
            $actions[] = 'dislike';
        }

        if( $this->prefs['creds_undislike'] != "0" ){
            $actions[] = 'undislike';
        }

        /**
         *
         *
         * @param object $this
         * 
         */
        do_action( "streamtube/core/mycred/hook/{$this->did_action}_post", array( &$this ) );        

        if( in_array( $this->did_action , $actions ) && ! $this->_has_entry() ){

            mycred_add( 
                $this->_get_ref(), 
                $this->recipient_id, 
                $this->prefs['creds_' . $this->did_action ], 
                $this->_get_log(), 
                $this->post->ID,
                $this->post->post_type,
                $this->mycred_type
            );  

            /**
            if( in_array( $this->did_action , array( 'unlike', 'undislike' )) ){
                mycred_subtract(
                    $this->_get_ref(), 
                    $this->recipient_id, 
                    $this->prefs['creds_' . str_replace( 'un', '', $this->did_action ) ], 
                    $this->_get_log(), 
                    $this->post->ID,
                    $this->post->post_type,
                    $this->mycred_type
                );
            }
            **/

            /**
             *
             *
             * @param object $this
             * 
             */
            do_action( "streamtube/core/mycred/hook/did_{$this->did_action}_post", array( &$this ) );
        }

    }

    /**
    * Hook Settings
    * Needs to be set if the hook has settings.
    */
    public function preferences() {

        // Our settings are available under $this->prefs
        $prefs = $this->prefs;

        ?>
        <div class="hook-instance">
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'creds_like' ) ),
                            sprintf(
                                esc_html__( '%s for liking', 'streamtube-core' ),
                                $this->core->plural()
                            )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'creds_like' ) ),
                            esc_attr( $this->field_id( 'creds_like' ) ),
                            esc_attr( $this->core->number( $prefs['creds_like'] ) )
                        );?>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'log_like' ) ),
                            esc_html__( 'Log Template', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'log_like' ) ),
                            esc_attr( $this->field_id( 'log_like' ) ),
                            esc_attr( $prefs['log_like'] )
                        );?>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'creds_unlike' ) ),
                            sprintf(
                                esc_html__( '%s for unliking', 'streamtube-core' ),
                                $this->core->plural()
                            )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'creds_unlike' ) ),
                            esc_attr( $this->field_id( 'creds_unlike' ) ),
                            esc_attr( $this->core->number( $prefs['creds_unlike'] ) )
                        );?>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'log_unlike' ) ),
                            esc_html__( 'Log Template', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'log_unlike' ) ),
                            esc_attr( $this->field_id( 'log_unlike' ) ),
                            esc_attr( $prefs['log_unlike'] )
                        );?>

                    </div>
                </div>                     

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'creds_dislike' ) ),
                            sprintf(
                                esc_html__( '%s for disliking', 'streamtube-core' ),
                                $this->core->plural()
                            )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'creds_dislike' ) ),
                            esc_attr( $this->field_id( 'creds_dislike' ) ),
                            esc_attr( $this->core->number( $prefs['creds_dislike'] ) )
                        );?>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'log_dislike' ) ),
                            esc_html__( 'Log Template', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'log_dislike' ) ),
                            esc_attr( $this->field_id( 'log_dislike' ) ),
                            esc_attr( $prefs['log_dislike'] )
                        );?>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'creds_undislike' ) ),
                            sprintf(
                                esc_html__( '%s for undisliking', 'streamtube-core' ),
                                $this->core->plural()
                            )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'creds_undislike' ) ),
                            esc_attr( $this->field_id( 'creds_undislike' ) ),
                            esc_attr( $this->core->number( $prefs['creds_undislike'] ) )
                        );?>

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group mb-4">

                        <?php printf(
                            '<label for="%s">%s</label>',
                            esc_attr( $this->field_id( 'log_undislike' ) ),
                            esc_html__( 'Log Template', 'streamtube-core' )
                        );?>

                        <?php printf(
                            '<input type="text" name="%s" id="%s" value="%s" class="form-control">',
                            esc_attr( $this->field_name( 'log_undislike' ) ),
                            esc_attr( $this->field_id( 'log_undislike' ) ),
                            esc_attr( $prefs['log_undislike'] )
                        );?>

                    </div>
                </div> 
               
            </div>
        </div>
        <?php
    }    
}