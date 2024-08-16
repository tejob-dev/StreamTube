<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$user_id = $args->ID;

?>
<div id="member-<?php echo $user_id; ?>" class="h-100 member-loop member-<?php echo $user_id; ?> shadow-sm bg-white rounded position-relative">
    <div class="profile-top d-flex flex-column h-100">
        <div class="profile-header ratio ratio-21x9 h-auto">

            <?php
            /**
             *
             * Fires before profile image
             *
             * @param  $args WP_User
             *
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/user/card/profile_image/before', $user_id );
            ?>  

            <?php streamtube_core_get_user_photo( array(
                'user_id'   =>  $user_id,
                'before'    =>  '<div class="profile-header__photo rounded-top">',
                'after'     =>  '</div>'
            ) );?>

            <?php
            /**
             *
             * Fires after profile image
             *
             * @param  $args WP_User
             *
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/user/card/profile_image/after', $user_id );
            ?>

            <?php
            /**
             *
             * Fires before avatar image
             *
             * @param  $args WP_User
             *
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/user/card/avatar/before', $user_id  );
            ?>              

            <?php
            streamtube_core_get_user_avatar( array(
                'user_id'       =>  $user_id,
                'wrap_size'     =>  'xl',
                'before'        =>  '<div class="profile-header__avatar">',
                'after'         =>  '</div>'
            ) );
            ?>

            <?php
            /**
             *
             * Fires after avatar image
             *
             * @param  $args WP_User
             *
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/user/card/avatar/after', $user_id );
            ?>              
            
        </div>

        <div class="author-info">

            <?php
            /**
             *
             * Fires before user name
             *
             * @param  $args WP_User
             *
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/user/card/name/before', $user_id );
            ?>            

            <?php streamtube_core_get_user_name( array(
                'user_id'   =>  $args->ID,
                'before'    =>  '<h2 class="author-name">',
                'after'     =>  '</h2>'
            ) );?>

            <?php
            /**
             *
             * Fires after user name
             *
             * @param  $args WP_User
             *
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/user/card/name/after', $user_id );
            ?>

        </div>

        <?php
        /**
         *
         * Fires before info
         *
         * @param  $args WP_User
         *
         * @since  1.0.0
         * 
         */
        do_action( 'streamtube/core/user/card/info/before', $user_id );

        ?>        

        <div class="member-info text-secondary border-top mt-auto nav nav-fill nav-justified">
            <?php
            /**
             *
             * Fires after video count
             *
             * @param  $args WP_User
             *
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/user/card/info/item', $user_id );
            ?>
        </div>

        <?php
        /**
         *
         * Fires after info
         *
         * @param  $args WP_User
         *
         * @since  1.0.0
         * 
         */
        do_action( 'streamtube/core/user/card/info/after', $user_id );
        ?>

    </div>
</div>