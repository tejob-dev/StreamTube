<?php
$yt_importer    = streamtube_core()->get()->yt_importer;
$is_existed     = $yt_importer->is_existed( $yt_importer->api->search->get_item_id( $args ), $_POST['post_ID'] );

?>
<?php printf(
    '<li class="d-flex" data-importer-id="%s" data-item-id="%s">',
    esc_attr(  $_POST['post_ID'] ),
    esc_attr( $yt_importer->api->search->get_item_id( $args ) )
);?>
    
    <div class="yt-cb">
        <?php printf(
            '<input %1$s type="checkbox" name="yt_ids[]" value="%2$s" data-item-id="%2$s" data-importer-id="%3$s">',
            $is_existed ? 'checked readonly disabled' : 'checked',
            esc_attr( $yt_importer->api->search->get_item_id( $args ) ),
            esc_attr( $args['importer_id'] )
        );?>
    </div>

    <div class="yt-thumbnail">
        <a target="_blank" href="<?php echo esc_url( $yt_importer->api->search->get_item_url( $args ) ); ?>">
            <?php printf(
                '<img src="%s">',
                $yt_importer->api->search->get_item_thumbnail_url( $args )
            );?>
        </a>
    </div>

    <div class="yt-content">
        <h3 class="yt-title">
            <?php printf(
                '<a target="_blank" href="%s">%s</a>',        
                esc_url( $yt_importer->api->search->get_item_url( $args ) ),
                $yt_importer->api->search->get_item_title( $args ),
            );?>
        </h3>

        <p class="yt-channel"><?php printf(
            esc_html__( 'By %s', 'streamtube-core' ),
            sprintf(
                '<a target="_blank" href="%s">%s</a>',
                esc_url( $yt_importer->api->search->get_item_channel_url( $args ) ),
                $yt_importer->api->search->get_item_channel_title( $args )
            )
        )?></p>

        <p class="yt-date">
            <?php printf(
                '%s (%s)',
                $yt_importer->api->search->get_item_published_at( $args ),
                sprintf(
                    esc_html__( '%s ago', 'streamtube-core' ),
                    human_time_diff( current_time( 'timestamp' ), strtotime( $yt_importer->api->search->get_item_published_at( $args ) ) )
                )
            ) ?>
        </p>

    </div>

    <div class="yt-button">
        <?php printf(
            '<button type="button" class="button button-primary button-yt-import" data-importer-id="%s" data-item-id="%s" %s>',
            esc_attr(  $_POST['post_ID'] ),
            esc_attr( $yt_importer->api->search->get_item_id( $args ) ),
            $is_existed ? 'readonly disabled' : '',
        );?>

            <span class="spinner"></span>

            <?php if( $is_existed ){
                esc_html_e( 'Imported', 'streamtube-core' );
            }else{
                esc_html_e( 'Import', 'streamtube-core' );
            }?>
        </button>               
    </div>     

</li>