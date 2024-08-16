<?php
/**
 * Template Name: Single Video V3
 *
 * Template Post Type: Video
 *
 * The template for displaying single video
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

	<?php 
	if( have_posts() ): the_post(); 
	?>

	<div class="page-main p-0">

		<div class="single-main">

			<div class="p-0 container-fluid">

		        <div class="row m-0">

		            <?php printf(
		            	'<div class="single-video__body p-0 col-12 %s">',
		            	streamtube_has_post_comments() ? 'col-lg-8 col-xl-9 col-xxl-9 no-scroll' : 'h-auto'
		            );?>

		            	<?php get_template_part( 'template-parts/alert/content', get_post_status() );?>

	                	<?php
	                	/**
	                	 *
	                	 * Fires before content wrapper
	                	 *
	                	 * @since  1.0.0
	                	 * 
	                	 */
	                	do_action( 'streamtube/single/content/wrap/before' );
	                	?>		            	

		                <div class="single-video__body__main bg-white rounded mb-4">

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
		                		'video-v3',
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
	                	 * Fires after content wrapper
	                	 *
	                	 * @since  1.0.0
	                	 * 
	                	 */
	                	do_action( 'streamtube/single/content/wrap/after' );
	                	?>			                

		                <?php if( streamtube_has_sidebar_bottom() ): ?>

		                	<?php add_filter( 'sidebars_widgets', 'streamtube_remove_comments_template_widget', 10, 1 ); ?>

			                <div class="single-video__body__bottom px-4">
			                	<?php get_sidebar( 'content-bottom' )?>
			                </div>

		            	<?php endif;?>

		            </div>

		            <?php if( streamtube_has_post_comments() ): ?>

			            <div class="single-video__comments comments-fixed p-0 col-12 col-lg-4 col-xl-3 col-xxl-3">
			                <?php comments_template();?>
			            </div>

		        	<?php endif;?>

		        </div>

			</div>

		</div>

	</div>

	<?php endif;?>

	<script type="text/javascript">
		window.addEventListener('load', function () {
			setTimeout(function () {
				window.scrollTo(0, 0);
			}, 0);
		});
	</script>

<?php 
get_footer();