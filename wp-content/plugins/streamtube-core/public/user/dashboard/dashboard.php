<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

define( 'STREAMTUBE_CORE_IS_DASHBOARD', true );

$welcome_text = sprintf(
    esc_html__( 'Welcome back, %s!', 'streamtube-core' ),
    '<strong class="text-body">'. wp_get_current_user()->display_name .'</strong>'
);

/**
 *
 * Filter the Welcome Back text
 *
 * @since 2.2
 * 
 */
$welcome_text = apply_filters( 'streamtube/user/dashboard/welcome_text', $welcome_text );

$has_sidebar = is_active_sidebar( 'user-dashboard' ) ? true : false;

if( $has_sidebar ){
    wp_enqueue_script( 'bootstrap-masonry.pkgd' );
}

/**
 *
 * Fires before page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/before' );

?>
<div class="page-head mb-3 d-flex gap-3 align-items-center border-bottom">
    <h1 class="page-title h4">
        <?php esc_html_e( 'Dashboard', 'streamtube-core' );?>
    </h1>

    <?php if( $welcome_text ): ?>
        <span class="ms-auto text-secondary fw-bold">
            <?php echo $welcome_text; ?>
        </span>
    <?php endif;?>
</div>

<?php
/**
 *
 * Fires after page header
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_header/after' );

/**
 *
 * Fires before page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/before' );
?>

<div class="page-content">
    <?php

    /**
     *
     * Fires before user dashboard
     *
     * @since 1.0.8
     * 
     */
    do_action( 'streamtube/core/user/dashboard/dashboard/before' );

    if( $has_sidebar ):
    ?>

    <div class="row" data-masonry="<?php echo esc_attr( json_encode( array( 'percentPosition'=>true ) ) );?>">

    	<?php dynamic_sidebar( 'user-dashboard' ); ?>

    </div>

    <?php

    endif;

    /**
     *
     * Fires after user dashboard
     *
     * @since 1.0.8
     * 
     */
    do_action( 'streamtube/core/user/dashboard/dashboard/after' );

    ?>
</div>
<?php

/**
 *
 * Fires after page content
 * 
 */
do_action( 'streamtube/core/user/dashboard/page_content/after' );