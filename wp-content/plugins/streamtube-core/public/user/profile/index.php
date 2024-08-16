<?php get_header();?>

    <div class="profile-main">

        <?php
        /**
         * Fires in user top
         *
         * @since  1.0.0
         * 
         */
        do_action( 'streamtube/core/user/header' );
        ?>

        <?php
        /**
         *
         * Fires in user main
         *
         * @since  1.0.0
         * 
         * 
         */
        do_action( 'streamtube/core/user/main' );
        ?>

        <?php
        /**
         * Fires in user bottom
         *
         * @since  1.0.0
         * 
         */
        do_action( 'streamtube/core/user/footer' );
        ?>

    </div>

<?php get_footer();