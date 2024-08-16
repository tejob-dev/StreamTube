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

$args = wp_parse_args( $args, array(
	'heading'				=>	'',
	'header_alignment'		=>	'default',
	'header_padding'		=>	4
) );

$classes = array( 'page-header', 'bg-white', 'px-2', 'border-bottom', 'mb-4' );

if( $args['header_alignment'] == 'center' ){
	$classes[] = 'text-center';
}

$args['header_padding'] = (int)$args['header_padding'];

if( ! $args['header_padding'] || $args['header_padding'] > 5 ){
	$args['header_padding'] = 5;
}

$classes[] = 'py-' . $args['header_padding'];

?>
<?php printf( '<div class="%s">', esc_attr( join( ' ', $classes ) ) ); ?>
	<div class="container">

		<?php streamtube_breadcrumbs(); ?>

		<?php 
		/**
		 *
		 * Fires before archive title
		 *
		 * @since 1.0.0
		 * 
		 */
		do_action( 'streamtube/page_header/title/before' );
		?>

		<?php if( $args['heading'] ): ?>
				<?php
					printf(
						'<h1 class="page-title">%s</h1>',
						$args['heading']
					);
				?>				
		<?php else: ?>

			<?php if( is_home() ):?>

				<?php
				$title = get_option( 'blog_heading', esc_html__( 'Blog', 'streamtube' ) );

				if( $title ){
					printf(
						'<h1 class="page-title archive-title">%s</h1>',
						$title
					);
				}
				?>

			<?php elseif( is_search() ): ?>

				<?php
					printf(
						'<h1 class="page-title search-title">%s</h1>',
						sprintf( esc_html__( 'Search result for "%s"', 'streamtube' ), get_search_query() )
					);
				?>			

			<?php elseif( is_singular() ): ?>

	            <?php 
	             the_title(
	                '<h1 class="post-meta__title post-title post-title-xxl py-2">', '</h1>'
	            );    
	            ?>			

			<?php else:?>

		        <?php the_archive_title( '<h1 class="page-title archive-title">', '</h1>' );?>

		        <?php the_archive_description( '<div class="archive-description text-secondary">', '</div>' );?>          

	    	<?php endif;?>
    	<?php endif;?>

    	<?php 
		/**
		 *
		 * Fires after archive title
		 *
		 * @since 1.0.0
		 * 
		 */
    	do_action( 'streamtube/page_header/title/after' );
    	?>

	</div>
</div>