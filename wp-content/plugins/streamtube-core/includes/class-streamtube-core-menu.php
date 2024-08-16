<?php
/**
 * Menu
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Menu{

	protected $user_id 			=	0;

	protected $base_url			=	'';

	protected $current 			=	'';

	protected $menu_items 		=	array();

	protected $menu_classes		=	array( 'nav' );

	protected $item_classes		=	array( 'nav-link' );

	protected $icon 			=	false;

	protected $icon_position	=	'left';

	protected $location 		= 	'';

	public function __construct( $args = array() ){
		$args = wp_parse_args( $args, array(
			'user_id'		=>	0,
			'base_url'		=>	'',
			'current'		=>	'',
			'menu_items'	=>	array(),
			'menu_classes'	=>	array(),
			'item_classes'	=>	array(),
			'icon'			=>	false,
			'icon_position'	=>	'left',
			'location'		=>	''
		) );

		$this->location 			= $args['location'];

		$this->user_id 				= $args['user_id'];

		$this->base_url 			= $args['base_url'];

		$this->current 				= $args['current'];

		$this->menu_items 			= $args['menu_items'];

		$this->icon 				= $args['icon'];

		if( $args['icon_position'] ){
			$this->icon_position = $args['icon_position'];	
		}

		if( is_string( $args['menu_classes'] ) ){
			$args['menu_classes'] 	= explode( " " , $args['menu_classes'] );
		}

		$this->menu_classes 		= array_merge( $args['menu_classes'], $this->menu_classes );

		if( $this->icon === true ){
			$this->menu_classes[] = 'nav-has-icon';

			if( $this->icon_position ){
				$this->menu_classes[] = 'nav-icon-' . $this->icon_position;
			}
		}

		if( is_string( $args['item_classes'] ) ){
			$args['item_classes'] 	= explode( " " , $args['item_classes'] );
		}

		$this->item_classes 		= array_merge( $args['item_classes'], $this->item_classes );
	}

	protected function uasort( &$items ){
		uasort( $items, function( $item1, $item2 ){
			return $item1['priority'] <=> $item2['priority'];
		} );
	}

	protected function get_url( $endpoint = '', $parent = '' ){

		$url = $this->base_url;

		if( ! $endpoint ){
			return $url;
		}

		if( ! get_option( 'permalink_structure' ) ){
			if( ! $parent ){
				$url = add_query_arg( array(
					$endpoint 	=>	1
				), $url );
			}
			else{
				$url = add_query_arg( array(
					$parent 	=>	$endpoint
				), $url );				
			}
		}
		else{

			$path = $endpoint;

			if( $parent ){
				$path = $parent . '/' . $endpoint;	
			}

			$url = trailingslashit( $url ) . $path;
		}

		return $url;
	}

	public function the_menu(){

		$this->uasort( $this->menu_items );

		?>
		<ul class="<?php echo esc_attr( join( ' ', $this->menu_classes ) );?>">

			<?php foreach( $this->menu_items as $menu_id => $menu ):?>

				<?php

				$menu_li = '';

				$menu = wp_parse_args( $menu, array(
					'title'			=>	'',
					'url'			=>	'',
					'desc'			=>	'',
					'badge'			=>	'',
					'parent'		=>	'',
					'cap'			=>	'read',
					'private'		=>	false,
					'id'			=>	'',
					'icon'			=>	'',
					'icon_color'	=>	'',
					'priority'		=>	1
				) );

				if( ! $menu['id'] ){
					$menu['id'] = 'nav-' . $menu_id;
				}

				if( ! $menu['title'] ){
					continue;
				}

				$tooltip = $menu['desc'] ? $menu['desc'] : $menu['title'];

				if( is_callable( $menu['badge'] ) ){
					$menu['badge'] = call_user_func( $menu['badge'], $this->user_id );
				}

				if( ( 
						( is_string( $menu['cap'] ) && user_can( $this->user_id, $menu['cap'] ) ) ||
						( is_callable( $menu['cap'] ) && call_user_func( $menu['cap'], $this->user_id ) === true )
					) 
					&& ! is_object( $menu['url'] ) ):

					$is_visible = $menu['private'] === false || $this->location != 'main' ? true : false;

					if( apply_filters( 'streamtube/core/menu/is_visible', $is_visible , $menu, $this->menu_items ) === true ){

						$menu_li = sprintf(
							'<li class="nav-item%1$s nav-%2$s%3$s" id="%4$s" data-priority="%5$s">',
							$this->icon === true && $menu['icon'] ? ' nav-item-icon' : '',
							sanitize_html_class( $menu['id'] ),
							$this->current == $menu_id ? ' current selected' : '',
							esc_attr( $menu['id'] ),
							(int)$menu['priority']
						);

							$menu_li .= sprintf(
								'<a class="%s %s" aria-current="page" href="%s">',
								esc_attr( join( ' ', $this->item_classes ) ),
								$this->current == $menu_id ? 'active' : '',
								$menu['url'] ? esc_url( $menu['url'] ) : esc_url( $this->get_url( $menu_id, $menu['parent'] ) )
							);

							if( $this->icon === true && array_key_exists( 'icon' , $menu ) ){

								$icon_class = array( 'menu-icon' );

								$icon_class[] = $menu['icon'];

								if( is_string( $this->icon_position ) && $this->icon_position ){
									$icon_class[] = 'icon-position-' . $this->icon_position;
								}

								if( $this->icon_position === 'left' ){
									$icon_class[] = 'me-3';
								}
								if( $this->icon_position === 'right' ){
									$icon_class[] = 'ms-3';
								}
								if( $this->icon_position === 'top' ){
									$icon_class[] = "mb-2 mx-auto h6";
								}

								$menu_li .= sprintf(
									'<span data-bs-toggle="tooltip" data-bs-placement="%s" data-bs-title="%s" class="%s" style="%s"></span>',
									is_string( $this->icon_position ) ? $this->icon_position : 'right',
									esc_attr( wp_strip_all_tags( $tooltip, true ) ),
									esc_attr( join( ' ', $icon_class ) ),
									$menu['icon_color'] ? 'color:' . esc_attr( $menu['icon_color'] ) : ''
								);
							}

							$menu_li .= sprintf(
								' <span class="menu-text">%s %s</span>',
								$menu['title'],
								$menu['badge']
							);

							$menu_li .= '</a>';

						$menu_li .= '</li>';
					}

				echo $menu_li;

				endif;
				?>

			<?php endforeach;?>
		</ul>
		<?php

	}
}