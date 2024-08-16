<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="section-profile profile-nav-wrap bg-white">
	<nav id="profile-nav" class="profile-nav navbar navbar-expand-lg navbar-light border-bottom">
		<?php printf(
			'<div class="%s">',
			esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
		)?>

			<button class="btn border-0 navbar-toggler collapsed shadow-none btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUserDropdown" aria-controls="navbarUserDropdown" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'streamtube' );?>">
				<span class="btn__icon icon-menu"></span>
			</button>

			<div class="navbar-collapse collapse item-list-tabs no-ajax" id="navbarUserDropdown">
				<?php streamtube_core_the_user_profile_menu( apply_filters( 'streamtube/core/user/profile/main_menu', array(
					'menu_classes'	=>  sprintf(
						'user-navbar navbar-nav me-auto mb-2 mb-lg-0%s',
						get_option( 'user_profile_menu_fill', 'on' ) ? ' nav-fill nav-justified w-100' : ''
					),
					'user_id'		=>	get_queried_object_id(),
					'icon'			=>	wp_validate_boolean( get_option( 'user_profile_menu_icon', 'on' ) ),
					'location'		=>	'main',
					'icon_position'	=>	'top'
				) ) );?>
			</div>

			<div class="d-flex gap-3 align-items-center profile-menu__right position-absolute">
				<?php
				/**
				 *
				 * Fires in the right side of the user navigation
				 *
				 * @since  1.0.0
				 * 
				 */
				do_action( 'streamtube/core/user/navigation/right' );
				?>
			</div>
		</div>
	</nav>
</div>