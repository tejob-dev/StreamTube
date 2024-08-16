<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

?>
<div id="item-header" class="section-profile section-profile-header pt-3 m-0 bg-white">

		<?php printf(
			'<div class="%s">',
			esc_attr( join( ' ', streamtube_core_get_user_profile_photo_container_classes() )  )
		)?>

		<div class="profile-top">

			<div class="profile-header">

				<div class="profile-header__photo rounded">

					<?php
					/**
					 * 
					 * @since 1.0.0
					 * 
					 */
					do_action( 'streamtube/core/user/header/profile_photo/before', get_queried_object_id() );
					?>						

					<?php streamtube_core_get_user_photo( array(
						'user_id'   =>  get_queried_object_id(),
						'link'      =>  false,
					) )?>

					<?php
					/**
					 * 
					 * @since 1.0.0
					 * 
					 */
					do_action( 'streamtube/core/user/header/profile_photo/after', get_queried_object_id() );
					?>					

				</div>

				<div class="profile-header__avatar">

					<?php
					/**
					 *
					 * Fires before avatar
					 *
					 * @since 1.0.0
					 * 
					 */
					do_action( 'streamtube/core/user/header/avatar/before', get_queried_object_id() );
					?>					

					<?php
					streamtube_core_get_user_avatar( array(
						'user_id'       =>  get_queried_object_id(),
						'link'          =>  false,
						'wrap_size'     =>  'xxl'
					) );
					?>

					<?php
					/**
					 *
					 * Fires after avatar
					 *
					 * @since 1.0.0
					 * 
					 */
					do_action( 'streamtube/core/user/header/avatar/after', get_queried_object_id() );
					?>

				</div>

			</div>

			<?php
			/**
			 *
			 * @since 1.0.0
			 * 
			 */
			do_action( 'streamtube/core/user/header/display_name/before', get_queried_object_id() );
			?>			

			<?php streamtube_core_get_user_name( array(
				'user_id'   =>  get_queried_object_id(),
				'link'      =>  false,
				'before'    =>  '<div class="author-info"><h2 class="author-name">',
				'after'     =>  '</h2></div>'
			) );?>

			<?php
			/**
			 *
			 * @since 1.0.0
			 * 
			 */
			do_action( 'streamtube/core/user/header/display_name/after', get_queried_object_id() );
			?>

			<?php load_template( plugin_dir_path( __FILE__ ) . 'social-profiles.php', true );?>

		</div>

	</div>

</div>

