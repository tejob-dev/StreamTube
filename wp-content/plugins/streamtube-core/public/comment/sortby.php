<?php
$options = streamtube_core_comment_sortby_options();
$current = streamtube_core_get_current_comment_sortby();
?>

<div class="d-flex align-items-center">
    <div class="sortby dropdown">
        <button class="btn shadow-none dropdown-toggle text-secondary" data-bs-display="static"  data-bs-toggle="dropdown" aria-expanded="false">            
            <?php echo $options[ $current  ]['title'];?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end animate slideIn">
        	<?php foreach( $options as $k=> $v ): ?>

        		<?php printf(
        			'<li><a class="dropdown-item small comment-order ajax-elm %s" href="#%s" data-params="%s" data-action="%s">%s</a></li>',
        			$current  == $k ? 'active' : '',
                    $k,
                    esc_attr( json_encode( array(
                        'post_id'   =>  get_the_ID(),
                        'paged'     =>  1,
                        'order'     =>  $k
                    ) ) ),
                    'load_comments',
        			esc_html( $v['title'] )
        		);?>

        	<?php endforeach;?>
        </ul>
    </div>
</div>