<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
wp_enqueue_script( 'bootstrap-tagsinput' );
wp_enqueue_style( 'bootstrap-tagsinput' );

global $post, $add_new_post_screen, $post_type_screen;

if( $add_new_post_screen ){

    if( ! function_exists( 'get_default_post_to_edit' ) ){
        include ABSPATH . 'wp-admin/includes/post.php';
    } 

    $post = get_default_post_to_edit( $post_type_screen, true );
}
?>
<form class="form-ajax form-add-post" method="post" autocomplete="off">
    <div class="widget mb-0">
        <div class="widget-title-wrap d-flex bg-white sticky-top border p-3">

            <div class="d-none d-sm-block group-title flex-grow-1">
                <h2 class="page-title">
                    <?php echo $post ? esc_html__( 'Update', 'streamtube-core' ) : esc_html__( 'Add New', 'streamtube-core' ); ?>
                </h2>
            </div>

            <div class="ms-md-auto">
                <div class="d-flex flex-wrap gap-3">

                    <?php if( $post ):?>

                        <button type="button" class="btn btn-danger px-3" name="submit" value="delete" data-bs-toggle="modal" data-bs-target="#deletePostModal" data-post-id="<?php echo $post->ID; ?>">
                            <span class="btn__icon icon-trash"></span>
                            <span class="btn__text">
                                <?php esc_html_e( 'Trash', 'streamtube-core' ); ?>
                            </span>
                        </button>

                        <a class="btn btn-info text-white current-post-permalink px-3" href="<?php the_permalink( $post->ID );?>">
                            <span class="btn__icon icon-eye"></span>
                            <span class="btn__text">
                                <?php esc_html_e( 'View', 'streamtube-core' ); ?>
                            </span>
                        </a>

                    <?php endif;?>

                    <button type="submit" class="btn btn-primary px-3" name="submit" value="update">
                    	<span class="btn__icon icon-floppy"></span>
                        <span class="btn__text">
                            <?php

                            $btn_text = esc_html__( 'Publish', 'streamtube-core' );

                            if( $post ){
                                $btn_text = esc_html__( 'Update', 'streamtube-core' );
                            }
                            else{
                                if( ! current_user_can( 'edit_others_posts' ) ){
                                    $btn_text = esc_html__( 'Submit for review', 'streamtube-core' );
                                }
                            }

                            echo apply_filters( 'streamtube/core/post/edit/submit/text', $btn_text, $post );
                            ?>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="widget-content">

            <?php
            /**
             *
             * Fires before edit post screen
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/post/edit/before' );
            ?>

            <div class="row">
                <div class="col-12 col-lg-8 col-xl-8 col-xxl-8">
                    <?php streamtube_core_load_template( 'post/edit/details/main.php', false ); ?>
                </div>
                <div class="col-12 col-lg-4 col-xl-4 col-xxl-4">
                    <?php streamtube_core_load_template( 'post/edit/metaboxes.php' ); ?>
                </div><!--.col-3-->
            </div>

            <?php
            /**
             *
             * Fires after edit post screen
             * @since  1.0.0
             * 
             */
            do_action( 'streamtube/core/post/edit/after' );
            ?>

        </div>
    </div>

    <?php printf(
        '<input type="hidden" name="action" value="%s">',
        $post ? 'update_post' : 'add_post'
    );?>

    <?php printf(
        '<input type="hidden" name="_auto_draft" value="%s">',
        $post ? $post->post_status : ''
    );?>    
</form>

<?php streamtube_core_load_template( 'modal/delete-post.php' ); ?>