<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$args = array(
    'post_id'           =>  '',
    'recipient_id'      =>  '',
    'amount'            =>  streamtube_core_get_mycred_settings( 'donate_min_points', 1 ),
    'ctype'             =>  streamtube_core_get_mycred_settings( 'donate_point_type' ),
    'user_balance'      =>  streamtube_core_get_mycred_settings( 'donate_user_balance' ),
    'reference'         =>  'donation',
    'button_next'       =>  esc_html__( 'Next', 'streamtube-core' ),
    'button_cancel'     =>  esc_html__( 'No, I\'m not', 'streamtube-core' ),
    'button'            =>  esc_html__( 'Yes, send it', 'streamtube-core' ),
    'button_size'       =>  'md',
    'button_classes'    =>  array( 
        'btn', 
        'px-4', 
        'shadow-none', 
        'd-flex', 
        'align-items-center', 
        'justify-content-center'
    )
);

$args['button_classes'] = array_merge( $args['button_classes'], array(
    'btn-' . $args['button_size']
) );

if( is_singular() ){
    global $post;

    $args['recipient_id'] = $post->post_author;

    $args['post_id'] = $post->ID;
}

if( is_author() ){
    $args['recipient_id'] = get_queried_object_id();
}

if( ! $args['recipient_id'] ){
    return;
}

$userdata = get_userdata( $args['recipient_id'] );

/**
 *
 * Filter the button args
 * 
 * @var array $args
 */
$args = apply_filters( 'streamtube/core/mycred/form_donate', $args );

extract( $args );

?>
<div class="button-donate-wrap ms-auto">
    <form class="form-ajax form-transfer-point">

        <?php
        /**
         *
         * Fires before form
         *
         * @param array $args
         * 
         */
        do_action( 'streamtube/core/mycred/form_donate/before', $args );
        ?>

        <?php
        streamtube_core_the_field_control( array(
            'label'     =>  esc_html__( 'Recipient', 'streamtube-core' ),
            'type'      =>  'text',
            'name'      =>  'recipient',
            'value'     =>  $userdata->display_name,
            'data'      =>  array(
                'readonly'  =>  'readonly',
                'disabled'  =>  'disabled'
            )
        ) )
        ?>

        <?php
        streamtube_core_the_field_control( array(
            'label'     =>  esc_html__( 'Amount', 'streamtube-core' ),
            'type'      =>  'text',
            'name'      =>  'amount',
            'value'     =>  $amount
        ) )
        ?>
        
        <?php printf(
            '<input type="hidden" name="recipient_id" value="%s">',
            $recipient_id
        );?>

        <?php printf(
            '<input type="hidden" name="reference" value="%s">',
            $reference
        );?>        

        <?php printf(
            '<input type="hidden" name="ctype" value="%s">',
            $ctype
        );?>

        <?php printf(
            '<input type="hidden" name="post_id" value="%s">',
            $post_id
        );?>

        <input type="hidden" name="action" value="transfers_points">

        <?php wp_nonce_field( 'mycred-new-transfer-' . $reference, 'token' );?>

        <?php if( $user_balance ){
            load_template( 
                plugin_dir_path( __FILE__ ) . 'user-balance.php',
                false,
                compact( 'ctype' )
            );
        }?>

        <div class="confirm-message p-3 text-center text-secondary d-none">
            <p>
                <?php printf(
                    esc_html__( 'Are you going to send %s %s to %s?', 'streamtube-core' ),
                    '<span class="total-amount text-success fw-bold">'. $amount .'</span>',
                    mycred( $ctype )->name['plural'],
                    sprintf(
                        '<span class="recipient"><a class="text-danger text-body text-decoration-none" target="_blank" href="%s">%s %s</a></span>',
                        get_author_posts_url( $userdata->ID ),
                        get_avatar( $userdata->ID, 64, null, null, array(
                            'class' =>  'rounded-circle'
                        ) ),
                        $userdata->display_name
                    ),
                );?>
            </p>
        </div>

        <?php printf(
            '<button type="button" class="%s">',
            esc_attr( join( ' ', array_merge( $button_classes, array( 
                'btn-primary', 
                'btn-next',
                'd-block',
                'w-100',
                'mb-4'
            ) ) ) )
        );?>
            <span class="btn__text text-white">
                <?php echo $button_next; ?>
            </span>
        </button>        

        <div class="form-submit button-group d-flex gap-4 mb-4 justify-content-between">

            <?php printf(
                '<button type="button" class="%s">',
                esc_attr( join( ' ', array_merge( $button_classes, array( 'btn-danger', 'btn-cancel' ) ) ) )
            );?>
                <span class="btn__text text-white">
                    <?php echo $button_cancel; ?>
                </span>
            </button>            

            <?php printf(
                '<button type="submit" class="%s">',
                esc_attr( join( ' ', array_merge( $button_classes, array( 'btn-success', 'btn-send' ) ) ) )
            );?>
                <span class="btn__text text-white">
                    <?php echo $button; ?>
                </span>
            </button>
        </div>

        <?php
        /**
         *
         * Fires after form
         *
         * @param array $args
         * 
         */
        do_action( 'streamtube/core/mycred/form_donate/after', $args );
        ?>

    </form>
</div>