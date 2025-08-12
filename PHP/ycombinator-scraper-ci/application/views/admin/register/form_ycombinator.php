<div class="box-body">
	<div class="form-group">
		<label for="i-category">Category</label>
		<?=form_dropdown('i-category', $scraper_cbo, $sc_id, 'id="i-category" class="form-control"');?>
	</div>
	<div class="form-group">
		<label for="i-username">Username</label>
		<input type="text" class="form-control" name="i-username" id="i-username" placeholder="Username">
	</div>
	<div class="form-group">
		<label for="i-password">Password</label>
		<input type="password" class="form-control" name="i-password" id="i-password" placeholder="Password">
	</div>
</div>
<div class="box-footer">
	<button type="submit" class="btn btn-primary" data-loading-text="Processing ...">Register</button>
</div>