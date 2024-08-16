<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/**
 *
 * @since      1.0.0
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location_Shortcode{

    /**
     *
     * The Map shortcode
     * 
     * @param  array  $args
     * @param  string $content
     * @return HTML
     *
     * @since 1.0.0
     * 
     */
    public static function _the_map( $args = array(), $content = '' ){

        $settings     = WP_Post_Location_Customizer::get_settings();

        $theme_mode   = function_exists( 'streamtube_get_theme_mode' ) ?  streamtube_get_theme_mode() : 'light';

        $args = wp_parse_args( $args, array(
            'locations'             =>  'all',
            'center'                =>  array(
                'lng'   =>  (float)$settings['default_longitude'],
                'lat'   =>  (float)$settings['default_latitude'],
            ),
            'zoom'                  =>  (int)$settings['default_zoom'],
            'max_zoom'              =>  19,
            'height'                =>  '80vh',
            'search_location'       =>  true,
            'find_my_location'      =>  true,
            'styles'                =>  '',
            'default_style_json'    =>  $settings['default_style_json'],
            'dark_style_json'       =>  $settings['dark_style_json'],
            'dark_class'            =>  '',
            'edit_mode'             =>  false,
            'mapTypeId'             =>  $settings['map_type'],
            'search_field'          =>  'search-input',
            'is_builder'            =>  false,
            'video_marker'          =>  $settings['marker_video'],
            'post_marker'           =>  $settings['marker_post'],
            'ost_geocoding_api'     =>  $settings['ost_geocoding_api']
        ) );

        if( ! $args['search_location'] ){
            $args['search_field'] = '';
        }

        if( $settings['restriction'] ){
            $args['restriction'] = array(
                'latLngBounds'  =>  array(
                    'north' =>  (float)$settings['north'],
                    'south' =>  (float)$settings['south'],
                    'east'  =>  (float)$settings['east'],
                    'west'  =>  (float)$settings['west']
                )
            );
        }

        if( (int)$args['zoom'] == 0 ){
            $args['zoom'] = 3;
        }

        if( $settings['map_provider'] == 'googlemap' ){
            wp_enqueue_script( 'google-map' );

            $args['gestureHandling'] = $settings['gesturehandling'];

            if( is_array( $args['locations'] ) ){
                if( count( $args['locations'] ) == 1 ){
                    $args['center'] = array(
                        'lat'   =>  (float)$args['locations'][0]['lat'],
                        'lng'   =>  (float)$args['locations'][0]['lng']
                    );

                    $args['zoom'] = (int)$args['locations'][0]['zoom'];
                }
            }

            if( is_string( $args['locations'] ) ){
                $args['center'] = array(
                    'lat'   =>  0,
                    'lng'   =>  0
                );
            }

            if( $args['default_style_json'] ){
                $args['default_style_json'] = json_decode( $args['default_style_json'] );
            }

            if( $args['dark_style_json'] && $settings['dark_style_json'] ){
                $args['dark_style_json'] = json_decode( $settings['dark_style_json'] );
            }

            if( $theme_mode == 'light' ){
                $args['styles'] = $args['default_style_json'];
            }

            if( $theme_mode == 'dark' ){
                $args['styles'] = $args['dark_style_json'];
            }            

        }else{
            wp_enqueue_style( 'leaflet' );
            wp_enqueue_script( 'leaflet' );
        }

        wp_enqueue_style( 'wp-post-location' );
        wp_enqueue_script( 'wp-post-location' );

        /**
         *
         * Filter map args
         * 
         * @var array $args;
         *
         * @since 1.0.0
         */
        $args = apply_filters( 'wp_post_location_map_args', $args );

        extract( $args );

        ob_start();

        ?>
        <div class="wp-post-location-map position-relative">            

            <?php if( ( $search_location || $find_my_location ) && ! $is_builder ): ?>
                <div class="global-map__topbar w-100 p-4 bg-light">

                    <?php 
                    if( $search_location ){
                        load_template( WP_POST_LOCATION_PATH_PUBLIC . '/partials/search-location.php', false, $args );
                    }?>                    

                    <div class="d-flex gap-3 align-items-center">

                        <?php 
                        if( $find_my_location ){
                            load_template( WP_POST_LOCATION_PATH_PUBLIC . '/partials/find-my-location.php', false, $args );
                        }?>

                        <?php 
                        if( $edit_mode ){
                            ?><div class="ms-auto"><?php
                            load_template( WP_POST_LOCATION_PATH_PUBLIC . '/partials/update-location.php', false, $args );
                            ?></div><?php
                        }?>                        

                    </div>
                    
                </div>
            <?php endif;?>

            <?php if( $is_builder && $find_my_location ){
                ?><div class="topbar-overlay position-absolute top-100 start-50 translate-middle"><?php
                load_template( WP_POST_LOCATION_PATH_PUBLIC . '/partials/find-my-location.php', false, $args );
                ?></div><?php
            }?>

            <?php printf(
                '<div id="wp_post_location_map" class="bg-light" data-setup="%s"></div>',
                esc_attr( json_encode( $args ) )
            );?>

            <style type="text/css">
                #wp_post_location_map { height: <?php echo $height?>; }
            </style>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     *
     * The Map shortcode wrapper
     * 
     * @param  array  $args
     * @param  string $content
     * @return HTML
     *
     * @since 1.0.0
     * 
     */
    public static function the_map(){
        add_shortcode( 'wp_post_location', array( __CLASS__, '_the_map' ) );
    }    
}