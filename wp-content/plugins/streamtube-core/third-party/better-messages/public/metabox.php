<?php
/**
 *
 * The Better Messages metabox template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$settings = streamtube_core()->get()->better_messages->get_post_settings( $post->ID );

?>
<div class="mb-2">
    <label class="form-check-label">
        <?php printf(
            '<input type="checkbox" name="bpbm[enable]" %s>',
            checked( $settings['enable'], 'on', false )
        );?>

        <?php esc_html_e( 'Enable Live Chat', 'streamtube-core' );?><br/>
    </label>
</div>

<div class="mb-2">
    <label class="form-check-label">
        <?php printf(
            '<input type="checkbox" name="bpbm[disable_reply]" %s>',
            checked( $settings['disable_reply'], 'on', false )
        );?>

        <?php esc_html_e( 'Disable Reply, Close Live Chat.', 'streamtube-core' );?><br/>
    </label>
</div>

<?php
if( class_exists( 'Better_Messages_Chats' ) ){
    Better_Messages_Chats()->bpbm_chat_settings( $post );
}
