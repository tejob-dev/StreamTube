<?php
/**
 *
 * The post settings template file
 * 
 * @link       https://1.envato.market/mgXE4y
 * @since      2.1.7
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$post_id = $post->ID;

$better_messages = streamtube_core()->get()->better_messages;

?>
<form class="form-ajax form-add-post" method="post">
    <div class="widget widget-live-chat-settings">
        <div class="widget-title-wrap d-flex bg-white sticky-top border p-3">

            <div class="d-none d-sm-block group-title flex-grow-1">
                <h4 class="page-title h4">
                    <?php esc_html_e( 'Live Chat', 'streamtube-core' ); ?>
                </h4>
            </div>

            <div class="ms-md-auto">
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-3" name="submit" value="update">
                    	<span class="btn__icon icon-floppy"></span>
                        <span class="btn__text">
                            <?php esc_html_e( 'Update', 'streamtube-core' )?>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="widget-content">

			<?php

            if( $better_messages->can_user_enable_full_live_chat() && $post_id ){
                echo '<input type="hidden" name="bpbm[allow_guests]">';              
                $better_messages->get_post_settings_fields( get_post( $post_id ) );
            }else{

                $settings = $better_messages->get_post_settings( $post_id );

                streamtube_core_the_field_control( array(
                    'label'         =>  esc_html__( 'Enable Live Chat', 'streamtube-core' ),
                    'type'          =>  'checkbox',
                    'name'          =>  'bpbm[enable]',
                    'current'       =>  $settings['enable']
                ) );

                streamtube_core_the_field_control( array(
                    'label'         =>  esc_html__( 'Disable Reply, Close Live Chat.', 'streamtube-core' ),
                    'type'          =>  'checkbox',
                    'name'          =>  'bpbm[disable_reply]',
                    'current'       =>  $settings['disable_reply']
                ) );                

                /**
                 *
                 * @since 2.1.7
                 * 
                 */
                do_action( 'streamtube/core/better_messages/live_chat_settings', $settings, $post_id );
            }
            ?>

		    <?php printf(
		        '<input type="hidden" name="action" value="%s">',
		        $post_id ? 'update_post' : 'add_post'
		    );?>	

		    <?php printf(
		        '<input type="hidden" name="post_ID" value="%s">',
		        $post_id ? $post_id : ''
		    );?>

		    <?php printf(
		        '<input type="hidden" name="comment_status" value="%s">',
		        $post ? $post->comment_status : 'open'
		    );?>

            <?php 
            if( ! Streamtube_Core_Permission::moderate_posts() ){
                printf(
                    '<input type="hidden" name="bpbm[allow_guests]" value="%s">',
                   '1'
                );
            }?>

		</div>

	</div>
	
</form>