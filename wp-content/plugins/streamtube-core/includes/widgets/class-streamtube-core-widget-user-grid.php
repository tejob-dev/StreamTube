<?php
/**
 * Define the custom User Grid widget functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class Streamtube_Core_Widget_User_Grid extends WP_Widget{
    /**
     * {@inheritDoc}
     * @see WP_Widget::__construct()
     */
    function __construct(){
    
        parent::__construct( 
            'user-grid-widget' ,
            esc_html__('[StreamTube] User Grid', 'streamtube-core' ), 
            array( 
                'classname'     =>  'user-grid-widget streamtube-widget', 
                'description'   =>  esc_html__('[StreamTube] User Grid', 'streamtube-core')
            )
        );
    }

    /**
     * Register this widget
     */
    public static function register(){
        register_widget( __CLASS__ );
    }

    /**
     *
     * AJAX load more users
     * 
     * @since  1.0.0
     * 
     */
    public static function ajax_load_more_users(){

        check_ajax_referer( '_wpnonce' );

        if( ! isset( $_POST['data'] ) ){
            wp_send_json_error( array(
                'code'      =>  'invalid_request',
                'message'   =>  esc_html__( 'Invalid Request.', 'streamtube-core' )
            ) );
        }

        $data = json_decode( wp_unslash( $_POST['data'] ), true );

        $data = wp_parse_args( $data, array(
            'paged'     =>  1,
        ) );

        $data['paged'] = (int)$data['paged']+1;

        ob_start();

        the_widget( __CLASS__, array_merge( $data, array(
            'title'             =>  '',
            'include_search'    =>  false,
            'include_sortby'    =>  false,
            'paged'             => $data['paged']
        ) ), array() );        

        $output = ob_get_clean();

        wp_send_json_success( array(
            'message'   =>  esc_html__( 'OK', 'streamtube-core' ),
            'data'      =>  json_encode( $data ),
            'output'    =>  trim( $output )
        ) );
    }

    /**
     *
     * Follow Types
     * 
     * @return array
     */
    public static function get_follow_types(){
        return array(
            'current_logged_in_following'   =>  esc_html__( 'Current logged-in user\'s following', 'streamtube-core' ),
            'current_logged_in_follower'    =>  esc_html__( 'Current logged-in user\'s followers', 'streamtube-core' ),
            'current_author_following'      =>  esc_html__( 'Current author\'s following', 'streamtube-core' ),
            'current_author_follower'       =>  esc_html__( 'Current author\'s followers', 'streamtube-core' )
        );
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::widget()
     */
    public function widget( $args, $instance ) {

        $output = '';

        $instance = wp_parse_args( $instance, array(
            'title'             =>  '',
            'number'            =>  12,
            'search_columns'    =>  array( 
                'user_login', 
                'user_url', 
                'user_email', 
                'user_nicename', 
                'display_name'
            ),
            'role__in'                      =>  array(),
            'orderby'                       =>  'login',
            'order'                         =>  'ASC',
            'has_published_posts'           =>  '',
            'include'                       =>  array(),
            'paged'                         =>  1,
            'include_search'                =>  '',
            'search'                        =>  '',
            'include_sortby'                =>  '',
            'current_logged_in'             =>  get_current_user_id(),
            'current_author'                =>  0,
            'follow_type'                   =>  '',
            'count_post_type'               =>  'video',
            'margin_bottom'                 =>  4,
            'col_xxl'                       =>  4,
            'col_xl'                        =>  4,
            'col_lg'                        =>  4,
            'col_md'                        =>  2,
            'col_sm'                        =>  2,
            'col'                           =>  1
        ) );

        if( isset( $_REQUEST['search_query'] ) ){
            $instance['search'] = wp_unslash( $_REQUEST['search_query'] );
        }

        $instance['title'] = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

        if( is_singular() ){
            global $post;
            $instance['current_author'] = $post->post_author;
        }

        if( is_author() ){
            $instance['current_author'] = get_queried_object_id();
        }

        /**
         *
         * Filter the instance
         * 
         * @var array $instance
         *
         * @since  1.0.0
         * 
         */
        $instance = apply_filters( 'streamtube/core/widget/user_grid/instance', $instance, $this );

        $GLOBALS['widget_instance'] = $instance;

        extract( $instance );      

        if( is_string( $search_columns ) ){
            $search_columns = explode( "," , $search_columns );
        }

        if( $search ){
            $search = '*' . $search . '*';
        }

        if( isset( $_REQUEST['orderby'] ) ){
            switch ( $_REQUEST['orderby'] ) {
                case 'popular':
                    $orderby = 'post_count';
                    $order   = 'ASC';
                break;

                case 'newest':
                    $orderby = 'registered';
                    $order   = 'DESC';
                break;

                case 'oldest':
                    $orderby = 'registered';
                    $order   = 'ASC';
                break;              

                case 'name':
                    $orderby = 'name';
                break;
            }
        }

        if( $role__in && is_string( $role__in ) ){
            $role__in = array_map( 'trim', explode( ',', $role__in ) );    
        }

        if( $follow_type && function_exists( 'wpuf_get_follow_users' ) ){
            switch ( $follow_type ) {
                case 'current_logged_in_following':
                    $include = wpuf_get_follow_users( $current_logged_in );
                break;

                case 'current_logged_in_follower':
                    $include = wpuf_get_follow_users( $current_logged_in, 'follower' );
                break;      

                case 'current_author_following':
                    $include = wpuf_get_follow_users( $current_author );
                break;
                    
                case 'current_author_follower':
                    $include = wpuf_get_follow_users( $current_author, 'follower' );
                break;
            }

            if( ! $include ){
                $include = array( 99999999 );
            }
        }

        if( $has_published_posts  && is_string( $has_published_posts ) ){
            $has_published_posts = array_map( 'trim', explode( ',', $has_published_posts ) );
        }

        $role__not_in = array(
            Streamtube_Core_Permission::ROLE_DEACTIVATE,
            Streamtube_Core_Permission::ROLE_SPAMMER
        );

        $query_args = compact(
            'number',
            'search_columns',
            'search',
            'orderby',
            'order',
            'has_published_posts',
            'role__in',
            'role__not_in',
            'include',
            'paged'
        );

        /**
         *
         * Filter the instance
         * 
         * @var array $instance
         *
         * @since  1.0.0
         * 
         */
        $query_args = apply_filters( 'streamtube/core/widget/user_grid/query_args', $query_args, $instance, $this );

        $user_query = new WP_User_Query( $query_args );

        $row_classes = array( 'row' );
        $row_classes[] = 'row-cols-' . $col;
        $row_classes[] = 'row-cols-sm-' . $col_sm;
        $row_classes[] = 'row-cols-md-' . $col_md;
        $row_classes[] = 'row-cols-lg-' . $col_lg;
        $row_classes[] = 'row-cols-xl-' . $col_xl;
        $row_classes[] = 'row-cols-xxl-' . $col_xxl;

        ob_start();

        if( $include_search || $include_sortby || $title ){

            echo $args['before_title'];

                if( $title ){
                    printf(
                        '<div class="me-3">%s</div>',
                        $title
                    );
                }

                if( $include_search ){
                    echo '<div class="d-none d-lg-block">';
                        streamtube_core_load_template( 'user/search-form.php' );
                    echo '</div>';
                }

                if( $include_sortby ){
                    ?>
                    <div class="ms-auto">
                        <?php streamtube_core_load_template( 'user/sortby.php' ); ?>
                    </div>
                    <?php
                }                                

            echo $args['after_title'];
        }    

        if( $user_query->get_results() ):

            $GLOBALS['count_post_type'] = $instance['count_post_type'];

            printf(
                '<div id="members-list" data-paged="%s" class="post-grid members-grid item-list"><div class="%s">',
                esc_attr( $paged ),
                esc_attr( join( ' ', $row_classes ) )
            );

            foreach ( $user_query->get_results() as $user ):

                printf(
                    '<div class="mb-%s user-item">',
                    $margin_bottom
                );

                    streamtube_core_load_template( 'user/card.php', false, $user );

                echo '</div>';

            endforeach;

            echo '</div></div><!--.members-grid-->';

            unset( $GLOBALS['count_post_type'] );

        else:
            if( $search || ! isset( $_POST['action'] ) || $_POST['action'] != 'load_more_users' ) :
            ?>
            <div class="not-found p-3 text-center text-muted fw-normal h6">
                <p class="text-muted">
                    <?php if( $search ){
                        esc_html_e( 'No users matched your search terms.', 'streamtube-core' );
                    }else{
                        esc_html_e( 'No users were found.', 'streamtube-core' );
                    }?>
                </p>
            </div>
            <?php          
            endif;
        endif;

        if( $user_query->total_users > (int)$number && ! wp_doing_ajax() ):
            // load more button.
            ?>
            <div class="d-flex justify-content-center navigation border-bottom py-2 position-relative">

                <?php if( get_option( 'user_list_pagination', 'click' ) == 'click' ) : ?>

                    <?php printf(
                        '<button type="button" class="btn border text-secondary load-users load-on-click bg-light" data-params="%s" data-action="load_more_users">',
                        esc_attr( json_encode( $instance ) )
                    );?>
                        <span class="load-icon icon-angle-down position-absolute top-50 start-50 translate-middle"></span>
                    </button>                       

                <?php else:?>

                    <span class="spinner spinner-border text-info" role="status">
                        <?php printf(
                            '<button class="btn jsappear load-users" data-params="%s" data-action="load_more_users">',
                            esc_attr( json_encode( $instance ) )
                        );?>
                            <span class="visually-hidden"><?php esc_html_e( 'Loading', 'streamtube-core' ); ?></span>
                        </button>
                    </span>

                <?php endif;?>
            </div>
            <?php
        endif;

        $output = ob_get_clean();

        if( isset( $_POST['action'] ) && $_POST['action'] == 'load_more_users' ){
            echo $output;
        }else{
            echo $args['before_widget'];

                printf(
                    '<div class="archive-user user-grid">%s</div>',
                    $output
                );                

            echo $args['after_widget'];            
        }

        unset( $GLOBALS['widget_instance'] );
    }

    /**
     * {@inheritDoc}
     * @see WP_Widget::update()
     */
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    /**
     *
     * Get the tabs
     * 
     * @return array
     *
     * @since  1.0.0
     * 
     */
    private function tabs(){

        $tabs = array();

        $tabs['appearance'] = array(
            'title'     =>  esc_html__( 'Appearance', 'streamtube-core' ),
            'callback'  =>  array( $this , 'tab_appearance' )
        );

        $tabs['layout'] = array(
            'title'     =>  esc_html__( 'Layout', 'streamtube-core' ),
            'callback'  =>  array( $this , 'tab_layout' )
        );        

        $tabs['data_source'] = array(
            'title'     =>  esc_html__( 'Data Source', 'streamtube-core' ),
            'callback'  =>  array( $this , 'tab_data_source' )
        );        

        return apply_filters( 'streamtube/core/widget/user_grid/tabs', $tabs, $this );
    }    

    public function tab_appearance( $instance ){

        $instance = wp_parse_args( $instance, array(
            'title'                 =>  '',
            'number'                =>  12,
            'orderby'               =>  'login',
            'order'                 =>  'ASC',
            'count_post_type'       =>  'video'
        ) );

        ob_start();

        ?>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'title' ) ),
                esc_html__( 'Title', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'title' ) ),
                esc_attr( $this->get_field_name( 'title' ) ),
                esc_attr( $instance['title'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'number' ) ),
                esc_html__( 'Number', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'number' ) ),
                esc_attr( $this->get_field_name( 'number' ) ),
                esc_attr( $instance['number'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'orderby' ) ),
                esc_html__( 'Order By', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'orderby' ) ),
                esc_attr( $this->get_field_name( 'orderby' ) ),
                esc_attr( $instance['orderby'] )
            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'order' ) ),
                esc_html__( 'Order', 'streamtube-core')

            );?>

            <?php printf(
                '<select class="widefat" id="%s" name="%s" />',
                esc_attr( $this->get_field_id( 'order' ) ),
                esc_attr( $this->get_field_name( 'order' ) )

            );?>

                <?php foreach( streamtube_core_get_order_options() as $order => $order_text ):?>

                    <?php printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $order ),
                        selected( $instance['order'], $order, false ),
                        esc_html( $order_text )
                    );?>

                <?php endforeach;?>


            </select><!-- end <?php echo $this->get_field_id( 'order' );?> -->
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'count_post_type' ) ),
                esc_html__( 'Post Type Count', 'streamtube-core')

            );?>

            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'count_post_type' ) ),
                esc_attr( $this->get_field_name( 'count_post_type' ) ),
                esc_attr( $instance['count_post_type'] )
            );?>
        </div>        

        <?php

        return ob_get_clean();

    }

    public function tab_data_source( $instance ){

        global $wp_roles;

        $instance = wp_parse_args( $instance, array(
            'search'                =>  '',
            'role__in'              =>  '',
            'has_published_posts'   =>  '',
            'follow_type'           =>  ''
        ) );

        if( is_string( $instance['role__in'] ) ){
            $instance['role__in'] = array_map( 'trim' , explode( ',' , $instance['role__in'] ) );
        }

        if( is_string( $instance['has_published_posts'] ) ){
            $instance['has_published_posts'] = array_map( 'trim' , explode( ',' , $instance['has_published_posts'] ) );
        }           

        ob_start();

        ?>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'number' ) ),
                esc_html__( 'Search', 'streamtube-core')
            );?>
            
            <?php printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'search' ) ),
                esc_attr( $this->get_field_name( 'search' ) ),
                esc_attr( $instance['search'] )
            );?>

            <span class="field-help">
                <?php esc_html_e( 'Retrieve users by searching for keywords', 'streamtube-core' );?>
            </span>
        </div>           

        <div class="field-group" style="max-height: 300px;overflow: scroll;">
            <?php printf(
                '<label>%s</label>',
                esc_html__( 'Roles', 'streamtube-core')
            );?>

            <?php foreach ( $wp_roles->roles as $role => $value ): ?>
                <div class="field-control">
                    <label>
                    <?php printf(
                        '<input type="checkbox" class="widefat" name="%s[]" value="%s" %s/> %s',
                        esc_attr( $this->get_field_name( 'role__in' ) ),
                        esc_attr( $role ),
                        in_array( $role , $instance['role__in'] ) ? 'checked' : '',
                        $value['name']
                    );?>
                    </label>
                </div>
            <?php endforeach ?>     
        
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'has_published_posts' ) ),
                esc_html__( 'Has Published Post Types', 'streamtube-core')
            );?>
            
            <?php printf(
                '<select multiple="multiple" class="widefat select-select2" id="%s" name="%s[]">',
                esc_attr( $this->get_field_id( 'has_published_posts' ) ),
                esc_attr( $this->get_field_name( 'has_published_posts' ) )
            );?>

                <?php
                foreach ( get_post_types() as $key => $value) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $key ),
                        in_array( $key, $instance['has_published_posts'] ) ? 'selected' : '',
                        esc_html( $value )
                    );
                }
                ?>
            </select>
        </div>      

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'follow_type' ) ),
                esc_html__( 'Follow', 'streamtube-core')

            );?>

            <?php printf(
                '<select class="widefat" id="%s" name="%s"/>',
                esc_attr( $this->get_field_id( 'follow_type' ) ),
                esc_attr( $this->get_field_name( 'follow_type' ) )

            );?>

                <option value=""><?php esc_html_e( 'Select...', 'streamtube-core' );?></option>

                <?php
                foreach ( self::get_follow_types() as $type => $text ) {
                    printf(
                        '<option value="%s" %s>%s</option>',
                        esc_attr( $type ),
                        selected( $type, $instance['follow_type'] ),
                        esc_html( $text )
                    );
                }
                ?>

            </select>
        
        </div>        
        <?php

        return ob_get_clean();
    }

    /**
     *
     * The layout tab
     * 
     * @param  array $instance
     * @return html
     *
     * @since  1.0.0
     * 
     */
    public function tab_layout( $instance ){

        $instance = wp_parse_args( $instance, array(    
            'margin_bottom'         =>  4,
            'col_xxl'               =>  4,
            'col_xl'                =>  4,
            'col_lg'                =>  2,
            'col_md'                =>  2,
            'col_sm'                =>  1,
            'col'                   =>  1
        ) );

        ob_start();

        ?>      

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'margin_bottom' ) ),
                esc_html__( 'Margin Bottom', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'margin_bottom' ) ),
                esc_attr( $this->get_field_name( 'margin_bottom' ) ),
                esc_attr( $instance['margin_bottom'] )

            );?>
        </div>  

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_xxl' ) ),
                esc_html__( 'Columns - Extra extra large ≥1400px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_xxl' ) ),
                esc_attr( $this->get_field_name( 'col_xxl' ) ),
                esc_attr( $instance['col_xxl'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_xl' ) ),
                esc_html__( 'Columns - Extra large ≥1200px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_xl' ) ),
                esc_attr( $this->get_field_name( 'col_xl' ) ),
                esc_attr( $instance['col_xl'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_lg' ) ),
                esc_html__( 'Columns - Large ≥992px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_lg' ) ),
                esc_attr( $this->get_field_name( 'col_lg' ) ),
                esc_attr( $instance['col_lg'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_md' ) ),
                esc_html__( 'Columns - Medium ≥768px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_md' ) ),
                esc_attr( $this->get_field_name( 'col_md' ) ),
                esc_attr( $instance['col_md'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col_sm' ) ),
                esc_html__( 'Columns - Small ≥576px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col_sm' ) ),
                esc_attr( $this->get_field_name( 'col_sm' ) ),
                esc_attr( $instance['col_sm'] )

            );?>
        </div>

        <div class="field-control">
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $this->get_field_id( 'col' ) ),
                esc_html__( 'Columns - Extra small <576px', 'streamtube-core')

            );?>
            
            <?php printf(
                '<input type="number" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $this->get_field_id( 'col' ) ),
                esc_attr( $this->get_field_name( 'col' ) ),
                esc_attr( $instance['col'] )

            );?>
        </div>
        <?php

        return ob_get_clean();
    }      

    /**
     * {@inheritDoc}
     * @see WP_Widget::form()
     */
    public function form( $instance ){

        wp_enqueue_style( 'select2' );
        wp_enqueue_script( 'select2' );

        $instance = wp_parse_args( $instance, array(
            'tab'   =>  'appearance'
        ) );

        $tabs = $this->tabs();

        echo '<div class="streamtube-widget-content">';

            echo '<ul class="nav nav-tabs widget-tabs">';

                foreach ( $tabs as $tab => $value ):

                    printf(
                        '<li class="nav-item" role="presentation">
                            <a class="nav-link %s" id="%2$s-tab" href="#%2$s">%3$s</a>
                        </li>',
                        $instance['tab'] == $tab ? 'active' : '',
                        esc_attr( $tab ),
                        esc_html( $value['title'] )
                    );

                endforeach;

            echo '</ul>';


            echo '<div class="tab-content widget-tab-content">';

                foreach ( $tabs as $tab => $value ):

                    printf(
                        '<div class="tab-pane %s" id="%s">',
                        $instance['tab'] == $tab ? 'active' : '',
                        esc_attr( $tab )
                    );

                    do_action( "streamtube/core/widget/posts/tab/{$tab}/before", $instance, $this );

                        if( is_callable( $value['callback'] ) ){
                            echo call_user_func( $value['callback'], $instance );   
                        }

                    do_action( "streamtube/core/widget/posts/tab/{$tab}/after", $instance, $this );

                    echo '</div>';

                endforeach;

                printf(
                    '<input class="current-tab" type="hidden" id="%s" name="%s" value="%s" />',
                    esc_attr( $this->get_field_id( 'tab' ) ),
                    esc_attr( $this->get_field_name( 'tab' ) ),
                    esc_attr( $instance['tab'] )

                );

            echo '</div><!--.tab-content-->';

            ?>
            <script type="text/javascript">
                jQuery(function () {
                    jQuery( '.select-select2' ).select2();
                });
            </script>
            <?php

        echo '</div><!--.streamtube-widget-content-->';

    }
}