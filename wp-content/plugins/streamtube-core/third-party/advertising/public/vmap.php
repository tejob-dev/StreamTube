<?php
header('Content-Type: application/xml; charset=utf-8');
/**
 *
 * The VMAP template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.3
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */

$advertising = streamtube_core()->get()->advertising;

extract( $args );

$expiration = (int)$expiration;

if( $expiration != 0 && false !== $cache = get_transient( 'ad_schedule_' . $schedule_id ) ){
    echo $cache;
    return;
}

if( $alias_schedule ){
    $ad_tags = $advertising->ad_schedule->get_ad_tags_by_placement( $schedule_id, 'preroll' );

    if( is_array( $ad_tags ) and isset( $ad_tags[0] ) ){

        $output = $advertising->ad_schedule->get_vmap_content( $ad_tags[0]['ad_tag'], false );

        if( $output && $expiration != 0 ){
            set_transient( 'ad_schedule_' . $schedule_id, $output, $expiration );
        }

        echo $output;
        
        return;
    }
}

ob_start();

?>
<?php printf(
    '<vmap:VMAP xmlns:vmap="%s" version="%s">',
    esc_url( $advertising->ad_schedule::VMAP_URL ),
    esc_attr( $advertising->ad_schedule::VMAP_VERSION )
)?>

    <?php foreach ( $advertising->ad_schedule->placement as $placement => $text ) : ?>

        <?php
        $ad_tags = $advertising->ad_schedule->get_ad_tags_by_placement( $schedule_id, $placement );

        if( is_array( $ad_tags ) ): ?>
            <?php for ( $i = 0; $i < count( $ad_tags ); $i++) : ?>

                <?php 
                if( $ad_tags[$i]['ad_tag_type'] == 'vast' ):
                    printf(
                        '<vmap:AdBreak timeOffset="%s" breakType="linear" breakId="%s">',
                        $advertising->ad_schedule->get_time_offset( $ad_tags[$i], $placement ),
                        $placement
                    );?>
                        <?php printf(
                            '<vmap:AdSource id="%1$s-ad-%2$s" allowMultipleAds="false" followRedirects="true">',
                            esc_attr( $placement ),
                            $i+1
                        );?>
                            <vmap:AdTagURI templateType="vast3">
                                <![CDATA[ <?php echo get_permalink( $ad_tags[$i]['ad_tag'] ); ?> ]]>
                            </vmap:AdTagURI>
                        </vmap:AdSource>
                    </vmap:AdBreak>
                <?php else:?>

                    <?php echo $advertising->ad_schedule->get_vmap_content( $ad_tags[$i]['ad_tag'] ); ?>

                <?php endif;?>

            <?php endfor;?>

        <?php endif;?>        
        
    <?php endforeach; ?>

</vmap:VMAP>
<?php

$output = ob_get_clean();

if( $expiration != 0 && $output ){
    set_transient( 'ad_schedule_' . $schedule_id, $output, $expiration );
}

echo $output;