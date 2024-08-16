<?php
if( ! defined('ABSPATH' ) ){
    exit;
}
$userdata = get_userdata( get_queried_object_id() );
?>

<form class="form form-profile form-ajax" method="post" autocomplete="off">

	<?php if( shortcode_exists( 'nextend_social_login' ) ):?>
		<div class="widget social-login-account">
			<div class="widget-title-wrap d-flex">
			    <h2 class="widget-title no-after"><?php esc_html_e( 'Social Login Accounts', 'streamtube-core' ); ?></h2>              
			</div>
			<div class="widget-content">
				<?php echo do_shortcode( '[nextend_social_login login="1" link="1" unlink="1"]' ); ?>
			</div>
		</div>
	<?php endif;?>

	<?php
	/**
	 *
	 * Fires before personal fields
	 *
	 * @param WP_User $userdata
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/settings/personal/personal/before', $userdata );
	?>		

	<div class="widget">
		<div class="widget-title-wrap d-flex">
		    <h2 class="widget-title no-after"><?php esc_html_e( 'Personal Info', 'streamtube-core' );?></h2>              
		</div>
		<div class="widget-content">

			<?php
			streamtube_core_the_field_control( array(
				'label'			=>	esc_html__( 'Username', 'streamtube-core' ),
				'name'			=>	'user_login',
				'value'			=>	$userdata->user_login,
				'data'			=>	array(
					'disabled'	=>	'disabled'
				)
			) );
			?>

			<?php
			streamtube_core_the_field_control( array(
				'label'			=>	esc_html__( 'Nickname', 'streamtube-core' ),
				'name'			=>	'nickname',
				'value'			=>	$userdata->nickname,
				'wrap_class'	=>	'd-none'
			) );
			?>			

			<div class="row">

				<div class="col-12 col-lg-6">
					<?php
					streamtube_core_the_field_control( array(
						'label'			=>	esc_html__( 'First Name', 'streamtube-core' ),
						'name'			=>	'first_name',
						'value'			=>	$userdata->first_name
					) );
					?>
				</div>

				<div class="col-col-12 col-lg-6">
					<?php
					streamtube_core_the_field_control( array(
						'label'			=>	esc_html__( 'Last Name', 'streamtube-core' ),
						'name'			=>	'last_name',
						'value'			=>	$userdata->last_name
					) );
					?>
				</div>

			</div>
			<?php
			streamtube_core_the_field_control( array(
				'label'			=>	esc_html__( 'Display Name', 'streamtube-core' ),
				'name'			=>	'display_name',
				'value'			=>	$userdata->display_name
			) );
			?> 					
		</div>	
	</div>

	<?php
	/**
	 *
	 * Fires before contact fields
	 *
	 * @param WP_User $userdata
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/settings/personal/contact/before', $userdata );
	?>	

	<div class="widget">
		<div class="widget-title-wrap d-flex">
		    <h2 class="widget-title no-after"><?php esc_html_e( 'Contact Info', 'streamtube-core' ); ?></h2>              
		</div>
		<div class="widget-content">
			<?php
			streamtube_core_the_field_control( array(
				'type'			=>	'email',
				'label'			=>	esc_html__( 'Email', 'streamtube-core' ),
				'name'			=>	'email',
				'value'			=>	$userdata->user_email
			) );
			?>

			<?php
			streamtube_core_the_field_control( array(
				'type'			=>	'url',
				'label'			=>	esc_html__( 'Website', 'streamtube-core' ),
				'name'			=>	'url',
				'value'			=>	$userdata->user_url
			) );
			?>

			<div class="form-floating mb-3">
				<?php 
				streamtube_core_the_field_control( array(
					'label'		=>	esc_html__( 'Bio', 'streamtube-core' ),
					'type'		=>	'editor',
					'name'		=>	'description',
					'value'		=>	$userdata->description,
					'settings'	=>	array(
						'teeny'			=>	true,
						'media_buttons'	=>	false,
						'tinymce'		=>	array(
							'toolbar1'	=>	implode( ',' , array(
								'bold',
								'italic',
								'underline',
								'bullist',
								'numlist',
								'separator',
								'alignleft',
								'aligncenter',
								'alignright',
								'separator',
								'link',
								'unlink',
								'undo',
								'redo'
							))
						)
					)
				) );
				?>
				<div class="form-text">
					<?php esc_html_e( 'Share a little biographical information to fill out your profile. This may be shown publicly.', 'streamtube-core' );?>
				</div>
			</div>
		</div>
	</div>

	<?php
	/**
	 *
	 * Fires before password fields
	 *
	 * @param WP_User $userdata
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/settings/personal/password/before', $userdata );
	?>		

	<div class="widget">
		<div class="widget-title-wrap d-flex">
		    <h2 class="widget-title no-after"><?php esc_html_e( 'Account Management', 'streamtube-core' ); ?></h2>              
		</div>
		<div class="widget-content">
			<div class="row">
				<div class="col-12 col-lg-6">
					<?php
					streamtube_core_the_field_control( array(
						'type'			=>	'password',
						'label'			=>	esc_html__( 'Password', 'streamtube-core' ),
						'name'			=>	'pass1'
					) );
					?>
				</div>
				<div class="col-12 col-lg-6">
					<?php
					streamtube_core_the_field_control( array(
						'type'			=>	'password',
						'label'			=>	esc_html__( 'Confirm Password', 'streamtube-core' ),
						'name'			=>	'pass2'					
					) );
					?>
				</div>											
			</div>
		</div>
	</div>

	<?php
	/**
	 *
	 * Fires after password fields
	 *
	 * @param WP_User $userdata
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/settings/personal/password/after', $userdata );
	?>	

	<div class="d-flex">
		<button type="submit" class="btn btn-primary ms-auto">
			<span class="icon-floppy"></span>
			<span class="button-label">
				<?php esc_html_e( 'Save Changes', 'streamtube-core' ); ?>
			</span>
		</button>
	</div>

	<input type="hidden" name="action" value="update_profile">

	<?php printf(
		'<input type="hidden" name="admin_bar_front" value="%s">',
		get_user_meta( $userdata->ID, 'admin_bar_front', true )
	)?>

	<?php printf(
		'<input type="hidden" name="request_url" value="%s">',
		streamtube_core()->get()->rest_api['user']->get_rest_url( '/update-profile' )
	);?>

	<?php
	/**
	 *
	 * Fires after submit field
	 *
	 * @param WP_User $userdata
	 * 
	 */
	do_action( 'streamtube/core/user/dashboard/settings/personal/submit/after', $userdata );
	?>	

