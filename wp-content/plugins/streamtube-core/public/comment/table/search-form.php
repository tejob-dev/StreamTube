<div class="search-form">
    <div class="input-group">
        <?php printf(
            '<input class="form-control outline-none shadow-none rounded-1" name="search_query" type="text" placeholder="%s" aria-label="%s" value="%s">',
            esc_attr__( 'Search comments...', 'streamtube-core' ),
            esc_attr__( 'Search comments ...', 'streamtube-core' ),
            isset( $_GET['search_query'] ) ? esc_attr( $_GET['search_query'] ) : ''
        )?>
        <button class="btn border-0 shadow-none btn-main text-muted" type="submit" name="submit" value="search">
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
            'comments'
        );?>

    <?php endif;?>

    <?php printf(
        '<input type="hidden" name="comment_status" value="%s">',
        isset( $_GET['comment_status'] ) ? $_GET['comment_status'] : ''
    );?>    

</div>