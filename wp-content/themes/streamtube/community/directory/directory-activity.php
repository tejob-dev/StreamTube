<?php
/**
 * The template for displaying buddypress activities page
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

$component 	= bp_current_component();

$sidebar 	= streamtube_bp_get_sidebar();
?>
<div class="page-main pt-4">

	<div class="container">

        <?php
	    /**
	     *
	     * Fires before directory
	     * 
	     */
	    do_action( 'streamtube/buddypress/directory/before' );

        /**
         *
         * Fires before activity
         * 
         */
        do_action( "streamtube/buddypress/{$component}/before" );
        ?>		

		<div class="row">

            <?php printf(
                '<div class="%1$s col-xl-8 col-lg-8 col-md-12 col-12 %s">',
                ! $sidebar ? 'mx-auto' : ''
            );?>

            <?php 
            /**
             *
             * Fires before activity loop
             * 
             */
            do_action( "streamtube/buddypress/{$component}_loop/before" );
            ?>

			<?php if( have_posts() ): the_post();

				the_content();

			endif;?>

            <?php 
            /**
             *
             * Fires after activity loop
             * 
             */
            do_action( "streamtube/buddypress/{$component}_loop/after" );
            ?>			

			</div>

            <?php if( $sidebar ): ?>
                <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                    <?php get_sidebar( $sidebar );?>
                </div>
            <?php endif;?>				

		</div>

        <?php 
        /**
         *
         * Fires after activities
         * 
         */
        do_action( "streamtube/buddypress/{$component}/after" );

	    /**
	     *
	     * Fires after directory
	     * 
	     */
	    do_action( 'streamtube/buddypress/directory/after' );        
        ?>			

	</div>

</div>