<?php
/**
 * The post comment meta template
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

$args = wp_parse_args( $args, array(
    'text'  =>  false
) );

?>
<div class="post-meta__comment">
    <a href="<?php echo esc_url( get_comments_link() )?>">
        <span class="post-meta__icon icon-chat-empty"></span>
        <span class="post-meta__text"><?php
            if( ! $args['text'] ){
                echo number_format_i18n( get_comments_number() );
            }
            else{
                comments_number( esc_html__( '0 comments', 'streamtube' ) );
            }
        ?></span>
    </a>
</div>