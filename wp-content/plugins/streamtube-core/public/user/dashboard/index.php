<?php get_header( 'dashboard' );?>

            <div id="dashboard-<?php echo get_queried_object_id();?>" class="user-dashboard overflow-hidden p-0">

                <?php streamtube_core_load_template( 'user/dashboard/menu.php' ); ?>

                <div class="col_main w-100">

                    <?php 
                    printf(
                        '<div class="%s">',
                        join( ' ', streamtube_core_get_dashboad_container_classes() )
                    );?>

                    	<?php
                    	/**
                    	 *
                    	 * Fires before dashboard main content
                    	 *
                    	 * @since  1.0.0
                    	 * 
                    	 */
                    	do_action( 'streamtube/core/user/dashboard/main/before' );
                    	?>

                    	<?php streamtube_core()->get()->user_dashboard->the_main(); ?>

                    	<?php
                    	/**
                    	 *
                    	 * Fires aftr dashboard main content
                    	 *
                    	 * @since  1.0.0
                    	 * 
                    	 */
                    	do_action( 'streamtube/core/user/dashboard/main/after' );
                    	?>

                    </div>

                </div>
            </div>

        </div><!--site-main-->

        <?php
        /**
         *
         * Fires after footer
         * 
         */
        do_action( 'streamtube/footer/after' );
        ?>

        <?php wp_footer();?>

    </body>

</html>