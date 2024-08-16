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

wp_enqueue_script( 'jquery-ui-sortable' );
wp_enqueue_script( 'jquery-ui-dropable' );

foreach ( $advertising->ad_schedule->placement as $placement => $text ): ?>
    <?php printf(
        '<table class="form-table table-ad_tags table-ad_tags-%1$s" id="table-ad_tags-%1$s" data-table-placement="%1$s">',
        esc_attr( $placement )
    );?>
        <thead>
            <tr>
                <th colspan="4"><strong><?php echo $text; ?></strong></th>
            </tr>
        </thead>

        <tbody>
            <?php 
            $ad_tags = $advertising->ad_schedule->get_ad_tags_by_placement( $post->ID, $placement );

            if( $ad_tags ){
                for ( $i=0; $i < count( $ad_tags ); $i++) { 

                    $ad_tag_options = $advertising->ad_tag->get_options( $ad_tags[$i]['ad_tag'] );

                    ?>
                    <tr class="ad_tag_row" id="ad_tag_row_<?php echo esc_attr( $ad_tags[$i]['ad_tag'] ); ?>">
                        <td class="ad_tag_index">
                            <?php echo $i+1; ?>
                        </td>
                        <td class="ad_tag_text">
                            <strong>
                            <?php printf(
                                '(#%s) %s',
                                $ad_tags[$i]['ad_tag'],
                                get_the_title( $ad_tags[$i]['ad_tag'] )
                            ); ?>
                            </strong>

                            <?php printf(
                                '<input class="field-ad_tag_id" type="hidden" name="ad_schedule[ad_tags][id][]" value="%s">',
                                esc_attr( $ad_tags[$i]['ad_tag'] )
                            );?>

                            <?php printf(
                                '<input class="field-ad_tag_placement" type="hidden" name="ad_schedule[ad_tags][placement][]" value="%s">',
                                esc_attr( $placement )
                            );?>

                        </td>

                        <td class="ad_tag_type">
                            <?php printf(
                                esc_html__( '%s (%s)', 'streamtube-core' ),
                                $ad_tag_options['ad_type'],
                                '<strong>'. $advertising->ad_schedule->get_time_offset( $ad_tags[$i], $placement ) .'</strong>'
                            ); ?>
                            <p>
                            <?php printf(
                                '<input class="regular-text field-ad_tag_position" type="text" name="ad_schedule[ad_tags][position][]" placeholder="%s" value="%s">',
                                esc_html__( 'Position e.g: 00:00:10 or 10', 'streamtube-core' ),
                                $advertising->ad_schedule->get_time_offset( $ad_tags[$i], $placement )
                            );?>
                            </p>
                        </td>

                        <td class="ad_tag_button">
                            <button title="<?php esc_attr_e( 'Remove Ad Tag', 'streamtube-core' ); ?>" type="button" class="button button-small button-delete">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </td>                            
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
<?php endforeach;?>
<script>
    jQuery( function($) {
        $( ".table-ad_tags tbody" ).sortable({
            connectWith: '.table-ad_tags tbody',
            receive: function( event, ui ) {
                //console.log( $( event.target ).closest( 'table' ) );
                var targetPlacement = $( ui.item.closest( 'table' ) )[0].getAttribute( 'data-table-placement' );

                $( ui.item ).find( 'input.field-ad_tag_placement' ).val( targetPlacement );

                if( targetPlacement == 'midroll' ){
                    $( ui.item ).find( 'input.field-ad_tag_position' ).removeAttr( 'readonly' ).val('');

                    $( ui.item ).find( 'input.field-ad_tag_placement' ).val( targetPlacement );
                }
            },
            update: function( event, ui ) {
                $(this).children().each(function(index) {
                $(this).find('td').first().html(index + 1);
                });
            }            
        }).disableSelection();
    } );
</script>