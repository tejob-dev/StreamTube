<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the rest functionality.
 *
 *
 * @since      1.0.6
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */
class StreamTube_Core_Rest_API{
    /**
     *
     * Holds the namespace
     * 
     * @var string
     */
    protected $namespace = 'streamtube/';

    /**
     *
     * Holds the verion
     * 
     * @var string
     */
    protected $version = 'v1';

    /**
     *
     * Holds the path
     * 
     * @var string
     *
     * @since 1.0.3
     */
    protected $path       =   '';    

    /**
     *
     * Get rest URL
     * 
     * @return string
     *
     * @since 1.0.3
     * 
     */
    public function get_rest_url( $path = '' ){
        return rest_url( "{$this->namespace}{$this->version}{$this->path}{$path}" );
    }
}