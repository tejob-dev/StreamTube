<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$types = streamtube_core()->get()->user_dashboard->get_upload_types();

if( ! $types || ! Streamtube_Core_Permission::can_upload() ){
    return;
}

$args = wp_parse_args( $args , array(
	'button_icon'	=>	'icon-videocam'
));

?>

<div class="header-user__upload">

	<?php if( count( $types ) > 1 ): ?>

	    <div class="dropdown">
	        
	        <button class="btn btn-submit shadow-none px-2 position-relative" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
	            <span class="btn__icon icon-videocam"></span>
	            <span class="dot"></span>
	        </button>

	        <?php streamtube_core_load_template( 'misc/upload-dropdown.php', false )?>
	    </div>

    <?php else: ?>

        <button class="btn btn-submit shadow-none px-3 position-relative" data-bs-toggle="modal" data-bs-target="#modal-<?php echo array_keys( $types )[0]; ?>">
            <?php printf(
            	'<span class="btn__icon %s"></span>',
            	esc_attr( $args['button_icon'] )
            );?>
            <span class="dot"></span>
        </button>

    <?php endif;?>

</div>