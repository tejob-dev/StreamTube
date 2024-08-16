<?php
/**
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

get_header();
?>

	<?php if( have_posts() ): the_post();
	$has_sidebar = streamtube_has_sidebar_primary();
	?>

	<div class="section-player bg-black mb-4">
	    <div class="container">
	        <div class="post-main">
	        	<?php
	        	/**
	        	 *
	        	 * Fires before player
	        	 *
	        	 * @since  1.0.0
	        	 * 
	        	 */
	        	do_action( 'streamtube/player/before' );
	        	?>

	            <?php get_template_part( 'template-parts/player' );?>

	        	<?php
	        	/**
	        	 *
	        	 * Fires after player
	        	 *
	        	 * @since  1.0.0
	        	 * 
	        	 */
	        	do_action( 'streamtube/player/after' );
	        	?>
	        </div>
	    </div>
	</div>
	
	<div class="page-main">

		<div class="<?php echo esc_attr( streamtube_get_container_single_classes() )?>">

	        <div class="row">

	            <?php printf(
	            	'<div class="col-xxl-%1$s col-xl-%1$s col-lg-%1$s col-md-12 col-12 %2$s">',
	            	$has_sidebar 	? streamtube_get_main_content_size() : 9,
	            	! $has_sidebar 	? 'mx-auto' : ''
	            );?>

                	<?php
                	/**
                	 *
                	 * Fires before main content
                	 *
                	 * @since  1.0.0
                	 * 
                	 */
                	do_action( 'streamtube/single/content/wrap/before' );

                	?>	     

                	<?php get_template_part( 'template-parts/alert/content', get_post_status() );?>

	                <div class="shadow-sm rounded bg-white mb-4">

	                	<?php
	                	/**
	                	 *
	                	 * Fires before main content
	                	 *
	                	 * @since  1.0.0
	                	 * 
	                	 */
	                	do_action( 'streamtube/single/content/before' );
	                	?>

	                	<?php get_template_part( 
	                		'template-parts/content/content-single',
	                		'video',
                            apply_filters( 'streamtube/single/video/part_args', array(
                                'author_avatar' =>  'on'
                            ) )
	                	); ?>

	                	<?php
	                	/**
	                	 *
	                	 * Fires after main content
	                	 *
	                	 * @since  1.0.0
	                	 * 
	                	 */
	                	do_action( 'streamtube/single/content/after' );
	                	?>	                	

	                </div>

                	<?php
                	/**
                	 *
                	 * Fires after main content
                	 *
                	 * @since  1.0.0
                	 * 
                	 */
                	do_action( 'streamtube/single/content/wrap/after' );
                	?>		                

					<?php
					if( streamtube_has_sidebar_bottom() ){
						get_sidebar( 'content-bottom' );	
					}
					?>

					<?php if( streamtube_has_post_comments() ): ?>
		                <?php comments_template(); ?>
	            	<?php endif;?>

	            </div>

                <?php if( $has_sidebar ): ?>

	                <?php printf(
	                	'<div class="col-xxl-%1$s col-xl-%1$s col-lg-%1$s col-md-12 col-12">',
	                	12-(int)streamtube_get_main_content_size()
	                )?>
	                	<?php get_sidebar( $has_sidebar )?>
	                </div>

            	<?php endif;?>	 

	        </div>

		</div>

	</div>

	<?php endif;

get_footer();