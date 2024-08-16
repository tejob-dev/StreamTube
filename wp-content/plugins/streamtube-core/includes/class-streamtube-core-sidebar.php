<?php
/**
 * Define the sidebar functionality
 *
 *
 * @link       https://themeforest.net/user/phpface
 * @since      1.0.0
 *
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 */

/**
 * Define the profile functionality
 *
 * @since      1.0.0
 * @package    Streamtube_Core
 * @subpackage Streamtube_Core/includes
 * @author     phpface <nttoanbrvt@gmail.com>
 */

if( ! defined('ABSPATH' ) ){
    exit;
}

class Streamtube_Core_Sidebar {

	public function widgets_init(){

		register_sidebar(
			array(
				'name'          => esc_html__( 'User Dashboard', 'streamtube-core' ),
				'id'            => 'user-dashboard',
				'description'   => esc_html__( 'Add widgets here to appear in user dashboard sidebar.', 'streamtube-core' ),
				'before_widget' => '<div class="col-md-4 col-12"><div id="%1$s" class="widget widget-dashboard p-4 bg-white rounded shadow-sm %2$s">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<h5 class="widget-title no-after d-flex border-bottom pb-3 mb-3">',
				'after_title'   => '</h5>',
			)
		);

        register_sidebar(
            array(
                'name'          => esc_html__( 'Advanced Search', 'streamtube-core' ),
                'id'            => 'advanced-search',
                'description'   => esc_html__( 'Add widgets here to appear in Advanced Search sidebar.', 'streamtube-core' ),
                'before_widget' => '<div id="%1$s" class="col-12 col-lg-6 col-xl-6 col-lg-6 widget widget-search-filter %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title-wrap d-flex"><h4 class="search-filter__title">',
                'after_title'   => '</h4></div>'
            )
        );
	}

}