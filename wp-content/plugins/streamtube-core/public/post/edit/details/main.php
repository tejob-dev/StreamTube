<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post, $post_type_screen, $streamtube;

/**
 *
 * Fires before title
 *
 * @since  1.0.0
 * 
 */
do_action( 'streamtube/core/post/edit/title/before', $post );

if( $post instanceof WP_Post ):

    $post_type_label = get_post_type_object( $post->post_type )->labels->singular_name;

    switch ( $post->post_status ) {
        case 'future':
        ?>
            <p class="alert alert-post-status alert-scheduled alert-info p-2 px-3">
                <?php printf(
                    esc_html__( 'This %s is scheduled.', 'streamtube-core' ),
                    strtolower( $post_type_label )
                );?>
            </p>
        <?php
        break;
        
        case 'pending':
        ?>
            <p class="alert alert-post-status alert-warning p-2 px-3">
                <?php printf(
                    esc_html__( 'This %s is pending review.', 'streamtube-core' ),
                    strtolower( $post_type_label )
                );?>
            </p>
        <?php
        break;

        case 'draft':
        ?>
            <p class="alert alert-post-status alert-warning p-2 px-3">
                <?php printf(
                    esc_html__( 'This %s is drafted.', 'streamtube-core' ),
                    strtolower( $post_type_label )
                );?>
            </p>
        <?php
        break;        

        case 'private':
        ?>
            <p class="alert alert-post-status alert-info p-2 px-3">
                <?php printf(
                    esc_html__( 'This %s is privated.', 'streamtube-core' ),
                    strtolower( $post_type_label )
                );?>
            </p>
        <?php
        break;

        case 'unlist':
        ?>
            <p class="alert alert-post-status alert-info p-2 px-3">
                <?php printf(
                    esc_html__( 'This %s is unlisted.', 'streamtube-core' ),
                    strtolower( $post_type_label )
                );?>
            </p>
        <?php
        break;

        case 'reject':
        ?>
            <p class="alert alert-post-status alert-danger p-2 px-3">
                <?php printf(
                    esc_html__( 'This %s is rejected.', 'streamtube-core' ),
                    strtolower( $post_type_label )
                );?>
            </p>
        <?php
        break;  

        case 'trash':
        ?>
            <p class="alert alert-post-status alert-danger p-2 px-3">
                <?php printf(
                    esc_html__( 'This %s is trashed.', 'streamtube-core' ),
                    strtolower( $post_type_label )
                );?>
            </p>
        <?php
        break; 

        default:
            /**
             *
             * 
             * 
             */
            do_action( 'streamtube/core/post/edit/post_status_info', $post );
        break;
    }

endif;

streamtube_core_the_field_control( array(
    'label'         =>  esc_html__( 'Title', 'streamtube-core' ),
    'type'          =>  'text',
    'name'          =>  'post_title',
    'value'         =>  $post ? $post->post_title : ''
) );

if( apply_filters( 'streamtube/core/post/edit/slug', true ) === true ){
    streamtube_core_the_field_control( array(
        'label'         =>  esc_html__( 'Slug', 'streamtube-core' ),
        'type'          =>  'text',
        'name'          =>  'post_name',
        'value'         =>  $post ? $post->post_name : ''
    ) );
}

if( $post && $post->post_type == 'video' && ! wp_doing_ajax() ){
    if( get_option( 'allow_edit_source' ) || current_user_can( 'administrator' ) ){
        streamtube_core_the_field_control( array(
            'label'         =>  esc_html__( 'Trailer', 'streamtube-core' ),
            'type'          =>  'text',
            'name'          =>  'video_trailer',
            'value'         =>  $post ? esc_attr( $streamtube->get()->post->get_video_trailer( $post->ID ) ) : '',
            'wpmedia'       =>  true
        ) );

        streamtube_core_the_field_control( array(
            'label'         =>  esc_html__( 'Main Source', 'streamtube-core' ),
            'type'          =>  'text',
            'name'          =>  'video_source',
            'value'         =>  $post ? esc_attr( $streamtube->get()->post->get_source( $post->ID ) ) : '',
            'wpmedia'       =>  true
        ) );        
    }
}

/**
 *
 * Fires before content
 *
 * @since  1.0.0
 * 
 */
do_action( 'streamtube/core/post/edit/content/before', $post );

if( ! wp_doing_ajax() ){
    $editor_setings = array(
        'teeny'             =>  false,
        'media_buttons'     =>  false,
        'drag_drop_upload'  =>  false
    );

    if( get_option( 'editor_add_media' ) ){
        $editor_setings = array_merge( $editor_setings, array(
            'media_buttons'     =>  current_user_can( 'upload_files' ) ? true : false,
            'drag_drop_upload'  =>  current_user_can( 'upload_files' ) ? true : false
        ) );
    }

    if( apply_filters( 'streamtube/core/wpeditor/teeny', false ) === true ){
        $editor_setings = array_merge( $editor_setings, array(
            'teeny'             =>  true,
            'media_buttons'     =>  false,
            'drag_drop_upload'  =>  false,
            'tinymce'       => array(
                'toolbar1'      => 'bold,italic,underline,bullist,numlist,link,unlink,forecolor,undo,redo,image'
            ),
            'quicktags'     =>  array(
                'buttons'   => 'strong,em,underline,ul,ol,li,code,img'
            )
        ) );
    }

    streamtube_core_the_field_control( array(
        'label'     =>  esc_html__( 'Content', 'streamtube-core' ),
        'type'      =>  'editor',
        'name'      =>  'post_content',
        'settings'  =>  $editor_setings,
        'value'     =>  $post ? $post->post_content : ''
    ) );    
}
else{
    echo '<div class="wp-editor-wrap">';
        streamtube_core_the_field_control( array(
            'type'      =>  'textarea',
            'name'      =>  'post_content',
            'id'        =>  '_post_content',
            'value'     =>  $post ? $post->post_content : ''
        ) );
    echo '</div>';
}

