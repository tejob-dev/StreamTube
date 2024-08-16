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
?>
<section class="no-results not-found position-relative">
	<?php if ( is_search() ) : ?>

		<p class="text-muted"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'streamtube' ); ?></p>

	<?php else : ?>

		<p class="text-muted h6"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'streamtube' ); ?></p>

	<?php endif; ?>

</section><!-- .no-results -->
