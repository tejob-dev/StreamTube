<?php

class StreamTube_Core_Customize_Control_Divider extends WP_Customize_Control {
	public $type = 'divider';
	/**
	* Render the control's content.
	*/
	public function render_content() {
		?>
		<div style="width: 100%; border-bottom: 2px solid #ddd"></div>
		<?php
	}
}