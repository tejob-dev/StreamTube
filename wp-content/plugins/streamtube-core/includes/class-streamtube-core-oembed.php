<?php
/**
 * Define the profile functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the profile functionality
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_oEmbed{

	/**
	 * Get YouTube URL from given input
	 */
	public static function get_youtube_url( $url = '' ){

	    $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/live/|youtube\.com/shorts/)([^"&?/ ]{11})%i';

	    preg_match( $pattern, $url, $matches );

	    if( is_array( $matches ) && isset( $matches[1] ) ){
	        return 'https://www.youtube.com/watch?v=' . $matches[1];
	    }

	    return false;
	}

	/**
	 *
	 * Get YouTube video ID
	 * 
	 */
	public static function get_youtube_id( $input ){
		$maybe_youtube_url = self::get_youtube_url( $input );

		if( $maybe_youtube_url ){
			$parsed = parse_url( $maybe_youtube_url );

			return str_replace( 'v=', '', $parsed['query'] );
		}

		return false;
	}

	/**
	 *
	 * Add oembed providers
	 * 
	 */
	public function add_providers(){
		wp_oembed_add_provider( home_url('/*'), get_oembed_endpoint_url() );
	}

	/**
	 *
	 * Get URL header content type
	 * 
	 */
	public function get_content_type( $url ){

		$content_type = '';

		if( ! wp_http_validate_url( $url ) ){
			return $content_type;
		}

	    $headers = wp_get_http_headers( $url );

	    return $headers['content-type'];
	}

	/**
	 *
	 * Get embed data
	 *
	 * @param  string $url
	 *
	 * @return array|WP_error
	 *
	 * @since 1.0.0
	 * 
	 */
	public function get_data( $url ){

		if( false !== $maybe_youtube_url = self::get_youtube_url( $url ) ){
			$url = $maybe_youtube_url;
		}

		$content_type = $this->get_content_type( $url );

		if( strpos( $content_type, 'text/html' ) === false ){
			return new WP_Error(
				'content_type_not_found',
				esc_html__( 'Content Type not found.', 'streamtube-core' )
			);			
		}

		$oembed_endpoint = _wp_oembed_get_object()->get_provider( $url, array( 'discover' => true ) );

		if( ! $oembed_endpoint || ! is_string( $oembed_endpoint ) ){
			return new WP_Error(
				'endpoint_not_found',
				esc_html__( 'Endpoint not found.', 'streamtube-core' )
			);
		}

		$response = wp_remote_get( add_query_arg( array(
			'url'		=>	$url,
			'format'	=>	'json'
		), $oembed_endpoint ) );

		if( is_wp_error( $response ) ){
			return $response;
		}

		$response = wp_parse_args( json_decode( wp_remote_retrieve_body( $response ), true ), array(
			'title'				=>	'',
			'author_name'		=>	'',
			'author_url'		=>	'',
			'thumbnail_url'		=>	'',
			'provider_name'		=>	'',
			'html'				=>	''
		) );

		if( array_key_exists( 'thumbnail_url', $response ) ){
			// Youtube
			preg_match( '/(youtube.com\/watch\?v=|youtu.be\/|youtube.com\/embed\/)(?P<id>.{11})/', $url, $matches );

			if( $matches ){
				$response['thumbnail_url'] = str_replace( 'hqdefault.jpg', 'maxresdefault.jpg', $response['thumbnail_url'] );

				$check_error = is_wp_error( wp_remote_get( $response['thumbnail_url'] ) );

				if( wp_remote_retrieve_response_code( wp_remote_get( $response['thumbnail_url'] ) ) == 404 ){
					$response['thumbnail_url'] = str_replace( 'maxresdefault.jpg', 'hqdefault.jpg', $response['thumbnail_url'] );
				}
			}
			
			preg_match( '/(vimeo.com\/|\/videos\/)(?P<id>\d+)/', $url, $matches );

			// Vimeo
			if( $matches ){
				$response['thumbnail_url'] = str_replace( 'd_295x166', 'd_720', $response['thumbnail_url'] ) . '.png';
			}
		}

		return $response;
	}

	/**
	 *
	 * Get thumbnail URL
	 * 
	 * @param  string $url
	 * @return string
	 *
	 * @since 1.0.0
	 * 
	 */
	public function get_thumbnail_url( $url ){

		$results = $this->get_data( $url );

		if( is_wp_error( $results ) ){
			return $results;
		}

		if( is_array( $results ) && array_key_exists( 'thumbnail_url' , $results ) ){

			return $results['thumbnail_url'];
		}

		return false;
	}

	/**
	 *
	 * Generate post thumbnail from given source
	 * 
	 * @param  int $post_id
	 * @param  strin $url
	 * @return array|WP_Error
	 *
	 * @since 1.0.6
	 * 
	 */
	public function generate_image( $post_id, $url ){

		$data = $this->get_data( $url );

		if( is_wp_error( $data ) ){
			return $data;
		}

        if( is_array( $data ) && array_key_exists( 'thumbnail_url', $data ) ){

        	if( ! function_exists( 'media_sideload_image' ) ){
				require_once(ABSPATH . 'wp-admin/includes/media.php');
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');        		
        	}

            $thumbnail_id = media_sideload_image( $data['thumbnail_url'], $post_id, null, 'id' );

            if( is_wp_error(  $thumbnail_id ) ){
            	return $thumbnail_id;
            }

            if( is_int( $thumbnail_id ) ){
                set_post_thumbnail( $post_id, $thumbnail_id );

                wp_update_post( array(
                    'ID'            =>  $thumbnail_id,
                    'post_parent'   =>  $post_id
                ) );
            }       

            return compact( 'post_id', 'thumbnail_id', 'url' );    
        }

        return new WP_Error( 
        	'undefined_error',
        	esc_html__( 'Undefined Error', 'streamtube-core' )
        );
	}

	/**
	 *
	 * Filter the oembed html
	 * 
	 */
	public function filter_embed_oembed_html( $cache, $url, $attr, $post_id ){

		$md5url = md5( $url );
		$oembed = get_transient( "oembed-{$md5url}" );

		if( false === $oembed ){
			$oembed = _wp_oembed_get_object()->get_data( $url );

			if( $oembed !== false ){
				set_transient( "oembed-{$md5url}", $oembed );
			}
		}

	    if( is_object( $oembed ) && $oembed->type == 'video' ){

	    	/**
	    	 *
	    	 * Filter ratio
	    	 *
	    	 * @param string $ratio
	    	 * @param object $oembed
	    	 * @param string $url
	    	 * @param array $attr
	    	 * @param int $post_id
	    	 * 
	    	 */
	    	$ratio = apply_filters( 'streamtube/core/embed_oembed_html/ratio', '16x9', $oembed, $url, $attr, $post_id );

	        $cache = sprintf(
	            '<div data-provider="%1$s" class="mb-4 oembed-%1$s oembed-wrapper ratio ratio-%2$s">%3$s</div>',
	            esc_attr( sanitize_html_class( strtolower( $oembed->provider_name ) ) ),
	            $ratio,
	            $cache
	        );
	    }

	    return $cache;
	}

	/**
	 *
	 * Fix unbalance tags for oembed html
	 * 
	 * @param  string $oembed_html
	 * @return string balanced
	 * 
	 */
	public function force_balance_tags_embed_html( $oembed_html, $args = array() ){

		global $post;

		$oembed_html = force_balance_tags( $oembed_html );

		$user_id = is_object( $post ) ? $post->post_author : false;

		if( function_exists( 'bp_get_activity_user_id' ) ){
			if( function_exists( 'bp_is_activity_component' ) && bp_is_activity_component() ){
				$user_id = bp_get_activity_user_id();
			}
		}

		if( ! $user_id ){
			return $oembed_html;
		}

		/**
		 *
		 * Filter html_filter
		 * 
		 */
		$html_filter = apply_filters( 'streamtube/core/filter_oembed_html', true, $oembed_html, $args, $user_id );

		if( ! is_multisite() && ! user_can( $user_id, 'unfiltered_html' ) && $html_filter ){
			$oembed_html = wp_strip_all_tags( $oembed_html );
		}

		return $oembed_html;
	}
}