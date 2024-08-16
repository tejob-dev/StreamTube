<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$heading = apply_filters( 'streamtube/core/user/profile/about', esc_html__( 'About', 'streamtube-core' ));

$bio = get_userdata( get_queried_object_id() )->description;

/**
 * Filter the bio
 */
$bio = apply_filters( 'streamtube/core/user/profile/about/bio', $bio );
?>
<section class="profile-profile py-4 pb-0 m-0">

    <?php printf(
        '<div class="%s">',
        esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
    )?>

        <div class="widget-title-wrap d-flex">
            <?php if( $heading ): ?>

                <h2 class="widget-title no-after">
                    <?php echo $heading;?>
                </h2>

            <?php endif;?>
        </div>

        <?php if( ! $bio ){

            echo '<div class="not-found p-3 text-center text-muted fw-normal h6"><p>';

                if( streamtube_core_is_my_profile() ){
                    esc_html_e( 'You have not updated profile yet.', 'streamtube-core' );
                }
                else{
                    printf(
                        esc_html__( '%s has not updated profile yet.', 'streamtube-core' ),
                        '<strong>'. get_user_by( 'ID', get_queried_object_id() )->display_name .'</strong>'
                    );
                }

            echo '</p></div>';

        }else{
            ?>
            <div class="user-bio p-4 shadow-sm bg-white rounded mb-4">

                <div class="post-content">
                    <?php echo $bio; ?>
                </div>

            </div>
            <?php
        }?>


    </div>

</section>