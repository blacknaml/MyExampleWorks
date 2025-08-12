<div class="row">
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Edit Profile</h3>
			</div>
			<div id="error_b"></div>
			<form action="<?=current_url().'/submit';?>" method="post" role="form">
				<div class="box-body">
					<div class="form-group">
						<label for="i-fullname">Fullname</label>
						<input type="text" class="form-control" name="i-fullname" id="i-fullname" placeholder="Fullname" value="<?=$user_fullname;?>">
					</div>
					<div class="form-group">
						<label for="i-email">Email</label>
						<input type="text" class="form-control" name="i-email" id="i-email" placeholder="Email" value="<?=$user_email;?>">
					</div>
					<div class="form-group">
						<label for="i-oldpassword">Current Password</label>
						<input type="password" class="form-control" name="i-oldpassword" id="i-oldpassword" placeholder="Old Password">
					</div>
					<div class="form-group">
						<label for="i-newpassword">New Password</label>
						<input type="password" class="form-control" name="i-newpassword" id="i-newpassword" placeholder="New Password">
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary" data-loading-text="Editing ...">Edit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('form').submit(function(){
		var btn = $(this).find('button[type="submit"]');
		btn.button('loading');

		$.ajax({
			url: $(this).attr('action'),
			data: $(this).serialize(),
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				json_handler(response);
				if(response.type == 'success'){}
				btn.button('reset');
			},
			error: function(){ 
				btn.button('reset');
				alert('error connection to server');
			}
		});

		return false;
	});
})
</script>