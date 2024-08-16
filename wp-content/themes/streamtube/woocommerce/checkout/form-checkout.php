<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {

	?>
	<div class="woocommerce-login-checkout login-wrap position-relative">
		<div class="text-center top-50 start-50 translate-middle position-absolute">

			<?php
				printf(
					'<h5 class="text-muted h5 mb-4">%s</h5>',
					esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) )
				);
			?>

			<?php printf(
					'<a class="btn btn-primary btn-login text-white px-3" href="%s">', 
					esc_url( wp_login_url( get_permalink( wc_get_page_id( 'checkout' ) ) ) ) 
				);
			?>
				<span class="menu-icon icon-user-circle me-0 me-sm-1"></span>
				<span class="menu-text small menu-text small">
					<?php esc_html_e( 'Log In', 'streamtube' )?>
				</span>
			</a>

		</div>
	</div>
	<?php

	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<div class="row">

		<div class="col-12 col-md-12 col-lg-8 col-xl-8">

			<div class="shadow-sm bg-white p-4 mb-4 mb-lg-0">

				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>


					<div class="col2-set" id="customer_details">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>

						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>

			</div>

		</div>
		
		<div class="col-12 col-md-12 col-lg-4 col-xl-4">
			<div class="shadow-sm bg-white p-4 checkout-section">
				<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
				
				<h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'streamtube' ); ?></h3>
				
				<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			</div>

		</div>

	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
