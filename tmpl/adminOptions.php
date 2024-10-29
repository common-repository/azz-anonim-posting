<?php
	$azzap_users = get_users();
	$isPIm  = ( intval(get_option('azzap_publish_imm'))    == 1 );
	$isAtt  = ( intval(get_option('azzap_attach'))         == 1 );
	$isUD   = ( intval(get_option('azzap_user_data'))      == 1 );
?>
<div class="wrap">
	<h2>Azz Anonim Posting Options</h2>

	<form action="<?php echo admin_url( 'admin.php' ); ?>" method="post">
		<input type="hidden" name="action" value="azzap_save_options" />
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Select user</th>
					<td>
					<select name="azzap_user_id" id="azzap_user_id">
						<?php foreach($azzap_users as $u): ?>
						<option <?php if($u->ID == intval(get_option('azzap_user_id'))) echo 'selected="selected"'?> value="<?= $u->ID ?>"><?= $u->user_nicename ?></option>
						<?php endforeach; ?>
					</select>
					<p class="description">Behalf of that user will publish all Anonim Posts</p>
					</td>
				</tr>

				<tr>
					<th scope="row">Publish immediately</th>
					<td>
						<fieldset>

						<label title="Yes">
							<input type="radio" <?php if($isPIm) echo 'checked="checked"'?> value="1" name="azzap_publish_imm">
							<span>Yes</span>
						</label>

						<label title="No">
							<input type="radio" <?php if(!$isPIm) echo 'checked="checked"'?> value="0" name="azzap_publish_imm">
							<span>No</span>
						</label>

						</fieldset>
						<p class="description">Select "No" and all post will be waiting moderation</p>
					</td>
				</tr>

				<tr>
					<th scope="row">Ask user data</th>
					<td>
						<fieldset>

						<label title="Yes">
							<input type="radio" <?php if($isUD) echo 'checked="checked"'?> value="1" name="azzap_user_data">
							<span>Yes</span>
						</label>

						<label title="No">
							<input type="radio" <?php if(!$isUD) echo 'checked="checked"'?> value="0" name="azzap_user_data">
							<span>No</span>
						</label>

						</fieldset>
						<p class="description">Show inputs for username and email, but not requied!!! Save this data in custom fields.</p>
					</td>
				</tr>

				<tr>
					<th scope="row">Allow attach images</th>
					<td>
						<fieldset>

						<label title="Yes">
							<input type="radio" <?php if($isAtt) echo 'checked="checked"'?> value="1" name="azzap_attach">
							<span>Yes</span>
						</label>

						<label title="No">
							<input type="radio" <?php if(!$isAtt) echo 'checked="checked"'?> value="0" name="azzap_attach">
							<span>No</span>
						</label>

						</fieldset>

					</td>
				</tr>

				<tr>
					<th scope="row">Max uploading file size, Mb</th>
					<td>
						<input type="text" name="azzap_max_file_size" class="small-text" value="<?= get_option('azzap_max_file_size') ?>"/>
					</td>
				</tr>

				<tr>
					<th scope="row">Post template</th>
					<td>
						<textarea name="azzap_content_tmpl" id="azzap_content_tmpl" cols="30" rows="5" style="width: 500px;"><?= get_option('azzap_content_tmpl') ?></textarea>
						<p class="description">
							Use like [img:thumbnail], [img:medium], [img:large].<br/>
							Every [img:xxx] insert 1 image from uploaded, and remove one from array of uploaded images.<br/>
							[images:large] inserts all uploaded images, exclude inserted by [img:xxx] early.<br/>
							And [content] insert text content.
						</p>
					</td>
				</tr>

			</tbody>
		</table>
		<p class="submit">
			<input id="submit" class="button button-primary" type="submit" value="Save changes" name="submit">
		</p>
	</form>

</div>