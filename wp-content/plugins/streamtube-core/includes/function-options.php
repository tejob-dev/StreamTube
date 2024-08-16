<?php
if( ! defined('ABSPATH' ) ){
    exit;
}

/**
 *
 * Heading options
 * 
 * @return array
 */
function streamtube_core_get_heading_options(){
	return array(
        'h1'    =>  'H1',
        'h2'    =>  'H2',
        'h3'    =>  'H3',
        'h4'    =>  'H4',
        'h5'    =>  'H5',
        'h6'    =>  'H6'
    );
}

/**
 *
 * Text styles
 * 
 * @return array
 * 
 */
function streamtube_core_get_text_styles(){
	return array(
		'primary'	=>	esc_html__( 'Primary', 'streamtube-core' ),
		'secondary'	=>	esc_html__( 'Secondary', 'streamtube-core' ),
		'success'	=>	esc_html__( 'Success', 'streamtube-core' ),
		'danger'	=>	esc_html__( 'Danger', 'streamtube-core' ),
		'warning'	=>	esc_html__( 'Warning', 'streamtube-core' ),
		'info'		=>	esc_html__( 'Info', 'streamtube-core' ),
		'light'		=>	esc_html__( 'Light', 'streamtube-core' ),
		'white'		=>	esc_html__( 'White', 'streamtube-core' ),
		'dark'		=>	esc_html__( 'Dark', 'streamtube-core' ),
		'body'		=>	esc_html__( 'Body', 'streamtube-core' ),
		'muted'		=>	esc_html__( 'Muted', 'streamtube-core' )
	);
}

/**
 *
 * Button styles
 * 
 * @return array
 * 
 */
function streamtube_core_get_button_styles(){
	return array(
		'danger'	=>	esc_html__( 'Danger', 'streamtube-core' ),
		'success'	=>	esc_html__( 'Success', 'streamtube-core' ),
		'info'		=>	esc_html__( 'Info', 'streamtube-core' ),
		'secondary'	=>	esc_html__( 'Secondary', 'streamtube-core' ),
		'warning'	=>	esc_html__( 'Warning', 'streamtube-core' ),
		'light'		=>	esc_html__( 'Light', 'streamtube-core' ),
		'dark'		=>	esc_html__( 'Dark', 'streamtube-core' )
	);
}

/**
 *
 * Get thumbnail sizes
 * 
 * @return array
 */
function streamtube_core_get_thumbnail_sizes(){

	$sizes = array(
		'full'	=>	esc_html__( 'full', 'streamtube-core' )
	);

	$default_image_sizes = array( 'thumbnail', 'medium', 'large' );

	global $_wp_additional_image_sizes;

	foreach ( $default_image_sizes as $key ) {
		$sizes[ $key ] = sprintf( 
			'%s (%sx%spx)', 
			$key, 
			intval( get_option( "{$key}_size_w") ), 
			intval( get_option( "{$key}_size_h") ) 
		);
	}

	if( ! $_wp_additional_image_sizes ){
		return $sizes;
	}

	foreach ( $_wp_additional_image_sizes as $key => $value ) {
		$sizes[ $key ] = sprintf( '%s (%sx%spx)', $key, $value['width'], $value['height'] );
	}	

	return $sizes;	
}

/**
 *
 * Get list type for displaying filter
 * 
 * @return array()
 * 
 */
function streamtube_core_get_list_types(){
	return array(
        'list'  =>  esc_html__( 'List', 'streamtube-core' ),
        'cloud' =>  esc_html__( 'Cloud', 'streamtube-core' )
    );
}

/**
 *
 * Get term orderby options
 * 
 * @return array
 *
 * @since 1.0.8
 * 
 */
