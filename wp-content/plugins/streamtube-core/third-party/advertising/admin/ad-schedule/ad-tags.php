<?php
/**
 *
 * The Ad Tags metabox template file
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

global $post;

wp_enqueue_style( 'select2' );
wp_enqueue_script( 'select2' );

add_thickbox();

$advertising    = streamtube_core()->get()->advertising;
    
$options        = $advertising->ad_schedule->get_options( $post->ID );

?>
<div class="metabox-wrap">

    <div class="field-group">
        <label for="enable">
            <?php printf(
                '<input id="enable" type="checkbox" name="%s" %s>',
                $advertising->ad_schedule->get_field( 'enable' ),
                checked( $options['enable'] , 'on', false )
            );?>
            <?php esc_html_e( 'Enable this schedule', 'streamtube-core' ); ?>
        </label>
    </div>   

    <div class="field-group">
        <label for="schedule_ad_tag"><?php esc_html_e( 'Ad Tag URL', 'streamtube-core' ); ?></label>
        <?php printf(
            '<input readonly onclick="javascript:this.select()" id="schedule_ad_tag" class="regular-text input-field" type="text" value="%s">',
            esc_attr( get_permalink( $post->ID ) )
        );?>
        <p>
            <?php esc_html_e( 'Your Ad Tag URL, you can share with anyone who has a video player that supports VMAP.', 'streamtube-core' );?>
        </p>
    </div>    

    <div class="field-group">
        <label for="start_date"><?php esc_html_e( 'Start Date', 'streamtube-core' ); ?></label>
        <?php printf(
            '<input id="start_date" class="regular-text input-field" type="datetime-local" name="%s" value="%s">',
            $advertising->ad_schedule->get_field( 'start_date' ),
            esc_attr( $options['start_date'] )
        );?>
    </div>

    <div class="field-group">
        <label for="end_date"><?php esc_html_e( 'End Date', 'streamtube-core' ); ?></label>
        <?php printf(
            '<input id="start_date" class="regular-text input-field" type="datetime-local" name="%s" value="%s">',
            $advertising->ad_schedule->get_field( 'end_date' ),
            esc_attr( $options['end_date'] )
        );?>
    </div>

    <div class="field-group">
        <label for="cache_expiry"><?php esc_html_e( 'Cache Expiry', 'streamtube-core' ); ?></label>
        <?php printf(
            '<input id="cache_expiry" class="regular-text input-field" type="number" name="%s" value="%s">',
            $advertising->ad_schedule->get_field( 'cache_expiry' ),
            esc_attr( $options['cache_expiry'] )
        );?>
        <?php esc_html_e( 'Set the cache expiration in seconds. For example, 60 represents one minute, and 0 means no caching.', 'streamtube-core' ); ?>
    </div>

    <div class="field-group">
        <label for="alias_schedule">
            <?php printf(
                '<input id="alias_schedule" type="checkbox" name="%s" %s>',
                $advertising->ad_schedule->get_field( 'alias_schedule' ),
                checked( $options['alias_schedule'] , 'on', false )
            );?>
            <?php esc_html_e( 'Enable this option if this schedule is a reference to an external AdTag.', 'streamtube-core' ); ?>
        </label>

        <p class="description">
            <?php printf(
                esc_html__( 'This schedule must contain %s in the %s position, and the added AdTag must contain %s.', 'streamtube-core' ),
                '<strong style="color:red">'. esc_html__( 'only one AdTag', 'streamtube-core' ) .'</strong>',
                '<strong style="color:red">'. esc_html__( 'Preroll', 'streamtube-core' ) .'</strong>',
                '<strong style="color:red">'. esc_html__( 'an URL of an external AdTag', 'streamtube-core' ) .'</strong>'
            )?>
        </p>
    </div>

    <p>
        <?php printf(
            '<a href="%1$s" class="thickbox button button-primary" name="%2$s">%2$s</a>',
            '#TB_inline?&width=500&height=320&inlineId=add-ad-tag-thickbox',
            esc_html__( 'Insert Ad', 'streamtube-core' )
        );?>
    </p>

    <?php include_once plugin_dir_path( __FILE__ ) . 'ad-tag-tables.php';?>

    <?php wp_nonce_field( $advertising->ad_schedule::NONCE, $advertising->ad_schedule::NONCE ); ?>
</div>

<div id="add-ad-tag-thickbox" style="display:none;">
    <?php include_once plugin_dir_path( __FILE__ ) . 'add-ad-tag-form.php';?>
</div>