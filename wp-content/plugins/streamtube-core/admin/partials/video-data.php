<?php
/**
 *
 * The admin metabox template file.
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post, $streamtube;

wp_enqueue_style( 'select2' );
wp_enqueue_script( 'select2' );
?>
<div class="metabox-wrap">

    <?php 
    /**
     * Fires before video metadata fields.
     *
     * @param $object $post
     */
    do_action( 'streamtube/core/admin/metabox/videodata/before', $post );
    ?>

    <?php if( wp_cache_get( "streamtube:license" ) ): ?>

    <div class="field-group">
        <label for="disable_ad">
        
            <?php printf(
                '<input type="checkbox" name="disable_ad" id="disable_ad" class="input-field" %s>',
                $streamtube->get()->post->is_ad_disabled() ? 'checked' : ''
            );?>
            <?php esc_html_e( 'Disable Advertising', 'streamtube-core' ); ?>
            
        </label>
    </div>    

    <div class="field-group">

        <label for="ad_schedules"><?php esc_html_e( 'Ad Schedules', 'streamtube-core' ); ?></label>

        <?php printf(
            '<select multiple="multiple" class="search-ads select-select2" id="ad_schedules" name="%s" data-placeholder="%s">',
            'ad_schedules[]',
            esc_html__( 'Search Ads', 'streamtube-core' )
        );?>

            <?php
            $ad_schedules = $streamtube->get()->post->get_ad_schedules();

            if( $ad_schedules ){
                for ( $i=0; $i < count( $ad_schedules ); $i++) { 
                    if( $ad_schedules[$i] ){
                        printf(
                            '<option value="%1$s" selected>(#%1$s) %2$s</option>',
                            esc_attr( $ad_schedules[$i] ),
                            esc_html( get_the_title( $ad_schedules[$i] ) )
                        );
                    }
                }
            }
            ?>
        </select>
        <p class="description">
            <?php esc_html_e( 'Search Results only includes the Active Ads.', 'streamtube-core' );?>
        </p>        
    </div>

    <?php endif;?>

    <div class="field-group">
        <label for="video_trailer"><?php esc_html_e( 'Trailer', 'streamtube-core' ); ?></label>
        <?php printf(
            '<textarea name="video_trailer" id="video_trailer" class="regular-text input-field">%s</textarea>',
            esc_textarea( $streamtube->get()->post->get_video_trailer() )
        );?>
        <p class="description">
            <?php esc_html_e( 'Upload a video file or paste a link/iframe code', 'streamtube-core' );?>
        </p>
        <button id="upload-file" type="button" class="button button-large button-primary button-upload w-100" data-media-type="video" data-media-source="id">
            <?php esc_html_e( 'Upload a file', 'streamtube-core' );?>
        </button>
    </div>    

    <div class="field-group">
        <label for="video_url"><?php esc_html_e( 'Media Id (Main Video Source)', 'streamtube-core' ); ?></label>
        
        <?php printf(
            '<textarea name="video_url" id="video_url" class="regular-text input-field">%s</textarea>',
            esc_textarea( $streamtube->get()->post->get_source() )
        );?>

        <p class="description">
            <?php esc_html_e( 'Upload a video file or paste a link/iframe code', 'streamtube-core' );?>
        </p>

        <button id="upload-file" type="button" class="button button-large button-primary button-upload w-100" data-media-type="video" data-media-source="id">
            <?php esc_html_e( 'Upload a file', 'streamtube-core' );?>
        </button>

        <?php if( function_exists( 'wp_cloudflare_stream' ) ): ?>

            <div style="margin: 0 auto;text-align: center;margin-bottom: 1rem;">
                <p><?php esc_html_e( 'OR', 'streamtube-core' );?></p>
            </div>

            <div style="display: flex; gap: 1rem">
                <?php WP_Cloudflare_Stream_Admin::start_live_stream( $post ); ?>

                <?php if( "" != $live_status = get_post_meta( $post->ID, 'live_status', true ) ): ?>

                    <?php printf(
                        '<button id="close-open-live" type="button" class="d-block w-100 button button-large button-%s" data-status="%s" data-post-id="%s" data-action="admin_close_open_live_stream">%s</button>',
                        $live_status != 'close' ? 'secondary' : 'primary',
                        esc_attr( $live_status ),
                        $post->ID,
                        $live_status != 'close' ? esc_html__( 'Close Live Stream', 'streamtube-core' ) : esc_html__( 'Open Live Stream', 'streamtube-core' )
                    );?>

                <?php endif;?>
            </div>
        <?php endif; ?>
    </div>

    <div class="field-groups">

        <div class="field-group">
            <label for="length"><?php esc_html_e( 'Upcoming Date', 'streamtube-core' ); ?></label>

            <?php printf(
                '<input type="datetime-local" name="_upcoming_date" id="_upcoming_date" class="regular-text" value="%s">',
                esc_attr( $streamtube->get()->post->get_upcoming_date( $post->ID ) )
            );?>
        </div>        

        <div class="field-group">
            <label for="length"><?php esc_html_e( 'Video Length', 'streamtube-core' ); ?></label>

            <?php printf(
                '<input type="text" name="length" id="length" class="regular-text" value="%s">',
                esc_attr( $streamtube->get()->post->get_length( $post->ID ) )
            );?>
        </div>

        <div class="field-group">
            <label for="aspect_ratio"><?php esc_html_e( 'Aspect Ratio', 'streamtube-core' ); ?></label>

            <select id="aspect_ratio" name="aspect_ratio" class="regular-text">

                <?php 

                $ratio_default = array(
                    '' =>  esc_html__( 'Default', 'streamtube-core' )
                );

                $ratios = streamtube_core_get_ratio_options();

                $ratios = array_merge( $ratio_default, $ratios );

                foreach ( $ratios as $key => $value ): ?>
                        
                        <?php printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $key ),
                            selected( $streamtube->get()->post->get_aspect_ratio( $post->ID ), $key, false ),
                            esc_html( $value )
                        );?>

                <?php endforeach ?>

            </select>
        </div>

        <div class="field-group">
            <label for="vr">
                <?php printf(
                    '<input type="checkbox" name="vr" id="vr" %s>',
                    checked( $streamtube->get()->post->is_video_vr( $post->ID ), 'vr', false )
                );?>
                <?php esc_html_e( '360 Degree Video', 'streamtube-core' ); ?>
            </label>
            <p class="description">
                <?php esc_html_e( 'Enable 360/VR Video Compatibility', 'streamtube-core' );?>
            </p>
        </div>        

    </div>

    <?php 
    /**
     * Fires after video metadata fields.
     *
     * @param $object $post
     */
    do_action( 'streamtube/core/admin/metabox/videodata/after', $post );
    ?>

    <?php
    wp_nonce_field( $this->nonce, $this->nonce );
    ?>
    <script type="text/javascript">
        jQuery( document ).ready(function() {
            jQuery( '.search-ads' ).select2({
                allowClear : true,
                minimumInputLength : 1,
                ajax : {
                    url : "<?php echo admin_url( 'admin-ajax.php' )?>",
                    delay: 250,
                    data: function (params) {
                        var query = {
                            s: params.term,
                            post_type : 'ad_schedule',
                            action: 'search_ads',
                            responseType : 'select2',
                            _wpnonce : '<?php echo wp_create_nonce( '_wpnonce' );?>'
                        }

                        return query;
                    },
                    processResults: function ( data, params ) {

                        params.page = params.page || 1;

                        return {
                            results: data.data.results,
                            pagination: {
                                more: (params.page * 20) < data.pagination
                            }                        
                        };
                    }
                }
            });
        });
    </script>
</div>