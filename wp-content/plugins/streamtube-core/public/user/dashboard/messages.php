<?php
/**
 *
 * BP messages template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD_MESSAGES', true );

streamtube_core()->get()->buddypress->setup_bp_environments( 'messages' );

$request = explode( '/' , $GLOBALS['wp_query']->query['dashboard'] );

if( count( $request ) == 1 ){
    $request = array_merge( $request, array( 'inbox' ) );
}

if( is_string( $request[1] ) && $request[1] == 'notices' && ! bp_current_user_can( 'bp_moderate' ) ){
    $request[1] = 'inbox';
}

add_filter( 'bp_current_action', function( $action ) use( $request ){

    if( is_string( $request[1] ) ){

        if( in_array( $request[1], array( 'view' ) ) ){
            add_filter( 'bp_action_variable', function( $var ) use ( $request ){
                return isset( $request[2] ) && absint( $request[2] ) > 0 ? $request[2] : $var;
            } );
        }

        if( isset( $request[2] ) && in_array( $request[2], array( 'activate', 'deactivate', 'delete', 'read', 'unread', 'bulk-manage', 'bulk-delete', 'exit' ) ) ){

            add_filter( 'bp_action_variables', function( $var ) use ( $request ){
                $_var = array( $request[2] );
                if( isset( $request[3] ) ){
                    $_var = array_merge( $_var, array( $request[3] ) );
                }

                return $_var;
            } );

            add_filter( 'bp_is_action_variable', '__return_true' );
        }

        $action = $request[1];
    }

    return $action;
} );

buddypress()->messages->late_includes();

do_action( 'bp_actions' );

bp_enqueue_scripts();

add_action( 'wp_footer', 'messages_add_autocomplete_css' );
add_action( 'wp_footer', 'messages_autocomplete_init_jsblock' );

?><div id="buddypress" class="buddypress-wrap bp-dir-hori-nav alignwide">
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
    		<?php esc_html_e( 'Messages', 'streamtube-core' ); ?>
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

        <div id="item-body" class="item-body">

    	   <?php bp_get_template_part( 'members/single/messages' );?>

        </div>
    	
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
