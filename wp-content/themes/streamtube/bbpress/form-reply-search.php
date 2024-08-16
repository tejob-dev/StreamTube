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
		<form method="get" id="bbp-reply-search-form">
			<div class="input-group">
				<input class="form-control" type="text" value="<?php bbp_search_terms(); ?>" name="rs" id="rs" />
				<button class="btn btn-secondary btn-sm" type="submit" id="bbp_search_submit">
					<span class="btn__icon icon-search"></span>
				</button>
			</div>
		</form>
	</div>

<?php endif;
