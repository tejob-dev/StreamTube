<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $wp_query;

$endpoint = isset( $wp_query->query_vars['forums'] ) ? $wp_query->query_vars['forums'] : 'topics';

if( empty( $endpoint ) ){
    $endpoint = 'topics';
}

?>
<section class="section-profile profile-forums py-4 pb-0 m-0">

        <?php printf(
            '<div class="%s">',
            esc_attr( join( ' ', streamtube_core_get_user_profile_container_classes() )  )
        )?>

        <div id="bbpress-forums" class="bbpress-wrapper">
    	
            <?php bbp_get_template_part( 'user-details' ); ?>

            <?php
            switch (  $endpoint ) {
                case 'replies':
                    bbp_get_template_part( 'user-replies-created' );
                break;

                case 'engagements':
                    bbp_get_template_part( 'user-engagements' );
                break;

                case 'favorites':
                    bbp_get_template_part( 'user-favorites' );
                break;

                case 'subscriptions':
                    bbp_get_template_part( 'user-subscriptions' );
                break;
                
                default:
                    bbp_get_template_part( 'user-topics-created' );
                break;
            }
            ?>
        </div>

    </div>

</div>