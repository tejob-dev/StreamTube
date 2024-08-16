<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );
?>
<div class="page-head mb-3 d-flex gap-3 align-items-center">
    <h1 class="page-title h4">
        <?php esc_html_e( 'Withdrawal' , 'streamtube-core' );?>
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
<div class="page-content">
    <div class="widget withdraw-points">
        <?php
        /**
         *
         * Fires before table
         *
         * 
         */
        do_action( 'streamtube/core/dashboard/withdraw/table/before' );
        ?>  
        <?php
            if( function_exists( 'mycred_render_cashcred' ) ){
                $output = mycred_render_cashcred( apply_filters(
                    'streamtube/core/mycred/cashcred/withdraw_args',
                    array(
                        'insufficient'  =>  sprintf(
                            '<div class="not-found p-3 text-center text-muted fw-normal h6"><p>%s</p></div>',
                            esc_html__( 'Insufficient Points for Withdrawal', 'streamtube-core' )
                        )
                    )
                ) );

                $find_replace = array(
                    '<form method="post" class="mycred-cashcred-form" action="">',
                    sprintf(
                        '<form method="post" class="mycred-cashcred-form" action="">%s',
                        wp_nonce_field( 'withdraw_on_dashboard', 'withdraw_on_dashboard', true, false )
                    )
                );

                $output = str_replace( $find_replace[0], $find_replace[1], $output );

                echo $output;
            }
        ?>
        <?php
        /**
         *
         * Fires before table
         *
         * 
         */
        do_action( 'streamtube/core/dashboard/withdraw/table/after' );
        ?>      
    </div>
</div>

<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );