<?php
/**
 * Dokan Help Text Template
 *
 * @since   2.4
 *
 * @package dokan
 */
?>

<div class="dokan-page-help alert alert-info p-2 px-3">
    <?php echo wp_kses_post( $help_text ); ?>
</div>
