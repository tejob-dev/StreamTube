<?php

/**
 *
 * The template for displaying float sidebar
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if (! defined('ABSPATH')) {
	exit;
}

$is_collapsed = false;

if (isset($_COOKIE['is_float_collapsed'])) {
	$is_collapsed = ! wp_validate_boolean($_COOKIE['is_float_collapsed']);
} else {
	$is_collapsed = get_option('sidebar_float_collapse');
}

if (has_nav_menu('primary') || is_active_sidebar('secondary')): ?>

	<?php printf(
		'<div id="sidebar-secondary" class="sidebar sidebar-secondary %s border-end bg-white no-scroll d-flex flex-column">',
		$is_collapsed ? 'sidebar-collapse' : ''
	) ?>

	<?php
	do_action('streamtube/sidebar/secondary/inner/before');
	?>

	<button id="btn-menu-collap" class="btn-collapse btn btn-lg bg-white btn-white rounded-0 px-0 shadow-none">
		<span class="icon-left text-secondary"></span>
	</button>

	<?php

	do_action('streamtube/sidebar/secondary/inner/menu/before');

	if (has_nav_menu('primary')):
		echo '<div class="widget_main-menu">';
		wp_nav_menu(array(
			'theme_location'  	=> 'primary',
			'container'       	=> 'div',
			'container_class' 	=> 'main-nav float-nav',
			'container_id'   	=> 'main-nav',
			'menu_class'     	=> 'nav flex-column',
			'echo'				=> true,
			'fallback_cb'		=> 'WP_Bootstrap_Navwalker::fallback',
			'walker'        	=> new WP_Bootstrap_Navwalker(),
		));
		echo '</div>';
	endif;

	do_action('streamtube/sidebar/secondary/inner/menu/after');

	if( is_user_logged_in() ){
		echo '<li class="nav-item nav-item-divider"><hr class="dropdown-divider"></li>';
		echo '
			<div class="widget_main-menu">
				<div id="main-nav2" class="main-nav float-nav">
					<ul id="menu-real-menu2" class="nav flex-column">
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="Accueil" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-editor-quote" data-bs-toggle="tooltip" data-bs-placement="right" title="Votre chaine"></span><span class="menu-title menu-text">Votre chaine</span>
								</span>
							</a>
						</li>
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="Votre chaine" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-welcome-widgets-menus" data-bs-toggle="tooltip" data-bs-placement="right" title="Votre chaine"></span><span class="menu-title menu-text">Votre chaine</span>
								</span>
							</a>
						</li>
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="Abonnements" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-groups" data-bs-toggle="tooltip" data-bs-placement="right" title="Abonnements"></span><span class="menu-title menu-text">Abonnements</span>
								</span>
							</a>
						</li>
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="Historique" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-backup" data-bs-toggle="tooltip" data-bs-placement="right" title="Historique"></span><span class="menu-title menu-text">Historique</span>
								</span>
							</a>
						</li>
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="Playlist" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-editor-ul" data-bs-toggle="tooltip" data-bs-placement="right" title="Playlist"></span><span class="menu-title menu-text">Playlist</span>
								</span>
							</a>
						</li>
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="A Regarder" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-category" data-bs-toggle="tooltip" data-bs-placement="right" title="A Regarder"></span><span class="menu-title menu-text">A Regarder</span>
								</span>
							</a>
						</li>
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="Vidéos `Liker`" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-smiley" data-bs-toggle="tooltip" data-bs-placement="right" title="Vidéos `Liker`"></span><span class="menu-title menu-text">Vidéos `Liker`</span>
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			';

	}else{
		echo '<li class="nav-item nav-item-divider"><hr class="dropdown-divider"></li>';
		echo '
			<div class="widget_main-menu">
				<div id="main-nav2" class="main-nav float-nav">
					<ul id="menu-real-menu2" class="nav flex-column">
						<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="menu-item-33233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home page_item page-item-1760 menu-item-3323 nav-item nav-item-icon">
							<a title="Se connecter" href="http://streamtubea.test/" class="nav-link" aria-current="page">
								<span class="menu-icon-wrap"><span class="menu-icon dashicons dashicons-businessman" data-bs-toggle="tooltip" data-bs-placement="right" title="Se connecter"></span><span class="menu-title menu-text">Se connecter</span>
								</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			';
	}

	if (is_active_sidebar('secondary')) {
		echo '<div class="widget-group p-3 mt-3">';

		dynamic_sidebar('secondary');

		echo '</div>';
	}

	do_action('streamtube/sidebar/secondary/inner/after');

	?>
	</div>

<?php endif;
