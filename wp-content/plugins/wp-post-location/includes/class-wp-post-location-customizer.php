<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the customizer functionality.
 *
 * @since      1.0.0
 * @package    WP_Post_Location
 * @subpackage WP_Post_Location/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_Post_Location_Customizer {

    const PANEL_ID = 'wp_post_location';

    /**
     *
     * Get map providers
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_map_providers(){
        return array(
            'openstreetmap' =>  esc_html__( 'Open Street Map', 'wp-post-location' ),
            'googlemap'     =>  esc_html__( 'Google Map', 'wp-post-location' ),
        );
    }

    /**
     *
     * Get settings
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function get_settings( $setting = '', $default = '' ){

        $settings = array();

        if( $setting ){
            return get_option( self::PANEL_ID . '_' . sanitize_key( $setting ), $default );
        }

        $defaults = array(
            'map_provider'          =>  'openstreetmap',
            'googlemap_api'         =>  '',
            'ost_geocoding_api'    =>  '',
            'language'              =>  'en',
            'map_type'              =>  'roadmap',
            'gesturehandling'       =>  'greedy',
            'default_longitude'     =>  0,
            'default_latitude'      =>  0,
            'default_zoom'          =>  3,
            'frontend_form'         =>  'on',
            'restriction'           =>  '',
            'north'                 =>  '',
            'south'                 =>  '',
            'east'                  =>  '',
            'west'                  =>  '',
            'default_style_json'    =>  '',
            'dark_style_json'       =>  '',
            'marker_video'          =>  '',
            'marker_post'           =>  ''
        );

        foreach ( $defaults as $setting => $default ) {
            $settings[ $setting ] = get_option( self::PANEL_ID . '_' . sanitize_key( $setting ), $default );
        }

        return $settings;
    }

    /**
     *
     * Customize Register
     * 
     * @return array
     *
     * @since 1.0.0
     * 
     */
    public static function register( $customizer ){

        $customizer->add_panel( self::PANEL_ID, array(
            'title'             =>  esc_html__( 'WP Post Location', 'wp-post-location' ),
            'priority'          =>  100
        ) );

            $customizer->add_section( self::PANEL_ID . '_general', array(
                'title'             =>  esc_html__( 'General', 'wp-post-location' ),
                'priority'          =>  1,
                'panel'             =>  self::PANEL_ID
            ) );        

                $customizer->add_setting( self::PANEL_ID . '_map_provider', array(
                    'default'           =>  'openstreetmap',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_map_provider', array(
                    'label'             =>  esc_html__( 'Map Provider', 'wp-post-location' ),
                    'type'              =>  'select',
                    'section'           =>  self::PANEL_ID . '_general',
                    'choices'           =>  self::get_map_providers()
                ) );

                $customizer->add_setting( self::PANEL_ID . '_googlemap_api', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_googlemap_api', array(
                    'label'             =>  esc_html__( 'Google Map API Key', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_general',
                    'active_callback'   =>  function(){
                        return get_option( self::PANEL_ID . '_map_provider', 'openstreetmap' ) == 'googlemap' ? true : false;
                    }
                ) );

                $customizer->add_setting( self::PANEL_ID . '_ost_geocoding_api', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_ost_geocoding_api', array(
                    'label'             =>  esc_html__( 'Open Street Map GEOcoding API', 'wp-post-location' ),
                    'type'              =>  'checkbox',
                    'section'           =>  self::PANEL_ID . '_general',
                    'active_callback'   =>  function(){
                        return get_option( self::PANEL_ID . '_map_provider', 'openstreetmap' ) == 'googlemap' ? true : false;
                    },
                    'description'       =>  esc_html__( 'Call Open Street Map GEOcoding API instead of Google Geocoding API', 'wp-post-location' )
                ) );                

                $customizer->add_setting( self::PANEL_ID . '_language', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_language', array(
                    'label'             =>  esc_html__( 'Default Language', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_general',
                    'active_callback'   =>  function(){
                        return get_option( self::PANEL_ID . '_map_provider', 'openstreetmap' ) == 'googlemap' ? true : false;
                    },                    
                    'description'       =>  sprintf(
                        esc_html__( 'See the list of %s or leave blank for default', 'wp-post-location' ),
                        '<a target="_blank" href="https://developers.google.com/maps/faq#languagesupport">'. esc_html__( 'supported languages', 'wp-post-location' ) .'</a>'
                    )
                ) );

                $customizer->add_setting( self::PANEL_ID . '_map_type', array(
                    'default'           =>  'roadmap',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_map_type', array(
                    'label'             =>  esc_html__( 'Map Type', 'wp-post-location' ),
                    'type'              =>  'select',
                    'section'           =>  self::PANEL_ID . '_general',
                    'choices'           =>  array(
                        'roadmap'   =>  esc_html__( 'Default (Roadmap)', 'wp-post-location' ),
                        'satellite' =>  esc_html__( 'Satellite', 'wp-post-location' ),
                        'hybrid'    =>  esc_html__( 'Hybrid', 'wp-post-location' ),
                        'terrain'   =>  esc_html__( 'Terrain', 'wp-post-location' )
                    ),
                    'active_callback'   =>  function(){
                        return get_option( self::PANEL_ID . '_map_provider', 'openstreetmap' ) == 'googlemap' ? true : false;
                    }                    
                ) );            

                $customizer->add_setting( self::PANEL_ID . '_gesturehandling', array(
                    'default'           =>  'en',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_gesturehandling', array(
                    'label'             =>  esc_html__( 'Zoom Controller', 'wp-post-location' ),
                    'type'              =>  'select',
                    'section'           =>  self::PANEL_ID . '_general',
                    'choices'           =>  array(
                        'cooperative'   =>  esc_html__( 'Scroll + CTRL', 'wp-post-location' ),
                        'greedy'        =>  esc_html__( 'Scroll', 'wp-post-location' )
                    ),
                    'active_callback'   =>  function(){
                        return get_option( self::PANEL_ID . '_map_provider', 'openstreetmap' ) == 'googlemap' ? true : false;
                    }                     
                ) );            

                $customizer->add_setting( self::PANEL_ID . '_default_longitude', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_default_longitude', array(
                    'label'             =>  esc_html__( 'Default Longitude', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_general'
                ) );

                $customizer->add_setting( self::PANEL_ID . '_default_latitude', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_default_latitude', array(
                    'label'             =>  esc_html__( 'Default Latitude', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_general'
                ) );

                $customizer->add_setting( self::PANEL_ID . '_default_zoom', array(
                    'default'           =>  3,
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_default_zoom', array(
                    'label'             =>  esc_html__( 'Default Zoom Level', 'wp-post-location' ),
                    'type'              =>  'number',
                    'section'           =>  self::PANEL_ID . '_general'
                ) );

                $customizer->add_setting( self::PANEL_ID . '_frontend_form', array(
                    'default'           =>  'on',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_frontend_form', array(
                    'label'             =>  esc_html__( 'FrontEnd Edit Post Form', 'wp-post-location' ),
                    'type'              =>  'checkbox',
                    'section'           =>  self::PANEL_ID . '_general',
                    'description'       =>  esc_html__( 'Enable Frontend Edit Post Form', 'wp-post-location' )
                ) );

            $customizer->add_section( self::PANEL_ID . '_restriction', array(
                'title'             =>  esc_html__( 'Region Restriction', 'wp-post-location' ),
                'priority'          =>  1,
                'panel'             =>  self::PANEL_ID,
                'description'       =>  esc_html__( 'Region Restriction works for Google Map only', 'wp-post-location' )
            ) );                

                $customizer->add_setting( self::PANEL_ID . '_restriction', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_restriction', array(
                    'label'             =>  esc_html__( 'Enable', 'wp-post-location' ),
                    'type'              =>  'checkbox',
                    'section'           =>  self::PANEL_ID . '_restriction'
                ) );

                $customizer->add_setting( self::PANEL_ID . '_north', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_north', array(
                    'label'             =>  esc_html__( 'North', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_restriction'
                ) );

                $customizer->add_setting( self::PANEL_ID . '_south', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_south', array(
                    'label'             =>  esc_html__( 'South', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_restriction'
                ) );

                $customizer->add_setting( self::PANEL_ID . '_east', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_east', array(
                    'label'             =>  esc_html__( 'East', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_restriction'
                ) );

                $customizer->add_setting( self::PANEL_ID . '_west', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_west', array(
                    'label'             =>  esc_html__( 'West', 'wp-post-location' ),
                    'type'              =>  'text',
                    'section'           =>  self::PANEL_ID . '_restriction'
                ) );

            $customizer->add_section( self::PANEL_ID . '_map_style', array(
                'title'             =>  esc_html__( 'Map Styles', 'wp-post-location' ),
                'priority'          =>  1,
                'panel'             =>  self::PANEL_ID
            ) );

                $customizer->add_setting( self::PANEL_ID . '_default_style_json', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_textarea_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_default_style_json', array(
                    'label'             =>  esc_html__( 'Default Style Json', 'wp-post-location' ),
                    'type'              =>  'textarea',
                    'section'           =>  self::PANEL_ID . '_map_style',
                    'description'       =>  sprintf(
                        esc_html__( 'Get default style json from %s, works for Google Map only.', 'wp-post-location' ),
                        '<a target="_blank" href="https://mapstyle.withgoogle.com/">Map Style</a>'
                    )
                ) );            

                $customizer->add_setting( self::PANEL_ID . '_dark_style_json', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_textarea_field'
                ) );

                $customizer->add_control( self::PANEL_ID . '_dark_style_json', array(
                    'label'             =>  esc_html__( 'Dark Style Json', 'wp-post-location' ),
                    'type'              =>  'textarea',
                    'section'           =>  self::PANEL_ID . '_map_style',
                    'description'       =>  sprintf(
                        esc_html__( 'Get dark mode json from %s, works for Google Map only.', 'wp-post-location' ),
                        '<a target="_blank" href="https://mapstyle.withgoogle.com/">Map Style</a>'
                    )
                ) );

            $customizer->add_section( self::PANEL_ID . '_markers', array(
                'title'             =>  esc_html__( 'Markers', 'wp-post-location' ),
                'priority'          =>  1,
                'panel'             =>  self::PANEL_ID
            ) );

                $customizer->add_setting( self::PANEL_ID . '_marker_video', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field',
                ) );

                $customizer->add_control(
                    new WP_Customize_Image_Control(
                        $customizer,
                        self::PANEL_ID . '_marker_video',
                        array(
                            'label'      => esc_html__( 'Video Marker', 'wp-post-location' ),
                            'section'    => self::PANEL_ID . '_markers'
                        )
                    )
                );

                $customizer->add_setting( self::PANEL_ID . '_marker_post', array(
                    'default'           =>  '',
                    'type'              =>  'option',
                    'capability'        =>  'edit_theme_options',
                    'sanitize_callback' =>  'sanitize_text_field',
                ) );

                $customizer->add_control(
                    new WP_Customize_Image_Control(
                        $customizer,
                        self::PANEL_ID . '_marker_post',
                        array(
                            'label'      => esc_html__( 'Post Marker', 'wp-post-location' ),
                            'section'    => self::PANEL_ID . '_markers'
                        )
                    )
                );                

    }
}
