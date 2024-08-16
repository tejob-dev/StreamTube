<?php
/**
 *
 * The Advanced Search template file
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
?>
<div class="advanced-search-filter dropdown w-100">

	<button type="button" class="btn btn-advanced-search shadow-none p-0 m-0" id="advanced-search-toggle" data-bs-display="static" data-bs-toggle="dropdown">
		<span class="btn__icon icon-sliders"></span>
	</button>	

	<div class="dropdown-menu dropdown-menu2 shadow w-100 px-0">

		<div class="search-filter-container border-bottom pb-0">

            <?php
            /**
             *
             * Fires before filters
             * 
             */
            do_action( 'streamtube/searchform/filters/before' );
            ?>

			<div class="row">
				<?php dynamic_sidebar( 'advanced-search' );?>
			</div>

            <?php
            /**
             *
             * Fires after filters
             * 
             */
            do_action( 'streamtube/searchform/filters/after' );
            ?>            
		</div>
        <div class="d-flex gap-3 p-3 justify-content-end">
            <?php
            /**
             *
             * Fires before buttons
             * 
             */
            do_action( 'streamtube/searchform/filters/buttons/before' );
            ?>  
    		<?php
                printf(
                    '<button type="reset" class="btn btn-secondary px-3 btn-sm" disabled>%s</button>',
                    esc_html__( 'Reset', 'streamtube' )
                );
    		?>
            <?php
                printf(
                    '<button type="submit" class="btn btn-danger px-3 btn-sm"><span class="btn__icon text-white icon-search"></span><span class="btn__text">%s</span></button>',
                    esc_html__( 'Search', 'streamtube' )
                );
            ?> 
            <?php
            /**
             *
             * Fires after buttons
             * 
             */
            do_action( 'streamtube/searchform/filters/buttons/after' );
            ?> 
        </div>
	</div>

    <script type="text/javascript">
        jQuery( document ).ready(function() {
            jQuery( 'form.search-form.advanced-search' ).on( 'change', function(e){
                jQuery(this).find( 'button[type=reset]' ).removeAttr( 'disabled' );
            } );

            jQuery( 'form.search-form.advanced-search button[type=reset]' ).on( 'click', function(e){

                if( typeof contentTypeSelect === 'function' ){
                    var current = jQuery( '.content-type-filter > div:first-child input' ).val();
                    if( current ){
                        contentTypeSelect( current );    
                    }
                    
                }
                
            });
        });
    </script>
</div>