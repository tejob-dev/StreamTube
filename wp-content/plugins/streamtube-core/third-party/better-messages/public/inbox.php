<?php
/**
 *
 * The Inbox template file
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD_INBOX' , true );

?>

<div class="widget widget-inbox">
	<?php echo do_shortcode( '[bp_better_messages]' ); ?>
</div>