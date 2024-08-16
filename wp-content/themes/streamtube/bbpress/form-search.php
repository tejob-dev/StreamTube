<?php

/**
 * Search 
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( bbp_allow_search() ) : ?>

	<div class="bbp-search-form">
		<form method="get" id="bbp-search-form">
			<div class="input-group">
				<input type="hidden" name="action" value="bbp-search-request" />
				<input type="text" value="<?php bbp_search_terms(); ?>" name="bbp_search" id="bbp_search" class="form-control" />
				<button class="btn btn-secondary btn-sm" type="submit" id="bbp_search_submit">
					<span class="btn__icon icon-search"></span>
				</button>
			</div>
		</form>
	</div>

<?php endif;
