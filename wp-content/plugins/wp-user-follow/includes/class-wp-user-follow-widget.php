<?php
/**
 * Define the custom users widget functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    WP_User_Follow
 * @subpackage WP_User_Follow/includes
 */

/**
 *
 * @since      1.0.0
 * @package    WP_User_Follow
 * @subpackage WP_User_Follow/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class WP_User_Follow_Widget extends WP_Widget{

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::__construct()
	 */
	function __construct(){
	
		parent::__construct( 
			'wp-user-follow-widget' ,
			esc_html__('[WP User Follow] User List', 'wp-user-follow' ), 
			array( 
				'classname'		=>	'wp-user-follow-widget', 
				'description'	=>	esc_html__('[WP User Follow] Users', 'wp-user-follow')
			)
		);
	}

	/**
	 * Register this widget
	 */
	public static function register(){
		register_widget( __CLASS__ );
	}

	/**
	 *
	 * Get default types
	 * 
	 * @return array
	 *
	 * @since 1.0.0
	 * 
	 */
	private function get_types(){
		return array(
			'following'	=>	esc_html__( 'Following', 'wp-user-follow' ),
			'follower'	=>	esc_html__( 'Follower', 'wp-user-follow' )
		);
	}

	/**
	 *
	 * get default users
	 * 
	 * @return array
	 *
	 * @since 1.0.0
	 * 
	 */
	private function get_users(){
		return array(
			'current_logged_in'	=>	esc_html__( 'Current Logged In User', 'wp-user-follow' ),
			'current_author'	=>	esc_html__( 'Current Author', 'wp-user-follow' )
		);		
	}

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {

		$instance = wp_parse_args( $instance, array(
			'title'			=>	'',
			'type'			=>	'following',// or follower
			'user'			=>	'current_logged_in',
			'number'		=>	4,
			'avatar_size'	=>	50
		) );

		$user_id = 0;

		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		if( $instance['user'] == 'current_logged_in' ){

			if( ! is_user_logged_in() ){
				return;
			}

			$user_id = get_current_user_id();
		}

		if( $instance['user'] == 'author' ){
			if( is_singular() ){
				global $post;

				$user_id = $post->post_author;
			}

			if( is_author() ){
				$user_id = get_queried_object_id();
			}
		}

		if( $user_id == 0 ){
			return;
		}

		$user_ids = wpuf_get_follow_users( $user_id, $instance['type'], $instance['number'] );

		if( ! $user_ids ){
			return;
		}

		echo $args['before_widget'];

			if( $instance['title'] ){
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}

			echo '<ul class="user-list list-unstyled">';

				for ( $i=0; $i < count( $user_ids ); $i++ ):
					printf(
						'<li class="user-%s d-flex my-2">',
						$user_ids[$i]
					);

						if( function_exists( 'streamtube_core_get_user_avatar' ) ){
							streamtube_core_get_user_avatar( array(
		                        'user_id'       =>  $user_ids[$i],
		                        'link'          =>  true,
		                        'wrap_size'     =>  'md',
		                        'before'        =>  '<div class="user-wrap me-2">',
		                        'after'         =>  '</div>'
							) );

							streamtube_core_get_user_name( array(
	                            'user_id'   =>  $user_ids[$i],
	                            'before'    =>  '<div class="user-name">',
	                            'after'     =>  '</div>'
	                        ) );							
						}

					echo '</li>';
				endfor;

				printf(
					'<li><a class="fw-bold text-decoration-none text-info d-block mx-auto" href="%s">%s</a></li>',
					esc_url( trailingslashit( get_author_posts_url( get_current_user_id() ) ) ) . 'following',
					esc_html__( 'See all', 'wp-user-follow' )
				);

			echo '</ul>';

		echo $args['after_widget'];
	}

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {

		return $new_instance;
	}	

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::form()
	 */
	public function form( $instance ){

		$instance = wp_parse_args( $instance, array(
			'title'			=>	'',
			'type'			=>	'following',// or follower
			'user'			=>	'current_logged_in',
			'number'		=>	'5'
		) );

		?>
		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_html__( 'Title', 'wp-user-follow')

			);?>
			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_attr( $this->get_field_name( 'title' ) ),
				esc_attr( $instance['title'] )

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'type' ) ),
				esc_html__( 'User', 'wp-user-follow')

			);?>			
			<?php printf(
				'<select class="widefat" id="%s" name="%s"/>',
				esc_attr( $this->get_field_id( 'user' ) ),
				esc_attr( $this->get_field_name( 'user' ) )

			);?>

				<?php foreach ( $this->get_users() as $key => $value ): ?>
						
					<?php printf(
						'<option %s value="%s">%s</option>',
						selected( $instance['user'], $key, false ),
						esc_attr( $key ),
						esc_html( $value )
					);?>

				<?php endforeach ?>

			<?php echo '<select>';?>		
		</div>		

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'type' ) ),
				esc_html__( 'Type', 'wp-user-follow')

			);?>			
			<?php printf(
				'<select class="widefat" id="%s" name="%s"/>',
				esc_attr( $this->get_field_id( 'type' ) ),
				esc_attr( $this->get_field_name( 'type' ) )

			);?>

				<?php foreach ( $this->get_types() as $key => $value ): ?>
						
					<?php printf(
						'<option %s value="%s">%s</option>',
						selected( $instance['type'], $key, false ),
						esc_attr( $key ),
						esc_html( $value )
					);?>

				<?php endforeach ?>

			<?php echo '<select>';?>		
		</div>		

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'number' ) ),
				esc_html__( 'Number', 'wp-user-follow')

			);?>
			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'number' ) ),
				esc_attr( $this->get_field_name( 'number' ) ),
				esc_attr( $instance['number'] )

			);?>
		</div>

		<?php
	}	
}