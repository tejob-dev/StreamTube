<?php
/**
 *
 * The post live chat box template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$better_messages 	= streamtube_core()->get()->better_messages;

// Get live chat settings
$settings 			= $better_messages->get_post_settings( $post->ID );


// Get avatar size
$avatar_size 		= (int)$settings['avatar_size'];

/**
 *
 * Filter avatar size
 * 
 * @param int $avatar_size
 *
 * @since 2.1.7
 */
$avatar_size	= apply_filters( 'streamtube/core/better_messages/livechat/avatar_size', $avatar_size, $settings );
?>
<div class="live-chatbox-wrap mb-4">
	<?php $better_messages->get_chat_room_output( $post->ID, true ); ?>
</div>
<?php
// end of file