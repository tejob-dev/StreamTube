<?php
/**
 * Define the custom users widget functionality
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
class Streamtube_Core_Widget_User_List extends WP_Widget{

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::__construct()
	 */
	function __construct(){
	
		parent::__construct( 
			'user-list-widget' ,
			esc_html__('[StreamTube] User List', 'streamtube-core' ), 
			array( 
				'classname'		=>	'user-list-widget streamtube-widget', 
				'description'	=>	esc_html__('[StreamTube] User List', 'streamtube-core')
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
	 * Show count options
	 * 
	 * @return array
	 */
	public static function get_show_count_options(){
		return array(
			'none'			=>	esc_html__( 'None', 'streamtube-core' ),
			'video'			=>	esc_html__( 'Video Count', 'streamtube-core' ),
			'post'			=>	esc_html__( 'Post Count', 'streamtube-core' ),
			'follower'		=>	esc_html__( 'Follower Count', 'streamtube-core' )
		);
	}

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {

		$instance = wp_parse_args( $instance, array(
			'title'					=>	'',
			'number'				=>	5,
			'role__in'				=>	array(),
			'orderby'				=>	'post_count',
			'order'					=>	'DESC',
			'show_count'			=>	'video',
			'has_published_posts'	=>	'video'
		) );

		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		extract( $instance );

		if( $role__in && is_string( $role__in ) ){
			$role__in = array_map( 'trim', explode( ',', $role__in ) );
		}

		if( $has_published_posts  && is_string( $has_published_posts ) ){
			$has_published_posts = array_map( 'trim', explode( ',', $has_published_posts ) );
		}

		if( ! $has_published_posts || (is_array( $has_published_posts ) && count( $has_published_posts ) == 0) ){
			$has_published_posts = false;
		}

		$query_args = compact(
			'number',
			'has_published_posts',
			'orderby',
			'order'
		);

		if( $role__in ){
			$query_args['role__in'] = $role__in;
		}

		if( $show_count == 'follower' ){
			$query_args = array_merge( $query_args, array(
				'meta_query'	=>	array(
					'key'		=>	'following_count',
					'value'		=>	0,
					'compare'	=>	'>'
				),
				'meta_key'		=>	'following_count',
				'orderby'		=>	'meta_value_num'
			) );
		}

		/**
		 * Filter query args
		 * @since 1.0.0
		 */
		$query_args = apply_filters( 'streamtube/widget/user_list/args', $query_args, $instance );

		$user_query = new WP_User_Query( $query_args );

		if ( empty( $user_query->get_results() ) ) {
			return;
		}

		echo $args['before_widget'];

			if( $title ){
				echo $args['before_title'] . $title . $args['after_title'];
			}

			echo '<ul class="user-list list-unstyled">';

				foreach ( $user_query->get_results() as $user ):

					?>
					<li class="user-item mb-4">
						<div class="d-flex align-items-start">
							<?php
								streamtube_core_get_user_avatar( array(
			                        'user_id'       =>  $user->ID,
			                        'link'          =>  true,
			                        'wrap_size'     =>  'lg',
			                        'before'        =>  '<div class="user-wrap">',
			                        'after'         =>  '</div>'
								) );
							?>
							<div class="user-meta">
								<?php
									streamtube_core_get_user_name( array(
			                            'user_id'   =>  $user->ID,
			                            'before'    =>  '<h4 class="user-name m-0">',
			                            'after'     =>  '</h4>',
			                            'link'		=>	true
			                        ) );
								?>

								<?php if( $show_count != 'none' ): ?>

									<?php 
									if( array_key_exists( $show_count, get_post_types() ) ): 
									$count = count_user_posts( $user->ID, $instance['show_count'], true );
									?>
										<div class="video-count text-secondary small">

											<?php if( $count > 1 || $count == 0 ){
												printf(
													esc_html__( '%s %s', 'streamtube-core' ),
													number_format_i18n( $count ),
													get_post_type_object( $instance['show_count'] )->label
												);
											}else{
												printf(
													esc_html__( '%s %s', 'streamtube-core' ),
													$count,
													get_post_type_object( $instance['show_count'] )->labels->singular_name
												);
											}?>

										</div>
									<?php endif;?>

									<?php 
									if( $show_count == 'follower' && function_exists( 'wpuf_get_following_count' ) ): 
									$count = wpuf_get_following_count( $user->ID )
									?>
										<div class="follower-count text-secondary small">
											<?php printf( _n( '%s follower', '%s followers', $count, 'streamtube-core' ), number_format_i18n( $count ) ); ?>
										</div>
									<?php endif;?>									

								<?php endif;?>
							</div>
						</div>
					</li>
					<?php

				endforeach;

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

		global $wp_roles;

		$instance = wp_parse_args( $instance, array(
			'title'					=>	'',
			'number'				=>	5,
			'role__in'				=>	array(),
			'show_count'			=>	'video',
			'has_published_posts'	=>	'video',
			'orderby'				=>	'post_count',
			'order'					=>	'DESC'
		) );

		if( is_string( $instance['role__in'] ) ){
			$instance['role__in'] = array_map( 'trim' , explode( ',' , $instance['role__in'] ) );
		}

		if( is_string( $instance['has_published_posts'] ) ){
			$instance['has_published_posts'] = array_map( 'trim' , explode( ',' , $instance['has_published_posts'] ) );
		}		
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
				esc_attr( $this->get_field_id( 'has_published_posts' ) ),
				esc_html__( 'Has Published Post Types', 'streamtube-core')
			);?>
			
			<?php printf(
				'<select multiple="multiple" class="widefat select-select2" id="%s" name="%s[]">',
				esc_attr( $this->get_field_id( 'has_published_posts' ) ),
				esc_attr( $this->get_field_name( 'has_published_posts' ) )
			);?>

				<?php
				foreach ( get_post_types() as $key => $value) {
					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						in_array( $key, $instance['has_published_posts'] ) ? 'selected' : '',
						esc_html( $value )
					);
				}
				?>
			</select>
		</div>		

		<div class="field-group" style="max-height: 300px;overflow: scroll;">
			<?php printf(
				'<label>%s</label>',
				esc_html__( 'Roles', 'streamtube-core')
			);?>

			<?php foreach ( $wp_roles->roles as $role => $value ): ?>
				<div class="field-control">
					<label>
					<?php printf(
						'<input type="checkbox" class="widefat" name="%s[]" value="%s" %s/> %s',
						esc_attr( $this->get_field_name( 'role__in' ) ),
						esc_attr( $role ),
						in_array( $role , $instance['role__in'] ) ? 'checked' : '',
						$value['name']
					);?>
					</label>
				</div>
			<?php endforeach ?>		
		
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'show_count' ) ),
				esc_html__( 'Show Count', 'streamtube-core')

			);?>

			<?php printf(
				'<select class="widefat" id="%s" name="%s"/>',
				esc_attr( $this->get_field_id( 'show_count' ) ),
				esc_attr( $this->get_field_name( 'show_count' ) )
			);?>

				<?php foreach( self::get_show_count_options() as $key => $value ):?>

					<?php printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						selected( $key, $instance['show_count'], false ),
						esc_html( $value )
					);?>

				<?php endforeach;?>

			</select><!-- end <?php echo $this->get_field_id( 'show_count' );?> -->
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'orderby' ) ),
				esc_html__( 'Order By', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'orderby' ) ),
				esc_attr( $this->get_field_name( 'orderby' ) ),
				esc_attr( $instance['orderby'] )
			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'order' ) ),
				esc_html__( 'Order', 'streamtube-core')

			);?>

			<?php printf(
				'<select class="widefat" id="%s" name="%s" />',
				esc_attr( $this->get_field_id( 'order' ) ),
				esc_attr( $this->get_field_name( 'order' ) )

			);?>

				<?php foreach( streamtube_core_get_order_options() as $order => $order_text ):?>

					<?php printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $order ),
						selected( $instance['order'], $order, false ),
						esc_html( $order_text )
					);?>

				<?php endforeach;?>


			</select><!-- end <?php echo $this->get_field_id( 'order' );?> -->
		</div>	

        <script type="text/javascript">
            jQuery(function () {
                jQuery( '.select-select2' ).select2();
            });
        </script>

		<?php
	}
}