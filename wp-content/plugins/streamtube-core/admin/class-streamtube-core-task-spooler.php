<?php
/**
 * Define the Task Spooler functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      2.1
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 *
 * @since      2.1
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Streamtube_Core_Task_Spooler{

    /**
     *
     * Admin menu
     * 
     * @since 2.1
     */
    public function admin_menu(){
        add_menu_page( 
            esc_html__( 'Task Spooler', 'streamtube-core' ), 
            esc_html__( 'Task Spooler', 'streamtube-core' ), 
            'administrator', 
            'task-spooler', 
            array( $this, 'task_spooler' ),
            'dashicons-calendar',
            50
        );
    }    

    /**
     *
     * The Task Spooler table
     * 
     * @since 2.1
     */
    public function task_spooler(){
        load_template( plugin_dir_path( __FILE__ ) . 'partials/task-spooler.php' );
    }    

    /**
     *
     * Check if can exec
     * 
     * @return boolean
     *
     * @since 2.1
     * 
     */
    public static function can_exec(){
        if( ! function_exists( 'exec' ) || ! function_exists( 'shell_exec' ) ){
            return false;
        }

        return true;        
    }

    /**
     *
     * Get all jobs
     *
     * @since 2.1
     * 
     */
    public static function get_tasks( $tsp_path = '' ){

        /**
         *
         * Holds the exec output
         * 
         * @var array
         *
         * @since 2.1
         * 
         */
        $output = array();

        /**
         *
         * Holds the result code
         * 
         * @var integer
         *
         * @since 2.1
         * 
         */
        $result_code = 0;

        /**
         *
         * Holds the data for wp table
         * 
         * @var array
         *
         * @since 2.1
         * 
         */
        $data   = array();

        if( ! self::can_exec() ){
            return $data;
        }

        $cmd = "{$tsp_path}";

        if( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ){
            $seach = '"'.sanitize_text_field( wp_unslash( $_GET['s'] ) ).'"';
            $cmd .= " | grep {$seach}";
        }

        exec( $cmd, $output, $result_code );

        if( is_array( $output ) && count( $output ) > 0 ){
            for ( $i=1;  $i < count( $output );  $i++ ) {

                $task = explode( " ", preg_replace('!\s+!', ' ', $output[$i] ) );

                $data[] = array(
                    'id'        =>  $task[0],
                    'pid'       =>  shell_exec( "{$tsp_path} -p {$task[0]}" ),
                    'status'    =>  $task[1],
                    'content'   =>  $output[$i]
                );
            }
        }

        return $data;
    }

    public static function clear_finished_tasks( $tsp_path ){
        if( self::can_exec() ){
            exec( "$tsp_path -C" );
        }
    }

    public static function delete_task( $tsp_path, $task_id = -1 ){
        if( self::can_exec() ){
            exec( "$tsp_path -r {$task_id}" );
        }
    }    

    public static function get_task_log( $tsp_path, $task_id = 0 ){
        if( self::can_exec() ){
            return shell_exec( "{$tsp_path} -c {$task_id}" );
        }
        return false;
    }
}

/**
 *
 * Task Spooler table
 *
 * @since 2.1
 * 
 */
class Streamtube_Core_Task_Spooler_Table extends WP_List_Table{

    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items(){
        $columns        = $this->get_columns();

        $data           = $this->table_data();

        $perPage        = 20;
        $currentPage    = $this->get_pagenum();
        $totalItems     = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns);
        $this->items = $data;
    }    

    protected function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">

            <?php if ( $this->has_items() ) : ?>
            <div class="alignleft actions bulkactions">
                <?php $this->bulk_actions( $which ); ?>
            </div>
                <?php
            endif;
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>

            <br class="clear" />
        </div>
        <?php
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns(){
        $columns = array(
            'id'            => esc_html__( 'Task ID', 'streamtube-core' ),
            'pid'           => esc_html__( 'PID', 'streamtube-core' ),
            'status'        => esc_html__( 'Status', 'streamtube-core' ),
            'content'       => esc_html__( 'Content', 'streamtube-core' ),
            'log'           => esc_html__( 'Log', 'streamtube-core' ),
            'action'        => esc_html__( 'Action', 'streamtube-core' )
        );

        return $columns;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name ){

        switch ( $column_name ) {
            case 'status':
                return sprintf(
                    '<span class="badge bg-%s">%s</span>',
                    $item[ $column_name ],
                    ucwords( $item[ $column_name ] )
                );
            break;

            case 'log':
                return sprintf(
                    '<a href="%s" class="button button-small button-secondary thickbox">%s</a>',
                    esc_url( add_query_arg( array(
                        'action'        =>  'read_task_log_content',
                        'task_id'       =>  $item['id'],
                        'TB_iframe'     =>  true,
                        'width'         =>  700,
                        'height'        =>  400,
                    ), admin_url( 'admin-ajax.php' ) ) ),
                    esc_html__( 'Log', 'streamtube-core' )
                );
            break;

            case 'action':
                return sprintf(
                    '<a class="button button-small button-primary" href="%s">%s</a>',
                    esc_url( add_query_arg(
                        array(
                            'action'    =>  'delete',
                            'task_id'   =>  $item['id']
                        )
                    ) ),
                    esc_html__( 'Delete', 'streamtube-core' )
                );
            break;
            
            default:
                return $item[ $column_name ];
            break;
        }
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data( $tsp_path = 'tsp' ){

        $data = array();

        $results = Streamtube_Core_Task_Spooler::get_tasks( $tsp_path );

        if( $results ){
            for ( $i=0; $i < count( $results ); $i++) { 
                $data[] = array_merge( $results[$i], array(
                    'log'   =>  ''
                ) );
            }
        }

        return $data;
    }

    public function clear_finished_job(){

    }
}