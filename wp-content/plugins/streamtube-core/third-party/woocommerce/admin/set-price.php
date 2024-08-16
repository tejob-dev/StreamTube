<?php
/**
 *
 * The Woocommerce Set Price template file
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

global $product, $post;

?>
<table class="form-table table table-set-price">
	<tr>
		<td>
            <div class="input-group">
                <label class="input-group-text bg-secondary border text-white" id="addon-wrapping">
                    <?php printf(
                        esc_html__( 'Regular Price (%s)', 'streamtube-core' ),
                        get_woocommerce_currency_symbol()
                    )?>
                </label>
                
                <?php printf(
                	'<input type="text" class="form-control regular-text text-success fw-bold" name="regular_price" value="%s" />',
                	$product ? $product->get_regular_price() : ''
                )?>
            </div>	        				
		</td>
		<td>
            <div class="input-group">
                <label class="input-group-text bg-secondary border text-white" id="addon-wrapping">
                    <?php printf(
                        esc_html__( 'Sale Price (%s)', 'streamtube-core' ),
                        get_woocommerce_currency_symbol()
                    )?>
                </label>
                
                <?php printf(
                	'<input type="text" class="form-control regular-text text-success fw-bold" name="sale_price" value="%s" />',
                	$product ? $product->get_sale_price() : ''
                )?>
            </div>	        				
		</td>	        			
	</tr>

	<?php if( $product ): ?>
	<tr>
		<td colspan="2">

			<div class="d-flex gap-4" style="gap: 1rem;">

				<?php streamtube_core_wc_edit_product_link( $product ); ?>

				<div class="form-check">
					<?php printf(
						'<input class="form-check-input" type="checkbox" name="disable_selling" id="disable_selling_%s" %s>',
						$post->ID,
						checked( 'on', get_post_meta( $post->ID, '_disable_selling', true ), false )
					)?>					
					<?php printf( '<label class="form-check-label" for="disable_selling_%s">', $post->ID ); ?>
						<?php esc_html_e( 'Disable Selling', 'streamtube-core' );?>
					</label>
				</div>		

			</div>
		</td>
	</tr>
	<?php endif;?>
</table>