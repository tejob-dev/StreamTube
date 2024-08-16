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

class Streamtube_Core_User {

	/**
	 *
	 * Holds the avatar meta key.
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $_avatar_key		=	'_avatar';

	/**
	 *
	 * Holds the profile photo meta key.
	 * 
	 * @var string
	 *
	 * @since  1.0.0
	 * 
	 */
	protected $_profile_photo	=	'_profile_photo';

	protected $_vast_tag_url	=	'_vast_tag_url';

	/**
	 *
	 * Get user IDs
	 * 
	 * @param  array  $args
	 * 
	 */
	public static function get_users( $args = array() ){

		$args = wp_parse_args( $args, array(
			'fields'	=>	'ID'
		) );

		/**
		 *
		 * Filter args
		 * 
		 */
		$args = apply_filters( 'streamtube/core/user/get_users', $args );

		$_cache_key = md5( json_encode( $args ) );

		if( false !== $users = wp_cache_get( $_cache_key ) ){
			return $users;
		}

		$users = get_users( $args );

		wp_cache_set( $_cache_key, $users );

		return $users;
	}	

	/**
	 *
	 * Check if current author is mine
	 *
	 * 
	 * @return true|false
	 *
	 * @since  1.0.0
	 * 
	 */
	public function is_my_profile(){

		if( ! is_user_logged_in() || ! is_author() ){
			return false; // always return false if current page isn't author
		}

		if( get_current_user_id() == get_queried_object_id() ){
			return true;
		}

		return false;
	}

	/**
	 *
	 * Check if user is verified
	 * 
	 * @param  integer $user_id
	 * @return boolean
	 *
	 * @since 2.2
	 * 
	 */
	public function is_verified( $user_id = 0 ){
		return Streamtube_Core_Permission::is_verified( $user_id );
	}

	/**
	 *
	 * Verify User
	 * 
	 * @param  integer $user_id
	 */
	public function verify_user( $user_id = 0 ){

		if( $this->is_verified( $user_id ) ){

			/**
			 *
			 * Fires before unverifying user
			 *
			 * @param int $user_id
			 * 
			 */
			do_action( 'streamtube/core/before_unverify_user', $user_id );

			Streamtube_Core_Permission::unverify_user( $user_id );

			/**
			 *
			 * Fires after unverifying user
			 *
			 * @param int $user_id
			 * 
			 */
			do_action( 'streamtube/core/after_unverified_user', $user_id );			
		}else{

			/**
			 *
			 * Fires before verifying user
			 *
			 * @param int $user_id
			 * 
			 */
			do_action( 'streamtube/core/before_verify_user', $user_id );

			Streamtube_Core_Permission::verify_user( $user_id );

			/**
			 *
			 * Fires after verifying user
			 *
			 * @param int $user_id
			 * 
			 */
			do_action( 'streamtube/core/after_verified_user', $user_id );			
		}
	}

