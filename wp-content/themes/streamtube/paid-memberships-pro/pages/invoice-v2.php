<?php 
/**
 * Template: Invoice
 *
 * See documentation for how to override the PMPro templates.
 * @link https://www.paidmembershipspro.com/documentation/templates/
 *
 * @version 2.0
 *
 * @author Paid Memberships Pro
 */

global $wpdb, $pmpro_invoice, $pmpro_msg, $pmpro_msgt, $current_user;
?>
<div class="<?php echo pmpro_get_element_class( 'pmpro_invoice_wrap' ); ?>">
	<?php
	if( $pmpro_msg ):
		printf(
			'<div class="%s">%s</div>',
			esc_attr( pmpro_get_element_class( 'alert alert-info pmpro_message ' . $pmpro_msgt, $pmpro_msgt ) ),
			$pmpro_msg
		);
	endif; // end of $pmpro_msg

	if( $pmpro_invoice ):
		$pmpro_invoice->getUser();
		$pmpro_invoice->getMembershipLevel();

		printf(
			'<h3>%s</h3>',
			sprintf(
				esc_html__('Invoice #%s on %s', 'paid-memberships-pro' ), 
				$pmpro_invoice->code, 
				date_i18n( get_option('date_format'), $pmpro_invoice->getTimestamp() )
			)
		);

		printf(
			'<a href="javascript:window.print()" class="%s">%s</a>',
			esc_attr( pmpro_get_element_class( 'pmpro_a-print' ) ),
			esc_html__('Print', 'paid-memberships-pro' )
		);

		?>

		<ul>
			<?php do_action("pmpro_invoice_bullets_top", $pmpro_invoice); ?>

			<li>
				<strong><?php esc_html_e('Account', 'paid-memberships-pro' );?>:</strong>
				<?php echo esc_html( $pmpro_invoice->user->display_name ); ?> (<?php echo esc_html( $pmpro_invoice->user->user_email ); ?>)
			</li>

			<li>
				<strong><?php esc_html_e('Membership Level', 'paid-memberships-pro' );?>:</strong>

				<?php if( $pmpro_invoice->membership_level ): ?>

					<span class="badge bg-success">
						<?php echo esc_html( $pmpro_invoice->membership_level->name ); ?>
					</span>

				<?php else:?>

					<span class="badge bg-danger">
						<?php esc_html_e( 'Deleted', 'paid-memberships-pro' );?>
					</span>

				<?php endif;?>
			</li>

			<?php if ( ! empty( $pmpro_invoice->status ) ) : ?>
				<li><strong><?php esc_html_e('Status', 'paid-memberships-pro' ); ?>:</strong>
				<?php
					if ( in_array( $pmpro_invoice->status, array( '', 'success', 'cancelled' ) ) ) {
						$display_status = esc_html__( 'Paid', 'paid-memberships-pro' );
					} else {
						$display_status = ucwords( $pmpro_invoice->status );
					}
					
					printf(
						'<span class="badge badge-invoice-status badge-%s">%s</span>',
						sanitize_html_class( strtolower( $display_status ) ),
						ucwords( $display_status )
					);
				?>
				</li>
			<?php endif; ?>

			<?php if($pmpro_invoice->getDiscountCode()) : ?>
				<li>
					<strong><?php esc_html_e('Discount Code', 'paid-memberships-pro' );?>:</strong>
					<?php echo esc_html( $pmpro_invoice->discount_code->code ); ?>
				</li>
			<?php endif; ?>

			<?php do_action( "pmpro_invoice_bullets_bottom", $pmpro_invoice ); ?>
		</ul>

		<?php
			// Check instructions
			if ( $pmpro_invoice->gateway == "check" && ! pmpro_isLevelFree( $pmpro_invoice->membership_level ) ):
				printf(
					'<div class="%s">%s</div>',
					esc_attr( pmpro_get_element_class( 'pmpro_payment_instructions' ) ),
					wpautop( wp_unslash( pmpro_getOption("instructions") ) )
				);
			endif;
		?>

		<hr />

		<?php printf( '<div class="%s">', esc_attr( pmpro_get_element_class( 'pmpro_invoice_details' ) ) );?>

			<?php if( ! empty( $pmpro_invoice->billing->street ) ) : ?>
				<div class="<?php echo pmpro_get_element_class( 'pmpro_invoice-billing-address' ); ?>">
					<strong><?php esc_html_e('Billing Address', 'paid-memberships-pro' );?></strong>
					<p>

						<?php printf(
							'<span class="%s">%s</span>',
							pmpro_get_element_class( 'pmpro_invoice-field-billing_name' ),
							$pmpro_invoice->billing->name
						);?>

						<?php printf(
							'<span class="%s">%s</span>',
							pmpro_get_element_class( 'pmpro_invoice-field-billing_street' ),
							$pmpro_invoice->billing->street
						);?>						


						<?php if($pmpro_invoice->billing->city && $pmpro_invoice->billing->state) { ?>

							<?php printf(
								'<span class="%s">%s</span>',
								pmpro_get_element_class( 'pmpro_invoice-field-billing_city' ),
								$pmpro_invoice->billing->city
							);?>

							<?php printf(
								'<span class="%s">%s</span>',
								pmpro_get_element_class( 'pmpro_invoice-field-billing_state' ),
								$pmpro_invoice->billing->state
							);?>							

							<?php printf(
								'<span class="%s">%s</span>',
								pmpro_get_element_class( 'pmpro_invoice-field-billing_zip' ),
								$pmpro_invoice->billing->zip
							);?>
	
							<?php printf(
								'<span class="%s">%s</span>',
								pmpro_get_element_class( 'pmpro_invoice-field-billing_country' ),
								$pmpro_invoice->billing->country
							);?>

						<?php } ?>

						<?php printf(
							'<span class="%s">%s</span>',
							pmpro_get_element_class( 'pmpro_invoice-field-billing_phone' ),
							$pmpro_invoice->billing->phone
						);?>

					</p>
				</div> <!-- end pmpro_invoice-billing-address -->
			<?php endif; ?>

			<?php if ( ! empty( $pmpro_invoice->accountnumber ) || ! empty( $pmpro_invoice->payment_type ) ) : ?>
				
				<?php printf( '<div class="%s">', esc_attr( pmpro_get_element_class( 'pmpro_invoice-payment-method' ) ) ); ?>

					<strong><?php esc_html_e('Payment Method', 'paid-memberships-pro' );?></strong>

					<?php if($pmpro_invoice->accountnumber) : ?>
						<p><?php echo ucwords( $pmpro_invoice->cardtype ); ?> <?php _e('ending in', 'paid-memberships-pro' );?> <?php echo last4($pmpro_invoice->accountnumber)?>
							<br />

							<?php printf(
								'%s: %s / %s',
								esc_html__('Expiration', 'paid-memberships-pro' ),
								$pmpro_invoice->expirationmonth,
								$pmpro_invoice->expirationyear
							);?>
						</p>
					<?php else: ?>
						<?php printf(
							'<p>%s</p>',
							$pmpro_invoice->payment_type
						)?>
					<?php endif; ?>

				</div> <!-- end pmpro_invoice-payment-method -->
			<?php endif; ?>

			<?php printf( '<div class="%s">', esc_attr( pmpro_get_element_class( 'pmpro_invoice-total' ) ) );?>
				<strong><?php esc_html_e('Total Billed', 'paid-memberships-pro' );?></strong>
				<p>
					<?php
						if ( (float)$pmpro_invoice->total > 0 ) {
							echo pmpro_get_price_parts( $pmpro_invoice, 'span' );
						} else {
							echo pmpro_escape_price( pmpro_formatPrice(0) );
						}
					?>
				</p>
			</div> <!-- end pmpro_invoice-total -->
		</div> <!-- end pmpro_invoice_details -->
		<hr />
		<?php
	else:
		//Show all invoices for user if no invoice ID is passed
		$invoices = $wpdb->get_results("SELECT o.*, UNIX_TIMESTAMP(CONVERT_TZ(o.timestamp, '+00:00', @@global.time_zone)) as timestamp, l.name as membership_level_name FROM $wpdb->pmpro_membership_orders o LEFT JOIN $wpdb->pmpro_membership_levels l ON o.membership_id = l.id WHERE o.user_id = '$current_user->ID' AND o.status NOT IN('review', 'token', 'error') ORDER BY timestamp DESC");
		if($invoices)
		{
			?>
			<table id="pmpro_invoices_table" class="<?php echo pmpro_get_element_class( 'pmpro_table pmpro_invoice', 'pmpro_invoices_table' ); ?>" width="100%" cellpadding="0" cellspacing="0" border="0">
			<thead>
				<tr>
					<th><?php esc_html_e('Date', 'paid-memberships-pro' ); ?></th>
					<th><?php esc_html_e('Invoice #', 'paid-memberships-pro' ); ?></th>
					<th><?php esc_html_e('Level', 'paid-memberships-pro' ); ?></th>
					<th><?php esc_html_e('Total Billed', 'paid-memberships-pro' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach($invoices as $invoice)
				{
					?>
					<tr>

						<?php printf(
							'<td><a href="%s">%s</a></td>',
							esc_url( pmpro_url("invoice", "?invoice=" . $invoice->code ) ),
							date_i18n( get_option("date_format"), strtotime( get_date_from_gmt( date( 'Y-m-d H:i:s', $invoice->timestamp ) ) ) )
						);?>

						<?php printf(
							'<td><a href="%s">%s</a></td>',
							esc_url( pmpro_url("invoice", "?invoice=" . $invoice->code ) ),
							esc_html( $invoice->code )
						);?>

						<?php printf(
							'<td>%s</td>',
							$invoice->membership_level_name
						);?>

						<?php printf(
							'<td>%s</td>',
							pmpro_formatPrice($invoice->total)
						);?>						

					</tr>
					<?php
				}
			?>
			</tbody>
			</table>
			<?php
		}
		else
		{
			?>
			<p><?php esc_html_e( 'No invoices found.', 'paid-memberships-pro' );?></p>
			<?php
		}
	endif;
?>
</div> <!-- end pmpro_invoice_wrap -->
