<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD_COMMENTS', true );

streamtube_core_load_template( 'comment/table-comments.php', true, array() );