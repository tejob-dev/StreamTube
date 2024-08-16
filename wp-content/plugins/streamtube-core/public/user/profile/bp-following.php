<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Filter heading
 *
 * @param $string $heading
 * 
 */
$heading = apply_filters( 'streamtube/core/user/profile/following', esc_html__( 'Following', 'streamtube-core' ));

?>
<section id="buddypress" class="buddypress-wrap section-profile profile-following py-4 pb-0 m-0">

    <?php printf(
        '<div class="%s">',
        esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
    )?>

	    <div class="widget-title-wrap d-flex">
	        
	        <?php if( $heading ): ?>

	            <h2 class="widget-title no-after">
	                <?php echo $heading;?>
	            </h2>

	        <?php endif;?>
	        <div class="item-list-tabs ms-auto" aria-label="<?php esc_attr_e( 'Members directory main navigation', 'buddypress' ); ?>" role="navigation">
				<ul>
					<?php

					/**
					 * Fires inside the members directory member sub-types.
					 *
					 * @since 1.5.0
					 */
					do_action( 'bp_members_directory_member_sub_types' ); ?>

					<li id="members-order-select" class="last filter">

						<?php // the ID for this is important as AJAX relies on it! ?>
						<label for="members-<?php echo bp_current_action(); ?>-orderby"><?php _e( 'Order By:', 'buddypress-followers' ); ?></label>
						<select id="members-<?php echo bp_current_action(); ?>-orderby" data-bp-filter="members">
							<?php if ( class_exists( 'BP_User_Query' ) ) : ?>
								<option value="newest-follows"><?php _e( 'Newest Follows', 'buddypress-followers' ); ?></option>
								<option value="oldest-follows"><?php _e( 'Oldest Follows', 'buddypress-followers' ); ?></option>
							<?php endif; ?>
							<option value="active"><?php _e( 'Last Active', 'buddypress-followers' ); ?></option>
							<option value="newest"><?php _e( 'Newest Registered', 'buddypress-followers' ); ?></option>

							<?php if ( bp_is_active( 'xprofile' ) ) : ?>
								<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress-followers' ); ?></option>
							<?php endif; ?>

							<?php do_action( 'bp_members_directory_order_options' ); ?>

						</select>
					</li>
				</ul>
			</div><!-- .item-list-tabs -->
	    </div>    

		<form action="" method="post" id="members-directory-form" class="dir-form">

			<div id="members-dir-list" class="dir-list members follow following">
				<?php bp_get_template_part( 'members/members-loop' ); ?>
			</div><!-- #members-dir-list -->

			<?php

			/**
			 * Fires and displays the members content.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_directory_members_content' ); ?>

			<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

			<?php

			/**
			 * Fires after the display of the members content.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_after_directory_members_content' ); ?>

		</form><!-- #members-directory-form -->

    </div>

</section>

<?php if( ! is_user_logged_in() ){
	?>
	<script type="text/javascript">
		jQuery( '#members-order-select select' ).val('newest-follows');
	</script>
	<?php
}