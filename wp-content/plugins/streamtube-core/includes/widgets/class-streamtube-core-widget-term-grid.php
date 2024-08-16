<?php
/**
 * Define the custom Term Grid widget functionality
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
class Streamtube_Core_Widget_Term_Grid extends WP_Widget{

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::__construct()
	 */
	function __construct(){
	
		parent::__construct( 
			'term-grid-widget' ,
			esc_html__('[StreamTube] Taxonomy Term Grid', 'streamtube-core' ), 
			array( 
				'classname'		=>	'term-grid-widget streamtube-widget', 
				'description'	=>	esc_html__( 'Create a Taxonomy Term Grid widget', 'streamtube-core')
			),
			array(
				'width'	=>	'700px'
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
	 * get the pagination types
	 * 
	 * @return array
	 *
	 * @since  1.0.0
	 * 
	 */
	public static function get_pagination_types(){
		return array(
			''			=>	esc_html__( 'None', 'streamtube-core' ),
			'scroll'	=>	esc_html__( 'Load on scroll', 'streamtube-core' ),
			'click'		=>	esc_html__( 'Load on click', 'streamtube-core' )
		);
	}	

	/**
	 *
	 * AJAX load more terms
	 * 
	 */
	public static function load_more_terms(){

		check_ajax_referer( '_wpnonce' );

		if( ! isset( $_POST['data'] ) ){
			wp_send_json_error( new WP_Error(
				'no_data',
				esc_html__( 'Invalid Request', 'streamtube-core' )
			));
		}

		$instance = json_decode( wp_unslash( $_POST['data'] ), true );

		$instance['page'] = (int)$instance['page']+1;

		ob_start();

		the_widget( __CLASS__, array_merge( $instance, array(
			'title'			=>	'',
			'icon'			=>	'',			
			'wrapper'		=>	false
		) ), array(
			'before_widget'	=>	'',
			'after_widget'	=>	'',
			'before_title'	=>	'',
			'after_title'	=>	''
		) );

		$output = trim( ob_get_clean() );

		$instance = json_encode( $instance );

		wp_send_json_success( compact( 'instance', 'output' ) );
	}

	/**
	 *
	 * Get array of layout
	 * 
	 * @return array
	 */
    public static function get_layouts(){
    	return array(
    		'default'	=>	esc_html__( 'Default', 'streamtube-core' ),
    		'playlist'	=>	esc_html__( 'Playlist', 'streamtube-core' )
    	);
    }	

	/**
	 *
	 * Get array of taxonomies
	 * 
	 * @return array
	 */
    public static function get_taxonomies(){
        $options = array();

        $taxonomies = array(
        	'category'			=>	esc_html__( 'Blog Category', 'streamtube-core' ),
        	'categories'		=>	esc_html__( 'Video Category', 'streamtube-core' ),
        	'video_collection'	=>	esc_html__( 'Video Collection', 'streamtube-core' ),
        	'video_tag'			=>	esc_html__( 'Video Tag', 'streamtube-core' ),
        	'hand_pick'			=>	esc_html__( 'Video Hand Pick', 'streamtube-core' ),
        	'product_cat'		=>	esc_html__( 'Product Category', 'streamtube-core' )
        );

        /**
         * @since 2.2.1
         */
        $taxonomies = apply_filters( 'streamtube/core/term_grid/taxonomies', $taxonomies );

        foreach ( $taxonomies as $key => $value ) {
        	if( function_exists( 'taxonomy_exists' ) && taxonomy_exists( $key ) ){
        		$options[ $key ] = $value;
        	}
        }

        return $options;
    }  	

	/**
	 *
	 * Query terms
	 * 
	 * @param  array $term_args
	 * 
	 */
	public function get_terms( $term_args ){
		return new WP_Term_Query( $term_args );
	}

	/**
	 *
	 * Get total terms
	 * 
	 * @param  array $term_args
	 * 
	 */
	public function get_total_terms( $term_args ){

		$term_args = array_merge( $term_args, array(
			'page'		=>	1,
			'offset'	=>	0,
			'number'	=>	0,
			'count'		=>	true
		) );

		$term_query = $this->get_terms( $term_args );

		return $term_query->terms ? count( $term_query->terms ) : 0;
	}

	/**
	 *
	 * Get max pages
	 * 
	 * @param  array $term_args
	 * @return int
	 * 
	 */
	public function get_max_pages( $term_args ){
		if( (int)$term_args['number'] > 0 ){
			return ceil( $this->get_total_terms( $term_args )/(int)$term_args['number'] );	
		}
		return 0;
	}

	/**
	 *
	 * Get image ratio
	 * 
	 */
	public static function get_image_ratio(){
		return array_merge( array(
			'default'	=> esc_html__( 'Default', 'streamtube-core' )
		), streamtube_core_get_ratio_options() );
	}

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {

		$output = '';

		$instance = wp_parse_args( $instance, array(
			'id'					=>	'',
			'title'					=>	'',
			'icon'					=>	'',
			'layout'				=>	'default',
			'play_all'				=>	'yes',
			'term_author'			=>	'',
			'term_status'			=>	'',
			'taxonomy'				=>	'categories',
			'public_only'			=>	'yes',
			'searchable'			=>	'',
			'exclude_builtin'		=>	'yes',
			'user_id'				=>	'',
			'current_logged_in'		=>	'',
			'current_author'		=>	'',
			'child_of'				=>	'',
			'parent'				=>	'',
			'include'				=>	'',
			'exclude'				=>	'',
			'exclude_tree'			=>	'',
			'childless'				=>	'',			
			'search'				=>	'',
			'hide_empty'			=>	false,
			'orderby'				=>	'count',
			'order'					=>	'DESC',
			'count'					=>	'',
			'overlay'				=>	'',
			'number'				=>	get_option( 'posts_per_page' ),
			'thumbnail_size'		=>	get_option( 'thumbnail_size', 'streamtube-image-medium' ),
			'thumbnail_ratio'		=>	get_option( 'thumbnail_ratio', '16x9' ),
			'offset'				=>	0,
			'hide_empty_thumbnail'	=>	'',
			'meta_query'			=>	array(),
			'hierarchical'			=>	false,
			'margin_bottom'			=>	4,
			'col_xxl'				=>	3,
			'col_xl'				=>	3,
			'col_lg'				=>	3,
			'col_md'				=>	2,
			'col_sm'				=>	2,
			'col'					=>	1,
			'slide'					=>	'',
			'slide_rows'			=>	'1',
			'slide_dots'			=>	'',
			'slide_arrows'			=>	'',
			'slide_center_mode'		=>	'',
			'slide_infinite'		=>	'',
			'slide_speed'			=>	'2000',
			'slide_autoplay'		=>	'',
			'slide_autoplaySpeed'	=>	'2000',
			'is_elementor'			=>	'',
			'template'				=>	'',
			'page'					=>	1,
			'pagination'			=>	'',
			'wrapper'				=>	true,
			'hide_if_empty'			=>	'',
			'empty_message'			=>	''
		) );

		if( is_string( $instance['taxonomy'] ) ){
			$instance['taxonomy'] = array_map( 'trim' , explode(',', $instance['taxonomy'] ));
		}

		if( in_array( $instance['thumbnail_ratio'] , array( '', 'default' ) ) ){
			$instance['thumbnail_ratio'] = get_option( 'thumbnail_ratio', '16x9' );
		}

		/**
		 *
		 * Filter the instance
		 * 
		 * @param  array $instance
		 *
		 * @since  1.0.0
		 * 
		 */
		$instance = apply_filters( 'streamtube/core/widget/term_grid/pre_instance', $instance );			

		/**
		 *
		 * Assign current widget ID into instance
		 * 
		 */
		if( $this->id ){
			$instance['id'] = $this->id;
		}

		if( $this->id_base ){
			$instance['id_base'] = $this->id_base;
		}

		$instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		if( $instance['pagination'] ){
			if( ! wp_doing_ajax() ){
				$instance['page'] = max( 1, isset( $_GET['cpage'] ) ? (int)$_GET['cpage'] : 1 );
			}
		}

		if( empty( $instance['layout'] ) ){
			$instance['layout'] = 'default';
		}

		switch ( $instance['layout'] ) {
			case 'playlist':
				$instance['template'] = 'taxonomy-playlist';
			break;
			
			default:
				$instance['template'] = 'taxonomy';
			break;
		}

		$instance['number'] = (int)$instance['number'];

		if( ! $instance['number'] || $instance['number'] == -1 ){
			$instance['number'] = get_option( 'posts_per_page' );
		}

		if( $instance['hide_empty_thumbnail'] ){
			$instance['meta_query'][] = array(
				'key'		=>	'thumbnail_id',
				'compare'	=>	'EXISTS'
			);
		}

		if( is_array( $instance['taxonomy'] ) && in_array( 'video_collection', $instance['taxonomy'] ) ){

			if( $instance['public_only'] ){
				$instance['meta_query'][] = array(
					'key'		=>	'status',
					'compare'	=>	'=',
					'value'		=>	'public'
				);
			}

			if( $instance['exclude_builtin'] ){
				$instance['meta_query'][] = array(
					'key'		=>	'type',
					'value'		=>	'collection',
					'compare'	=>	'='
				);
			}

			if( $instance['user_id'] ){
				$user_id = array_map( 'trim', explode(',', $instance['user_id'] ));
				if( $user_id ){
					$instance['meta_query'][] = array(
						'key'		=>	'user_id',
						'compare'	=>	'IN',
						'value'		=>	$user_id
					);
				}
			}

			if( $instance['searchable'] ){
				$instance['meta_query'][] = array(
					'key'		=>	'searchable',
					'compare'	=>	'EXISTS',
					'value'		=>	'on'
				);
			}			

			if( $instance['current_logged_in'] && is_user_logged_in() ){
				$instance['meta_query'][] = array(
					'key'		=>	'user_id',
					'compare'	=>	'IN',
					'value'		=>	get_current_user_id()
				);
			}

			if( $instance['current_author'] ){

				if( ! wp_doing_ajax() ){
					if( is_singular() ){
						global $post;
						$instance['current_author'] = $post->post_author;
					}

					if( is_author() ){
						$instance['current_author'] = get_queried_object_id();
					}
				}

				$instance['meta_query'][] = array(
					'key'		=>	'user_id',
					'compare'	=>	'IN',
					'value'		=>	$instance['current_author']
				);
			}
		}

		if( is_array( $instance['meta_query'] ) && count( $instance['meta_query'] ) > 0 ){
			$instance['meta_query']['relation'] = 'AND';
		}		

		$instance['offset'] = ((int)$instance['page'] - 1) * (int)$instance['number'];

		if( absint( $instance['col_xxl'] ) == 1 ){
			$instance['col_xl'] = $instance['col_lg'] = $instance['col_md'] = $instance['col_sm'] = $instance['col'] = 1;
		}

		$instance['col_xxl'] 	= absint( $instance['col_xxl'] ) 	== 0 ? 1 : absint( $instance['col_xxl'] );
		$instance['col_xl'] 	= absint( $instance['col_xl'] ) 	== 0 ? 1 : absint( $instance['col_xl'] );
		$instance['col_lg'] 	= absint( $instance['col_lg'] ) 	== 0 ? 1 : absint( $instance['col_lg'] );
		$instance['col_md'] 	= absint( $instance['col_md'] ) 	== 0 ? 1 : absint( $instance['col_md'] );
		$instance['col_sm'] 	= absint( $instance['col_sm'] ) 	== 0 ? 1 : absint( $instance['col_sm'] );
		$instance['col'] 		= absint( $instance['col'] ) 		== 0 ? 1 : absint( $instance['col'] );

		$instance = apply_filters( 'streamtube/core/widget/term_grid/instance', $instance );

		extract( $instance );

		$term_args = compact( 
			'taxonomy', 
			'child_of',
			'parent',
			'include',
			'exclude',
			'exclude_tree',
			'childless',
			'search',
			'hide_empty', 
			'orderby', 
			'order', 
			'meta_query', 
			'number', 
			'offset',
			'hierarchical'
		);

		/**
		 * @since 2.2.1
		 */
		$term_args = apply_filters( 'streamtube/core/widget/term_grid/term_args', $term_args, $instance );

		$term_query = $this->get_terms( $term_args );

		if( ! $search && $hide_if_empty && ! $term_query->terms ){
			return;
		}

		echo $args['before_widget'];

			if( ! empty( $title ) ){

				if( $icon ){
					$title = sprintf(
						'<span class="title-icon %s"></span>',
						esc_attr( $icon )
					) . $title;
				}

				echo $args['before_title'] . $title . $args['after_title'];
			}		

		if( $term_query->terms ){

			$wrap_classes = array(
				'term-grid',
				'term-layout-' . $instance['layout']
			);

			if( $overlay ){
				$wrap_classes[] = 'term-layout-overlay';
			}

			if( is_string( $taxonomy )){
				$wrap_classes[] = 'term-' . $taxonomy;
			};

			if( is_array( $taxonomy )){
				$wrap_classes[] = 'term-' . join( '-', $taxonomy );
			};			

			$row_classes = array( 'row' );

			$row_classes[] = 'row-cols-' 		. $col;
			$row_classes[] = 'row-cols-sm-' 	. $col_sm;
			$row_classes[] = 'row-cols-md-' 	. $col_md;
			$row_classes[] = 'row-cols-lg-' 	. $col_lg;
			$row_classes[] = 'row-cols-xl-' 	. $col_xl;
			$row_classes[] = 'row-cols-xxl-' 	. $col_xxl;

			if( $slide ){
				$slick = array(
					'slidesToShow'		=>	absint( $col_xxl ),
					'slidesToScroll'	=>	absint( $col_xxl ),
					'responsive'		=>	array(
						array(
							'breakpoint'	=>	1200,
							'settings'		=>	array(
								'slidesToShow'		=>	absint( $col_xl ),
								'slidesToScroll'	=>	absint( $col_xl )
							)
						),
						array(
							'breakpoint'	=>	992,
							'settings'		=>	array(
								'slidesToShow'		=>	absint( $col_lg ),
								'slidesToScroll'	=>	absint( $col_lg )
							)
						),
						array(
							'breakpoint'	=>	768,
							'settings'		=>	array(
								'slidesToShow'		=>	absint( $col_md ),
								'slidesToScroll'	=>	absint( $col_md ),
								'dots'				=>	false,
								'arrows'			=>	true,
								'centerPadding'		=>	'0'
							)
						),
						array(
							'breakpoint'	=>	576,
							'settings'		=>	array(
								'slidesToShow'		=>	absint( $col_sm ),
								'slidesToScroll'	=>	absint( $col_sm ),
								'dots'				=>	false,
								'arrows'			=>	true,
								'centerMode'		=>	false,
								'centerPadding'		=>	'0'								
							)
						),
						array(
							'breakpoint'	=>	500,
							'settings'		=>	array(
								'slidesToShow'		=>	1,
								'slidesToScroll'	=>	1,
								'dots'				=>	false,
								'arrows'			=>	true,
								'centerMode'		=>	false,
								'centerPadding'		=>	'0'
							)
						)
					),
					'arrows'			=>	wp_validate_boolean( $slide_arrows ),
					'dots'				=>	wp_validate_boolean( $slide_dots ),
					'rows'				=>	absint( $slide_rows ),
					'infinite'			=>	wp_validate_boolean( $slide_infinite ),
					'centerMode'		=>	wp_validate_boolean( $slide_center_mode ),
					'speed'				=>	absint( $slide_speed ),
					'autoplay'			=>	wp_validate_boolean( $slide_autoplay ),
					'autoplaySpeed'		=>	absint( $slide_autoplaySpeed ),
					'rtl'				=>	is_rtl() ? true : false
				);

				if( $slick['centerMode'] ){
					$slick['centerPadding'] = '100px';
				}				
			}

			if( $wrapper ):
				printf(
					'<div class="%s">',
					esc_attr( join( ' ', $wrap_classes ) )
				);
			endif;

				printf(
					'<div class="%s" %s>',
					$slide ? 'js-slick post-grid-slick' : join( ' ', $row_classes ),
					$slide ? 'data-slick="'. esc_attr( json_encode( $slick ) ) .'"' : ''
				);

					foreach( $term_query->terms as $term ) :

						$GLOBALS['term'] = $term;

						$GLOBALS['term_grid_settings'] = $instance;

						printf(
							'<div id="term-%s" class="term-item term-%s taxonomy-%s mb-%s">',
							$term->term_id,
							esc_attr( sanitize_html_class( $term->slug ) ),
							esc_attr( sanitize_html_class( $term->taxonomy ) ),
							esc_attr( $margin_bottom )
						);

							get_template_part( 'template-parts/content/content', $instance['template'] );

						echo '</div><!--.term-item-->';

						unset( $GLOBALS['term'] );
						unset( $GLOBALS['term_grid_settings'] );

					endforeach;

				if( $wrapper ):
					echo '</div><!--.term-grid-->';
				endif;

				if( $instance['slide'] && ! wp_doing_ajax() ){
					streamtube_core_preplaceholder( $wrap_classes, $row_classes, array_merge( $instance, array(
						'layout'	=>	'grid'
					) ) );
				}

				if( $pagination && ! wp_doing_ajax() && $this->get_max_pages( $term_args ) > 1 ) :
					if( in_array( $pagination , array( 'click', 'scroll' )) && $this->get_total_terms( $term_args ) >= $number ):
					?>
					<div class="d-flex justify-content-center navigation border-bottom mb-5 position-relative">
						<?php printf(
							'<button type="button" class="btn border text-secondary ajax-elm jsappear bg-light shadow-none btn-load-more-terms load-on-%s" data-params="%s" data-action="%s">',
							$pagination,
							esc_attr( json_encode( $instance ) ),
							'load_more_tax_terms'
						);?>

							<?php if( $pagination == 'click' ):?>

								<span class="load-icon icon-angle-down position-absolute top-50 start-50 translate-middle"></span>

							<?php else:?>
								<span class="spinner spinner-border text-info" role="status">
									<span class="visually-hidden">
										<?php esc_html_e( 'Loading...', 'streamtube-core' ); ?>
									</span>
								</span>
							<?php endif;?>
						</button>
					</div>
					<?php		
					else:
						$paginate = paginate_links( array(
							'format' 	=> '?cpage=%#%',
							'current' 	=> $instance['page'],
							'total' 	=> $this->get_max_pages( $term_args ),
							'type'		=> 'list'
						) );

						if( ! empty( $paginate ) ){
							printf(
								'<div class="navigation-wrap"><nav class="navigation pagination">%s</nav></div>',
								$paginate
							);
						}
					endif;
				endif;

			echo '</div><!--.row-->';

		}else{

			if( ! $empty_message ){
				$empty_message = esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'streamtube-core' );
			}
							
			if( ! empty( $search ) && ! wp_doing_ajax() ){

				printf(
					'<div class="not-found p-3 text-center text-muted fw-normal h6"><p class="text-secondary">%s</p></div>',
					$empty_message
				);
			}
		}

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
	 *
	 * The Appearance tab
	 * 
	 * @param  array $instance
	 * @return html
	 *
	 * @since  1.0.0
	 * 
	 */
	private function tab_appearance( $instance ){

		$instance = wp_parse_args( $instance, array(
			'title'					=>	'',
			'number'				=>	get_option( 'posts_per_page' ),
			'icon'					=>	'',
			'thumbnail_size'		=>	get_option( 'thumbnail_size', 'streamtube-image-medium' ),
			'thumbnail_ratio'		=>	get_option( 'thumbnail_ratio', '16x9' ),			
			'layout'				=>	'default',
			'play_all'				=>	'yes',
			'term_author'			=>	'',
			'term_status'			=>	'',
			'hide_if_empty'			=>	'',
			'count'					=>	'',
			'overlay'				=>	'',
			'pagination'			=>	''
		) );

		ob_start();

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
				esc_attr( $this->get_field_id( 'icon' ) ),
				esc_html__( 'Icon', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'icon' ) ),
				esc_attr( $this->get_field_name( 'icon' ) ),
				esc_attr( $instance['icon'] )
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
				esc_attr( $this->get_field_id( 'thumbnail_size' ) ),
				esc_html__( 'Thumbnail Image Size', 'streamtube-core')

			);?>
			
			<?php printf(
				'<select class="widefat" id="%s" name="%s">',
				esc_attr( $this->get_field_id( 'thumbnail_size' ) ),
				esc_attr( $this->get_field_name( 'thumbnail_size' ) )
			);?>

				<?php foreach ( streamtube_core_get_thumbnail_sizes() as $key => $value ): ?>
					
					<?php printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						selected( $key, $instance['thumbnail_size'], false ),
						esc_html( $value )
					);?>	

				<?php endforeach ?>

			</select>
		</div>	

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'thumbnail_ratio' ) ),
				esc_html__( 'Thumbnail Ratio', 'streamtube-core')

			);?>
			
			<?php printf(
				'<select class="widefat" id="%s" name="%s">',
				esc_attr( $this->get_field_id( 'thumbnail_ratio' ) ),
				esc_attr( $this->get_field_name( 'thumbnail_ratio' ) )
			);?>

				<?php foreach ( self::get_image_ratio() as $key => $value): ?>
					
					<?php printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $key ),
						selected( $key, $instance['thumbnail_ratio'], false ),
						esc_html( $value )
					);?>

				<?php endforeach ?>

			</select>
		</div>			

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'pagination' ) ),
				esc_html__( 'Pagination', 'streamtube-core')

			);?>

			<?php printf(
				'<select class="widefat" id="%s" name="%s"/>',
				esc_attr( $this->get_field_id( 'pagination' ) ),
				esc_attr( $this->get_field_name( 'pagination' ) )

			);?>

			<?php
			$options = self::get_pagination_types();

			foreach ( $options as $type => $text ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $type ),
					selected( $type, $instance['pagination'] ),
					esc_html( $text )
				);
			}

			?>

			</select>
		
		</div>			

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'layout' ) ),
				esc_html__( 'Layout', 'streamtube-core')

			);?>

			<?php printf(
				'<select class="widefat" id="%s" name="%s" />',
				esc_attr( $this->get_field_id( 'layout' ) ),
				esc_attr( $this->get_field_name( 'layout' ) )

			);?>

				<?php foreach( self::get_layouts() as $layout => $text ):?>

					<?php printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $layout ),
						selected( $instance['layout'], $layout, false ),
						esc_html( $text )
					);?>

				<?php endforeach;?>

			</select><!-- end <?php echo $this->get_field_id( 'layout' );?> -->
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'overlay' ) ),
				esc_attr( $this->get_field_name( 'overlay' ) ),
				checked( 'on', $instance['overlay'], false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'overlay' ) ),
				esc_html__( 'Overlay', 'streamtube-core')
			);?>
		</div>		

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'play_all' ) ),
				esc_attr( $this->get_field_name( 'play_all' ) ),
				checked( 'on', $instance['play_all'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'play_all' ) ),
				esc_html__( 'Play All', 'streamtube-core')
			);?>
			<span class="field-help">
				<?php esc_html_e( 'Enable Play All, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>			
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'term_author' ) ),
				esc_attr( $this->get_field_name( 'term_author' ) ),
				checked( 'on', $instance['term_author'], false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'term_author' ) ),
				esc_html__( 'Author', 'streamtube-core')
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Show collection author, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>
			
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'term_status' ) ),
				esc_attr( $this->get_field_name( 'term_status' ) ),
				checked( 'on', $instance['term_status'], false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'term_status' ) ),
				esc_html__( 'Status', 'streamtube-core')
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Show collection status, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>
			
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'count' ) ),
				esc_attr( $this->get_field_name( 'count' ) ),
				checked( 'on', $instance['count'], false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'count' ) ),
				esc_html__( 'Show post count', 'streamtube-core')
			);?>
		</div>					

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'hide_if_empty' ) ),
				esc_attr( $this->get_field_name( 'hide_if_empty' ) ),
				checked( 'on', $instance['hide_if_empty'], false )
			);?>
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'hide_if_empty' ) ),
				esc_html__( 'Hide widget if no terms found', 'streamtube-core')
			);?>			
		</div>
	
		<?php

		return ob_get_clean();
	}	

	/**
	 *
	 * The layout tab
	 * 
	 * @param  array $instance
	 * @return html
	 *
	 * @since  1.0.0
	 * 
	 */
	private function tab_layout( $instance ){

		$instance = wp_parse_args( $instance, array(	
			'margin_bottom'			=>	4,
			'col_xxl'				=>	4,
			'col_xl'				=>	4,
			'col_lg'				=>	2,
			'col_md'				=>	2,
			'col_sm'				=>	1,
			'col'					=>	1
		) );

		ob_start();

		?>		

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'margin_bottom' ) ),
				esc_html__( 'Margin Bottom', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'margin_bottom' ) ),
				esc_attr( $this->get_field_name( 'margin_bottom' ) ),
				esc_attr( $instance['margin_bottom'] )

			);?>
		</div>	

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'col_xxl' ) ),
				esc_html__( 'Columns - Extra extra large ≥1400px', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'col_xxl' ) ),
				esc_attr( $this->get_field_name( 'col_xxl' ) ),
				esc_attr( $instance['col_xxl'] )

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'col_xl' ) ),
				esc_html__( 'Columns - Extra large ≥1200px', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'col_xl' ) ),
				esc_attr( $this->get_field_name( 'col_xl' ) ),
				esc_attr( $instance['col_xl'] )

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'col_lg' ) ),
				esc_html__( 'Columns - Large ≥992px', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'col_lg' ) ),
				esc_attr( $this->get_field_name( 'col_lg' ) ),
				esc_attr( $instance['col_lg'] )

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'col_md' ) ),
				esc_html__( 'Columns - Medium ≥768px', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'col_md' ) ),
				esc_attr( $this->get_field_name( 'col_md' ) ),
				esc_attr( $instance['col_md'] )

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'col_sm' ) ),
				esc_html__( 'Columns - Small ≥576px', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'col_sm' ) ),
				esc_attr( $this->get_field_name( 'col_sm' ) ),
				esc_attr( $instance['col_sm'] )

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'col' ) ),
				esc_html__( 'Columns - Extra small <576px', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'col' ) ),
				esc_attr( $this->get_field_name( 'col' ) ),
				esc_attr( $instance['col'] )

			);?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 *
	 * The slide tab
	 * 
	 * @param  array $instance
	 *
	 * @since 1.0.0
	 * 
	 */
	private function tab_slide( $instance ){
		$instance = wp_parse_args( $instance, array(
			'slide'					=>	'',
			'slide_rows'			=>	'1',
			'slide_arrows'			=>	'',
			'slide_center_mode'		=>	'',
			'slide_infinite'		=>	'',
			'slide_speed'			=>	'2000',
			'slide_autoplay'		=>	'',
			'slide_autoplaySpeed'	=>	'2000'
		) );

		ob_start();
		?>
		<div class="field-control">
			
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'slide' ) ),
				esc_attr( $this->get_field_name( 'slide' ) ),
				checked( 'on', $instance['slide'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide' ) ),
				esc_html__( 'Enable sliding', 'streamtube-core')

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide_rows' ) ),
				esc_html__( 'Rows', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'slide_rows' ) ),
				esc_attr( $this->get_field_name( 'slide_rows' ) ),
				esc_attr( $instance['slide_rows'] )

			);?>
		</div>

		<div class="field-control">
			
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'slide_arrows' ) ),
				esc_attr( $this->get_field_name( 'slide_arrows' ) ),
				checked( 'on', $instance['slide_arrows'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide_arrows' ) ),
				esc_html__( 'Show Prev/Next Arrows', 'streamtube-core')

			);?>
		</div>

		<div class="field-control">
			
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'slide_center_mode' ) ),
				esc_attr( $this->get_field_name( 'slide_center_mode' ) ),
				checked( 'on', $instance['slide_center_mode'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide_center_mode' ) ),
				esc_html__( 'Center mode', 'streamtube-core')

			);?>
		</div>

		<div class="field-control">
			
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'slide_infinite' ) ),
				esc_attr( $this->get_field_name( 'slide_infinite' ) ),
				checked( 'on', $instance['slide_infinite'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide_infinite' ) ),
				esc_html__( 'Infinite Loop Sliding', 'streamtube-core')

			);?>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide_speed' ) ),
				esc_html__( 'Speed', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'slide_speed' ) ),
				esc_attr( $this->get_field_name( 'slide_speed' ) ),
				esc_attr( $instance['slide_speed'] )
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Slide Animation Speed', 'streamtube-core' ); ?>
			</span>
		</div>		

		<div class="field-control">
			
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'slide_autoplay' ) ),
				esc_attr( $this->get_field_name( 'slide_autoplay' ) ),
				checked( 'on', $instance['slide_autoplay'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide_autoplay' ) ),
				esc_html__( 'Enables Autoplay', 'streamtube-core')

			);?>
		</div>
		
		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'slide_autoplaySpeed' ) ),
				esc_html__( 'Autoplay Speed', 'streamtube-core')

			);?>
			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'slide_autoplaySpeed' ) ),
				esc_attr( $this->get_field_name( 'slide_autoplaySpeed' ) ),
				esc_attr( $instance['slide_autoplaySpeed'] )
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Autoplay Speed in milliseconds', 'streamtube-core' ); ?>
			</span>
		</div>	
		<?php
		return ob_get_clean();
	}

	/**
	 *
	 * The Data source tab
	 * 
	 * @param  array $instance
	 * @return html
	 *
	 * @since  1.0.0
	 * 
	 */
	private function tab_data_source( $instance ){

		$instance = wp_parse_args( $instance, array(
			'hide_empty'			=>	'',
			'hide_empty_thumbnail'	=>	'',			
			'taxonomy'				=>	'categories',
			'public_only'			=>	'',
			'searchable'			=>	'',
			'current_logged_in'		=>	'',
			'current_author'		=>	'',
			'user_id'				=>	'',
			'child_of'				=>	'',
			'parent'				=>	'',
			'include'				=>	'',
			'exclude'				=>	'',
			'exclude_tree'			=>	'',
			'childless'				=>	''
		) );

		if( is_string( $instance['taxonomy'] ) ){
			$instance['taxonomy'] = array_map( 'trim' , explode(',', $instance['taxonomy'] ));
		}

		ob_start();

		?>

        <div class="field-group" style="max-height: 300px;overflow: scroll;">
            <?php printf(
                '<label>%s</label>',
                esc_html__( 'Taxonomies', 'streamtube-core')
            );?>
            <?php foreach( self::get_taxonomies() as $tax => $label ): ?>
                <div class="field-control">
                    <label>
                    <?php printf(
                        '<input type="checkbox" class="widefat" name="%s[]" value="%s" %s/> %s',
                        esc_attr( $this->get_field_name( 'taxonomy' ) ),
                        esc_attr( $tax ),
                        in_array( $tax , $instance['taxonomy'] ) ? 'checked' : '',
                        esc_html( $label )
                    );?>
                    </label>
                </div>
            <?php endforeach ?> 
        </div>		

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'hide_empty' ) ),
				esc_attr( $this->get_field_name( 'hide_empty' ) ),
				checked( 'on', $instance['hide_empty'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'hide_empty' ) ),
				esc_html__( 'Hide Empty Terms', 'streamtube-core')

			);?>
			
		</div>		

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'hide_empty_thumbnail' ) ),
				esc_attr( $this->get_field_name( 'hide_empty_thumbnail' ) ),
				checked( 'on', $instance['hide_empty_thumbnail'], false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'hide_empty_thumbnail' ) ),
				esc_html__( 'Hide Empty Thumbnail Terms', 'streamtube-core')
			);?>
			
		</div>        

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'public_only' ) ),
				esc_attr( $this->get_field_name( 'public_only' ) ),
				checked( 'on', $instance['public_only'], false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'public_only' ) ),
				esc_html__( 'Public Collections', 'streamtube-core')
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Only retrieve public collections, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>
			
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'searchable' ) ),
				esc_attr( $this->get_field_name( 'searchable' ) ),
				checked( 'on', $instance['searchable'], false )
			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'searchable' ) ),
				esc_html__( 'Searchable', 'streamtube-core')
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Only retrieve searchable collections, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>
			
		</div>		

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'current_logged_in' ) ),
				esc_attr( $this->get_field_name( 'current_logged_in' ) ),
				checked( 'on', $instance['current_logged_in'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'current_logged_in' ) ),
				esc_html__( 'Current Logged In', 'streamtube-core')
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Retrieve collections of current logged in user, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>
			
		</div>

		<div class="field-control">
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'current_author' ) ),
				esc_attr( $this->get_field_name( 'current_author' ) ),
				checked( 'on', $instance['current_author'], false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'current_author' ) ),
				esc_html__( 'Current Author', 'streamtube-core')
			);?>

			<span class="field-help">
				<?php esc_html_e( 'Retrieve collections of current author, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>
			
		</div>		

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'user_id' ) ),
				esc_html__( 'User IDs', 'streamtube-core')
			);?>			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'user_id' ) ),
				esc_attr( $this->get_field_name( 'user_id' ) ),
				checked( 'on', $instance['user_id'], false )

			);?>
			<span class="field-help">
				<?php esc_html_e( 'User IDs to retrieve terms of, separated by comma, supports Collection Taxonomy only', 'streamtube-core' ); ?>
			</span>
			
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'child_of' ) ),
				esc_html__( 'Child Of', 'streamtube-core')
			);?>

			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'child_of' ) ),
				esc_attr( $this->get_field_name( 'child_of' ) ),
				$instance['child_of']
			);?>
			<span class="field-help">
				<?php esc_html_e( 'Term ID to retrieve child terms of.', 'streamtube-core' );?>
			</span>			
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'parent' ) ),
				esc_html__( 'Parent Term ID.', 'streamtube-core')

			);?>			
			<?php printf(
				'<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'parent' ) ),
				esc_attr( $this->get_field_name( 'parent' ) ),
				$instance['parent']
			);?>
			<span class="field-help">
				<?php esc_html_e( 'Parent term ID to retrieve direct-child terms of.', 'streamtube-core' );?>
			</span>				
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'include' ) ),
				esc_html__( 'Include.', 'streamtube-core')

			);?>

			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'include' ) ),
				esc_attr( $this->get_field_name( 'include' ) ),
				$instance['include']

			);?>

			<span class="field-help">
				<?php esc_html_e( 'Comma/space-separated string of term IDs to include.', 'streamtube-core' );?>
			</span>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'exclude' ) ),
				esc_html__( 'Exclude.', 'streamtube-core')

			);?>			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'exclude' ) ),
				esc_attr( $this->get_field_name( 'exclude' ) ),
				$instance['exclude']

			);?>

			<span class="field-help">
				<?php esc_html_e( 'Comma/space-separated string of term IDs to exclude.', 'streamtube-core' );?>
			</span>
		</div>

		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'exclude_tree' ) ),
				esc_html__( 'Exclude Tree.', 'streamtube-core')

			);?>			
			<?php printf(
				'<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
				esc_attr( $this->get_field_id( 'exclude_tree' ) ),
				esc_attr( $this->get_field_name( 'exclude_tree' ) ),
				$instance['exclude_tree']

			);?>

			<span class="field-help">
				<?php esc_html_e( 'Comma/space-separated string of term IDs to exclude along with all of their descendant terms.', 'streamtube-core' );?>
			</span>
		</div>

		<div class="field-control">
			
			<?php printf(
				'<input type="checkbox" class="widefat" id="%s" name="%s" %s />',
				esc_attr( $this->get_field_id( 'childless' ) ),
				esc_attr( $this->get_field_name( 'childless' ) ),
				checked( $instance['childless'], 'on', false )

			);?>

			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'childless' ) ),
				esc_html__( 'Childless', 'streamtube-core')

			);?>

			<span class="field-help">
				<?php esc_html_e( 'Limit results to terms that have no children.', 'streamtube-core' );?>
			</span>
		</div>
		<?php

		return ob_get_clean();
	}		

	/**
	 *
	 * The Order tab
	 * 
	 * @param  array $instance
	 * @return html
	 *
	 * @since  1.0.0
	 * 
	 */
	private function tab_order( $instance ){
		$instance = wp_parse_args( $instance, array(
			'orderby'	=>	'name',
			'order'		=>	'DESC'
		));

		ob_start();		

		?>
		<div class="field-control">
			<?php printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_id( 'orderby' ) ),
				esc_html__( 'Order by', 'streamtube-core')

			);?>

			<?php printf(
				'<select class="widefat" id="%s" name="%s" />',
				esc_attr( $this->get_field_id( 'orderby' ) ),
				esc_attr( $this->get_field_name( 'orderby' ) )

			);?>

				<?php foreach( streamtube_core_get_term_orderby_options() as $orderby => $orderby_text ):?>

					<?php printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $orderby ),
						selected( $instance['orderby'], $orderby, false ),
						esc_html( $orderby_text )
					);?>

				<?php endforeach;?>

			</select><!-- end <?php echo $this->get_field_id( 'orderby' );?> -->
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
		<?php

		return ob_get_clean();
	}	

	/**
	 *
	 * Get the tabs
	 * 
	 * @return array
	 *
	 * @since  1.0.0
	 * 
	 */
	private function tabs(){

		$tabs = array();

		$tabs['appearance'] = array(
			'title'		=>	esc_html__( 'Appearance', 'streamtube-core' ),
			'callback'	=>	array( $this , 'tab_appearance' )
		);

		$tabs['layout'] = array(
			'title'		=>	esc_html__( 'Layout', 'streamtube-core' ),
			'callback'	=>	array( $this , 'tab_layout' )
		);

		$tabs['slide'] = array(
			'title'		=>	esc_html__( 'Slide', 'streamtube-core' ),
			'callback'	=>	array( $this , 'tab_slide' )
		);		

		$tabs['data-source'] = array(
			'title'		=>	esc_html__( 'Data Source', 'streamtube-core' ),
			'callback'	=>	array( $this , 'tab_data_source' )
		);

		$tabs['order'] = array(
			'title'		=>	esc_html__( 'Order', 'streamtube-core' ),
			'callback'	=>	array( $this , 'tab_order' )
		);

		return $tabs;

	}	

	/**
	 * {@inheritDoc}
	 * @see WP_Widget::form()
	 */
	public function form( $instance ){

		$instance = wp_parse_args( $instance, array(
			'tab'	=>	'appearance'
		) );

		$tabs = $this->tabs();

		echo '<div class="streamtube-widget-content">';

			echo '<ul class="nav nav-tabs widget-tabs">';

				foreach ( $tabs as $tab => $value ):

					printf(
						'<li class="nav-item" role="presentation">
							<a class="nav-link %s" id="%2$s-tab" href="#%2$s">%3$s</a>
						</li>',
						$instance['tab'] == $tab ? 'active' : '',
						esc_attr( $tab ),
						esc_html( $value['title'] )
					);

				endforeach;

			echo '</ul>';


			echo '<div class="tab-content widget-tab-content">';

				foreach ( $tabs as $tab => $value ):

					printf(
						'<div class="tab-pane %s" id="%s">%s</div>',
						$instance['tab'] == $tab ? 'active' : '',
						esc_attr( $tab ),
						call_user_func( $value['callback'], $instance )

					);

				endforeach;

				printf(
					'<input class="current-tab" type="hidden" id="%s" name="%s" value="%s" />',
					esc_attr( $this->get_field_id( 'tab' ) ),
					esc_attr( $this->get_field_name( 'tab' ) ),
					esc_attr( $instance['tab'] )

				);

			echo '</div><!--.tab-content-->';

		echo '</div><!--.streamtube-widget-content-->';

	}
}