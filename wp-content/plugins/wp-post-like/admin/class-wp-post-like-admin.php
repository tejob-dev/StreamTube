<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WP_Post_Like
 * @subpackage WP_Post_Like/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
	exit;
}

class WP_Post_Like_Admin {

	protected $settings;

	public function __construct(){
		$this->settings = WP_Post_Like_Customizer::get_options();
	}

    /**
     *
     * Add metaboxes
     *
     * @since 1.0.0
     * 
     */
    public function add_meta_boxes(){
        add_meta_box(
            'wp-post-like',
            esc_html__( 'WP Post Like', 'wp-post-like' ),
            array( $this , 'wp_post_like_html' ),
            $this->settings['post_types'],
            'side',
            'high'
        );
    }

    public function wp_post_like_html( $post ){

    	$ajax_url = add_query_arg( array(
    		'action'	=>	'reset_post_like',
    		'_wpnonce'	=>	wp_create_nonce()
    	), admin_url( 'admin-ajax.php' ) );

		?>
		<div class="metabox-wrap">
			<button id="button-reset-post-like" class="button button-primary w-100" type="button">
				<?php esc_html_e( 'Reset Post Like', 'wp-post-like' )?>
			</button>

			<script type="text/javascript">

				document.getElementById( 'button-reset-post-like' ).addEventListener( 'click', function(){

					let button = this;

					button.setAttribute( 'disabled', 'disabled' );
					button.innerText = '<?php esc_html_e( 'Resetting', 'wp-post-like' );?>';

					fetch( '<?php echo $ajax_url; ?>', {
					    method: 'POST',
					    headers: {
					        'Content-Type'	: 'application/json; charset=UTF-8'
					    },
					    body: '<?php echo $post->ID; ?>'
					})
				    .then( response => response.json() )
				    .then( data => {
						button.removeAttribute('disabled');
						if( data.success == true ){
							alert( data.data.message );	
						}else{
							alert( data.data[0].message );
						}

						button.innerText = '<?php esc_html_e( 'Reset Post Like', 'wp-post-like' );?>';
				    })
				    .catch(error => {
				    	button.removeAttribute('disabled');
				    	console.error(error)
				    });
				} );

			</script>
		</div>
		<?php
    }

    public function reset_post_like( $post_id = 0 ){

    	if( ! current_user_can( 'edit_post', $post_id ) ){
    		return new WP_Error(
    			'no_permission',
    			esc_html__( 'You do not have permission to reset this post like', 'wp-post-like' )
    		);
    	}

        do_action( 'wp_post_like_before_reset_like', $post_id );

    	global $wppl;

    	$wppl->get()->query->delete_by_post_id( $post_id );

    	delete_post_meta( $post_id, '_like_count' );
    	delete_post_meta( $post_id, '_dislike_count' );

        do_action( 'wp_post_like_after_reset_like', $post_id );        

    	return true;

    }

    public function ajax_reset_post_like(){

    	$http_data = json_decode( trim(file_get_contents( "php://input")), true );

    	check_ajax_referer();

    	$result = $this->reset_post_like( $http_data );

    	if( is_wp_error( $result ) ){
    		wp_send_json_error( $result );
    	}

    	wp_send_json_success( array(
    		'message'	=>	esc_html__( 'Post Like has been reset successfully.', 'wp-post-like' )
    	) );
    }

}