/**
 *
 * Fires after content field.
 *
 * @since  1.0.0
 * 
 */
do_action( 'streamtube/core/post/edit/content/after', $post );

$post_statuses = $streamtube->get()->post->get_post_statuses_for_edit( true );

/**
 *
 * Filter the statuses
 * 
 * @param  array $post_statuses
 *
 * @since  1.0.0
 * 
 */
$post_statuses = apply_filters( 'streamtube/core/post/edit/statuses', $post_statuses, $post );

$post_status = $post ? $post->post_status : '';

if( $post_status == 'future' ){
    $post_status = 'publish';
}

streamtube_core_the_field_control( array(
	'label'			=>	esc_html__( 'Visibility', 'streamtube-core' ),
	'type'			=>	'select',
	'name'			=>	'post_status',
	'current'		=>	$post_status,
	'options'		=>	$post_statuses
) );

if( apply_filters( 'streamtube/core/post/edit/post_password', true, $post ) === true ){
    streamtube_core_the_field_control( array(
        'label'         =>  esc_html__( 'Password Protected', 'streamtube-core' ),
        'type'          =>  'text',
        'name'          =>  'post_password',
        'id'            =>  'post_password',
        'value'         =>  $post ? $post->post_password : ''
    ) );
}

streamtube_core_the_field_control( array(
    'label'         =>  esc_html__( 'Publish Date', 'streamtube-core' ),
    'type'          =>  'datetime-local',
    'name'          =>  'post_date',
    'value'         =>  $post ? date( 'Y-m-d\TH:i' , strtotime( $post->post_date ) ) : ''
) );

$upcoming_date = $streamtube->get()->post->get_upcoming_date( $post->ID );

streamtube_core_the_field_control( array(
    'label'         =>  esc_html__( 'Upcoming Date', 'streamtube-core' ),
    'type'          =>  'datetime-local',
    'name'          =>  'meta_input[_upcoming_date]',
    'value'         =>  $post && $upcoming_date ? date( 'Y-m-d\TH:i' , strtotime( $upcoming_date ) ) : ''
) );

/**
 *
 * Fires before meta fields
 *
 * @since 1.1
 * 
 */
do_action( 'streamtube/core/post/edit/meta/before', $post );

if( $post && $post->post_type == 'video' && apply_filters( 'streamtube/core/post/edit/meta', true ) === true ):

    ?>
    <div class="row field-group-meta-1">
        <div class="col-6">
            <?php

            $default_ratios = streamtube_core_get_ratio_options();

            /**
             *
             * Filter default allowed ratio options
             * 
             */
            $default_ratios = apply_filters( 'streamtube/core/post/edit/meta/default_ratios', $default_ratios );

        	streamtube_core_the_field_control( array(
        		'label'			=>	esc_html__( 'Aspect Ratio', 'streamtube-core' ),
        		'type'			=>	'select',
        		'name'			=>	'meta_input[_aspect_ratio]',
                'current'       =>  $post ? $streamtube->get()->post->get_aspect_ratio( $post->ID ) : '',
        		'options'		=>	array_merge( array(
                    ''  =>  esc_html__( 'Default', 'streamtube-core' )
                ), $default_ratios )
        	) );
            ?>
            </div>
        <div class="col-6">
            <?php
            streamtube_core_the_field_control( array(
                'label'         =>  esc_html__( 'Video Length', 'streamtube-core' ),
                'type'          =>  'text',
                'name'          =>  'meta_input[_length]',
                'value'         =>  $post ? $streamtube->get()->post->get_length( $post->ID ) : ''
            ) );
            ?>
        </div>
    </div>
    <?php

endif;

/**
 *
 * Fires after meta fields
 *
 * @since 1.1
 * 
 */
do_action( 'streamtube/core/post/edit/meta/after', $post );

?><div class="d-flex gap-4 field-group-meta-2"><?php

streamtube_core_the_field_control( array(
	'label'			=>	esc_html__( 'Allow comments', 'streamtube-core' ),
	'type'			=>	'checkbox',
	'name'			=>	'comment_status',
    'value'         =>  'open',
    'current'       =>  is_object( $post ) ? $post->comment_status : 'open',
    'id'            =>  is_object( $post ) ? "comment_status_{$post->ID}" : "comment_status"
) );

if( is_object( $post ) && $post->post_type == 'video' ){
    streamtube_core_the_field_control( array(
        'label'         =>  esc_html__( '360 Degree Video', 'streamtube-core' ),
        'type'          =>  'checkbox',
        'name'          =>  'meta_input[_vr]',
        'value'         =>  'vr',
        'current'       =>  $post ? $streamtube->get()->post->is_video_vr( $post->ID ) : '',
        'id'            =>  is_object( $post ) ? "vr_{$post->ID}" : "vr"
    ) );
}

?></div><?php

if( is_object( $post ) ){
    printf(
        '<input type="hidden" name="post_ID" value="%s">',
        $post->ID
    );
}