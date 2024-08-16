<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$post_status = isset( $_REQUEST['post_status'] ) && is_string( $_REQUEST['post_status'] ) ? wp_unslash( $_REQUEST['post_status'] ) : 'any';
?>
<div class="search-form" method="get">
    <div class="input-group">

        <span class="input-group-text p-0 m-0 border-0">
            <select name="post_status" class="form-select">
                <?php
                foreach ( $args as $status => $text ) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $status ),
                        $post_status == $status ? 'selected' : '',
                        esc_html( $text )
                    );
                }
                ?>
            </select>
        </span>

        <?php printf(
            '<input class="form-control outline-none shadow-none rounded-1" name="search_query" type="text" placeholder="%s" aria-label="%s" value="%s">',
            esc_attr__( 'Search ...', 'streamtube-core' ),
            esc_attr__( 'Search ...', 'streamtube-core' ),
            isset( $_REQUEST['search_query'] ) && is_string( $_REQUEST['search_query'] ) ? esc_attr( wp_unslash($_REQUEST['search_query']) ) : ''
        )?>
        <button class="btn border-0 shadow-none btn-main text-muted p-1" type="submit" name="submit" value="search">
            <span class="icon-search"></span>
        </button>
    </div>

    <?php if( ! get_option( 'permalink_structure' ) ) :?>

        <?php printf(
            '<input type="hidden" name="author" value="%s">',
            esc_attr( get_queried_object_id() )
        );?>

        <?php printf(
            '<input type="hidden" name="dashboard" value="%s">',
            'videos'
        );?>

    <?php endif;?>

</div>