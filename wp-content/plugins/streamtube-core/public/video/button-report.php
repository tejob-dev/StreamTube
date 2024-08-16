<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="button-group button-group-report">
    <?php printf(
        '<button class="btn shadow-none px-1" data-bs-toggle="modal" data-bs-target="#modal-%s" title="%s">',
        ! is_user_logged_in() ? 'login' : 'report',
        esc_attr__( 'Report', 'streamtube-core' )
    );?>
        <span class="btn__icon icon-flag-empty"></span>
    </button>
</div>