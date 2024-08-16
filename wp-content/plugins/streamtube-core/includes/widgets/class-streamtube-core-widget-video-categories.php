<?php
/**
 * Define the Video Category widget functionality
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
class Streamtube_Core_Widget_Video_Category extends WP_Widget{

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::__construct()
	 */
	function __construct(){
	
		parent::__construct( 
			'categories-widget' ,
			esc_html__('[StreamTube] Video Categories', 'streamtube-core' ), 
			array( 
				'classname'		=>	'video-categories-widget widget_categories streamtube-widget', 
				'description'	=>	esc_html__('[StreamTube] Video Categories', 'streamtube-core')
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
	 * {@inheritDoc}
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, array(
			'title'			=>	'',
			'taxonomy'		=>	'categories',
			'show_count'	=>	'',
			'orderby'		=>	'name',
			'order'			=>	'ASC',
			'title_li'		=>	''
		) );

		extract( $instance );

		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

			if( $instance['title'] ){
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}

			$_args = compact( 'title_li','taxonomy', 'show_count', 'orderby', 'order' );

			?>
			<ul class="list-unstyled">

				<?php wp_list_categories( apply_filters( 'streamtube/core/widget/video_categories', $_args ) );?>

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
			'show_count'			=>	''
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
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s/>',
				esc_attr( $this->get_field_id( 'show_count' ) ),
				esc_attr( $this->get_field_name( 'show_count' ) ),
				checked( 'on', $instance['show_count'], false )

			);?>
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'show_count' ) ),
				esc_html__( 'Show post count', 'streamtube-core')

			);?>			
		</div>		
		<?php		
	}
}