</form>

<style type="text/css">

    <?php if( ! current_user_can( 'administrator' ) ): ?>
        #wp-link-wrap{
            height:  320px;
        }            
        #wp-link-wrap #search-panel,
        #wp-link-wrap #wplink-link-existing-content,
        #wp-link .link-target label span{
            display: none!important;
        }
    <?php endif;?>

    #wp-link-wrap .link-target{
        margin-top: 1rem
    }

    #wp-link-wrap #link-options label,
    #wp-link-wrap #link-options label input[type=text],
    #wp-link-wrap #link-options label input[type=url]{
        width:  100%;
    }

    #wp-link-wrap #link-options label span{
        width:  auto;
    }

    html[data-theme=dark] div.mce-panel,
    html[data-theme=dark] #wp-link-wrap{
        background: #333;
        border: 1px solid #444;
    }

    html[data-theme=dark] #wp-link-wrap #link-modal-title{
        background:  #222;
        border-bottom: 1px solid #666;
    }

    html[data-theme=dark] #wp-link-wrap .submitbox{
        background:  #222;
        border-top: 1px solid #666;
    }

    html[data-theme=dark] #wp-link-wrap .submitbox #wp-link-cancel button{
        color: #f9f9f9;
        border-color: #444;
        background: #333;            
    }

    html[data-theme=dark] .mce-window-head{
        background:  #222;
        border-bottom: 1px solid #666;
        color: #f9f9f9;
    }

    html[data-theme=dark] .mce-window-head .mce-title{
        color: #f9f9f9;
    }
</style>
<?php