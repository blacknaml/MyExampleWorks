<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Create Account</h3>
			</div>
			<form role="form" id="frm-register" action="<?=current_url().'/submit';?>">
				<div class="box-body">
					<div class="form-group">
						<label for="i-category">Category</label>
						<?=form_dropdown('i-category', $scraper_cbo, '', 'id="i-category" class="form-control"');?>
					</div>
				</div>
			</form>
		</div>
		<div id="error_b"></div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header">
				<h3 class="box-title">List of Accounts</h3>
			</div>
			<div class="box-body">
				<table id="account-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Username</th>
							<th>Information</th>
							<th style="text-align: center;">En/Dis</th>
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
	function get_account_data(){
		$.ajax({
			url: '<?=current_url();?>/get_data',
			data: {i_category: $('#i-category').val()},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				$('table#account-tbl tbody').html(response.content);
			},
			error: function(){ alert('error connection to server');}
		});
	}

	$('#frm-register').on('submit', function(){
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
					get_account_data();
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
	$('#frm-register').on('change', 'select', function(){
		$.ajax({
			url: '<?=current_url()."/category_change";?>',
			data: {'i-category': $(this).val()},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				json_handler(response);
				if(response.type == 'success'){ 
					$('#frm-register').html(response.param.form); 
					get_account_data();
				}
			},
			error: function(){ alert('error connection to server'); }
		});

		return false;
	});

	$('table#account-tbl>tbody').on('click', 'a.btn-enable', function(){
		var btn = $(this);
		btn.button('loading');
		var id = btn.attr('data-id');
		$.ajax({
			url: '<?=current_url();?>/set_enable',
			data: {id:id},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				json_handler(response);
				if(response.type == 'success'){
					get_account_data();
				}
				btn.button('reset');
			},
			error: function(){ alert('error connection to server'); }
		});

		return false;
	});

	$('table#account-tbl>tbody').on('click', 'a.btn-disable', function(){
		var btn = $(this);
		btn.button('loading');
		var id = btn.attr('data-id');
		$.ajax({
			url: '<?=current_url();?>/set_disable',
			data: {id:id},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				json_handler(response);
				if(response.type == 'success'){
					get_account_data();
				}
				btn.button('reset');
			},
			error: function(){ alert('error connection to server'); }
		});

		return false;
	});

	get_account_data();
})
</script>