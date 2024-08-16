<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$param_name = 'orderby';

$options = streamtube_core_get_user_sortby_options();

$current = array_keys($options)[0];

if( isset( $_GET[$param_name] ) && array_key_exists( $_GET[$param_name] , $options ) ){
	$current = $_GET[$param_name];
}
?>

<div class="sortby dropdown">
    <button class="btn shadow-none dropdown-toggle text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
        <?php printf(
            esc_html__( 'Sort by %s', 'streamtube-core' ),
            '<strong>'.$options[ $current ].'</strong>'
        );?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end animate slideIn">
    	<?php foreach( $options as $k => $v ){
    		printf(
    			'<li><a class="dropdown-item small text-capitalize %s" href="%s">%s</a></li>',
    			$current == $k ? 'active' : '',
    			esc_url( add_query_arg( array(
    				$param_name	=>	$k
    			) ) ),
    			$v
    		);
    	}?>
    </ul>
</div>