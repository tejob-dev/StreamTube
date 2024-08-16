<?php
/**
 *
 * The Add Ad Tags form
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

?>
<div id="add-ad-tags-wrap">

    <p class="field-group">
        <label for="ad_tag"><strong><?php esc_html_e( 'Ad Tag', 'streamtube-core' );?></strong></label>
        <select id="ad_tag" name="ad_tag" class="regular-text input-field w-100">
            <option value=""><?php esc_html_e( 'Select ad tag', 'streamtube-core' );?></option>

            <?php
            $ad_tags = $advertising->ad_tag->get_ad_tags();

            if( $ad_tags ){
                foreach ( $ad_tags as $ad_tag ) {

                    $ad_tag_options = $advertising->ad_tag->get_options( $ad_tag->ID );

                    printf(
                        '<option data-ad-type="%1$s" value="%2$s">(#%2$s) %3$s</option>',
                        esc_attr( $ad_tag_options['ad_type'] ),
                        esc_attr( $ad_tag->ID ),
                        esc_html( $ad_tag->post_title )
                    );
                }
            }
            ?>
        </select>
    </p>

    <p class="field-group">
        <label for="ad_placement"><strong><?php esc_html_e( 'Placement', 'streamtube-core' );?></strong></label>
        
        <select id="ad_placement" name="ad_placement" class="regular-text input-field w-100">
            
            <?php foreach ( $advertising->ad_schedule->placement as $key => $value ): ?>
                    
                <?php printf(
                    '<option value="%s">%s</option>',
                    esc_attr( $key ),
                    esc_html( $value )
                );?>

            <?php endforeach; ?>

        </select>
    </p>

    <p class="field-group group-ad_position d-none">
        <label for="ad_position">
            <strong><?php esc_html_e( 'Position', 'streamtube-core' );?></strong>
        </label>
        <input id="ad_position" name="ad_position" type="text" class="regular-text input-field w-100">
        <span class="description">
            <?php printf(
                esc_html__( 'Set ad position, e.g: %s', 'streamtube-core' ),
                '<strong>00:00:05</strong>'
            );?>
        </span>
    </p>

    <p class="field-group">
        <?php printf(
            '<button id="button-add-ad_tag" type="button" class="button button-primary w-100 d-block" data-text-added="%s">',
            esc_attr__( 'Added', 'streamtube-core' )
        );?>
            <?php esc_html_e( 'Add', 'streamtube-core' );?>
        </button>
    </p>

</div>

<script type="text/javascript">
    jQuery(function () {
        jQuery( '#ad_tag' ).select2();
    });
</script>