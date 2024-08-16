<?php
/**
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

if( did_action( 'streamtube/core/widget/live_chat_comments_template/loaded' ) ){
	return;
}

$tabs = array(
	'livechat'	=>	sprintf(
		'%s %s',
		'<span class="dot"></span>',
		esc_html__( 'Live Chat', 'streamtube-core' )
	)
);
	
if( ! comments_open() && ! get_comments_number() ){
	return load_template( plugin_dir_path( __FILE__ ) . 'template-livechat.php' );
}

$tabs['comments'] = esc_html__( 'Comments', 'streamtube-core' );

?>

<div class="comments-livechat mb-4">
	<nav class="comments-livechat-tabs">
		<div class="nav-fill nav nav-tabs" id="nav-tab" role="tablist">
			<?php foreach ( $tabs as $tab => $value ): ?>
	    		<?php printf(
	    			'<button class="nav-link %1$s" id="nav-%2$s-tab" data-bs-toggle="tab" data-bs-target="#nav-%2$s" type="button" role="tab" aria-controls="nav-%2$s" aria-selected="true">',
	    			$tab == array_keys( $tabs )[0] ? 'active' : '',
	    			esc_attr( $tab )
	    		);?>
	    			<span class="position-relative">
	    				<?php echo $value; ?>
	    			</span>
	    		</button>
			<?php endforeach; ?>
		</div>
	</nav>

	<div class="tab-content">

		<?php foreach ( $tabs as $tab => $value ): ?>
			<?php printf(
				'<div class="tab-pane fade show %1$s" id="nav-%2$s" role="tabpanel" aria-labelledby="nav-%2$s-tab">',
				$tab == array_keys( $tabs )[0] ? 'active' : '',
				esc_attr( $tab )
			);?>
				<?php load_template( plugin_dir_path( __FILE__ ) . 'template-'. sanitize_file_name( $tab ) .'.php', false );?>
			</div>
		<?php endforeach; ?>
		
	</div>
</div>

<?php

do_action( 'streamtube/core/widget/live_chat_comments_template/loaded' );