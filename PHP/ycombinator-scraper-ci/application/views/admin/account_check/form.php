<div class="row">
	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header">
				<h3 class="box-title">Accounts</h3>
				<div class="box-tools pull-right">	
					<form id="frm-loa" class="form-inline" role="form">
						<div class="checkbox">
							<label>
								 Use Proxy <input type="checkbox" name="i-proxy" id="i-proxy" checked="checked">
							</label>
						</div>
						<div class="form-group">
							<label class="sr-only" for="exampleInputEmail2">Email address</label>
							<?=form_dropdown('i-category', $scraper_cbo, '', 'id="i-category" class="form-control"');?>
						</div>	
						<button type="submit" class="btn btn-default" data-loading-text="Checking ...">Check</button>
					</form>
				</div>
			</div>
			<div class="box-body">
				<table id="account-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Username</th>
							<th>Status</th>
							<th style="text-align: center;">Control</th>
						</tr>
					</thead>
					<tbody> </tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var pos = 0;

	function get_account_data(){
		$.ajax({
			url: '<?=current_url();?>/get_data',
			data: {i_category: $('#i-category').val(), i_proxy: $('#i-proxy').is(':checked'), pos: pos},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {
				if(pos == 0){
					$('table#account-tbl tbody').html('<tr><td colspan="4">Loading ... </td></tr>');
				} 
			}, 
			success: function(response){
				if(pos==0){
					$('table#account-tbl tbody').html(response.content);
				} else {
					$('table#account-tbl tbody tr#more').remove();
					$('table#account-tbl tbody').append(response.content);
				}
			},
			error: function(){ 
				if(pos == 0){
					$('table#account-tbl tbody').html('<tr><td colspan="4">Server Error Connection, Try Again.</td></tr>');
				} 
			}
		});
	}

	$('#i-category').change(function(){ pos = 0; });

	$('#frm-loa').on('submit', function(){
		pos = 0;
		get_account_data();
		return false;
	});

	$('table#account-tbl>tbody').on('click', 'a.btn-play', function(){
		var btn = $(this);
		btn.button('loading');
		var id = btn.attr('data-id');
		$.ajax({
			url: '<?=current_url();?>/play',
			data: {i_category: $('#i-category').val(), id:id},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				json_handler(response);
				if(response.type == 'success'){
					btn.parent().parent().html(response.content);
				}
				btn.button('reset');
			},
			error: function(){ btn.button('reset');	}
		});

		return false;
	});

	$('table#account-tbl>tbody').on('click', 'a.btn-load', function(){
		var btn = $(this);
		btn.button('loading');
		var p_next = btn.attr('data-id');
		pos = p_next;
		get_account_data();
		//btn.button('reset');
		return false;
	});

	get_account_data();
})
</script>