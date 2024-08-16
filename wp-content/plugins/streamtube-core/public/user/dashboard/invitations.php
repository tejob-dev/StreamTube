<?php
/**
 *
 * BuddyPress activity template
 * 
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD_INVITATIONS', true );

streamtube_core()->get()->buddypress->setup_bp_environments( 'invitations' );

add_filter( 'bp_current_action', function( $action ){
    switch ( $GLOBALS['wp_query']->query['dashboard']  ){

        case 'invitations/list-invites':
            $action = 'list-invites';
        break;

        default:
            $action = 'send-invites';
        break;
    }

    return $action;
} );

add_filter( 'bp_displayed_user_url', function( $url, $path_chunks ){
    return trailingslashit( get_author_posts_url( get_queried_object_id() ) ) . 'dashboard/invitations/list-invites';
}, 10, 2 );

buddypress()->members->late_includes();

add_action( 'bp_actions', 'members_screen_send_invites' );
add_action( 'bp_actions', 'members_screen_list_sent_invites' );

do_action( 'bp_actions' );

bp_enqueue_scripts();

/**
 *
 * Filter heading
 *
 * @param $string $heading
 * 
 */
$heading = apply_filters( 'streamtube/core/user/profile/invitations', esc_html__( 'Invitations', 'streamtube-core' ));

?>
<div id="buddypress" class="buddypress-wrap bp-dir-hori-nav alignwide">

    <?php

    /**
     *
     * Fires before page header
     * 
     */
    do_action( 'streamtube/core/user/dashboard/page_header/before' );
    ?>

    <div class="page-head mb-3 d-flex gap-3 align-items-center">
        <h1 class="page-title h4">
            <?php esc_html_e( 'Invitations', 'streamtube-core' ); ?>
        </h1>
    </div>

    <?php
    /**
     *
     * Fires after page header
     * 
     */
    do_action( 'streamtube/core/user/dashboard/page_header/after' );

    /**
     *
     * Fires before page content
     * 
     */
    do_action( 'streamtube/core/user/dashboard/page_content/before' );
    ?>

    <div class="page-content bp-wrap">

        <?php bp_get_template_part( 'members/single/invitations' );?>
        
    </div>
    <?php

    /**
     *
     * Fires after page content
     * 
     */
    do_action( 'streamtube/core/user/dashboard/page_content/after' );

    ?>            
</div>