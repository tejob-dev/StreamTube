<?php
header('Content-Type: application/xml; charset=utf-8');
/**
 *
 * The Video Vast template file
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
                <Creative>
                    <?php printf(
                        '<Linear%s>',
                        $ad_skipoffset ? ' skipoffset="'.esc_attr( $ad_skipoffset ).'"' : ''
                    );?>

                        <?php if( $ad_video_duration ): ?>

                        <Duration> <?php echo $ad_video_duration; ?> </Duration>

                        <?php endif;?>

                        <?php if( $ad_target_url ): ?>
                            <VideoClicks>
                                <ClickThrough>
                                    <![CDATA[ <?php echo $ad_target_url; ?> ]]>
                                </ClickThrough>
                            </VideoClicks>
                        <?php endif;?>

                        <?php if( $ad_media_files ): ?>
                            <MediaFiles>
                            <?php foreach ( $ad_media_files as $video => $data ) :?>                    
                                <?php printf(
                                    '<MediaFile id="%s" delivery="progressive" type="%s" width="%s" height="%s" bitrate="%s" scalable="true" maintainAspectRatio="true">',
                                    esc_attr( $data['id'] ),
                                    esc_attr( get_post_mime_type( $data['id'] ) ),
                                    esc_attr( $data['meta']['width'] ),
                                    esc_attr( $data['meta']['height'] ),
                                    esc_attr( $data['meta']['bitrate'] )
                                );?>
                                    <![CDATA[ <?php echo $data['url']; ?> ]]>
                                </MediaFile>
                            <?php endforeach; ?>
                            </MediaFiles>
                        <?php endif;?>
                    </Linear>
                </Creative>
            </Creatives>
        </InLine>
    </Ad>
</VAST>