<?php
/**
 * The template for displaying buddypress members page
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
         * Fires before groups
         * 
         */
        do_action( "streamtube/buddypress/{$component}/before" );
        ?>

		<div class="row">

            <?php printf(
                '<div class="col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
                $sidebar ? '8' : '12'
            );?>

            <?php 
            /**
             *
             * Fires before the loop
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
             * Fires after the loop
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
         * Fires after groups
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