<?php

/**
 * Define the shortcode functionality
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_ShortCode {

	/**
	 * Do shortcode
	 * @see do_shortcode()
	 * @param  string $content
	 * @return shortcoded content
	 *
	 * @since 2.1.7
	 * 
	 */
	private function do_shortcode( $content ){
		return do_shortcode( $content );
	}

	/**
	 *
	 * is_logged_in shortcode
	 * 
	 * @param  array   $args
	 * @param  string  $content
	 * @return string
	 *
	 * @since 2.1.7
	 * 
	 */
	public function _is_logged_in( $args = array(), $content = '' ){
		if( is_user_logged_in() ){
			return $this->do_shortcode( $content );
		}

		return;
	}

	/**
	 *
	 * is_logged_in shortcode
	 *
	 * @since 2.1.7
	 * 
	 */
	public function is_logged_in(){
		add_shortcode( 'is_logged_in', array( $this , '_is_logged_in' ), 10 );
	}

	/**
	 *
	 * is_logged_in shortcode
	 * 
	 * @param  array   $args
	 * @param  string  $content
	 * @return string
	 *
	 * @since 2.1.7
	 * 
	 */
	public function _is_not_logged_in( $args = array(), $content = '' ){
		if( ! is_user_logged_in() ){
			return $this->do_shortcode( $content );
		}

		return;
	}

	/**
	 *
	 * is_not_logged_in shortcode
	 *
	 * @since 2.1.7
	 * 
	 */
	public function is_not_logged_in(){
		add_shortcode( 'is_not_logged_in', array( $this , '_is_not_logged_in' ), 10 );
	}

	/**
	 *
	 * can_upload shortcode
	 * 
	 * @param  array   $args
	 * @param  string  $content
	 * @return string
	 *
	 * @since 2.1.7
	 * 
	 */
	public function _can_upload( $args = array(), $content = '' ){
		if( Streamtube_Core_Permission::can_upload() ){
			return $this->do_shortcode( $content );
		}

		return;
	}

	/**
	 *
	 * can_upload shortcode
	 *
	 * @since 2.1.7
	 * 
	 */
	public function can_upload(){
		add_shortcode( 'can_upload', array( $this , '_can_upload' ), 10 );
	}

	/**
	 *
	 * can_not_upload shortcode
	 * 
	 * @param  array   $args
	 * @param  string  $content
	 * @return string
	 *
	 * @since 2.1.7
	 * 
	 */
	public function _can_not_upload( $args = array(), $content = '' ){
		if( ! Streamtube_Core_Permission::can_upload() ){
			return $this->do_shortcode( $content );
		}

		return;
	}

	/**
	 *
	 * can_not_upload shortcode
	 *
	 * @since 2.1.7
	 * 
	 */
	public function can_not_upload(){
		add_shortcode( 'can_not_upload', array( $this , '_can_not_upload' ), 10 );
	}

	/**
	 *
	 * Current User name
	 * 
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function _user_name( $args = array() ){

		$args = wp_parse_args( $args, array(
			'user_id'	=>	'',
			'echo'		=>	false
		) );

		if( ! $args['user_id'] ){
			return;
		}

		switch ( $args['user_id'] ) {
			case 'logged_in':
				$args['user_id'] = get_current_user_id();
			break;
			
			case 'author':
				if( is_singular() ){
					global $post;

					$args['user_id'] = $post->post_author;
				}

				if( is_author() ){
					$args['user_id'] = get_queried_object_id();
				}
				
			break;
		}

		return sprintf(
			'<span class="ms-1 d-flex align-items-center">%s</span>',
			streamtube_core_get_user_name( $args )
		);
	}

	/**
	 *
	 * User name shortcode
	 * 
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function user_name(){
		add_shortcode( 'user_name', array( $this , '_user_name' ), 10 );
	}

	/**
	 *
	 * User avatar
	 * 
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function _user_avatar( $args = array() ){

		$args = wp_parse_args( $args, array(
			'user_id'	=>	'',
			'wrap_size'	=>	'sm',
			'echo'		=>	false
		) );

		if( ! $args['user_id'] ){
			return;
		}

		switch ( $args['user_id'] ) {
			case 'logged_in':
				$args['user_id'] = get_current_user_id();
			break;
			
			case 'author':
				if( is_singular() ){
					global $post;

					$args['user_id'] = $post->post_author;
				}

				if( is_author() ){
					$args['user_id'] = get_queried_object_id();
				}
				
			break;
		}

		return sprintf(
			'<span class="ms-1 d-flex align-items-center">%s</span>',
			streamtube_core_get_user_avatar( $args )
		);
	}

	/**
	 *
	 * User avatar shortcode
	 * 
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function user_avatar(){
		add_shortcode( 'user_avatar', array( $this , '_user_avatar' ), 10 );
	}

	/**
	 *
	 * [user_data] shortcode
	 * 
	 */
	public function _user_data( $args = array() ){
		$args = wp_parse_args( $args, array(
			'user_id'		=>	0,
			'user_type'		=>	'current_author',
			'field'			=>	''
		) );

		extract( $args );

		switch ( $user_type ) {
			case 'current_author':

				if( is_singular() ){
					global $post;
					$user_id = $post->post_author;
				}

				if( is_author() ){
					$user_id = get_queried_object_id();
				}

			break;
			
			case 'current_logged_in':
				$user_id = get_current_user_id();
			break;
		}

		if( $user_id ){
			return get_userdata( $user_id )->$field;
		}
	}

	public function user_data(){
		add_shortcode( 'user_data', array( $this , '_user_data' ), 10 );
	}

	/**
	 *
	 * The users list
	 * 
	 * @param  array $args
	 * @return HTML
	 *
	 * @since  1.0.0
	 * 
	 */
	public function _user_grid( $args = array() ){

		ob_start();

		the_widget( 'Streamtube_Core_Widget_User_Grid', $args, array(
            'before_widget' => '<div class="widget widget-elementor user-grid-widget streamtube-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title-wrap"><h2 class="widget-title d-flex align-items-center">',
            'after_title'   => '</h2></div>'
        ));

        return ob_get_clean();
	}

	/**
	 *
	 * Add "users_list" shortcode
	 * 
	 * @return $this->_users_list()
	 *
	 * @since  1.0.0
	 * 
	 */
	public function user_grid(){
		add_shortcode( 'user_grid', array( $this , '_user_grid' ), 10 );
	}	

	/**
	 *
	 * Posts
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function _post_grid( $args ){
		if( class_exists( 'Streamtube_Core_Widget_Posts' ) ){

			ob_start();

			the_widget( 'Streamtube_Core_Widget_Posts', $args, array(
				'before_widget' => '<section class="widget widget-primary %1$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<div class="widget-title-wrap d-flex"><h2 class="widget-title d-flex align-items-center">',
				'after_title'   => '</h2></div>',
			) );

			return ob_get_clean();
		}
	}

	/**
	 *
	 * Posts grid shortcode
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function post_grid(){
		add_shortcode( 'post_grid', array( $this , '_post_grid' ), 10 );
	}

	/**
	 *
	 * The playlist shortcode
	 * 
	 * @param  array $args
	 * 
	 */
	public function _playlist( $args = array() ){

		$content_layout = 'grid';

		$args = wp_parse_args( $args, array(
			'post_type'			=>	'video',
			'post_status'		=>	'publish',
			'posts_per_page'	=>	10,
			'search'			=>	'',
			'orderby'			=>	'date',
			'order'				=>	'DESC',
			'ratio'				=>	'16x9',
			'style'				=>	'light',
			'layout'			=>	'list_sm',
			'template'			=>	'vertical',
			'upnext'			=>	'',
			'author_name'		=>	'on',
			'post_date'			=>	'',
			'post_comment'		=>	'',
			'container'			=>	'container'
		) );

		if( $args['layout'] != 'grid' ){
			$content_layout = 'list';
		}

        $query_args = array(
            'post_type'         =>  $args['post_type'],
            'post_status'       =>  $args['post_status'],
            'posts_per_page'    =>  $args['posts_per_page'],
            's'                 =>  $args['search'],
            'orderby'           =>  $args['orderby'],
            'order'             =>  $args['order'],
            'meta_query'        =>  array()
        );

        if( $args['post_type'] == 'video' ){
        	$query_args['meta_query'][] = array(
                'key'       =>  '_thumbnail_id',
                'compare'   =>  'EXISTS'
        	);
        	$query_args['meta_query'][] = array(
                'key'       =>  Streamtube_Core_Post::VIDEO_URL,
                'compare'   =>  'EXISTS'
        	);
        }

        // Set taxonomies
        $taxonomies = get_object_taxonomies( $query_args['post_type'], 'object' );

        if( $taxonomies ){

            $tax_query = array();

            foreach ( $taxonomies as $tax => $object ) {
                
                if( array_key_exists( 'tax_query_' . $tax , $args ) && $args[ 'tax_query_' . $tax ] ){
                    $tax_query[] = array(
                        'taxonomy'  =>  $tax,
                        'field'     =>  'slug',
                        'terms'     =>  (array)$args[ 'tax_query_' . $tax ]
                    );
                }
            }

            if( $tax_query ){
                $query_args['tax_query'] = $tax_query;
            }
        }        

        /**
         * 
         * Filter the post args
         *
         * @param array $args
         * @param array $settings
         *
         * @since 1.0.0
         * 
         */
        $query_args = apply_filters( 'streamtube/core/playlist/post_args', $query_args, $args );

        $post_query = new WP_Query( $query_args );

        if( ! $post_query->have_posts() ){
            return;
        }

        ob_start();

        $loop = 0;

        printf(
        	'<div class="widget-videos-playlist posts-widget streamtube-widget %s"><div class="%s"><div class="row">',
        	$args['upnext'] ? 'up-next' : 'up-next-off',
        	$args['container'] ? $args['container'] : 'no-container'
        );

            while ( $post_query->have_posts() ):

                $post_query->the_post();

                $loop++;

                if( $loop == 1 ){
                    // Get first post
                    
                    do_action( 'streamtube/playlist/first_post/loaded' );
                    
                    printf(
                    	'<div class="col-xxl-%1$s col-xl-%2$s col-lg-%2$s col-md-12 col-12">',
                    	$args['template'] == 'vertical' ? '9' : '12',
                    	$args['template'] == 'vertical' ? '8' : '12'
                    );

                        printf(
                            '<div class="embed-wrap"><div class="ratio ratio-%s">%s</div></div>',
                            $args['ratio'],
                           get_post_embed_html( 560, 315, get_the_ID() )
                        );
                    echo '</div>';

                    printf(
                    	'<div class="col-xxl-%1$s col-xl-%2$s col-lg-%2$s col-md-12 col-12">',
                    	$args['template'] == 'vertical' ? '3' : '12',
                    	$args['template'] == 'vertical' ? '4' : '12'
                    );

                    printf(
                        '<div class="playlist-item border post-grid-%s post-grid post-grid-%s d-none">',
                        sanitize_html_class( $args['style'] ),
                        sanitize_html_class( $args['layout'] )
                    );
                }

                printf(
                	'<div class="post-item %s p-3">',
                	$loop == 1 ? 'active' : ''
                );

                    get_template_part( 'template-parts/content/content', $content_layout, array(
                        'thumbnail_size'        =>  'medium',
                        'post_excerpt_length'   =>  0,
                        'show_author_name'		=>	$args['author_name'],
                        'show_post_date'		=>	$args['post_date'],
                        'show_post_comment'		=>	$args['post_comment']
                    ) );                        

                echo '</div>';

            endwhile;          

        echo '</div></div></div></div></div>';

        wp_reset_postdata();

        return ob_get_clean();
	}

	/**
	 *
	 * Playlist shortcode
	 * 
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	public function playlist(){
		add_shortcode( 'playlist', array( $this , '_playlist' ), 10 );
	}

	/**
	 *
	 * player shortcode generator 
	 * 
	 * @param  array $args
	 * @return string
	 *
	 * @since 1.0.9
	 */
	public function _player( $args = array() ){

		if( ! defined( 'STREAMTUBE_IS_PLAYER_SHORTCODE' ) ){
		    define( 'STREAMTUBE_IS_PLAYER_SHORTCODE' , true );    
		}		

		$args = wp_parse_args( $args, array(
			'uniqueid'		=>	uniqid(),
			'post_id'   	=>  '',
			'source'    	=>  '',
			'poster'    	=>  '',
			'ratio'     	=>  get_option( 'player_ratio', '21x9' ),
			'player'		=>	'videojs',
			'autoplay'		=>	false,
			'has_filter'	=>	false,
			'is_embed'		=>	true,
			'youtube'		=>	get_option( 'override_youtube', 'on' )
		) );

		if( ! $args['post_id'] && is_string( $args['source'] && ! $args['uniqueid'] ) ){
			$args['uniqueid'] = md5( $args['source'] );
		}

		ob_start();

		if( wp_validate_boolean( $args['has_filter'] ) === false ){
			remove_all_filters( 'streamtube/player/file/output' );
			remove_all_filters( 'streamtube/player/embed/output' );
		}

		add_filter( 'streamtube_pre_player_args', function( $_args ) use( $args ){
			return array_merge( $_args, array(
				'autoplay'	=>	$args['autoplay']
			) );
		}, 10, 1 );

		get_template_part( 'template-parts/player',null, $args );

		return ob_get_clean();
	}

	/**
	 *
	 * The player shortcode
	 *
	 * @since 1.0.9
	 * 
	 */
	public function player(){
		add_shortcode( 'player', array( $this , '_player' ), 10 );
	}

	/**
	 *
	 * [embed_media] shortcode
	 * 
	 * @param  array $args
	 * @param  string $content
	 * @return string
	 */
	public function _embed_media( $args = array(), $content = '' ){

		if( empty( $content ) ){
			return;
		}

		$args = wp_parse_args( $args, array(
			'ratio'		=>	'16x9',
			'mb'		=>	4,
			'max_width'	=>	'100%',
			'align'		=>	'center'
		) );

		extract( $args );

		switch ( $align ) {
			case 'center':
				$align = 'margin:0 auto';
			break;
			
			case 'left':
				$align = 'margin-right:auto';
			break;

			case 'right':
				$align = 'margin-left:auto';
			break;			
		}

		$embed = wp_oembed_get( $content );

		if( empty( $embed ) ){
			$embed = $content;
		}

		return sprintf(
			'<div class="embed-media mb-%s" style="max-width: %s; %s"><div class="ratio ratio-%s"><div class="embed-content">%s</div></div></div>',
			esc_attr( $mb ),
			esc_attr( $max_width ),
			esc_attr( $align ),
			esc_attr( $ratio ),
			$embed
		);
	}

	public function embed_media(){
		add_shortcode( 'embed_media', array( $this , '_embed_media' ), 10 );
	}

	/**
	 *
	 * The Upload shortcode
	 * 
	 * @param  array  $args
	 * @return string
	 *
	 * @since 2.1.7
	 * 
	 */
	public function _button_upload( $args = array() ){

		$args = wp_parse_args( $args, array(
			'type'			=>	'upload', // or embed
			'button_icon'	=>	'icon-videocam',
			'button_text'	=>	esc_html__( 'Upload Video', 'streamtube-core' ),
			'button_class'	=>	'btn btn-primary',
			'button_modal'	=>	'',
			'no_permission'	=>	esc_html__( 'Sorry, You do not have permission to upload videos.', 'streamtube-core' )
		) );

		if( $args['type'] == 'upload' && ! get_option( 'upload_files', 'on' ) ){
			return sprintf(
				'<p class="text-muted">%s</p>',
				esc_html__( 'Upload is disabled', 'streamtube-core' )
			);
		}

		$args['button_modal'] = '#modal-' . sanitize_html_class( $args['type'] );

		if( ! is_user_logged_in() ){
			return sprintf(
				'<p class="login-required text-muted">'. esc_html__( 'Please %s to upload videos', 'streamtube-core' ) .'</p>',
				'<a class="text-muted" href="'. esc_url( wp_login_url( get_permalink() ) ) .'">'. esc_html__( 'log in', 'streamtube-core' ) .'</a>'
			);
		}

		if( Streamtube_Core_Permission::can_upload() ){

			/**
			 * @since 2.1.7
			 */
			do_action( 'streamtube/core/shortcode/upload' );

			return sprintf(
				'<button type="button" class="%s" data-bs-toggle="modal" data-bs-target="%s">%s %s</button>',
				esc_attr( $args['button_class'] ),
				esc_attr( $args['button_modal'] ),
				'<span class="'. esc_attr( $args['button_icon'] ) .'"></span>',
				$args['button_text']
			);
		}else{
			return sprintf(
				'<p class="text-danger">%s</p>',
				$args['no_permission']
			);
		}
	}

	/**
	 *
	 * Add Upload shortcode
	 * 
	 * @since 2.1.7
	 */
	public function button_upload(){
		add_shortcode( 'button_upload', array( $this , '_button_upload' ), 10 );
	}

	/**
	 *
	 * The upload form shortcode
	 * 
	 * @param  array  $args
	 */
	public function _form_upload( $args = array() ){

		if( is_admin() ){
			return;
		}

		ob_start();

		if( Streamtube_Core_Permission::can_upload() ):

		?>
			<form class="form-ajax form-steps upload-video-form mb-4" autocomplete="off">

				<?php streamtube_core_the_upload_form(); ?>

				<input type="hidden" name="action" value="upload_video">
				<input type="hidden" name="quick_update" value="1">

				<div class="form-submit d-flex">
					<button type="submit" class="btn btn-danger px-4 text-white btn-next d-none ms-auto">
						<?php esc_html_e( 'Save Changes', 'streamtube-core' ); ?>
					</button>				
				</div>
			</form>
		<?php

		else:
			printf(
				'<p class="text-warning m-0">%s</p>',
				esc_html__( 'Sorry, You do not have permission to upload videos', 'streamtube-core' )
			);
		endif;

		return ob_get_clean();
	}

	/**
	 *
	 * The upload form wrapper
	 * 
	 */
	public function form_upload(){
		add_shortcode( 'form_upload', array( $this , '_form_upload' ), 10 );
	}

	/**
	 *
	 * The embed form shortcode
	 * 
	 * @param  array  $args
	 */
	public function _form_embed( $args = array() ){

		if( is_admin() ){
			return;
		}

		ob_start();		
		?>
		<form class="form-ajax form-regular upload-video-form mb-4" autocomplete="off">

			<?php streamtube_core_the_embed_form(); ?>

			<input type="hidden" name="action" value="import_embed">
			<input type="hidden" name="quick_update" value="1">

			<div class="form-submit d-flex">
				<button type="submit" class="btn btn-danger px-4 text-white btn-next ms-auto">
					<span class="icon-plus"></span>
					<?php esc_html_e( 'Import', 'streamtube-core' ); ?>
				</button>
			</div>

		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 *
	 * The embed form wrapper
	 * 
	 */
	public function form_embed(){
		add_shortcode( 'form_embed', array( $this , '_form_embed' ), 10 );
	}

	/**
	 * Redirects non-logged-in users to the login page before accessing upload forms.
	 */
	public function redirect_nonlogged_to_login_on_upload() {
	    global $post;

	    // Check if the current page is a post and the user is not logged in
	    if (is_a($post, 'WP_Post') && !is_user_logged_in()) {
	        // Check if the post content contains the 'form_upload' or 'form_embed' shortcode
	        if ( 
	        	has_shortcode($post->post_content, 'form_upload') || 
	        	has_shortcode($post->post_content, 'form_embed')  ||
	        	has_shortcode($post->post_content, 'form_golive')
	        ) {
	            // Redirect to the login page and exit
	            wp_redirect(wp_login_url(get_permalink($post->ID)));
	            exit;
	        }
	    }
	}


	/**
	 *
	 * [category_list] shortcode
	 * 
	 * @param  array  $args
	 * 
	 * @since 2.2.1
	 * 
	 */
	public function _term_grid( $args = array() ){

		$output = '';

		if( class_exists( 'Streamtube_Core_Widget_Term_Grid' ) ){

			ob_start();

			the_widget( 'Streamtube_Core_Widget_Term_Grid', $args, array(
				'before_widget' => '<section class="widget widget-primary %1$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<div class="widget-title-wrap d-flex"><h2 class="widget-title d-flex align-items-center">',
				'after_title'   => '</h2></div>',				
			) );

			$output = ob_get_clean();
		}

		return $output;
	}

	/**
	 *
	 * [category_list] shortcode
	 * 
	 * @param  array  $args
	 * 
	 * @since 2.2.1
	 * 
	 */
	public function term_grid(){
		add_shortcode( 'term_grid', array( $this , '_term_grid' ), 10 );
	}

	/**
	 *
	 * The User Library shortcode
	 * 
	 * @param  array  $args
	 * @return string
	 * 
	 */
	public function _user_library( $args = array() ){

		$args = wp_parse_args( $args, array(
			'user_id'			=>	get_current_user_id(),
	        'posts_per_column' 	=>  4,
	        'rows_per_page'     =>  2,
	        'col_xl'            =>  4,
	        'col_lg'            =>  4,
	        'col_md'            =>  2,
	        'col_sm'            =>  2,
	        'col'               =>  1,
	        'pagination'		=>	'click',
	        'not_login_message'	=>	esc_html__( 'Sign in to discover your library', 'streamtube-core' ),
	        'btn_login_icon'	=>	'icon-user-circle',
	        'btn_login_text'	=>	esc_html__( 'Log In', 'streamtube-core' )
		) );

		ob_start();
		streamtube_core_load_template( 'shortcode/user-library.php', true, $args );
		return ob_get_clean();
	}

	public function user_library(){
		add_shortcode( 'user_library', array( $this , '_user_library' ), 10 );
	}

	/**
	 *
	 * The [dashboard_url] shortcode
	 * 
	 * @param  array  $args
	 * @return string
	 * 
	 */
	public function _user_dashboard_url( $args = array() ){
		$args = wp_parse_args( $args, array(
			'endpoint'	=>	''
		) );

		extract( $args );

		if( ! is_user_logged_in() ){
			$endpoint = wp_login_url();
		}else{
			$endpoint = streamtube_core_get_user_dashboard_url( get_current_user_id(), $endpoint );
		}

		return $endpoint;
	}

	public function user_dashboard_url(){
		add_shortcode( 'dashboard_url', array( $this , '_user_dashboard_url' ), 10 );
	}	

	/**
	 *
	 * The [chapter] shortcode
	 * 
	 * @param  array $args
	 * @param  string $content
	 * @return string
	 * 
	 */
	public function _chapters( $args, $content = '' ){

		$output = '';

		$args = wp_parse_args( $args, array(
			'max_height'	=>	false
		) );

		extract( $args );

		if( empty( $content ) ){
			return;
		}

		$lines = explode( "\n", $content );

		if( is_array( $lines ) ){

			$lines = str_replace( array( '<br/>', '<br />', '\r', '\n' ), '', $lines );

			$lines = array_filter( $lines, function( $line ) {
				return wp_strip_all_tags( $line );
			});

			$lines = array_unique( $lines );

			$output = sprintf( 
				'<ul class="chapter-list" style="%s">', 
				$max_height ? 'max-height:' . esc_attr( $max_height ) : ''
			);

				for ( $i = 0; $i < count( $lines ); $i++) {
					if( ! empty( $lines[$i]) ){
						$output .= sprintf( '<li>%s</li>', $lines[$i] );	
					}
				}
			$output .= '</ul>';
		}

		return trim( $output );
	}

	/**
	 *
	 * [Chapter] shortcode wrapper
	 * 
	 */
	public function chapter(){
		add_shortcode( 'chapters', array( $this , '_chapters' ), 10 );
		add_shortcode( 'timestamps', array( $this , '_chapters' ), 10 );
	}


	/**
	 *
	 * [term_menu] shortcode
	 * 
	 */
	public function _term_menu( $args = array() ){

		$output = '';

		$args = wp_parse_args( $args, array(
			'post_type'			=>	'video',
			'taxonomy'			=>	array( 'categories', 'video_tag' ),
			'hide_empty'		=>	true,
			'orderby'			=>	'count',
			'order'				=>	'DESC',
			'include'			=>	array(),
			'exclude'			=>	array(),
			'exclude_tree'		=>	array(),
			'count'				=>	'',
			'number'			=>	30,
			'parent'			=>	0,
			'childless'			=>	false,
			'childofcurrent'	=>	false, // auto show childs of current parent term
			'include_all'		=>	get_option( 'archive_video', 'on' ) ? true : false,
			'all_url'			=>	'',
			'all_text'			=>	esc_html__( 'All', 'streamtube-core' ),
			'all_text_callback'	=>	function( $all_text, $terms, $term_args = array(), $args = array() ){
				return $all_text;
			},
			'slide'				=>	true,
			'slidesToScroll'	=>	3,
			'variableWidth'		=>	true,
			'infinite'			=>	false,
			'class'				=>	'',
			'rtl'				=>	is_rtl() ? true : false,
			'active'			=>	'',
			'title_callback'	=>	function( $title, $term, $term_args = array(), $args = array() ){
				return $title;
			}
		) );

		extract( $args );

		if( $include_all ){
			if( ! $all_url ){
				$all_url = get_post_type_archive_link( $post_type );
			}
		}

		$slidesToScroll = absint( $slidesToScroll ) == 0 ? 3 : absint($slidesToScroll);

		if( is_string( $taxonomy ) ){
			$taxonomy = array_map( 'trim' , explode( ',' , $taxonomy ));
		}

		if( is_string( $include ) ){
			$include = array_map( 'trim' , explode( ',' , $include ));
		}

		if( is_string( $exclude ) ){
			$exclude = array_map( 'trim' , explode( ',' , $exclude ));
		}

		if( is_string( $exclude_tree ) ){
			$exclude_tree = array_map( 'trim' , explode( ',' , $exclude_tree ));
		}

		if( $childofcurrent && is_tax() ){

			$current_term = get_queried_object();

			if( get_term_children( $current_term->term_id, $current_term->taxonomy ) ){
				$parent = $current_term->term_id;
			}
		}

		$term_args = compact( 
			'taxonomy', 
			'hide_empty',
			'orderby',
			'order',
			'include',
			'exclude',
			'exclude_tree',
			'number',
			'parent',
			'childless'
		);

		$terms = get_terms( apply_filters( 'streamtube/core/term_menu/term_args', $term_args, $args ) );

		if( is_array( $terms ) ){

			if( $orderby == 'name' ){
				uasort( $terms, function( $term1, $term2 ) use( $order ){
					if( strtolower( $order ) == 'asc' ){
						return strnatcmp( $term1->name, $term2->name );	
					}else{
						return strnatcmp( $term2->name, $term1->name );
					}
				} );
			}

			if( wp_validate_boolean( $include_all ) ){
	            $output .= sprintf(
	                '<div class="term-item term-archive %s"><a href="%s">%s</a></div>',
	                is_post_type_archive( $post_type ) ? 'current' : '',
	                $all_url,
	                is_callable( $all_text_callback ) ? call_user_func( $all_text_callback, $all_text, $terms, $term_args, $args ) : $all_text
	            );
        	}

            foreach( $terms as $term ) {     	

                $output .= sprintf(
                    '<div class="term-item term-%s %s" data-term-id="%s" data-taxonomy="%s"><a href="%s">%s%s</a></div>',
                    esc_attr( $term->slug ),
                    is_tax( null, $term ) && get_queried_object_id() == $term->term_id || $active == $term->term_id ? 'current' : '',
                    esc_attr( $term->term_id ),
                    esc_attr( $term->taxonomy ),
                    get_term_link( $term ),
                    is_callable( $title_callback ) ? call_user_func( $title_callback, $term->name, $term, $term_args, $args ) : $term->name,
                    $count ? ' <span class="term-count">('.$term->count.')</span>' : ''
                );
            }
		}

		if( ! empty( $output ) ){
			$slick_options = compact( 
				'slidesToScroll', 
				'variableWidth',
				'infinite',
				'rtl'
			);

			return sprintf(
				'<div class="terms-menu %s %s" data-slick="%s">%s</div>',
				$class 	? esc_attr( $class ) : '',
				$slide && $terms ? 'js-slick' : 'no-slick',
				esc_attr( json_encode( $slick_options ) ),
				$output
			);
		}
	}

	/**
	 *
	 * [term_menu] shortcode wrapper
	 * 
	 */
	public function term_menu(){
		add_shortcode( 'term_menu', array( $this , '_term_menu' ), 10 );
	}

	/**
	 *
	 * The term menu with settings from customize
	 * 
	 */
	public function _the_term_menu(){
		$settings = (array)get_option( 'term_menu' );

		$settings = wp_parse_args( $settings, array(
			'enable'			=>	'on'
		) );

		if( $settings['enable'] ){

			/**
			 *
			 * Filter the default archive term menu settings
			 * 
			 * @param array $settings
			 * 
			 */
			$settings = apply_filters( 'streamtube/core/archive_term_menu_settings', $settings );

			/**
			 *
			 * Fires before menu being loaded
			 * 
			 */
			do_action( 'streamtube/core/archive_term_menu/loaded' );

			return $this->_term_menu( $settings );
		}
	}

	/**
	 *
	 * [term_menu] shortcode wrapper
	 * 
	 */
	public function the_term_menu(){
		add_shortcode( 'the_term_menu', array( $this , '_the_term_menu' ), 10 );
	}

	/**
	 *
	 * The term play all url shortcode
	 */
	public function _term_play_all_url( $args = array() ){

		$args = wp_parse_args( $args, array(
			'term_id'	=>	0
		) );

		extract( $args );

		$term_id = (int)$term_id;

		if( ! $term_id ){
			return;
		}

		$term = get_term( $term_id );

		if( $term ){
			global $streamtube;

			return $streamtube->get()->collection->get_play_all_link( $term, $term->taxonomy );
		}
	}

	public function term_play_all_url(){
		add_shortcode( 'term_play_all_url', array( $this , '_term_play_all_url' ), 10 );
	}	
}