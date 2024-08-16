<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<form class="form form-report form-ajax" method="post">

	<?php
	/**
	 *
	 * Fires before report fields.
	 *
	 * @since  1.0.0
	 * 
	 */
	do_action( 'streamtube/video/report/before' );

	if( get_terms( array( 'taxonomy' => Streamtube_Core_Taxonomy::TAX_REPORT, 'hide_empty' => false ) ) ):
	?>
	<div class="mb-3 field-report-tax">
		<?php
			wp_dropdown_categories( array(
				'taxonomy'		=>	Streamtube_Core_Taxonomy::TAX_REPORT,
				'hide_empty'	=>	false,
				'name'			=>	'category'
			) );
		?>
	</div>
	<?php
	endif;

	streamtube_core_the_field_control( array(
		'label'	=>	esc_html__( 'Description', 'streamtube-core' ),
		'type'	=>	'textarea',
		'name'	=>	'description'					
	) );

	/**
	 *
	 * Fires after report fields.
	 *
	 * @since  1.0.0
	 * 
	 */
	do_action( 'streamtube/video/report/after' );
	?>

    <div class="d-flex">
        <button type="submit" class="btn btn-primary ms-auto">
            <span class="btn__icon icon-flag-empty"></span>
            <span class="button-label">
                <?php esc_html_e( 'Report', 'streamtube-core' ); ?>
            </span>
        </button>
    </div>					

	<input type="hidden" name="action" value="report_video">
	<input type="hidden" name="post_id" value="<?php the_ID(); ?>">
</form>