<?php
/**
 *
 * The Woocommerce Sell Content template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.3
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

wp_enqueue_style( 'select2' );
wp_enqueue_script( 'select2' );

global $post, $streamtube;

$sell_content 	= $streamtube->get()->woocommerce->sell_content;

$relevant 		= $sell_content->get_relevant_product( $post->ID );

$product 		= $sell_content->get_builtin_product( $post->ID );

if( $product ){
	$GLOBALS['product'] = $product;
}

/**
 *
 * Fires before sell content box
 * 
 */
do_action( 'streamtube/core/woocommerce/sell_content_box/before' );
?>
<?php if( ! $product || ! $product->exists() ) :?>

	<?php if( $sell_content->can_set_relevant_product( $post->ID ) ) : ?>

		<div class="metabox-wrap select-product">

		    <?php printf(
		    	'<p class="text-muted fst-italic mb-0 %s">',
		    	! is_admin() || wp_doing_ajax() ? 'mb-3' : ''
		    )?>
		        <?php esc_html_e( 'Configure a relevant product to enable the sale of this video content', 'streamtube-core' );?>
		    </p>	

			<?php if( is_admin() && ! wp_doing_ajax() ): ?>
				<p>
					<strong><?php esc_html_e( 'Note:' );?></strong>
					<?php esc_html_e( 'Admin, Editor, and Video Owner can always view the video content without the need to purchase any related product', 'streamtube-core' ); ?>
				</p>
			<?php endif;?>

			<div class="field-group border p-3 bg-white">
				<p>
			        <a class="dropdown-toggle d-block w-100 text-body fw-bold" data-bs-toggle="collapse" href="#quick-select-product">
						<?php esc_html_e( 'Select an existing product.', 'streamtube-core' );?>
			        </a>
		    	</p>
		        <div class="collapse" id="quick-select-product">
					<select id="product_id" name="product_id" class="regular-text input-field w-100 select-select2">

						<option value="0"><?php esc_html_e( 'Select a relevant product', 'streamtube-core' ); ?></option>

						<?php
						$products 	= streamtube_core_wc_get_products();

			            if( $products ){
			            	foreach ( $products as $product ) {

			                    printf(
			                        '<option %1$s value="%2$s">(ID: #%2$s) %3$s (%4$s%5$s)</option>',
			                        $relevant && $relevant->get_id() == $product->get_id() ? 'selected' : '',
			                        esc_attr( $product->get_id() ),
			                        esc_html( $product->get_name() ),
			                        get_woocommerce_currency_symbol(),
			                        esc_html( $product->get_price() )
			                    );
			            	}
			            }
						?>
					</select>		
		    	</div>
			</div>

			<?php wp_nonce_field( 'update_relevant_product', 'update_relevant_product', false ); ?>

			<?php if( ! wp_doing_ajax() ): ?>
			    <script type="text/javascript">
			        jQuery(function () {
			            jQuery( '.select-select2' ).select2({ width: '100%' });
			        });
			    </script>
			<?php endif;?>
		</div>

	<?php endif;?>

	<?php if( $sell_content->can_set_builtin_product( $post->ID ) ) : ?>

		<div class="metabox-wrap add-product">
		    <div class="field-group border p-3 bg-white">
		    	<p>
			        <a class="dropdown-toggle d-block w-100 text-body fw-bold" data-bs-toggle="collapse" href="#quick-add-product">
						<?php esc_html_e( 'Set price', 'streamtube-core' );?>
			        </a>
		    	</p>

		        <div class="collapse" id="quick-add-product">

					<?php load_template( plugin_dir_path( __FILE__ ) . 'set-price.php' ); ?>

		        </div>
		    </div>

		    <?php wp_nonce_field( 'update_builtin_product', 'update_builtin_product', false ); ?>
		</div>

	<?php endif;?>

<?php elseif( $sell_content->can_set_builtin_product( $post->ID ) && StreamTube_Core_Woocommerce_Permission::can_edit_product( $product->get_id() ) ): ?>

	<?php load_template( plugin_dir_path( __FILE__ ) . 'set-price.php' ); ?>

	<?php wp_nonce_field( 'update_builtin_product', 'update_builtin_product' ); ?>

<?php endif; ?>
<?php
/**
 *
 * Fires after sell content box
 * 
 */
do_action( 'streamtube/core/woocommerce/sell_content_box/after' );