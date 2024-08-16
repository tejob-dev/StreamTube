<?php
/**
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

/**
 *
 * Fires before header
 *
 * @since 1.0.0
 * 
 */
do_action( 'streamtube/header/before' );

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :

    $content_width      = streamtube_get_site_content_width();

    $header_template    = sanitize_file_name( streamtube_get_header_template() );

    if( empty( $header_template ) ){
        $header_template = 1;
    }

    switch ( $content_width ) {
        case 'container':
        case 'container-wide':
            get_template_part( 'template-parts/header/header-'. $header_template .'-boxed' );
        break;
        
        default:
            get_template_part( 'template-parts/header/header-'. $header_template .'-fullwidth' );
        break;
    }

endif;

if( streamtube_is_login_page() && get_option( 'custom_theme_login', 'on' ) && $bg_image = get_option( 'login_bg_image' ) ){
    ?>
    <style type="text/css">
        
        #site-main{
            position:relative; 
            background-color: rgba(0, 0, 0, 0.5);
        }

        #site-main:after{
            z-index: -1;
            content: "";
            position: absolute;
            width:  100%;
            height: 100%;
            top:  0;
            left:  0;
            background-size: cover;
            background-repeat: no-repeat;
            background-image:url(<?php echo esc_url($bg_image)?>);
        }

        #site-main #login_error,
        #site-main .message,
        #site-main div#login form{
            background: #0000009c!important;
            color:  #fff!important;
        }        

        #site-main #login_error a,
        #site-main div#login #nav a,
        #site-main div#login #backtoblog a{
            color:  #fff;
        }        

        #site-main div#login form select,
        #site-main div#login form input[type="text"],
        #site-main div#login form input[type="email"],
        #site-main div#login form input[type="password"],
        #site-main div#login form input[type="text"]:focus,
        #site-main div#login form input[type="email"]:focus,
        #site-main div#login form input[type="password"]:focus{
            background: #00000040!important;
            color: #fff!important;
            border: none!important;
            border-bottom: 1px solid #ffffff36!important;         
        }

        html[data-theme=dark] #site-main:after{   
            opacity: .4;
        }

    </style>
    <?php
}

/**
 *
 * Fires after header
 *
 * @since 1.0.0
 * 
 */
do_action( 'streamtube/header/after' );