function streamtube_core_get_term_orderby_options(){
	return array(
        'name'              =>  esc_html__( 'Name', 'streamtube-core' ),
        'slug'              =>  esc_html__( 'Slug', 'streamtube-core' ),
        'term_group'        =>  esc_html__( 'Term Group', 'streamtube-core' ),
        'term_id'           =>  esc_html__( 'Term ID', 'streamtube-core' ),
        'id'                =>  esc_html__( 'ID', 'streamtube-core' ),
        'description'       =>  esc_html__( 'Description', 'streamtube-core' ),
        'parent'            =>  esc_html__( 'Parent', 'streamtube-core' ),
        'count'             =>  esc_html__( 'Count', 'streamtube-core' )
    );	
}

/**
 *
 * Get orderby options
 * 
 * @return array
 *
 * @since 1.0.8
 * 
 */
function streamtube_core_get_orderby_options(){
	$orderby = array(
        'none'              =>  esc_html__( 'None', 'streamtube-core' ),
        'ID'                =>  esc_html__( 'Order by post id.', 'streamtube-core' ),
        'author'            =>  esc_html__( 'Order by author', 'streamtube-core' ),
        'title'             =>  esc_html__( 'Order by post title', 'streamtube-core' ),
        'name'              =>  esc_html__( 'Order by post slug', 'streamtube-core' ),
        'date'              =>  esc_html__( 'Order by date (default)', 'streamtube-core' ),
        'modified'          =>  esc_html__( 'Order by last modified date.', 'streamtube-core' ),
        'rand'              =>  esc_html__( 'Random order', 'streamtube-core' ),
        'comment_count'     =>  esc_html__( 'Order by number of comments', 'streamtube-core' ),
        'relevance'         =>  esc_html__( 'Relevance', 'streamtube-core' )
    );

    if( class_exists( 'Streamtube_Core_GoogleSiteKit_Analytics' ) ){
	    $googleSitekitAnalytics = new Streamtube_Core_GoogleSiteKit_Analytics();

		if( $googleSitekitAnalytics->is_connected() ){
			$orderby['post_view']	= esc_html__( 'Order by number of views', 'streamtube-core' );
		}    
	}

    return apply_filters( 'streamtube_core_get_orderby_options', $orderby );
}

/**
 *
 * Get order options
 * 
 * @return array
 *
 * @since 1.0.8
 * 
 */
function streamtube_core_get_order_options(){
	return array(
		'ASC'				=>	esc_html__( 'Ascending', 'streamtube-core' ),
		'DESC'				=>	esc_html__( 'Descending (default).', 'streamtube-core' )
	);
}

/**
 *
 * Get post view types
 * 
 * @return array
 *
 * @since 1.0.8
 * 
 */
function streamtube_core_get_post_view_types(){
	$types = array(
		'pageviews'			=>	esc_html__( 'Page Views', 'streamtube-core' ),
		'videoviews'		=>	esc_html__( 'Video Views', 'streamtube-core' )
	);

	return $types;
}


/**
 *
 * Get default date ranges
 * 
 * @return array $date_ranges
 *
 * @since  1.0.0
 * 
 */
function streamtube_core_get_default_date_ranges(){
	$date_ranges = array(
		'today'			=>	esc_html__( 'Today', 'streamtube-core' ),
		'yesterday'		=>	esc_html__( 'Yesterday', 'streamtube-core' ),
		'7daysAgo'		=>	esc_html__( 'Last 7 days', 'streamtube-core' ),
		'15daysAgo'		=>	esc_html__( 'Last 15 days', 'streamtube-core' ),
		'28daysAgo'		=>	esc_html__( 'Last 28 days', 'streamtube-core' ),
		'90daysAgo'		=>	esc_html__( 'Last 90 days', 'streamtube-core' ),
		'180daysAgo'	=>	esc_html__( 'Last 180 days', 'streamtube-core' ),
		'all'			=>	esc_html__( 'All the time', 'streamtube-core' )
	);

	/**
	 *
	 * Filter default date ranges
	 *
	 * @param  array $date_ranges
	 *
	 * @since  1.0.0
	 * 
	 */
	return apply_filters( 'streamtube_core_get_default_date_ranges', $date_ranges );
}

