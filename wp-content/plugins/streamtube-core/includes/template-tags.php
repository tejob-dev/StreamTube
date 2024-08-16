<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 * The Pre placehoder
 */
function streamtube_core_preplaceholder( $wrap_classes = array(), $row_classes = array(), $args = array() ){

    $args = wp_parse_args( $args, array(
        'thumbnail_ratio'   =>  '16x9',
        'author_avatar'     =>  false,
        'layout'            =>  'grid',
        'slide_rows'        => 1
    ) );

    extract( $args );

    ?>
    <div class="preplacehoder overflow-hidden">

        <?php printf(
            '<div class="%s">',
            esc_attr( join( ' ', $wrap_classes )  )
        );?>
            <div class="<?php echo join( ' ', $row_classes );?>">

                <?php for( $i = 0; $i < $col_xxl * absint( $slide_rows ); $i++ ): ?>

                    <?php printf(
                        '<div class="post-item %s mb-%s">',
                        $layout != 'grid' ? 'p-0' : '',
                        esc_attr( $margin_bottom )
                    );?>
                        <div class="ph-item p-0 m-0">

                            <?php if( $layout == 'grid' ): ?>

                            <div class="ph-col-12 p-0 m-0">
                                <div class="post-thumbnail ratio ratio-<?php echo esc_attr( $thumbnail_ratio ); ?> rounded overflow-hidden">
                                    <div class="ph-picture"></div>
                                </div>
                                <div class="ph-row mb-3">
                                </div>
                            </div>

                            <?php if( $author_avatar ): ?>
                                <div class="ph-col-2">
                                    <div class="ph-avatar"></div>
                                </div>
                            <?php endif;?>

                            <div>
                                <div class="ph-row">
                                    <div class="ph-col-12"></div>
                                    <div class="ph-col-2"></div>
                                </div>
                            </div>
                            <?php else:?>
                                <div class="ph-col-2 p-0">
                                    <div class="post-thumbnail ratio ratio-<?php echo esc_attr( $thumbnail_ratio ); ?> rounded overflow-hidden">
                                        <div class="ph-picture"></div>
                                    </div>                                    
                                </div>

                                <div>
                                    <div class="ph-row">
                                        <div class="ph-col-10 big"></div>
                                        <div class="ph-col-2 empty big"></div>
                                        <div class="ph-col-4"></div>
                                        <div class="ph-col-8 empty"></div>
                                    </div>
                                </div>
                            <?php endif;?>

                        </div><!--.ph-item-->

                    </div><!--.post-item-->

                <?php endfor;?>
            </div><!--.end row-->
        </div><!--.end wrapper-->
    </div><!--end placehoder-->
    <?php
}