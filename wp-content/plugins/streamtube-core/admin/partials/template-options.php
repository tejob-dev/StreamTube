<?php
/**
 *
 * The admin metabox template file.
 * 
 */
if( ! defined('ABSPATH' ) ){
    exit;
}

global $post;

$metabox = streamtube_core()->get()->metabox;

$options = $metabox->get_template_options( $post->ID );

?>

<div class="metabox-wrap">

    <?php if( in_array( $post->post_type , array( 'page' ) ) ):?>

    <p>
        <label for="disable_title">
        
            <?php printf(
                '<input type="checkbox" name="template_options[disable_title]" id="disable_title" class="input-field" %s>',
                $options['disable_title'] ? 'checked' : ''
            );?>
            <?php esc_html_e( 'Disable Page Title', 'streamtube-core' ); ?>
            
        </label>
    </p>

    <p>
        <label for="disable_thumbnail">
        
            <?php printf(
                '<input type="checkbox" name="template_options[disable_thumbnail]" id="disable_thumbnail" class="input-field" %s>',
                $options['disable_thumbnail'] ? 'checked' : ''
            );?>
            <?php esc_html_e( 'Disable Featured Image', 'streamtube-core' ); ?>
            
        </label>
    </p>    

    <p>
        <label for="header_alignment">

             <?php esc_html_e( 'Header Alignment', 'streamtube-core' ); ?>

            <select name="template_options[header_alignment]" id="header_alignment" class="regular-text">

                <?php foreach( $metabox->_get_options_alignment() as $key => $value ): ?>

                    <?php printf(
                        '<option %s value="%s">%s</option>',
                        selected( $options['header_alignment'], $key, false ),
                        esc_attr( $key ),
                        esc_html( $value )
                    );?>

                <?php endforeach; ?>

            </select>
            
        </label>
    </p>

    <p>
        <label for="header_padding">

            <?php esc_html_e( 'Header Padding', 'streamtube-core' ); ?>
        
            <?php printf(
                '<input type="number" name="template_options[header_padding]" id="header_padding" class="regular-text" value="%s">',
                absint( $options['header_padding'] )
            );?>

        </label>
    </p>

    <p>
        <label for="remove_content_box">

            <?php printf(
                '<input type="checkbox" name="template_options[remove_content_box]" id="remove_content_box" %s>',
                $options['remove_content_box'] ? 'checked' : ''
            );?>

            <?php esc_html_e( 'Remove Content Box Shadow and Background', 'streamtube-core' ); ?>

        </label>
    </p>

    <p>
        <label for="disable_content_padding">

            <?php printf(
                '<input type="checkbox" name="template_options[disable_content_padding]" id="disable_content_padding" %s>',
                $options['disable_content_padding'] ? 'checked' : ''
            );?>

            <?php esc_html_e( 'Disable Main Content Padding', 'streamtube-core' ); ?>

        </label>
    </p>

    <?php endif;?>

    <p>
        <label for="disable_primary_sidebar">

            <?php printf(
                '<input type="checkbox" name="template_options[disable_primary_sidebar]" id="disable_primary_sidebar" %s>',
                $options['disable_primary_sidebar'] ? 'checked' : ''
            );?>

            <?php esc_html_e( 'Disable Primary Sidebar', 'streamtube-core' ); ?>

        </label>
    </p>

    <p>
        <label for="disable_bottom_sidebar">

            <?php printf(
                '<input type="checkbox" name="template_options[disable_bottom_sidebar]" id="disable_bottom_sidebar" %s>',
                $options['disable_bottom_sidebar'] ? 'checked' : ''
            );?>

            <?php esc_html_e( 'Disable Content Bottom Sidebar', 'streamtube-core' ); ?>

        </label>
    </p>

    <p>
        <label for="disable_comment_box">

            <?php printf(
                '<input type="checkbox" name="template_options[disable_comment_box]" id="disable_comment_box" %s>',
                $options['disable_comment_box'] ? 'checked' : ''
            );?>

            <?php esc_html_e( 'Disable Comments Box', 'streamtube-core' ); ?>

        </label>
    </p>    

    <input type="hidden" name="template_options[hidden]" value="1">

</div>