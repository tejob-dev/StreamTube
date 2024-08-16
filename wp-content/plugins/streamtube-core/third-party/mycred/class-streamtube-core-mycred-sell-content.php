<?php
/**
 * Define the myCred functionality
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

class Streamtube_Core_myCRED_Sell_Content extends Streamtube_Core_myCRED_Base{

    /**
     *
     * Holds settings
     * 
     * @var array
     *
     * @since 1.1
     * 
     */
    protected $settings;

    protected $User;

    protected $Post;

    /**
     *
     * Class contructor
     * 
     * @param array $settings
     *
     * @since 1.1
     * 
     */
    public function __construct( $settings = array() ){
        $this->settings = $settings;

        $this->User     = new Streamtube_Core_User();

        $this->Post     = new Streamtube_Core_Post();
    }

    /**
     *
     * Check if addon activated
     * 
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_activated(){
        return class_exists( 'myCRED_Sell_Content_Module' );
    }

    /**
     *
     * Get addon settings, Alias of mycred_sell_content_settings()
     * 
     * @return array
     *
     * @since 1.1
     * 
     */
    public function get_mycred_settings(){
        return function_exists( 'mycred_sell_content_settings' ) ? mycred_sell_content_settings() : array();
    }

    /**
     *
     * Check if post type is for sale
     * 
     * @param  string  $post_type
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_post_type_for_sale( $post_type = 'video' ){
        return function_exists( 'mycred_post_type_for_sale' ) ? mycred_post_type_for_sale( $post_type ) : false;
    }

    /**
     *
     * Check if given post for sale
     * 
     * @param  int|WP_Post  $post
     * @return boolean
     *
     * @since 1.1
     * 
     */
    public function is_post_for_sale( $post ){
        return function_exists( 'mycred_post_is_for_sale' ) ? mycred_post_is_for_sale( $post ) : false;
    }

    /**
     *
     * Get post price
     * 
     * @param  int $post_id
     * @param  string $point_type
     * @return mycred_get_content_price()
     *
     * @since 1.1
     * 
     */
    public function get_post_price( $post_id = NULL, $point_type = 'mycred_default' ){
        return mycred_get_content_price( $post_id, $point_type );
    }

    /**
     *
     * Check if current logged in user can set post price
     *
     * Always return true if is admin or editor
     * 
     * @param  integer $post_id
     * @return true if can, otherwise is false
     *
     * @since 1.1
     * 
     */    
    public function can_user_set_price( $post_id = null, $post_type = null ){

        $_post_type = $post_id ? get_post_type( $post_id ) : $post_type;

        if( ! $this->is_post_type_for_sale( $_post_type ) ){
            return false;
        }

        /**
         *
         * Always return true if current logged in user is admin or editor
         * 
         */
        if( Streamtube_Core_Permission::moderate_posts() ){
            return true;
        }

        if( ! $this->settings['author_driven_pricing'] ){
            return false;
        }

        if( $this->settings['sell_content_verified_user'] && ! $this->User->is_verified() ){
            return false;
        }

        if( $post_id && current_user_can( 'edit_post', $post_id ) ){
            return true;
        }

        /**
         *
         * Filter the permission
         * 
         */
        return apply_filters( 'streamtube/core/mycred/can_user_set_price', false, $post_id, $post_type );
    }

    /**
     *
     * Render sell content
     * 
     * @param  array $args
     * @param  string $content
     * @return stirng
     *
     * @since 1.1
     * 
     */
    public function render_sell_content( $content, $setup ){

        $content = mycred_render_sell_this( array(), $content );

        $content = str_replace( 'text-center', 'text-center position-absolute top-50 start-50 translate-middle', $content );

        if( strpos( $content , 'mycred-sell-this-wrapper' ) !== false ){

            $content = str_replace( 'mycred-sell-this-wrapper', 'mycred-sell-this-wrapper error-message', $content );

            $content .= sprintf(
                '<div class="player-poster bg-cover" style="background-image:url(%s)"></div>',
                $setup['poster2'] ? $setup['poster2'] : $setup['poster']
            );

            if( $setup['is_embed'] ){
                $content = preg_replace(
                    '/<button\s+.*?>(.*?)<\/button>/s',
                    '<a href="'. esc_url( get_permalink( $setup['mediaid'] ) ) .'" class="mycred-buy-this-content-button btn btn-primary btn-lg">$1</a>',
                    $content
                );
            }

            $content = str_replace( '%login_url%', wp_login_url( get_permalink( $setup['mediaid'] ) ), $content );

            $trailer_url = '';

            if( $setup['trailer'] ){

                $trailer_url = sprintf(
                    '<a class="btn btn-danger btn-trailer px-4" href="%s">%s</a>',
                    esc_url( add_query_arg( 
                        array( 'view_trailer' => '1', 'autoplay' => '1' ),
                        get_permalink( $setup['mediaid'] )
                    ) ),
                    esc_html__( 'Trailer', 'streamtube-core' )
                );                

            }

            $content = str_replace( '%view_trailer%', $trailer_url, $content );
        }

        return $content;
    }

    /**
     * Filter player advertisements
     * Remove Ad if user purchased levels
     * 
     */
    public function filter_advertisements( $vast_tag_url, $setup, $source  ){

        if( ! get_post_status( $setup['mediaid'] ) ){
            return $vast_tag_url;
        }

        /**
         * Return setup if Sell Content isn't activated yet.
         */
        if( ! $this->is_activated() 
            || ! $this->settings['sell_video_content']
            || ! $this->is_post_for_sale( $setup['mediaid'] ) ){
            return $vast_tag_url;
        }

        if( $this->is_post_for_sale( $setup['mediaid'] ) && 
            mycred_user_paid_for_content( get_current_user_id(), $setup['mediaid'] ) &&
            $this->settings['disable_advertisement']
        ){
            return false;
        }

        return $vast_tag_url;
    }

    /**
     *
     * Filter video player, return buy form if post is for sale
     * 
     * @param  string $player
     * @return string
     *
     * @since 1.1
     * 
     */
    public function filter_player_output( $player, $setup ){
        /**
         * Return player if Sell Content isn't activated yet.
         */
        if( ! $this->is_activated() 
            || ! $this->settings['sell_video_content'] 
            || ! get_post_status( $setup['mediaid'] ) ){
            return $player;
        }

        if( $setup['trailer'] ){

            global $mycred_partial_content_sale;

            $mycred_partial_content_sale = true;

            return $player;
        }

        // Always return true if requested part is in activity loop
        global $activities_template;

        if( $activities_template ){
            add_filter( 'mycred_post_type_for_sale', function( $for_sale, $post_type ){
                return true;
            }, 10, 2 );

            add_filter( 'mycred_sell_this_get_post_ID', function( $post_id ) use ( $setup ){
                return $setup['mediaid'];
            } );            
        }

        /**
         * Return player if post isn't for sale
         */
        if( ! $this->is_post_for_sale( $setup['mediaid'] ) ){
            return $player;
        }

        return $this->render_sell_content( $player, $setup );
    }   

    /**
     *
     * Filter oembed html
     * 
     */
    public function filter_player_embed_output( $oembed_html, $setup ){
        return $this->filter_player_output( $oembed_html, $setup );
    }

    /**
     *
     * Filter Download permission
     * 
     */
    public function filter_download_permission( $can ){

        if( function_exists( 'mycred_post_is_for_sale' )
            && mycred_post_is_for_sale( get_the_ID() ) 
            && ! mycred_user_paid_for_content( get_current_user_id(), get_the_ID() ) ){
            $can = false;
        }      

        return $can;
    }

    /**
     *
     * Update prices from frontend form
     * 
     * @param  int $post_id
     * @return WP_Error|Int
     *
     * @since 1.1
     * 
     */
    public function update_price( $post_id ){

        /**
         * Return player if Sell Content isn't activated yet.
         */
        if( ! $this->is_activated() ){
            return $post_id;
        }        

        if( ! $this->can_user_set_price( $post_id ) ){
            return $post_id;
        }

        if( ! array_key_exists( 'sell_content', $_POST ) ){
            return $post_id;
        }

        $sell_content = $_POST['sell_content'];

        if( ! is_array( $sell_content ) || ! isset( $_POST['point_types'] ) ){
            return $post_id;
        }

        $point_types = explode( ',', $_POST['point_types'] );

        for ( $i = 0; $i < count( $point_types ); $i++) { 

            if( array_key_exists( $point_types[$i], $sell_content ) ){

                $price = (int)$sell_content[ $point_types[$i] ]['price'];
                $expire = (int)$sell_content[ $point_types[$i] ]['expire'];

                $metadata = array(
                    'status'    =>  $price > 0 ? 'enabled' : 'disabled',
                    'price'     =>  $price,
                    'expire'    =>  $price > 0 ? $expire : 0
                );

                if( $point_types[$i] == 'mycred_default' ){
                    update_post_meta( $post_id, 'myCRED_sell_content', $metadata );
                }else{
                    update_post_meta( $post_id, 'myCRED_sell_content_' . sanitize_key( $point_types[$i] ), $metadata );
                }
            }
        }

        return $post_id;
    }

    /**
     *
     * Load custom price form for frontend dashboard
     *
     * $args{
     *     $post
     *     $post_type
     * }
     * 
     * @since 1.1
     * 
     */
    public function load_metabox_price(){

        /**
         * Return player if Sell Content isn't activated yet.
         */
        if( ! $this->is_activated() ){
            return;
        }

        global $post;

        if( $post ){

            if( ! $this->can_user_set_price( $post->ID, $post->post_type ) ){
                return;
            }

            if( $post->post_type == 'video' && $this->settings['sell_video_content'] ){
                return $this->load_template( 'form-price.php', true );    
            }

            if( $post->post_type == 'post' && $this->settings['sell_post_content'] ){
                return $this->load_template( 'form-price.php', true );    
            }
        }

        if( ! $post && $this->settings['sell_post_content'] ){
            return $this->load_template( 'form-price.php', true );    
        }
    }
}