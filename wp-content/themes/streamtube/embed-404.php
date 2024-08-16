<?php
/**
 * The embed 404 error template file
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

/**
 *
 * Fires before error content
 * 
 */
do_action( 'streamtube/embed/404/content/before' );
?>
<div class="player-wrapper bg-black no-jsappear">
	<div class="player-wrapper-ratio ratio ratio-16x9">
		<div class="video-not-found position-absolute top-50 start-50 translate-middle">
			<?php

			/**
			 *
			 * Fires before error text
			 * 
			 */
			do_action( 'streamtube/embed/404/text/before' );

			printf(
				'<h3 class="text-white">%s</h3>',
				esc_html__( 'Video unavailable', 'streamtube' )
			);

			/**
			 *
			 * Fires after error text
			 * 
			 */
			do_action( 'streamtube/embed/404/text/after' );

			printf(
				'<button data-href="%s" class="text-white browse" id="browse">%s</button>',
				esc_url( home_url('/') ),
				esc_html__( 'Browse', 'streamtube' )
			);

			/**
			 *
			 * Fires after error button
			 * 
			 */
			do_action( 'streamtube/embed/404/button/after' );

			?>
		</div>

	</div><!--.player-wrapper-ratio-->
</div>

<?php
/**
 *
 * Fires after error content
 * 
 */
do_action( 'streamtube/embed/404/content/after' );
?>
<style type="text/css">

	.video-not-found{
		text-align: center;
	}

	.browse{
	    color: #fff;
	    font-size: 1.1rem;
	    text-decoration: none;
	    background: #000;
	    border: 1px solid #666;
	    padding: 0.5rem 1.5rem;
	    border-radius: 35px;
	    cursor: pointer;
	}
</style>

<script type="text/javascript">

	document.getElementById("browse").addEventListener("click", function(e){
		e.preventDefault();
        window.open( this.getAttribute( 'data-href' ) , "_blank");
    });

</script>