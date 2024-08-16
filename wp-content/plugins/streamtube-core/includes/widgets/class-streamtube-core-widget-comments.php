<?php
/**
 * Define the custom comments widget functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
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
class Streamtube_Core_Widget_Comments extends WP_Widget{

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::__construct()
	 */
	function __construct(){
	
		parent::__construct( 
			'comments-widget' ,
			esc_html__('[StreamTube] Comments', 'streamtube-core' ), 
			array( 
				'classname'		=>	'comments-widget streamtube-widget', 
				'description'	=>	esc_html__('[StreamTube] Comments', 'streamtube-core')
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
	 * Get comment author avatar
	 * 
	 * @param  [type]  $comment [description]
	 * @param  integer $size    [description]
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	private function get_comment_avatar( $comment, $size = 30 ){
		if( $comment->user_id ){
			return get_avatar( $comment->user_id, $size );
		}
		else{
			return get_avatar( $comment->comment_author_email, $size );
		}
	}

	/**
	 *
	 * Get comment author name
	 * 
	 * @param  [type]  $comment [description]
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	private function get_comment_author_name( $comment ){
		$display_name = $comment->comment_author;

		if( $comment->user_id ){
			$display_name = get_userdata( $comment->user_id )->display_name;
		}

		return $display_name;
	}

	/**
	 *
	 * Get comment author url
	 * 
	 * @param  [type]  $comment [description]
	 * @return string
	 *
	 * @since  1.0.0
	 * 
	 */
	private function get_comment_author_url( $comment ){
		$url = $comment->comment_author_url;

		if( $comment->user_id ){
			$url = get_author_posts_url( $comment->user_id );
		}

		return $url;
	}

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {

		$instance = wp_parse_args( $instance, array(
			'title'					=>	'',
			'number'				=>	4,
			'avatar_size'			=>	40,
			'comment_length'		=>	10,
			'current_logged_user'	=>	'',
			'current_logged_author'	=>	'',
			'current_author'		=>	'',
			'current_post'			=>	''
		) );

		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		$number = 4;

		$post_id = 0;

		$user_id = 0;

		$post_author__in = array();

		if( $instance['current_logged_user'] && is_user_logged_in() ){
			$user_id = get_current_user_id();
		}

		if( $instance['current_author'] ){
			if( is_singular() ){
				global $post;
				$post_author__in[] = $post->post_author;
			}

			if( is_author() ){
				$post_author__in[] = get_queried_object_id();	
			}
		}

		if( $instance['current_post'] && is_singular() ){
			$post_id = get_the_ID();
		}

		if( absint( $instance['number'] ) > 0 ){
			$number = absint( $instance['number'] );
		}

		$comment_args = compact( 'number', 'post_author__in', 'post_id' );

		if( $user_id ){
			$comment_args['user_id'] = $user_id;
		}

		$comment_args = array_merge( $comment_args, array(
			'hierarchical'	=>	'threaded'
		) );

		/**
		 *
		 * Filter comment args
		 * 
		 * @since 1.0.0
		 */
		$comment_args = apply_filters( 'streamtube/core/widget/comments/args', $comment_args, $instance );

		$comments = get_comments( $comment_args );

		if( ! $comments ){
			return;// return nothing if no comments found.
		}

		echo $args['before_widget'];

			if( $instance['title'] ){

				if( is_author() && isset( $GLOBALS['wp_query']->query_vars['dashboard'] ) ){
					$instance['title'] .= sprintf(
						'<a href="%s" class="ms-auto fw-bolder text-decoration-none small text-uppercase view-more">%s</a>',
						esc_url( streamtube_core_get_user_dashboard_url( get_queried_object_id(), 'comments' ) ),
						esc_html__( 'View more', 'streamtube-core' )
					);					
				}

				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}

			?>

			<ul class="list-unstyled m-0 p-0 comment-list">

				<?php foreach ( $comments as $comment ):?>

					<li class="text-muted border-bottom pb-3 mb-3">

						<div class="comment-wrap">

							<div class="user-avatar comment-avatar float-start me-3">
								<?php printf(
									'<a href="%s">%s</a>',
									esc_url( $this->get_comment_author_url( $comment ) ),
									$this->get_comment_avatar( $comment, $instance['avatar_size'] )
								);?>
							</div>

							<div class="comment-body overflow-hidden">

								<div class="comment-meta">
									<?php printf(
										'<a class="d-block text-body fw-bolder text-decoration-none" href="%s" title="%s">%s</a>',
										esc_url( $this->get_comment_author_url( $comment ) ),
										'',
										$this->get_comment_author_name( $comment )
									);?>

									<small class="ccomment-time">
										<span class="icon-clock"></span>
										<?php printf( 
											esc_html__('%s ago', 'streamtube-core' ), 
											human_time_diff( strtotime( $comment->comment_date_gmt ), current_time('timestamp') ) 
										);?>
									</small>
								</div>

								<div class="comment-content small">
									<?php echo wp_trim_words( get_comment_excerpt( $comment->comment_ID ), $instance['comment_length'] ); ?>
								</div>
							</div>

						</div>

					</li>	
				<?php endforeach; ?>
			</ul>

			<?php

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
			'title'					=>	'',
			'number'				=>	5,
			'avatar_size'			=>	40,
			'comment_length'		=>	10,
			'current_logged_user'	=>	'',
			'current_author'		=>	'',
			'current_post'			=>	''
		) );

		?>
		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'title' ) ),
				esc_html__( 'Title', 'streamtube-core')

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
				esc_attr( $this->get_field_id( 'number' ) ),
				esc_html__( 'Number', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'number' ) ),
				esc_attr( $this->get_field_name( 'number' ) ),
				esc_attr( $instance['number'] )

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'avatar_size' ) ),
				esc_html__( 'Avatar Size', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'avatar_size' ) ),
				esc_attr( $this->get_field_name( 'avatar_size' ) ),
				esc_attr( $instance['avatar_size'] )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'current_logged_user' ) ),
				esc_html__( 'Size of commenter avatar', 'streamtube-core')

			);?>			
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'comment_length' ) ),
				esc_html__( 'Comment Content Length', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'comment_length' ) ),
				esc_attr( $this->get_field_name( 'comment_length' ) ),
				esc_attr( $instance['comment_length'] )

			);?>
		</div>		

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
				esc_attr( $this->get_field_id( 'current_logged_user' ) ),
				esc_attr( $this->get_field_name( 'current_logged_user' ) ),
				checked( $instance['current_logged_user'], 'on', false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'current_logged_user' ) ),
				esc_html__( 'Retrieve comments of current logged in user.', 'streamtube-core')

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
				esc_attr( $this->get_field_id( 'current_author' ) ),
				esc_attr( $this->get_field_name( 'current_author' ) ),
				checked( $instance['current_author'], 'on', false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'current_author' ) ),
				esc_html__( 'Retrieve comments of current author.', 'streamtube-core')

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
				esc_attr( $this->get_field_id( 'current_post' ) ),
				esc_attr( $this->get_field_name( 'current_post' ) ),
				checked( $instance['current_post'], 'on', false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'current_post' ) ),
				esc_html__( 'Retrieve comments of current post.', 'streamtube-core')

			);?>
		</div>		
		<?php
	}	
}