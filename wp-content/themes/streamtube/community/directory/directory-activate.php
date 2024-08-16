<?php
/**
 * The template for displaying buddypress activation page
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
?>
<div class="page-main pt-4">

	<div class="container bg-registration-container bg-activate-container">

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

		<div class="p-4 bg-white rounded shadow-sm mb-4">

			<?php if( have_posts() ): the_post();

				the_content();

			endif;?>

			<div class="clearfix"></div>

		</div>

        <?php 
        /**
         *
         * Fires after activate
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