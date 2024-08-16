<?php
/**
 *
 * The template for displaying collection sidebar
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      1.0.0
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

$Collection = streamtube_get_core()->get()->collection;

$term_id    = get_queried_object_id();

$can_manage = method_exists( $Collection, '_can_manage' ) ? $Collection->_can_manage() : true;

?>
<div class="sidebar sidebar-primary sidebar-collection">

    <?php get_template_part( 'template-parts/widget-term', 'thumbnail' ); ?>

    <?php get_template_part( 'template-parts/widget-term', 'description' ); ?>

    <?php if( 'history' == $Collection->_is_builtin_term( $term_id ) && $can_manage ){
        $Collection->the_button_pause( compact( 'term_id' ) );

        $Collection->the_button_clear_all( compact( 'term_id' ) );
    } ?>

</div>