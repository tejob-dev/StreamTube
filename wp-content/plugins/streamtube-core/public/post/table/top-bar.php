<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
global $streamtube;

$post_statuses = $streamtube->get()->post->get_post_statuses_for_read();

?>
<div class="d-md-flex align-items-center mb-4">

	<?php streamtube_core_load_template( 'post/table/badge-filters.php', true, $post_statuses );?>

	<div class="search-form-wrap ms-auto">
		<?php streamtube_core_load_template( 'post/table/search-form.php', true, $post_statuses );?>
	</div>	
</div>