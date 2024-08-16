<?php
/**
 * Define the Mobile Bar functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.2
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.2
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}


class StreamTube_Core_Mobile_Bottom_Bar{

    const NAV_LOCATION = 'mobile_footer';

    /**
     *
     * Get the location ID
     * 
     */
    public function get_location(){
        /**
         *
         * Filter the location ID
         *
         * @param string location
         * 
         */
        return apply_filters( 'streamtube/core/mobile_bottom_bar/location', self::NAV_LOCATION );
    }

    /**
     *
     * Check if mobile footer navigation is enabled
     * 
     * @return boolean
     */
    public function is_active(){

        $nav = wp_is_mobile() && has_nav_menu( $this->get_location() ) && get_option( 'mobile_bottom_bar', 'on' ) ? $this->get_location() : false;

        /**
         *
         * Filter the retvar
         *
         * @param string|false
         * 
         */
        return apply_filters( 'steamtube/core/mobile_bottom_bar/is_enable', $nav );        
    }

    /**
     *
     * Register mobile bottom menu
     * 
     */
    public function register_nav_menus(){
        register_nav_menus(
            array(
                $this->get_location() =>  esc_html__( 'Mobile Footer Menu', 'streamtube-core' )
            )
        );        
    }
    
    /**
     *
     * Filter body classes
     * 
     * @param  array $classes
     * @return array $classes
     */
    public function filter_body_classes( $classes ){
        if( $this->is_active() ){
            $classes[] = 'mobile-footer-menu';
        }

        return $classes;
    }

    /**
     *
     * Output the bar
     * 
     */
    public function the_bar(){
        if( false !== $location = $this->is_active() ):
            echo '<nav id="mobile-bottom-bar" class="mobile-nav-bottom fixed-bottom border-top py-2">';
                wp_nav_menu( array(
                    'theme_location'    => $location,
                    'container'         => 'div',
                    'container_class'   => 'container-fluid',
                    'menu_class'        => 'nav nav-fill nav-justified',
                    'echo'              => true,
                    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                    'walker'            => class_exists( 'WP_Bootstrap_Navwalker' ) ? new WP_Bootstrap_Navwalker() : null
                ) );
            echo '</nav>';
            echo '<div id="mobile-padding-top"></div>';
        endif;
    }
}