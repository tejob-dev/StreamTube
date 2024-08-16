<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

$search =  isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';
    
$is_admin = is_admin() ? true : false;

if( ! $is_admin ) : ?>
<form id="search-locations" class="search-locations" method="post">
<?php endif;?>

    <div class="w-100 mb-4">

        <div class="input-group">
            
            <?php printf(
                '<input style="min-width:%s" type="search" id="search-input" name="search" class="form-control" value="%s" placeholder="%s">',
                '90%',
                esc_attr( $search ),
                esc_html__( 'Search ...', 'wp-post-location' )
            );?>


            <?php printf(
                '<button id="search-locations" type="%s" class="btn btn-secondary button">',
                ! $is_admin ? 'submit' : 'button'
            );?>
                <?php printf(
                    '<span class="btn__icon %s"></span>',
                    ! $is_admin ? 'icon-search' : 'dashicons-search dashicons'
                );?>
            </button>
        </div>  

    </div>

    <input type="hidden" name="zoom" value="12">

<?php if( ! $is_admin ) : ?>    
</form>
<?php endif;