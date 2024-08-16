<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="google-analytics-wrap google-search-console-wrap">

	<?php if( current_user_can( apply_filters( 'streamtube/core/googlesitekit/search_console/perm', 'administrator' ) ) ){
		if( defined( 'STREAMTUBE_CORE_IS_DASHBOARD' ) ){
			include_once( 'search-queries.php' );
		}
	}?>	

</div>