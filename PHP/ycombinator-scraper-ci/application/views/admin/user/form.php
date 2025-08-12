<div class="row">
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Add New User</h3>
			</div>
			<div id="error_b"></div>
			<form id="user-frm" action="<?=current_url().'/submit';?>" method="post" role="form">
				<div class="box-body">
					<div class="form-group col-xs-6">
						<label for="i-fullname">Fullname</label>
						<input type="text" class="form-control" name="i-fullname" id="i-fullname" placeholder="Fullname">
					</div>
					<div class="form-group col-xs-6">
						<label for="i-email">Email</label>
						<input type="text" class="form-control" name="i-email" id="i-email" placeholder="Email">
					</div>
					<div class="form-group col-xs-6">
						<label for="i-username">Username</label>
						<input type="text" class="form-control" name="i-username" id="i-username" placeholder="Username">
					</div>
					<div class="form-group col-xs-6">
						<label for="i-password">Password</label>
						<input type="password" class="form-control" name="i-password" id="i-password" placeholder="Password">
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary" data-loading-text="Adding ...">Add</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">List of Users</h3>
			</div>
			<div class="box-body">
				<table id="user-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>Fullname</th>
							<th>Username</th>
							<th>Email</th>
							<th style="width: 40px">Dis/En</th>
						</tr>
					</thead>
					<tbody>	</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	function get_user_data(){
		$.ajax({
			url: '<?=current_url();?>/get_data',
			data: {},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				$('table#user-tbl tbody').html(response.content);
			},
			error: function(){ alert('error connection to server'); }
		});
	}

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
				if(response.type == 'success'){
					get_user_data();
					$(':input','#user-frm').not(':button, :submit, :reset, :hidden').val('');
				}
				btn.button('reset');
			},
			error: function(){ 
				btn.button('reset');
				alert('error connection to server');
			}
		});

		return false;
	});

	$('table#user-tbl>tbody').on('click', 'a.btn-disable', function(){
		if(confirm('Do you want disable this User? ')){
			var btn = $(this);
			btn.button('loading');
			var id = btn.attr('data-id');
			$.ajax({
				url: '<?=current_url();?>/disable/'+id,
				data: {},
				dataType: 'json',
				type: 'post',
				beforeSend: function() {}, 
				success: function(response){
					json_handler(response);
					get_user_data();
				},
				error: function(){ alert('error connection to server'); }
			});
		}
	});
	$('table#user-tbl>tbody').on('click', 'a.btn-enable', function(){
		if(confirm('Do you want Enable this User? ')){
			var btn = $(this);
			btn.button('loading');
			var id = btn.attr('data-id');
			$.ajax({
				url: '<?=current_url();?>/enable/'+id,
				data: {},
				dataType: 'json',
				type: 'post',
				beforeSend: function() {}, 
				success: function(response){
					json_handler(response);
					get_user_data();
				},
				error: function(){ 
					btn.button('reset');
					alert('error connection to server');
				}
			});
		}
	});

	get_user_data();
})
</script>