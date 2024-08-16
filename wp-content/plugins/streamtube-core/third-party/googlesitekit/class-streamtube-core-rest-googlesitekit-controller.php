<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the rest functionality.
 *
 * @since      1.0.8
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class StreamTube_Core_GoogleSiteKit_Rest_Controller extends StreamTube_Core_Rest_API{

    /**
     *
     * Get date ranges
     * 
     * @param  WP_Rest_Request  $request
     * @param  boolean $compare
     * @return array or WP_Error
     */
    protected function get_date_ranges( $start_date = '7daysAgo', $end_date = 'yesterday', $compare = true ){

        $dateRanges = array();

        $date_diff = 0;

        $startDate  = $endDate = $compareStartDate = $compareEndDate = '';
        
        if( $start_date == 'today' ){
            $end_date = 'today';
        }

        $startDate      = date( 'Y-m-d', strtotime( $start_date ) );
        $endDate        = date( 'Y-m-d', strtotime( $end_date ) );

        $date_diff = (int)date_diff( date_create( $startDate ), date_create( $endDate ))->format("%R%a");

        if( $date_diff < 0 ){
            return new WP_Error( 
                'invalid_date_range',
                esc_html__( 'Invalid Date Ranges', 'streamtube-core' )
            );
        }

        if( $compare ){
            $compareEndDate     = date( 'Y-m-d', strtotime( "-1 day", strtotime( $startDate ) ));            
            $compareStartDate   = date( 'Y-m-d', strtotime( "-{$date_diff} days", strtotime( $compareEndDate ) ));
        }

        $dateRanges[] = compact( 'startDate', 'endDate' );

        if( $compareStartDate && $compareEndDate ){
            $dateRanges[] = array(
                'startDate'     =>  $compareStartDate,
                'endDate'       =>  $compareEndDate
            );
        };

        return $dateRanges;
    }
}