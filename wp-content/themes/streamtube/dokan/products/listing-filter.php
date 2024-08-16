<?php
/**
 * Dokan Dashboard Product Listing
 * filter template
 *
 * Note: "product_cat" param break WooCommerce functionality
 * We change it to "category_id"
 *
 * @var int|string           $product_cat
 * @var array<string,string> $product_types
 * @var string               $product_search_name
 * @var string|int           $date
 * @var string               $product_type
 * @var string               $filter_by_other
 * @var string               $post_status
 *
 * @since 2.4
 */

do_action( 'dokan_product_listing_filter_before_form' );
?>
<?php do_action( 'dokan_product_listing_filter_before_search_form' ); ?>
    <form class="dokan-form-inline dokan-product-date-filter" method="get" >

        <?php do_action( 'dokan_product_listing_filter_from_start', [] ); ?>

        <div class="dokan-form-group">
            <?php dokan_product_listing_filter_months_dropdown( dokan_get_current_user_id() ); ?>
        </div>

        <div class="dokan-form-group">
            <?php
            wp_dropdown_categories(
                apply_filters(
                    'dokan_product_cat_dropdown_args',
                    [
                        'show_option_none' => __( '- Select a category -', 'dokan-lite' ),
                        'hierarchical'     => 1,
                        'hide_empty'       => 0,
                        'name'             => 'category_id',
                        'id'               => 'category_id',
                        'taxonomy'         => 'product_cat',
                        'orderby'          => 'name',
                        'order'            => 'ASC',
                        'title_li'         => '',
                        'class'            => 'product_cat dokan-form-control chosen',
                        'exclude'          => '',
                        'selected'         => isset( $_REQUEST['category_id'] ) ? (int)$_REQUEST['category_id'] : -1
                    ]
                )
            );
            ?>
        </div>

        <?php if ( is_array( $product_types ) ) : ?>
            <div class="dokan-form-group">
                <select name="product_type" id="filter-by-type" class="dokan-form-control">
                    <option value=""><?php esc_html_e( 'Product type', 'dokan-lite' ); ?></option>
                    <?php foreach ( $product_types as $type_key => $p_type ) : ?>
                        <option value="<?php echo esc_attr( $type_key ); ?>" <?php selected( $product_type, $type_key ); ?>>
                            <?php echo esc_html( $p_type ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <?php do_action( 'dokan_product_listing_filter_from_end', [] ); ?>

        <div class="dokan-form-group">
            <input type="text" class="dokan-form-control" name="product_search_name" placeholder="<?php esc_html_e( 'Search Products', 'dokan-lite' ); ?>" value="<?php echo esc_attr( $product_search_name ); ?>">
        </div>        

        <?php wp_nonce_field( 'product_listing_filter', '_product_listing_filter_nonce', false ); ?>

        <div class="dokan-form-group d-flex gap-2">
            <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search', 'streamtube' ); ?></button>

            <a class="btn btn-secondary" href="<?php echo esc_attr( dokan_get_navigation_url( 'products' ) ); ?>"><?php esc_html_e( 'Reset', 'dokan-lite' ); ?></a>

            <?php if ( ! empty( $post_status ) ) : ?>
                <input type="hidden" name="post_status" value="<?php echo esc_attr( $post_status ); ?>">
            <?php endif; ?>            
        </div>
    </form>
<?php do_action( 'dokan_product_listing_filter_after_form' ); ?>
