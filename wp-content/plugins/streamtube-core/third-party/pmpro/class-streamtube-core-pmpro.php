<?php
/**
 * Define the PMPro functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.2
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.2
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class StreamTube_Core_PMPro{

    /**
     * Holds the page slug
     *
     * @since 2.2
     */
    const PAGE_SLUG         = 'membership';

    /**
     *
     * Holds the admin
     * 
     * @var object
     *
     * @since 2.2
     * 
     */
    public $admin;

    public function __construct(){

        $this->load_dependencies();

        $this->admin = new StreamTube_Core_PMPro_Admin();
    }

    /**
     *
     * Include file
     * 
     * @param  string $file
     *
     * @since 2.2
     * 
     */
    private function include_file( $file ){
        require_once plugin_dir_path( __FILE__ ) . $file;
    }

    /**
     *
     * Load dependencies
     *
     * @since 2.2
     * 
     */
    private function load_dependencies(){
        $this->include_file( 'class-streamtube-core-pmpro-admin.php' ); 
    }

    /**
     *
     * Get settings
     * 
     * @return array
     *
     * @since 2.2
     * 
     */
    public function get_settings(){
        return wp_parse_args( get_option( 'pmpro_settings', array() ), array(
            'paid_icon'                 =>  'icon-lock',
            'paid_label'                =>  esc_html__( 'Premium', 'streamtube-core' ),
            'disable_comments_filter'   =>  ''
        ) );
    }

    /**
     *
     * Check if plugin is activated
     * 
     * @return boolean
     *
     * @since 2.2
     * 
     */
    public function is_activated(){
        return function_exists( 'pmpro_activation' );
    }

    /**
     *
     * 
     * @return string
     */
    public function is_version3(){
        if( defined( 'PMPRO_VERSION' ) && 
            is_string( PMPRO_VERSION ) && 
            version_compare( PMPRO_VERSION , '2.99', '>' ) ){
            return true;
        }
        return true;
    }

    /**
     *
     * Get level setting
     * 
     */
    public function get_level_setting( $level_id = 0, $option = '' ){

        if( ! $level_id ){
            return false;
        }

        return get_option( "pmpro_{$level_id}_" . sanitize_key( $option ) );
    }

    /**
     *
     * Check if advertisements is disabled for given level
     * 
     * @param  integer $level_id
     * @return boolean\
     */
    public function is_advertising_disabled( $level_id = 0 ){
        $retvar = get_option( "pmpro_{$level_id}_disable_advertising" );

        if( $retvar === 'on' ){
            return true;
        }

        return false;
    }

    /**
     *
     * Update level settings
     * 
     */
    public function update_level_settings( $level_id = 0 ){

        $default_fields     = array( 'disable_advertising' );

        $http_post = wp_parse_args( $_POST, array(
            'pmpro_streamtube'  =>  array()
        ) );

        $http_data = wp_parse_args( $http_post['pmpro_streamtube'], array(
            'disable_advertising'    =>  ''
        ) );

        for ( $i = 0;  $i < count( $default_fields );  $i++ ) {

            $key = sanitize_key( $default_fields[$i] );

            if( array_key_exists( $default_fields[$i], $http_data ) ){
                update_option( "pmpro_{$level_id}_" . $key , wp_unslash( $http_data[$default_fields[$i]] ) );
            }else{
                delete_option( "pmpro_{$level_id}_" . $key );
            }
        }
    }    

    /**
     * @since 2.2
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'streamtube-ppmpro-scripts', 
            plugin_dir_url( __FILE__ ) . 'public/scripts.js', 
            array(), 
            filemtime( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public/scripts.js' ),
            true
        );
    }    

    /**
     *
     * The [subscription_levels] shortcode
     * 
     * @param  array  $args
     * @param  string $content
     * @return string
     *
     * @since 2.2
     * 
     */
    public function _shortcode_membership_levels( $args = array(), $content = '' ){

        if( ! defined( 'IS_MEMBERSHIP_LEVELS' ) ){
            define( 'IS_MEMBERSHIP_LEVELS', true );    
        }

        $output = '';

        $args = wp_parse_args( $args, array(
            'heading'           =>  '',
            'heading_tag'       =>  'h2',
            'plan_description'  =>  'on',
            'select_button'     =>  'primary',
            'renew_button'      =>  'primary',
            'your_level_button' =>  'success',
            'button_size'       =>  'md',
            'shadow'            =>  'sm',
            'col_xxl'           =>  3,
            'col_xl'            =>  3,
            'col_lg'            =>  2,
            'col_md'            =>  2,
            'col_sm'            =>  1,
            'col'               =>  1,
            'classes'           =>  array( 'row' ),
            'mb'                =>  4,
            'custom_levels'     =>  '',
            'min_height'        =>  ''
        ) );

        if( $args['custom_levels'] && is_string( $args['custom_levels'] ) ){
            $args['custom_levels'] = array_map( 'trim' , explode(',', $args['custom_levels'] ));
        }

        $args['classes'] = array_merge( $args['classes'], array(
            'row-cols-' .       $args['col'],
            'row-cols-sm-' .    $args['col_sm'],
            'row-cols-md-' .    $args['col_md'],
            'row-cols-lg-' .    $args['col_lg'],
            'row-cols-xl-' .    $args['col_xl'],
            'row-cols-xxl-' .   $args['col_xxl']
        ) );

        ob_start();

        load_template( plugin_dir_path( __FILE__ ) . 'public/shortcodes/levels.php', false, $args );

        $output = ob_get_clean();

        return $output;

    }

    /**
     *
     * The [membership_levels] shortcode
     * 
     * @param  array  $args
     * @param  string $content
     * @return string
     *
     * @since 2.2
     * 
     */
    public function shortcode_membership_levels(){
        add_shortcode( 'membership_levels', array( $this , '_shortcode_membership_levels' ) );
    }

    /**
     *
     * Get shortcode account content, used in Dashboard page only.
     *
     * @since 2.2
     * 
     * @return string
     */
    public function get_shortcode_account_content(){

        $search = array(
            'pmpro_table',
            add_query_arg( 
                array( 'invoice' => '' ), 
                get_permalink( get_option( 'pmpro_invoice_page_id' ) ) 
            )
        );

        $replaceWidth = array(
            'pmpro_table pmpro_membersip_table table table-hover mt-3',
            ''
        );

        $output = pmpro_shortcode_account( array(
            'sections'  =>  'membership'
        ) );

        $output = str_replace( $search, $replaceWidth, $output );

        /**
         *
         * @since 2.2
         * 
         */
        return apply_filters( 'streamtube/core/pmpro_account_content', $output );
    }

    /**
     *
     * Get invoice content
     * 
     * @return string
     *
     * @since 2.2
     * 
     */
    public function get_invoices_content(){
        require_once( PMPRO_DIR . '/preheaders/invoice.php' );

        ob_start();

        if( $this->is_version3() ){
            get_template_part( 'paid-memberships-pro/pages/invoice' );
        }else{
            get_template_part( 'paid-memberships-pro/pages/invoice-v2' );
        }

        $output = ob_get_clean();

        $search = array(
            'pmpro_table',
            get_permalink( get_option( 'pmpro_invoice_page_id' ) ) 
        );

        $replaceWidth = array(
            'pmpro_table table table-hover mt-3',
            ''
        );        

        $output = str_replace( $search, $replaceWidth, $output );

        return $output;
    }

    /**
     *
     * Get invoice content
     * 
     * @return string
     *
     * @since 2.2
     * 
     */
    public function get_billing_content(){
        //require_once( PMPRO_DIR . '/preheaders/billing.php' );

        ob_start();

        if( $this->is_version3() ){
            get_template_part( 'paid-memberships-pro/pages/billing' );
        }else{
            get_template_part( 'paid-memberships-pro/pages/billing-v2' );
        }

        $output = ob_get_clean();

        $search = array(
            'pmpro_table',
            get_permalink( get_option( 'pmpro_invoice_page_id' ) ) 
        );

        $replaceWidth = array(
            'pmpro_table table table-hover mt-3',
            ''
        );        

        $output = str_replace( $search, $replaceWidth, $output );

        return $output;
    }

    /**
     *
     * Get affiliates content
     * 
     */
    public function get_affiliates_content(){

        $output = '';

        if( shortcode_exists( 'pmpro_affiliates_report' ) ){
            $output = do_shortcode( '[pmpro_affiliates_report]' );

            if( preg_match('/<ul>\s*<\/ul>/', $output ) ){
                $output = sprintf(
                    '<p class="text-muted mb-0">%s</p>',
                    esc_html__( 'You currently do not possess any affiliate code.', 'streamtube-core' )
                );
            }
        }

        if( $output ){
            return sprintf(
                '<div class="pmpro-affiliates-wrap pmpro_box">%s</div>',
                $output
            );
        }
    }

    /**
     *
     * Redirect default pages to user dashboard
     * 
     */
    public function redirect_default_pages(){

        if( ! is_user_logged_in() || ! is_page() ){
            return;
        }

        $user_id        = get_current_user_id();

        $redirect_url   = wp_login_url();

        if( $user_id ){
            $redirect_url = trailingslashit( get_author_posts_url( $user_id ) ) . 'dashboard/' . self::PAGE_SLUG;
        }

        // Set account page
        $account_page = get_option( 'pmpro_account_page_id' );

        if( $account_page && is_page( $account_page ) ){
            wp_redirect( $redirect_url  );
            exit;
        }

        // Set billing page
        $billing_page = get_option( 'pmpro_billing_page_id' );

        if( $billing_page && is_page( $billing_page ) ){
            wp_redirect( trailingslashit( $redirect_url ) . 'billing'  );
            exit;
        }

        // Set invoices page
        $invoice_page = get_option( 'pmpro_invoice_page_id' );

        if( $invoice_page && is_page( $invoice_page ) ){
            wp_redirect( trailingslashit( $redirect_url ) . 'invoices'  );
            exit;
        }        

        // Set affiliate
        $affiliate_page = get_option( 'pmpro_affiliate_report_page_id' );

        if( $affiliate_page && is_page( $affiliate_page ) ){
            wp_redirect( trailingslashit( $redirect_url ) . 'affiliate' );
            exit;       
        }
    }

    /**
     *
     * Add level settings after other settings within Level settings page
     * 
     */
    public function add_level_settings_box(){

        $level_id = isset( $_REQUEST['edit'] ) ? (int)$_REQUEST['edit'] : 0;
        ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" valign="top"><label><?php esc_html_e( 'Disable Advertisements', 'streamtube-core' );?></label></th>
                    <td>

                        <label>
                            <?php printf(
                                '<input id="disable_advertising" name="pmpro_streamtube[disable_advertising]" type="checkbox" %s>',
                                $this->is_advertising_disabled( $level_id ) ? 'checked' : ''
                            );?>

                            <?php esc_html_e( 'Disable advertisements for this membership level, members at this level will not see any advertisements on video content.', 'streamtube-core' );?>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    /**
     * Filter player advertisements
     * Remove Ad if user purchased levels
     * 
     */
    public function filter_advertisements( $vast_tag_url, $setup, $source  ){

        // Return if invalid post or user isn't logged in
        if( ! get_post_status( $setup['mediaid'] ) || ! is_user_logged_in() ){
            return $vast_tag_url;
        }
        
        $user_id   = get_current_user_id();
        $hasaccess = pmpro_has_membership_access( $setup['mediaid'] , $user_id, true );

        if( $hasaccess ){

            if( ! $hasaccess[0] ){
                return $vast_tag_url;
            }

            $level_ids = $hasaccess[1];

            if( $level_ids ){
                for ( $i = 0; $i < count( $level_ids ); $i++) { 
                    if( $this->is_advertising_disabled( $level_ids[$i] ) ){
                        // If any disabled level was found, return it.
                        return false;
                    }
                }
            }
        }        

        return $vast_tag_url;
    }

    /**
     *
     * Filter player output
     * 
     * @param  string $player
     * @return string
     *
     * @since 2.2
     * 
     */
    public function filter_player_output( $player, $setup ){  

        global $post;

        if( ! get_post_status( $setup['mediaid'] ) ){
            return $player;
        }

        if( ! function_exists( 'pmpro_membership_content_filter' ) ){
            return $player;
        }

        // Return player if current logged in user is moderator or post owner
        if( 
            Streamtube_Core_Permission::moderate_posts() 
            || Streamtube_Core_Permission::is_post_owner( $setup['mediaid'] )
            || $setup['trailer'] ){
            /**
             * Show full content since we have protected video content only.
             */
            add_filter( 'pmpro_membership_content_filter', function( $return, $content, $hasaccess ){
                return $content;
            }, 9999, 3 );
                        
            return $player;
        }

        if( ! pmpro_has_membership_access( $setup['mediaid'], get_current_user_id() ) ){

            // BuddyPress compatiblity, check if within activity loop
            global $activities_template;

            if( $activities_template ){
                $_post = get_post($setup['mediaid']);
                setup_postdata( $GLOBALS['post'] = & $_post );
            }

            $player = '<div class="require-membership require-pmpro-levels">';

                $player .= '<div class="top-50 start-50 translate-middle position-absolute">';

                    $player .= pmpro_membership_content_filter( $player );

                $player .= '</div>';

            $player .= '</div>';

            $player .= sprintf(
                '<div class="player-poster bg-cover" style="background-image:url(%s)"></div>',
                $setup['poster2'] ? $setup['poster2'] : $setup['poster']
            );

            if( $activities_template ){
                setup_postdata( $GLOBALS['post'] = & $post );
                wp_reset_postdata();           
            }else{
                /**
                 * Show full content since we have protected video content only.
                 */
                add_filter( 'pmpro_membership_content_filter', function( $return, $content, $hasaccess ){
                    return $content;
                }, 9999, 3 );     
            }

            $player = str_replace( '!!trailer_url!!', add_query_arg( array(
                'view_trailer'  =>  '1'
            ), get_permalink( $setup['mediaid'] ) ), $player );

            $player = str_replace( wp_login_url(), wp_login_url( get_permalink( $setup['mediaid'] ) ), $player );
        }

        return apply_filters( 'streamtube/core/pmp/protected_player', $player, $setup );
    }

    /**
     *
     * Filter embed html
     * 
     */
    public function filter_player_embed_output( $oembed_html, $setup ){
        return $this->filter_player_output( $oembed_html, $setup );
    }

    /**
     *
     * Filter download permission
     * 
     */
    public function filter_download_permission( $can ){
        if( function_exists( 'pmpro_has_membership_access' )
            && ! pmpro_has_membership_access( get_the_ID(), get_current_user_id() ) ){
            $can = false;
        }        

        return $can;
    }

    /**
     *
     * Filter Post List widget
     * 
     */
    public function filter_widget_posts_join( $join, $query ){
        global $wpdb, $widget_instance;

        $pmp_pages_table    = $wpdb->prefix . 'pmpro_memberships_pages';
        $pmp_levels_table   = $wpdb->prefix . 'pmpro_membership_levels';    

        $widget_instance = wp_parse_args( $widget_instance, array(
            'content_cost'      =>  '',
            'level_type'        =>  '',
            'level__in'         =>  array(),
            'level__not_in'     =>  array()
        ) );

        extract( $widget_instance );

        if( is_array( $post_type ) && ! array_intersect( array( 'video', 'post' ), $post_type ) ){
            return $join;
        }   

        $level_type = trim( $level_type );

        if( in_array( $content_cost , array( 'free', 'premium' )) ){
            $level_type = $content_cost;
        }

        if( is_string( $level__in ) && ! empty( $level__in ) ){
            $level__in = array_map( 'intval', $level__in );
        }

        if( is_string( $level__not_in ) && ! empty( $level__not_in ) ){
            $level__not_in = array_map( 'intval', $level__not_in );
        }

        if( ! empty( $level_type ) || $level__in || $level__not_in ){
            $join .= " INNER JOIN $pmp_pages_table AS pmp_pages ON pmp_pages.page_id = {$wpdb->prefix}posts.ID";
            $join .= " INNER JOIN $pmp_levels_table AS pmp_levels ON pmp_levels.id = pmp_pages.membership_id";
        }

        return $join;
    } 

    /**
     *
     * Filter Post List widget
     * 
     */
    public function filter_widget_posts_where( $where, $query ){

        global $wpdb, $widget_instance;

        $widget_instance = wp_parse_args( $widget_instance, array(
            'content_cost'      =>  '',
            'level_type'        =>  '',
            'level__in'         =>  array(),
            'level__not_in'     =>  array()
        ) );

        extract( $widget_instance );

        if( is_array( $post_type ) && ! array_intersect( array( 'video', 'post' ), $post_type ) ){
            return $where;
        }         

        $level_type = trim( $level_type );

        if( in_array( $content_cost , array( 'free', 'premium' )) ){
            $level_type = $content_cost;    
        }

        if( ! empty( $level_type ) || $level__in || $level__not_in ){
            $where .= " AND pmp_levels.allow_signups = 1";
        }

        switch ( $level_type ) {
            case 'free':
                $where .= " AND initial_payment = ''";
            break;
            
            case 'premium':
                $where .= " AND initial_payment <> ''";
            break;
        }

        if( $level__in ){
            $where .= ' AND pmp_levels.id IN ('. implode(',', $level__in ) .')';    
        }

        if( $level__not_in ){
            $where .= ' AND pmp_levels.id NOT IN ('. implode(',', $level__not_in ) .')';    
        }
            
        return $where;
    }

    public function filter_widget_posts_distinct( $distinct, $query ){

        global $widget_instance;

        $widget_instance = wp_parse_args( $widget_instance, array(
            'content_cost'      =>  ''
        ) );

        extract( $widget_instance );

        if( $level_type ){
            $distinct = 'DISTINCT';
        }

        return $distinct;
    }

    public function disable_comments_filter(){
        $settings = $this->get_settings();

        if( $settings['disable_comments_filter'] ){
            remove_filter( 'comments_array', 'pmpro_comments_filter', 10, 2 );
            remove_filter( 'comments_open', 'pmpro_comments_filter', 10, 2 );
        }
    }    

    /**
     *
     *
     * Add Premium badge to thumbnail image
     *
     * @since 2.2
     * 
     */
    public function add_thumbnail_paid_badge(){
        global $wpdb, $post;

        $results = $wpdb->query(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}pmpro_memberships_pages WHERE page_id = %s",
                $post->ID
            )
        );

        if( ! $results ){
            return;
        }

        return load_template( plugin_dir_path( __FILE__ ) . 'public/paid-badge.php', false, $this->get_settings() );
    }

    /**
     *
     * Add dashboard menu
     *
     * @since 2.2
     * ]
     */
    public function add_dashboard_menu( $items ){

        $items[ self::PAGE_SLUG ] = array(
            'title'     =>  esc_html__( 'Membership', 'streamtube-core' ),
            'desc'      =>  esc_html__( 'Your membership', 'streamtube-core' ),
            'icon'      =>  'icon-credit-card',
            'callback'  =>  function(){
                load_template( plugin_dir_path( __FILE__ ) . 'public/dashboard/membership.php', true );
            },
            'parent'    =>  'dashboard',
            'cap'       =>  'read',
            'priority'  =>  5,
            'submenu'   =>  array(
                'subscription'  =>  array(
                    'title'     =>  esc_html__( 'Memberships', 'streamtube-core' ),
                    'icon'      =>  'icon-user-o',
                    'callback'  =>  function(){
                        load_template( plugin_dir_path( __FILE__ ) . 'public/dashboard/subscription.php' );
                    },
                    'priority'  =>  10
                ),
                'billing'    =>  array(
                    'title'     =>  esc_html__( 'Billing', 'streamtube-core' ),
                    'icon'      =>  'icon-money',
                    'callback'  =>  function(){
                        load_template( plugin_dir_path( __FILE__ ) . 'public/dashboard/billing.php' );
                    },
                    'priority'  =>  20
                ),
                'invoices'    =>  array(
                    'title'     =>  esc_html__( 'Invoices', 'streamtube-core' ),
                    'icon'      =>  'icon-doc-text',
                    'callback'  =>  function(){
                        load_template( plugin_dir_path( __FILE__ ) . 'public/dashboard/invoices.php' );
                    },
                    'priority'  =>  30
                )
            ),            
        );

        if( function_exists( 'pmproio_displayInviteCodes' ) ){
            $items[ self::PAGE_SLUG ]['submenu']['invite-codes'] = array(
                'title'     =>  esc_html__( 'Invite Codes', 'streamtube-core' ),
                'icon'      =>  'icon-doc-text',
                'callback'  =>  function(){
                    load_template( plugin_dir_path( __FILE__ ) . 'public/dashboard/invite-codes.php' );
                },
                'priority'  =>  40
            );
        }

        if( function_exists( 'pmpro_affiliates_load_textdomain' ) ){
            $items[ self::PAGE_SLUG ]['submenu']['affiliates'] = array(
                'title'     =>  esc_html__( 'Affiliates', 'streamtube-core' ),
                'icon'      =>  'icon-user-plus',
                'callback'  =>  function(){
                    load_template( plugin_dir_path( __FILE__ ) . 'public/dashboard/affiliates.php' );
                },
                'priority'  =>  50
            );            
        }

        return $items;
    }

    /**
     *
     * Add dashboard menu
     *
     * @since 2.2
     * ]
     */
    public function add_profile_menu( $items ){
        $items[self::PAGE_SLUG]  = array(
            'title'         =>  esc_html__( 'Membership', 'streamtube-core' ),
            'icon'          =>  'icon-credit-card',
            'url'           =>  trailingslashit( get_author_posts_url( get_current_user_id() ) ) . 'dashboard/' . self::PAGE_SLUG,
            'priority'      =>  50,
            'private'       =>  true
        );

        return $items;
    }    

    /**
     *
     * Add Require Membership levels widget
     * 
     */
    public function add_membership_levels_widget(){

        $can =  current_user_can( 'administrator' );

        if( ! function_exists( 'pmpro_page_meta' ) ){
            return;
        }

        if( apply_filters( 'streamtube/core/pmp/post/edit/membership', $can ) == true ){
            load_template( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'public/post/edit-membership-levels.php' );
        }
    }

    /**
     *
     * PMPro v3 compatible
     * 
     */
    public function save_membership_levels_widget(){
        if( $this->is_version3() && function_exists( 'pmpro_page_save' ) ){
            if( wp_doing_ajax() && array_key_exists( 'action' , $_REQUEST ) && $_REQUEST['action'] == 'update_post' ){
                add_action( 'save_post', 'pmpro_page_save' );
            }
        }
    }

    /**
     * 
     * Register Elementor Widgets
     *
     * @since 1.0.0
     *
     */
    public function elementor_widgets_registered( $widget_manager ) {
        $this->include_file( 'class-streamtube-core-pmpro-levels-elementor.php' ); 
        $this->include_file( 'class-streamtube-core-pmpro-level-name-elementor.php' ); 
        $this->include_file( 'class-streamtube-core-pmpro-level-description-elementor.php' ); 
        $this->include_file( 'class-streamtube-core-pmpro-level-cost-elementor.php' ); 
        $this->include_file( 'class-streamtube-core-pmpro-level-signup-button-elementor.php' ); 
    }
}