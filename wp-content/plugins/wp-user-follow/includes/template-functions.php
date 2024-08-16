<?php
/**
 *
 * @see  query->is_following()
 *
 * 
 * @param  integer $follower_id
 * @param  integer $following_id
 * @return true if following, otherwise is false
 *
 *
 * @since  1.0.0
 * 
 */
function wpuf_is_following( $follower_id = 0, $following_id = 0 ){

	global $wpuf;

	return $wpuf->get()->query->is_following( $follower_id, $following_id );

}

/**
 *
 * @see  query->get_following_count()
 * 
 * @param  integer $user_id
 * @return int
 *
 * @since  1.0.0
 * 
 */
function wpuf_get_following_count( $user_id = 0 ){
	global $wpuf;

	return $wpuf->get()->query->get_following_count( $user_id );	
}

/**
 *
 * @see  query->get_follower_count()
 * 
 * @param  integer $user_id
 * @return int
 *
 * @since  1.0.0
 * 
 */
function wpuf_get_follower_count( $user_id = 0 ){
	global $wpuf;

	return $wpuf->get()->query->get_follower_count( $user_id );	
}


/**
 *
 * Get follow user IDs of given user ID
 *
 * @param  int $user_id
 * @param  string $type
 *
 * 
 * @return array|false
 *
 * @since  1.0.0
 * 
 */
function wpuf_get_follow_users( $user_id, $type = 'following', $limit = 0 ){

	global $wpuf;

	$args = array();

	if( $type == 'following' ){
		$args['follower_id'] = $user_id;
	}
	else{
		$args['following_id'] = $user_id;
	}

	if( $limit ){
		$args['limit'] = (int)$limit;
	}

	$user_ids = $wpuf->get()->query->get( $args );

	if( $user_ids ){
		return array_unique( wp_list_pluck( $user_ids, "{$type}_id" ) ); 
	}

	return false;
}

/**
 *
 * The follow button
 * 
 * @return output the button
 *
 * @since  1.0.0
 * 
 */
function wpuf_button_follow( $args = array() ){

	$output 		= '';

	$is_following 	=	false;

	$args = wp_parse_args( $args, array(
		'text'			=>	esc_html__( 'Follow', 'wp-user-follow' ),
		'icon'			=>	'icon-plus',
		'user_id'		=>	0,
		'classes'		=>	array( 'btn', 'btn-follow', 'shadow-none', 'px-3', 'd-inline-flex', 'd-flex', 'align-items-center' ),
		'btn_size'		=>	'sm',
		'wrap_class'	=>	'',
		'count'			=>	true,
		'echo'			=>	true
	) );

	$follower_id = get_current_user_id();

	$is_following = wpuf_is_following( $follower_id, $args['user_id'] );

	if( $is_following ){

		$args['icon'] = 'icon-ok-circled';

		$args['text'] = esc_html__( 'Following', 'wp-user-follow' );

		$args['classes'] = array_merge( $args['classes'], array(
			'btn-following',
			'btn-info',
			'text-white'
		) );
	}
	else{
		$args['classes'][] = 'btn-secondary';
	}

	$args['classes'][] = 'btn-sm';

	/**
	 *
	 * Filter the button args
	 * 
	 * @var array $args
	 *
	 * @since  1.0.0
	 * 
	 */
	$args = apply_filters( 'wpuf_button_follow_args', $args, $is_following );

	// Turn on buffering
	ob_start();

	?>
	<div class="follow-button-group <?php echo esc_attr( $args['wrap_class'] ); ?>" data-user-id="<?php echo $args['user_id'];?>">
		<form class="form-ajax" method="post">
			<div class="btn-group">
			    <?php printf(
			    	'<button type="%s" class="%s" %s>',
			    	! is_user_logged_in() ? 'button' : 'submit',
			    	esc_attr( join( ' ', array_unique( $args['classes'] ) ) ),
			    	! is_user_logged_in() ? 'data-bs-toggle="modal" data-bs-target="#modal-login"' : ''
			    );?>
			        
			        <?php 
			        if( $args['icon'] ){
				        printf(
				        	'<span class="btn__icon %s"></span>',
				        	esc_attr( $args['icon'] )
				        );
			        }?>
			        
			        <?php printf(
			        	'<span class="btn__text">%s</span>',
			        	$args['text']
			        );?>

			    </button>

			    <?php if( $args['count'] && 0 < $count = wpuf_get_following_count( $args['user_id'] ) ):?>

			    	<?php printf(
			    		'<button class="btn btn-sm btn-danger btn-count px-3">%s</button>',
			    		apply_filters( 'wpuf_button_follow_count', number_format_i18n( $count ), $count, $args )
			    	);?>

				<?php endif;?>
			</div>

			<input type="hidden" name="action" value="user_follow">			

			<?php printf(
				'<input type="hidden" name="user_id" value="%s">',
				esc_attr( $args['user_id'] )
			);?>

			<?php printf(
				'<input type="hidden" name="nonce" value="%s">',
				esc_attr( wp_create_nonce( 'wp_rest' ) )
			);?>

			<?php printf(
				'<input type="hidden" name="request_url" value="%s">',
				esc_url( run_wp_user_follow()->get()->rest_api->get_rest_url() )
			);?>			

		</form>
	</div>
	<?php

	$output = trim( ob_get_clean() );

	if( $args['echo'] ){
		echo $output;
	}
	else{
		return $output;
	}
}