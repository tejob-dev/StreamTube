<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$types = streamtube_core()->get()->user_dashboard->get_upload_types();

if( ! $types ){
    return;
}

?>
<ul class="dropdown-menu dropdown-menu-end animate slideIn">

    <?php foreach( $types as $type => $v ): ?>

        <?php 

        $v = wp_parse_args( $v, array(
            'cap'   =>  'read',
            'url'   =>  ''
        ) );

        $can = is_string( $v['cap'] ) ? current_user_can( $v['cap'] ) : call_user_func( $v['cap'] );

        if( $can === true ):
        ?>

         <li class="type-<?php echo esc_attr( $type ); ?>">
            <?php

            if( $v['url'] ){
                printf(
                    '<a href="%s"  class="dropdown-item d-flex align-items-center">',
                    esc_url( $v['url'] ),
                );
            }else{
                printf(
                    '<a href="#%1$s" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-%1$s">',
                    esc_attr( $type )
                );
            }

            ?>
                <?php printf(
                    '<span class="menu-icon %s"></span>',
                    sanitize_html_class( $v['icon'] )
                );?>
                <?php printf(
                    '<span class="menu-text">%s</span>',
                    $v['text']
                );?>
            </a>

        </li>

        <?php do_action( "streamtube/core/upload/{$type}/loaded", $type, $v );?>

        <?php endif;?>

    <?php endforeach; ?>

</ul>