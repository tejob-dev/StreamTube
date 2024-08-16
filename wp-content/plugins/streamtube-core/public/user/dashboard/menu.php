<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
?>
<div class="col_sidebar sidebar-secondary navbar-dark p-0">
    <div class="main-nav float-nav">
        <?php
        streamtube_core()->get()->user_dashboard->the_menu( array(
            'base_url'      =>  get_author_posts_url( get_current_user_id() ),
            'user_id'		=>	get_current_user_id(),
            'menu_classes'  =>  'flex-column secondary-nav nav-tab navbar-nav',
            'item_classes'  =>  'px-3 py-2 d-flex align-items-center rounded-0',
            'icon'          =>  true
        ) );
        ?>
    </div>
</div>