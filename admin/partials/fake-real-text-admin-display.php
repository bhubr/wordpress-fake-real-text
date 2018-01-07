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




<table class="form-table">

	<form id="generate-posts">

		<tbody>

			<tr>
				<th scope="row"><label for="blogname"><?php echo __('Number of posts to generate', 'fake-real-text'); ?></label></th>
				<td>
					<select name="num_posts">
						<option value="1">1</option>
						<option value="10" selected>10</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="blogname"><?php echo __('Type of posts to generate', 'fake-real-text'); ?></label></th>
				<td>
					<select name="post_type">
						<?php foreach( $post_types_options as $option ) { echo $option; } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="blogname"><?php echo __('Time interval (backwards from now)', 'fake-real-text'); ?></label></th>
				<td>
					<select name="time_interval">
						<option value="1 month"><?php echo __('1 month', 'fake-real-text'); ?></option>
						<option value="3 months" selected><?php echo __('3 months', 'fake-real-text'); ?></option>
						<option value="6 months"><?php echo __('6 months', 'fake-real-text'); ?></option>
						<option value="1 year"><?php echo __('1 year', 'fake-real-text'); ?></option>
					</select>
				</td>
			</tr>

			<tr>
				<th id="posts-stats" scope="row"></th>
				<td>
					<input type="submit" name="submit" id="generate" class="button button-primary" value="<?php echo __('Generate', 'fake-real-text'); ?>">
				</td>
			</tr>

		</tbody>

	</form>

</table>