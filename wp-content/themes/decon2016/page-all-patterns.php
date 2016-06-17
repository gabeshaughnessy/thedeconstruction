<?php
/**
 * TEMPLATE NAME: All Patterns
 *
 *
 * @package WordPress
 * @subpackage decon
 */

get_header();
echo '<div class="page-wrapper"><div class="outer container">';
	get_template_part('all-patterns');
	echo '<h2>Components</h2><hr/>';
	get_template_part('all-components');
echo '</div></div>';
get_footer();
?>
