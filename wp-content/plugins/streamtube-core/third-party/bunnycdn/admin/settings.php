<?php
/**
 *
 * The Settings template file
 * 
 *
 * @link       https://1.envato.market/mgXE4y
 * @since      2.1
 *
 * @package    WordPress
 * @subpackage StreamTube
 * @author     phpface <nttoanbrvt@gmail.com>
 */
if( ! defined( 'ABSPATH' ) ){
    exit;
}

$bunnycdn       = streamtube_core()->get()->bunnycdn;

$tabs           = $bunnycdn->get_setting_tabs();

$current_tab    = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';

if( ! array_key_exists( $current_tab, $tabs ) ){
    $current_tab = array_keys( $tabs )[0];
}
?>

<div class="wrap">

	<h1><?php esc_html_e( 'Bunny CDN Settings', 'streamtube-core' );?></h1>

    <?php
    if( isset( $_POST ) && isset( $_POST['bunnycdn_nonce'] ) ){
        if( wp_verify_nonce( $_POST['bunnycdn_nonce'], 'bunnycdn_nonce' ) && current_user_can( 'administrator' ) ){
          
            $_bunny_api = new Streamtube_Core_BunnyCDN_API( array(
                'AccessKey'     =>  $_POST['bunnycdn']['AccessKey'],
                'libraryId'     =>  $_POST['bunnycdn']['libraryId']
            ) );

            $results = $_bunny_api->create_video( 'Test API' );

            if( is_wp_error( $results ) ){
                printf(
                    '<div class="notice notice-error"><p>%s</p></div>',
                    $results->get_error_message()
                );

                update_option( '_bunnycdn', array_merge( wp_unslash( $_POST['bunnycdn'] ), array(
                    'is_connected'  =>  ''
                ) ) );
            }else{
                printf(
                    '<div class="notice notice-success"><p>%s</p></div>',
                    esc_html__( 'You have connected to Bunny CDN successfully', 'streamtube-core' )
                );

                $_bunny_api->delete_video( $results['guid'] );

                update_option( '_bunnycdn', array_merge( wp_unslash( $_POST['bunnycdn'] ), array(
                    'is_connected'  =>  'on'
                ) ) );              
            }
        }
    }

    $settings = Streamtube_Core_BunnyCDN_Settings::get_settings();
    ?>    

    <form method="post">

    	<nav class="nav-tab-wrapper wp-clearfix">
    		
            <?php foreach ( $tabs as $tab => $value ): ?>
                
                <?php printf(
                    '<a href="%s" class="nav-tab %s">%s</a>',
                    esc_url( add_query_arg( compact( 'tab' ) ) ),
                    $current_tab == $tab ? 'nav-tab-active' : '',
                    esc_html( $value['heading'] )
                );?>

            <?php endforeach ?>

    	</nav>

        <div class="widget-tab-content">

            <?php foreach ( $tabs as $tab => $value ): ?>

                <?php printf(
                    '<div class="tab-pane tab-content tab-content-%s %s">',
                    esc_attr( $tab ),
                    $current_tab == $tab ? 'active' : ''
                );?>

                    <?php include( plugin_dir_path( __FILE__ ) . $tab . '.php' ); ?>

                </div>

            <?php endforeach ?>

        </form>

        <p class="submit">

            <?php wp_nonce_field( 'bunnycdn_nonce', 'bunnycdn_nonce' );?>

            <input type="hidden" name="page" value="sync-bunnycdn">

            <input type="hidden" name="tab" value="<?php echo esc_attr( $current_tab ); ?>">

            <?php printf(
                '<input name="bunnycdn[webhook_key]" type="hidden" id="webhook_key" value="%s" class="regular-text">',
                esc_attr( $settings['webhook_key'] )
            );?>

            <?php if( $tabs[ $current_tab ]['inform'] ): ?>

                <?php printf(
                    '<input type="submit" name="submit" id="submit" class="button button-primary" value="%s">',
                    esc_html__( 'Save Changes', 'streamtube-core' )
                );?>

            <?php endif;?>
        </p>

    </form>

</div>    