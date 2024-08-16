<?php
header('Content-Type: application/xml; charset=utf-8');
/**
 *
 * The Image Vast template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.3
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
extract( $args );

$ad_tag = streamtube_core()->get()->advertising->ad_tag;
?>
<VAST version="3.0">
    <?php printf( '<Ad id="%s">', $ad_tag_id ); ?>
        <InLine>
            <AdSystem> <?php echo $ad_system; ?> </AdSystem>
            <AdTitle> <?php echo $ad_title; ?> </AdTitle>

            <?php if( $ad_description ): ?>
            <Description> <?php echo $ad_description; ?> </Description>
            <?php endif?>

            <Creatives>
                <Creative sequence="1">
                    <NonLinearAds>

                        <?php printf(
                            '<NonLinear apiFramework="VPAID" width="%s" height="%s" id="overlay-1">',
                            $ad_image_data ? $ad_image_data[1] : '0',
                            $ad_image_data ? $ad_image_data[2] : '0'
                        );?>

                            <AdParameters>
                                <![CDATA[ <?php echo json_encode( $ad_params );?> ]]>
                            </AdParameters>

                            <StaticResource creativeType="application/javascript">
                                <![CDATA[ <?php echo $scripts_url; ?> ]]>
                            </StaticResource>

                            <?php if( $ad_target_url ): ?>
                                <NonLinearClickThrough>
                                    <![CDATA[ <?php echo $ad_target_url; ?> ]]>
                                </NonLinearClickThrough>
                            <?php endif;?>

                        </NonLinear>
                    </NonLinearAds>
                </Creative>
            </Creatives>
        </InLine>
    </Ad>
</VAST>