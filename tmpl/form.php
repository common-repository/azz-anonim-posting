<?php
    $urlSite = site_url();
    $urlUploadImage = $urlSite.'?'.AZZAP_QUERY_VAR.'=uploadimage';
    $urlSaveForm    = $urlSite.'?'.AZZAP_QUERY_VAR.'=saveform';
?>
<div id="azzap-wrapper">
	<?php
		if(isset($_COOKIE['azzap_messaging']) && $_COOKIE['azzap_messaging'] !== 1):
			$out = json_decode(str_replace('\\','',$_COOKIE['azzap_messaging']));
	?>
	<div id="azzap-message"><?= $out->message ?></div>
	<?php endif; ?>

<form id="azzap-form" method="POST" action="<?= $urlSaveForm ?>">
	<div class="azzap-control-group">
		<label for="azzap-post-title">Post title</label>
		<input type="text" id="azzap-post-title" name="azzap-post-title" value="" />
		<small>Write briefly.</small>
	</div>

	<div class="azzap-control-group">
		<label for="azzap-post-content">Post content</label>
		<textarea name="azzap-post-content" id="azzap-post-content" cols="30" rows="10"></textarea>
		<small>No any tags allowed.</small>
	</div>

    <div class="azzap-control-group">
        <label for="azzap-category">Category</label>
        <select name="azzap-category" id="azzap-category">
            <option value="0" selected>Select category</option>
            <?php foreach(get_categories() as $c): ?>
                <option value="<?= $c->cat_ID ?>"><?= $c->cat_name ?></option>
            <?php endforeach; ?>
        </select>
    </div>

	<?php if(intval(get_option('azzap_user_data')) == 1): ?>
		<div class="azzap-control-group">
			<label for="azzap-user-name">Your name</label>
			<input type="text" id="azzap-user-name" name="azzap-user-name" value="" />
			<small>Seen by admin only</small>
		</div>

		<div class="azzap-control-group">
			<label for="azzap-user-email">Your email</label>
			<input type="text" id="azzap-user-email" name="azzap-user-email" value="" />
			<small>Seen by admin only</small>
		</div>
	<?php endif; ?>

	<?php if(intval(get_option('azzap_attach')) == 1): ?>
    <div class="azzap-control-group">
        <div id="fileupload-btn" class="btn">
            <span>Attach images ( до <?= get_option('azzap_max_file_size') ?> Mb )</span>
            <input id="fileupload" type="file" name="files[]" data-url="<?= $urlUploadImage ?>" multiple>
        </div>
        <div id="upload-progress"></div>
        <div id="uploaded"></div>
    </div>
	<?php endif; ?>

    <div class="azzap-control-group">
        <button type="submit">Send</button>
    </div>
</form>
</div>