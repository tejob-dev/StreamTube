<?php

/**
 * User Details
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_user_details' ); ?>

<div id="bbp-single-user-details" class="w-100 float-none mb-4">

	<?php do_action( 'bbp_template_before_user_details_menu_items' ); ?>

	<div id="bbp-user-navigation">
		<ul class="nav nav-tabs">

			<li class="nav-item">
				<a class="bbp-user-topics-created-link nav-link <?php if ( bbp_is_single_user_topics() ) :?>active<?php endif; ?>" href="<?php bbp_user_topics_created_url(); ?>" title="<?php printf( esc_attr__( "%s's Topics Started", 'streamtube' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php esc_html_e( 'Topics Started', 'streamtube' ); ?></a>

			</li>

			<li class="nav-item">
				<a class="bbp-user-replies-created-link nav-link <?php if ( bbp_is_single_user_replies() ) :?>active<?php endif; ?>" href="<?php bbp_user_replies_created_url(); ?>" title="<?php printf( esc_attr__( "%s's Replies Created", 'streamtube' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php esc_html_e( 'Replies Created', 'streamtube' ); ?></a>
			</li>

			<?php if ( bbp_is_engagements_active() ) : ?>
				<li class="nav-item">
					<a class="bbp-user-engagements-created-link nav-link <?php if ( bbp_is_single_user_engagements() ) :?>active<?php endif; ?>" href="<?php bbp_user_engagements_url(); ?>" title="<?php printf( esc_attr__( "%s's Engagements", 'streamtube' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php esc_html_e( 'Engagements', 'streamtube' ); ?></a>
				</li>
			<?php endif; ?>

			<?php if ( bbp_is_favorites_active() ) : ?>
				<li class="nav-item">
					<a class="bbp-user-favorites-link nav-link <?php if ( bbp_is_favorites() ) :?>active<?php endif; ?>" href="<?php bbp_favorites_permalink(); ?>" title="<?php printf( esc_attr__( "%s's Favorites", 'streamtube' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php esc_html_e( 'Favorites', 'streamtube' ); ?></a>
				</li>
			<?php endif; ?>

			<?php if ( bbp_is_user_home() || current_user_can( 'edit_user', bbp_get_displayed_user_id() ) ) : ?>

				<?php if ( bbp_is_subscriptions_active() ) : ?>
					<li class="nav-item">
						<a class="bbp-user-subscriptions-link nav-link <?php if ( bbp_is_subscriptions() ) :?>active<?php endif; ?>" href="<?php bbp_subscriptions_permalink(); ?>" title="<?php printf( esc_attr__( "%s's Subscriptions", 'streamtube' ), bbp_get_displayed_user_field( 'display_name' ) ); ?>"><?php esc_html_e( 'Subscriptions', 'streamtube' ); ?></a>
					</li>
				<?php endif; ?>

			<?php endif; ?>

		</ul>

		<?php do_action( 'bbp_template_after_user_details_menu_items' ); ?>

	</div>
</div>

<?php do_action( 'bbp_template_after_user_details' );
