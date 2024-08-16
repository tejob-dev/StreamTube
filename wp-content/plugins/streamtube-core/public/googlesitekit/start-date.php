<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

$start_dates    = streamtube_core()->get()->googlesitekit->analytics->get_start_dates();

$start_date     = isset( $_GET['start_date'] ) ? sanitize_text_field( wp_unslash($_GET['start_date']) ) : '7daysAgo';
$end_date       = isset( $_GET['end_date'] ) ? sanitize_text_field( wp_unslash($_GET['end_date']) ) : 'yesterday';

/**
 *
 * Filter default date
 * 
 * @since 1.0.8
 */
$default = apply_filters( 'streamtube/core/analytics/date_default', '7daysAgo', $start_dates );

// get current request date
$current_start_date = $start_date ? $start_date : $default;

?>
<?php printf(
    '<div class="date-selection dropdown %s">',
    ! is_rtl() ? 'ms-auto' : 'me-auto'
);?>

    <button class="btn btn-text dropdown-toggle border text-secondary bg-white" type="button" id="start_date" data-bs-toggle="dropdown" aria-expanded="false">
        <?php 
        if( array_key_exists( $current_start_date, $start_dates ) ){
            echo $start_dates[ $current_start_date ];
        }else{
            printf(
                esc_html__( '%s to %s' ),
                '<strong>'. date( 'Y-M-d', strtotime( $start_date ) ) .'</strong>',
                '<strong>'. date( 'Y-M-d', strtotime( $end_date ) ) .'</strong>'
            );
        }
        ?>
    </button>

    <ul class="dropdown-menu p-3" aria-labelledby="start_date">

        <?php foreach ( $start_dates as $date => $label ): ?>
                
            <?php printf(
                '<li><a class="dropdown-item %s" href="%s">%s</a></li>',
                $current_start_date == $date ? 'active' : '',
                esc_url( add_query_arg( array(
                    'start_date'    =>  $date,
                    'end_date'      =>  'today'
                ) ) ),
                $label
            );?>

        <?php endforeach; ?>
        <li class="date-ranges mt-3">
            <div class="row">
                <p><?php esc_html_e( 'Custom', 'streamtube-core' );?></p>
                <form>
                    <p>
                        <?php printf(
                            '<input class="form-control" type="date" name="start_date" value="%s">',
                            $start_date ? esc_attr( date( 'Y-m-d', strtotime( $start_date ) ) ) : ''
                        );?>
                    </p>
                    <p>
                        <?php printf(
                            '<input class="form-control" type="date" name="end_date" value="%s">',
                            $end_date ? esc_attr( date( 'Y-m-d', strtotime( $end_date ) ) ) : ''
                        );?>
                    </p>

                    <p class="form-submit">

                        <?php if( array_key_exists( $current_start_date, $start_dates ) ){
                            sprintf(
                                '<input class="form-control" type="date" name="start_date" value="%s">',
                                esc_attr( $start_dates[ $current_start_date ] )
                            );
                        }?>

                        <button class="btn btn-primary btn-sm w-100">
                            <?php esc_html_e( 'Search', 'streamtube-core' );?>
                        </button>
                    </p>                    
                </form>              
            </div>
        </li>
    </ul>
</div>