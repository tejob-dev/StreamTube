<?php
if( ! defined('ABSPATH' ) ){
    exit;
}?>
<div class="search_user">
    <form class="search-form" method="get">
        <div class="input-group-wrap position-relative w-100">
            
            <?php printf(
                '<input class="form-control border-0 shadow-none" name="search_query" type="text" placeholder="%s" aria-label="%s" value="%s">',
                esc_attr__( 'Search users ...', 'streamtube-core' ),
                esc_attr__( 'Search ...', 'streamtube-core' ),
                isset( $_GET['search_query'] ) ? esc_attr( $_GET['search_query'] ) : ''
            )?>

            <input type="hidden" name="page_id" value="<?php the_ID(); ?>">

            <button class="btn border-0 shadow-none btn-main text-muted" type="submit">
                <span class="icon-search"></span>
            </button>
        </div>
    </form>
</div>