/**
 *
 * Get linechart options
 * 
 * @return array
 *
 * @since 1.0.8
 * 
 */
function streamtube_core_get_linechart_options(){

	$theme_mode = function_exists( 'streamtube_get_theme_mode' ) ? streamtube_get_theme_mode() : 'light';

	$options = array(
		'legend'	=>	array(
			'position'	=>	'top',
			'textStyle'	=>	array(
				'color'	=>	'#aaa'
			)
		),
		'chartArea'	=>	array(
			'width'		=>	'95%',
			'height'	=>	'600px'
		),
		'hAxis'		=>	array(
			'format'	=>	'dd/MM/YY',
			'titleTextStyle'	=>	array(
				'color'	=>	'#aaa'
			),
			'textStyle'	=>	array(
				'color'	=>	'#aaa',
				'fontSize'	=>	15
			),
			'gridlines'	=>	array(
				'color'	=>	'transparent'
			)
		),
		'vAxis'	=>	array(
			'minValue'	=>	0,
			'textStyle'	=>	array(
				'color'	=>	'#aaa',
				'fontSize'	=>	15			
			),
			'gridlines'	=>	array(
				'color'	=>	$theme_mode == 'light' ? '#e9ecef' : '#333',
				'count'	=>	3
			)
		),
		//'curveType'			=>	'function',
		'focusTarget'		=>	'category',
		'crosshair'			=>	array(
			'orientation'	=>	'vertical',
			'trigger'		=>	'focus'
		),
		'tooltip'			=>	array(
			'isHtml'	=>	true,
			'trigger'	=>	'focus'
		),
		'series'		=>	array(
			array(
				'color'	=>	'#4285f4'
			),
			array(
				'color'	=>	'#4285f4',
				'lineDashStyle'	=>	array( 2,2 ),
				'lineWidth'	=>	1
			)
		),
		'backgroundColor'	=>	'transparent'
	);

	/**
	 *
	 * Filter the chart options
	 *
	 * @param array $chart_options
	 * 
	 * @since 1.0.8
	 */
	return apply_filters( 'streamtube_core_get_linechart_options', $options );
}

/**
 *
 * Get language list
 *
 * https://gist.github.com/joshuabaker/d2775b5ada7d1601bcd7b31cb4081981
 * 
 * @return array
 */
function streamtube_core_get_languages(){
	$languages = file_get_contents( STREAMTUBE_CORE_PUBLIC . '/assets/languages.json' );

	if( $languages ){
		$languages = json_decode( $languages, true );

		if( $languages ){
			/**
			 *
			 * Filter languages
			 * 
			 */
			return apply_filters( 'streamtube_core_get_languages', $languages );
		}
	}

	return false;
}

/**
 *
 * Get language options
 * 
 * @return array
 */
function streamtube_core_get_language_options(){

	$options = array();

	$languages = streamtube_core_get_languages();

	if( $languages ){
		foreach ( $languages as $language ) {
			$options[ $language['code'] ] = sprintf(
				'(%s) - %s - %s',
				strtoupper( $language['code'] ),
				$language['name'],
				$language['native']
			);
		}

		return $options;
	}

	return false;
}

/**
 *
 * Search language by code
 * 
 * @param  string $code
 * @return array|false
 * 
 */
function streamtube_core_get_language_by_code( $code ){

	$languages = streamtube_core_get_languages();

	if( ! $languages ){
		return false;
	}

	$search = array_search( $code, array_column( $languages, 'code'));

	if( $search ){
		return $languages[ $search ];
	}

	return false;
}

/**
 *
 * Get all available roles
 * 
 * @return array
 */
function streamtube_get_get_role_options(){
	global $wp_roles;

	$roles = array();

	foreach ( $wp_roles->roles as $role => $value ){

		$roles[ $role ] = $value['name'];
	}

	return $roles;
}