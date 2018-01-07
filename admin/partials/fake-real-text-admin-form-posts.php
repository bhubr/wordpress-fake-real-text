<table class="form-table">

	<form id="generate-posts">

		<tbody>

			<tr>
				<th scope="row"><label for="num_posts"><?php echo __('Number of posts to generate', 'fake-real-text'); ?></label></th>
				<td>
					<select id="num_posts" name="num_posts">
						<option value="1">1</option>
						<option value="5" selected>5</option>
						<option value="10">10</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row"><label for="post_type"><?php echo __('Type of posts to generate', 'fake-real-text'); ?></label></th>
				<td>
					<select id="post_type" name="post_type">
						<?php foreach( $post_types_options as $option ) { echo $option; } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="time_interval"><?php echo __('Time interval (backwards from now)', 'fake-real-text'); ?></label></th>
				<td>
					<select id="time_interval" name="time_interval">
						<option value="1 month"><?php echo __('1 month', 'fake-real-text'); ?></option>
						<option value="3 months" selected><?php echo __('3 months', 'fake-real-text'); ?></option>
						<option value="6 months"><?php echo __('6 months', 'fake-real-text'); ?></option>
						<option value="1 year"><?php echo __('1 year', 'fake-real-text'); ?></option>
					</select>
				</td>
			</tr>

			<tr>
				<th scope="row"></th>
				<td>
					<input type="submit" name="submit" id="generate" class="button button-primary" value="<?php echo __('Generate', 'fake-real-text'); ?>">
				</td>
			</tr>

		</tbody>

	</form>

</table>