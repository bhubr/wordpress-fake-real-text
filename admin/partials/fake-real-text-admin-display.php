<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://developpeur-web-toulouse.fr/
 * @since      1.0.0
 *
 * @package    Fake_Real_Text
 * @subpackage Fake_Real_Text/admin/partials
 */

$wp_post_types = array_keys( get_post_types() );
$post_types_options = array_map( function( $type ) {
	$selected = $type === 'post' ? ' selected' : '';
	return "<option value=\"$type\" $selected>$type</option>";
}, $wp_post_types );
?>

<h3><?php echo __('Generate fake posts', 'fake-real-text'); ?></h3>


<div class="section group">
	<div class="col span_1_of_2">
		<?php require 'fake-real-text-admin-form-posts.php'; ?>
	</div>
	<div class="col span_1_of_2" id="posts-stats">
		<ul></ul>
		<!-- Change the below data attribute to play -->
		<div class="progress-wrap progress">
		  <div class="progress-bar progress"></div>
		</div>
	</div>
</div>