    /**
     *
     * do AJAX update user verification badge
     * 
     * @return JSON
     *
     * @since 2.0
     * 
     */
	public function ajax_verify_user(){
        check_ajax_referer( '_wpnonce' );

        if( ! current_user_can( 'administrator' ) || ! $_POST['user_id'] ){
            wp_send_json_error( array(
                'message'   =>  esc_html__( 'You do not have permission to verify this user.', 'streamtube-core' ) 
            ) );
        }

        $user_id = (int)$_POST['user_id'];

        $result = $this->verify_user( $user_id );

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'OK', 'streamtube-core' ),
            'button'    =>  $this->get_verify_button( $user_id )
        ) );       	
	}

    /**
     *
     * The verification button
     * 
     * @param  int $user_id
     * @return string
     *
     * @since 2.0
     * 
     */
    public function get_verify_button( $user_id = 0 ){

        $is_verified = $this->is_verified( $user_id );

        return sprintf(
            '<button type="button" class="button button-%s button-small button-verification" data-user-id="%s">%s</button>',
            $is_verified ? 'primary' : 'secondary',
            esc_attr( $user_id ),
            $is_verified ? esc_html__( 'Verified', 'streamtube-core' ) : esc_html__( 'N/A', 'streamtube-core' )
        );
    }

	/**
	 *
	 * Get user dashboard URL
	 * 
	 * @param  integer $user_id
	 * @param  string  $endpoint
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_dashboard_url( $user_id = 0, $endpoint = '' ){
		if( ! $user_id ){
			return;
		}

		$url = get_author_posts_url( $user_id );

		if( ! get_option( 'permalink_structure' ) ){
			return add_query_arg( array(
				'dashboard'	=>	$endpoint
			), $url );
		}

		return trailingslashit( $url ) . 'dashboard/' . $endpoint;		
	}

	/**
	 *
	 * Get user avatar meta key
	 * 
	 * @return [type] [description]
	 */
	public function get_avatar_key(){
		/**
		 *
		 * filter and return the key
		 * @param  string  $this->_avatar_key
		 *
		 * @since  1.0.0
		 * 
		 */
		return apply_filters( 'streamtube_user_avatar_key', $this->_avatar_key );
	}

	/**
	 *
	 * Get custom avatar URL
	 * 
	 * @param  integer $user_id
	 * @return string|false
	 * 
	 */
	public function get_custom_avatar_url( $user_id = 0 ){
		$image_id = get_user_meta( $user_id, $this->get_avatar_key(), true );

		if( wp_attachment_is( 'image', $image_id ) ){
			return wp_get_attachment_image_url( $image_id, 'full' );
		}

		return false;
	}

	/**
	 *
	 * Get user profile photo meta key
	 * 
	 * @return [type] [description]
	 */
	public function get_profile_photo_key(){
		/**
		 *
		 * filter and return the key
		 * @param  string  $this->_avatar_key
		 *
		 * @since  1.0.0
		 * 
		 */
		return apply_filters( 'streamtube_user_profile_photo_key', $this->_profile_photo );
	}

	/**
	 *
	 * Get the user avatar
	 *
	 * @param  array $args{
	 *
	 * 		@var int $user_id
	 * 		@var int $image_size
	 * 		@var string $wrap_size
	 * 		@var boolean $link link to user page
	 * 		@var string $before before name
	 * 		@var string $after after name
	 * 		@var boolean $echo print or return the result
	 * 
	 * }
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_avatar( $args = array() ){

		$args = wp_parse_args( $args, array(
			'user_id'			=>	'',
			'image_size'		=>	200,
			'wrap_class'		=>	'',
			'wrap_size'			=>	'',
			'link'				=>	true,
			'name'				=>	false,
			'name_class'		=>	'',
			'before'			=>	'',
			'after'				=>	'',
			'echo'				=>	true
		) );

		$user_data = get_user_by( 'ID', $args['user_id'] );

		if( ! $user_data ){
			return;
		}

		$image_classes = array( 'img-thumbnail' );

		$image = get_avatar( $args['user_id'], $args['image_size'], null, null, array(
			'class'	=>	join( ' ', $image_classes )
		) );

		if( $args['link'] ){
			$output = sprintf(
				'<a data-bs-toggle="tooltip" data-bs-placement="%s" class="d-flex align-items-center fw-bold text-decoration-none" title="%s" href="%s">%s</a>',
				! is_rtl() ? 'right' : 'left',
				esc_attr( $user_data->display_name ),
				esc_url( get_author_posts_url( $args['user_id'] ) ),
				$image
			);
		}
		else{
			$output = $image;
		}

		$image_classes = array( 'user-avatar', 'is-off' );

		if( $args['wrap_size'] ){
			$image_classes[] = 'user-avatar-' . esc_attr( $args['wrap_size'] );
		}

		if( $args['wrap_class'] ){
			$image_classes[] = $args['wrap_class'];
		}

		if( $this->is_verified( $user_data->ID ) ){
			$image_classes[] = 'is-verified';
		}

		$output = sprintf(
			'<div class="%s">%s</div>',
			join( ' ', $image_classes ),
			$output
		);

		if( $args['name'] ){
			$args['name'] = sprintf(
				'<span class="user-name text-body %s"><a class="text-body fw-bold text-decoration-none" title="%s" href="%s">%s</a></span>',
				$args['name_class'] ? esc_attr( $args['name_class'] ) : 'ms-2',
				esc_attr( $user_data->display_name ),
				esc_url( get_author_posts_url( $args['user_id'] ) ),
				$user_data->display_name
			);
		}

		$output = $args['before'] . $output . $args['name'] . $args['after'];

		if( $args['echo'] ){
			echo $output;
		}
		else{
			return $output;	
		}
	}

	/**
	 *
	 * Get custom profile image URL
	 * 
	 * @param  integer $user_id
	 * @return string|false
	 * 
	 */
	public function get_custom_profile_image_url( $user_id = 0, $size = 'full' ){

		$image_url = '';

		$image_id = get_user_meta( $user_id, $this->get_profile_photo_key(), true );

		if( wp_attachment_is( 'image', $image_id ) ){
			$image_url = wp_get_attachment_image_url( $image_id, $size );
		}

		/**
		 *
		 * Filter profile image url
		 *
		 * @param string $image_url
		 * @param int $user_id
		 * 
		 */
		return apply_filters( 'streamtube/core/profile_image_url', $image_url, $user_id, $size );
	}	

	/**
	 *
	 * Get the user profile photo
	 *
	 * @param  array $args{
	 *
	 * 		@var int $user_id
	 * 		@var boolean $link link to user page
	 * 		@var boolean $echo print or return the result
	 * 
	 * }
	 *
	 * @since  1.0.0
	 * 
	 */
	public function get_profile_photo( $args ){

		$photo = $default_photo = "";

		$args = wp_parse_args( $args, array(
			'user_id'			=>	'',
			'before'			=>	'',
			'after'				=>	'',		
			'link'				=>	true,
			'echo'				=>	true
		) );

		$user_data = get_user_by( 'ID', $args['user_id'] );

		if( ! $user_data ){
			return;
		}

		$photo = $this->get_custom_profile_image_url( $args['user_id'] );

		if( ! $photo ){
			if( "" != $default_photo = get_option( 'user_default_profile_photo' ) ){
				$photo = $default_photo;
			}
		}

		/**
		 * Filter profile photo
		 *
		 * @param string $photo
		 * @param string $default_photo
		 * @param array $args
		 * 
		 */
		$photo = apply_filters( 'streamtube/core/user/profile_photo', $photo, $default_photo, $args );

		$output = sprintf(
			'<div class="profile-photo" style="%s"></div>',
			$photo ? 'background-image: url('. esc_url( $photo ) .')' : ''
		);

		if( $args['link'] ){
			$output = sprintf(
				'<a title="%s" href="%s">%s</a>',
				esc_attr( get_user_by( 'ID', $args['user_id'] )->display_name ),
				esc_url( get_author_posts_url( $args['user_id'] )),
				$output
			);
		}

		$output = $args['before'] . $output . $args['after'];

		if( $args['echo'] ){
			echo $output;
		}
		else{
			return $output;	
		}
	}

	/**
	 *
	 * Get user social profiles
	 *
	 * @since 2.2
	 * 
	 */
	public function get_social_profiles( $user_id = 0, $social_id = '' ){

		if( ! $user_id ){
			$user_id = get_current_user_id();
		}

		$socials = (array)get_user_meta( $user_id, '_socials', true );

		if( $social_id ){
			if( array_key_exists( $social_id, $socials ) ){
				return $socials[ $social_id ];
			}else{
				return false;
			}			
		}

		return array_unique( $socials );
	}

	public function get_avatar_url( $url, $id_or_email, $args  ){
		$user_id = 0;

		if( is_numeric( $id_or_email ) ){
			$user_id = absint( $id_or_email );
		}
		elseif ( is_string( $id_or_email ) ) {
			$user = get_user_by( 'email' , $id_or_email );
			if( is_object( $user ) ){
				$user_id = $user->ID;
			}
		}
		elseif ( $id_or_email instanceof WP_User ) {
			$user_id = $id_or_email->ID;
		}
		elseif ( $id_or_email instanceof WP_Post ) {
			$user_id = $id_or_email->post_author;
		}
		elseif ( $id_or_email instanceof WP_Comment ) {
			$user_id = $id_or_email->user_id;
		}

		if( $custom_url = $this->get_custom_avatar_url( $user_id ) ){
			$url = $custom_url;
		}

		return $url;
	}

	/**
	 * Upload user photo
	 * 
	 * @return int|WP_Error
	 *
	 * @since  1.0.0
	 * 
	 */
	public function upload_photo(){

		$user_id = get_current_user_id();

		$check_fast_upload = apply_filters( 'streamtube/core/user/check_fast_upload_photo', 60 );

		$errors = new WP_Error();

		$http_data = wp_parse_args( wp_unslash( $_POST ), array(
			'image_data'	=>	'',
			'field'			=>	'avatar'
		) );

		extract( $http_data );

		// Check image data
		if( empty( $image_data ) ){
			$errors->add(
				'no_image_data',
				esc_html__( 'Image data was not found.', 'streamtube-core' )
			);
		}

		$image_data = json_decode( wp_unslash( $image_data ), true );

		if( ! in_array( $field , array( 'avatar', 'profile' ) ) ){
			$errors->add(
				'no_request_field',
				esc_html__( 'No request field.', 'streamtube-core' )
			);
		}

		if( ! current_user_can( 'administrator' ) && $check_fast_upload ){
			if( false !== get_transient( "update_{$user_id}_{$field}" ) ){
				$errors->add(
					'slowdown',
					esc_html__( 'Please slow down', 'streamtube-core' )
				);
			}
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/user/upload_photo/errors', $errors );

		if( $errors->get_error_code() ){
			return $errors;
		}

		if( ! function_exists( 'media_handle_upload' ) ){
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}

		// Don't crop the image into multiple sizes
		add_filter( 'image_resize_dimensions', '__return_false', 99999, 1 );

		$attachment_id = media_handle_upload( 'file', null, array( '' ), array( 'test_form' => false ) );

		if( is_wp_error( $attachment_id ) ){
			return $attachment_id;
		}
		
		remove_filter( 'image_resize_dimensions', '__return_false', 99999, 1 );

		// Get the original image path
		//$original_image = wp_get_original_image_path( $attachment_id, true );
		$original_image = get_attached_file( $attachment_id );

		$exif_data = wp_read_image_metadata( $original_image );

		// Load the image into image editor
		$image_editor = wp_get_image_editor( $original_image );

		// If editor failed
		if( is_wp_error( $image_editor ) ){

			// Delete the file.
			wp_delete_attachment( $attachment_id, true );

			return $image_editor;
		}

		if( array_key_exists( 'orientation', $exif_data ) ){
			switch ( $exif_data['orientation'] ) {

				case 8:
					$image_editor->rotate( 90 );
				break;

				case 2:
					$image_editor->flip( true, false );
				break;

				case 7:
					$image_editor->flip( false, true );
					$image_editor->rotate( 90 );
				break;

				case 4:
					$image_editor->flip( false, true );
				break;			

				case 5:
					$image_editor->flip( false, true );
					$image_editor->rotate( 270 );
				break;				

				case 3:
					$image_editor->rotate( 180 );
				break;

				case 6:
					$image_editor->rotate( 270 );
				break;
			}			
		}

	    //$image_editor->crop( $image_data['x'], $image_data['y'], $image_data['width'],$image_data['height'] );
	    $image_editor->crop( 
	    	$image_data['x'],
	    	$image_data['y'],
	    	$image_data['width'],
	    	$image_data['height']
	    );

	    $image_save = $image_editor->save( $original_image );

	    if( is_wp_error( $image_save ) ){
			// Delete the file.
			wp_delete_attachment( $attachment_id, true );

	    	return $image_save;
	    }

	    wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id,  $image_save['path'] ) );

	    $_field = ( $field == 'avatar' ) ? $this->get_avatar_key() : $this->get_profile_photo_key();

	    update_user_meta( $user_id, $_field, $attachment_id );

	    if( $check_fast_upload ){

	    	$check_fast_upload = is_int( $check_fast_upload ) ? $check_fast_upload : 60;

	    	set_transient( "update_{$user_id}_{$field}", 1, $check_fast_upload );
		}

		/**
		 *
		 * Fires after photo uploaded
		 * 
		 */
		do_action( 'streamtube/core/user/uploaded_photo', $user_id, $_field, $attachment_id );

	    return $attachment_id;
	}

	/**
	 * Delete user avatar
	 */
	public function delete_photo( $user_id = 0, $field = 'avatar' ){

		$errors = new WP_Error();

		if( ! in_array( $field, array( 'avatar', 'profile' ) ) ){
			$errors->add(
				'no_request_field',
				esc_html__( 'No request field.', 'streamtube-core' )
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/user/delete_photo/errors', $errors, $user_id, $field );

		if( $errors->get_error_code() ){
			return $errors;
		}

		$key = $field == 'avatar'? $this->get_avatar_key() : $this->get_profile_photo_key();

		$image_id = get_user_meta( $user_id, $key, true );

		wp_delete_attachment( $image_id, true );

		return delete_user_meta( $user_id, $key );
	}

	/**
	 *
	 * AJAX delete photo image
	 * 
	 */
	public function ajax_delete_user_photo(){
		check_ajax_referer( '_wpnonce' );

		$http_data = wp_parse_args( wp_unslash( $_POST ), array(
			'data'	=>	0
		) );

		extract( $http_data );

		$response = $this->delete_photo( get_current_user_id(), $data );

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages(),
				'errors'	=>	$response
			) );
		}

		wp_send_json_success( array(
			'message'	=>	esc_html__( 'Image has been deleted', 'streamtube-core' )
		) );
	}

	/**
	 *
	 * Get default registration roles
	 *
	 * @since 2.1.6
	 * 
	 * @return array
	 */
	public function get_registration_roles(){
		$roles = array(
			'subscriber'	=>	array(
				'default'	=>	true,
				'label'		=>	esc_html__( 'Subscriber', 'streamtube-core' )
			),
			'author'	=>	array(
				'default'	=>	false,
				'label'		=>	esc_html__( 'Video Creator', 'streamtube-core' )
			),			
		);

		/**
		 * @since 2.1.6
		 */
		return apply_filters( 'streamtube/core/form/registration/roles', $roles );
	}

	/**
	 *
	 * Get registration settings
	 * 
	 * @return object
	 * 
	 */
	public function get_registration_settings(){
		return (object)wp_parse_args( get_option( 'custom_registration' ), array(
    		'custom_role'		=>	'',
    		'first_last_name'	=>	'',
    		'password'			=>	'',
    		'redirect_url'		=>	'home',
    		'agreement'			=>	''
		) );
	}

    /**
     *
     * Add additional fields to default WP Registration form
     * 
     * @since 2.1.6
     */
    public function build_form_registration(){

    	$settings = $this->get_registration_settings();

    	/**
    	 *
    	 * Fires before custom fields
    	 * 
    	 */
    	do_action( 'streamtube/core/form/registration/custom_fields/before' );    	

    	if( $settings->first_last_name ):
    		load_template( STREAMTUBE_CORE_PUBLIC . '/login/registration-display-name.php' );
    	endif;

    	if( $settings->custom_role ):
    		load_template( STREAMTUBE_CORE_PUBLIC . '/login/registration-roles.php' );
    	endif;

    	if( $settings->password ):
    		load_template( STREAMTUBE_CORE_PUBLIC . '/login/registration-passwords.php' );
    	endif;

    	if( $settings->agreement ){
    		load_template( STREAMTUBE_CORE_PUBLIC . '/login/registration-agreement.php' );
    	}

    	/**
    	 *
    	 * Fires after custom fields
    	 * 
    	 */
    	do_action( 'streamtube/core/form/registration/custom_fields/after' );

    	// Security nonce field.
    	wp_nonce_field( plugin_dir_path( __FILE__ ), 'wp_nonce_registration' );
    }

	/**
	 *
	 * Verify registration form
	 *
	 * @param WP_Error $errors
	 * @param string $sanitized_user_login
	 * @param string $user_email
	 * 
	 * @see register_new_user
	 *
	 * @since 2.1.6
	 */
	public function verify_registration_data( $errors ){

		$settings = $this->get_registration_settings();

    	$http_data = wp_parse_args( $_REQUEST, array(
    		'user_role'				=>	'',
    		'password1'				=>	'',
    		'password2'				=>	'',
    		'agreement'				=>	'',
    		'wp_nonce_registration'	=>	''
    	) );

    	extract( $http_data );
    	
		if( $settings->custom_role ){

			if( 
				! $user_role || 
				! array_key_exists( $user_role, $this->get_registration_roles() ) || 
				in_array( $user_role , array( 'administrator', 'editor' )) ){
				$errors->add(
					'invalid_role',
					sprintf(
						'<strong>%s </strong>: %s',
						esc_html__( 'Error', 'streamtube-core' ),
						esc_html__( 'Invalid Role', 'streamtube-core' )
					)
				);
			}
		}

		if( $settings->password ){

			$password1 = trim( $password1 );
			$password2 = trim( $password2 );

			if( empty( $password1 ) ){
				$errors->add(
					'empty_password',
					sprintf(
						'<strong>%s </strong>: %s',
						esc_html__( 'Error', 'streamtube-core' ),
						esc_html__( 'Password is required', 'streamtube-core' )
					)
				);
			}

			if( $password1 != $password2 ){
				$errors->add(
					'invalid_password',
					sprintf(
						'<strong>%s </strong>: %s',
						esc_html__( 'Error', 'streamtube-core' ),
						esc_html__( 'Passwords do not match. Please enter the same password in both password fields.', 'streamtube-core' )
					)
				);
			}			
		}

		if( ! $wp_nonce_registration || ! wp_verify_nonce( $wp_nonce_registration, plugin_dir_path( __FILE__ ) ) ){
			$errors->add(
				'invalid_request',
				sprintf(
					'<strong>%s </strong>: %s',
					esc_html__( 'Error', 'streamtube-core' ),
					esc_html__( 'Invalid Requested', 'streamtube-core' )
				)
			);			
		}

		if( $settings->agreement && ! $agreement ){
			$errors->add(
				'agreement',
				sprintf(
					'<strong>%s </strong>: %s',
					esc_html__( 'Error', 'streamtube-core' ),
					esc_html__( 'Please accept the terms & conditions', 'streamtube-core' )
				)
			);				
		}

		return $errors;
	}	

	/**
	 *
	 * Proccess registration form
	 *
	 * @param int $user_id
	 * 
	 * @see register_new_user
	 *
	 * @since 2.1.6
	 */
	public function save_form_registration( $user_id ){

		$user = new WP_User( $user_id );

    	$settings = $this->get_registration_settings();	

    	$http_data = wp_parse_args( $_REQUEST, array(
    		'user_role'		=>	'',
    		'first_name'	=>	'',
    		'last_name'		=>	'',
    		'password1'		=>	''
    	) );

    	extract( $http_data );

    	// Update role
		if( $settings->custom_role && $user_role ){

			if( 
				! $user_role || 
				! array_key_exists( $user_role, $this->get_registration_roles() ) || 
				in_array( $user_role , array( 'administrator', 'editor' )) )
			{
				$user_role = get_option( 'default_role', 'subscriber' );
			}

			$user->add_role( $user_role );
		}

		$user_data = array_merge( array(
			'ID'	=>	$user_id
		), compact( 'first_name', 'last_name' ) );

		if( $first_name && $last_name ){
			$user_data['display_name'] = sprintf(
				'%s %s',
				$first_name,
				$last_name
			);
		}

		if( $settings->password ){
			$user_data['user_pass'] = $password1;
		}

		// Update additional fields.
		wp_update_user( $user_data );

		if( $settings->password ){
			wp_signon( array(
				'user_login'		=>	$user->user_login,
				'user_password'		=>	$password1
			) );

			if( $settings->redirect_url == 'home' ){
				$settings->redirect_url = home_url('/');
			}

			if( $settings->redirect_url == 'dashboard' ){
				$settings->redirect_url = trailingslashit( get_author_posts_url( $user_id ) ) . 'dashboard';
			}

			/**
			 *
			 * Filter the redirect_url
			 *
			 * @param string $redirect_url
			 * @param WP_User $user
			 * @param array $http_data
			 * 
			 */
			$settings->redirect_url = apply_filters( 
				'streamtube/core/user/registered/redirect_url', 
				$settings->redirect_url, 
				$user, 
				$http_data 
			);

			wp_redirect( $settings->redirect_url );
			exit;
		}
	}

	/**
	 *
	 * Get user's vast tag
	 * 
	 * @param  integer $user_id
	 * 
	 */
	public function get_vast_tag_url( $user_id = 0 ){

		if( ! $user_id ){
			$user_id = get_current_user_id();
		}

		$vast_tag_url = get_user_meta( $user_id, $this->_vast_tag_url, true );

		return apply_filters( 'streamtube/core/user/vast_tag_url', $vast_tag_url, $user_id );
	}

	/**
	 *
	 * Update user advertising
	 * 
	 * @return true|WP_Error
	 * 
	 */
	public function update_advertising(){

		check_ajax_referer( '_wpnonce' );

		$errors = new WP_Error();

		$user_id = get_current_user_id();

		$http_data = wp_parse_args( $_POST, array(
			'vast_tag_url'	=>	''
		) );

		extract( $http_data );

		if( ! empty( $vast_tag_url ) && ! wp_http_validate_url( $vast_tag_url ) ){
			$errors->add(
				'invalid_vast_tag_url',
				esc_html__( 'Invalid Vast Tag URL', 'streamtube-core' )
			);			
		}

		if( ! Streamtube_Core_Permission::can_manage_vast_tag( $user_id ) ){
			$errors->add(
				'no_permission',
				esc_html__( 'You do not have permission to update Vast Tag URL', 'streamtube-core' )
			);
		}

		/**
		 *
		 * Filter the errors
		 * 
		 * @var WP_Error
		 *
		 * @since  1.0.0
		 * 
		 */
		$errors = apply_filters( 'streamtube/core/user/update_vast_tag_url/errors', $errors , $vast_tag_url );		

		if( $errors->get_error_code() ){
			return $errors;
		}		

		if( $vast_tag_url ){
			return update_user_meta( $user_id, $this->_vast_tag_url, $vast_tag_url );
		}

		return delete_user_meta( $user_id, $this->_vast_tag_url );
	}

	/**
	 *
	 * Update user advertising
	 * 
	 * @return true|WP_Error
	 * 
	 */
	public function ajax_update_advertising(){
		$response = $this->update_advertising();

		if( is_wp_error( $response ) ){
			wp_send_json_error( array(
				'message'	=>	$response->get_error_messages()
			) );
		}

		wp_send_json_success( array(
			'message'	=>	esc_html__( 'Advertising Updated', 'streamtube-core' )
		) );
	}

	/**
	 *
	 * Load user's vast tag
	 * 
	 * @param  array $setup
	 * 
	 */
	public function load_vast_tag_url( $vast_tag_url, $setup, $source ){

		if( ! array_key_exists( 'mediaid', $setup ) ){
			return $vast_tag_url;
		}

		$_post = get_post( $setup['mediaid'] );

		if( is_object( $_post ) ){
			$_vast_tag_url = $this->get_vast_tag_url( $_post->post_author );

			if( $_vast_tag_url && Streamtube_Core_Permission::can_manage_vast_tag( $_post->post_author ) ){
				$vast_tag_url = $_vast_tag_url;
			}
		}

		return $vast_tag_url;
	}
}