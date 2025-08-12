<style type="text/css">
.td-f1{padding-left: 0px !important;}
.td-f1{padding-left: 15px !important;}
.td-f2{padding-left: 30px !important;}
.td-f3{padding-left: 45px !important;}
.td-f4{padding-left: 60px !important;}
.td-f5{padding-left: 75px !important;}
.td-f6{padding-left: 90px !important;}
.td-f7{padding-left: 105px !important;}
</style>
<div class="row">
	<div class="col-md-12">
		<div id="d-upload" class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Upload CSV</h3>
			</div>
			<div class="box-body">
				<div id="error_b"></div>
				<form id="f-article" action="<?=current_url().'/submit';?>" role="form">
					<div class="form-group">
						<label for="i-article">Article Url</label>
						<input type="text" class="form-control" name="i-article" id="i-article" placeholder="Article Url">
						<input type="hidden" name="i-fn" id="i-fn">
					</div>
				</form>
				<form id="f-csv" action="<?=current_url().'/upload';?>" method="post" role="form" enctype="multipart/form-data">
					<div class="form-group">
						<label for="i-csv">CSV File</label>
						<input type="file" class="form-control" name="i-csv" id="i-csv"></input>
						<span id='uf-error' class='text-danger'></span>
					</div>
				</form>
			</div>
			<div class="box-footer">
				<button type="button" id="b-upload" class="btn btn-primary" data-loading-text="Uploading ...">Upload</button>
			</div>
		</div>
		<div id="d-history" class="box box-success">
			<div class="box-header">
				<h3 class="box-title">Schedules</h3>
			</div>
			<div class="box-body">
				<form id="frm-history" class="form-inline" role="form">
					<div class="form-group">
						<label class="sr-only" for="i-date-1">Date 1</label>
						<input type="text" class="form-control datepicker" id="i-date-1" name="i-date-1" placeholder="Date From" value="<?=$now;?>" >
					</div>	
					<div class="form-group">
						<label class="sr-only" for="i-date-2">Date 2</label>
						<input type="text" class="form-control datepicker" id="i-date-2" name="i-date-2" placeholder="Date To" value="<?=$now;?>" >
					</div>
					<button type="submit" class="btn btn-default" data-loading-text="Show ...">Show</button>
				</form>
				<hr/>
				<table id="history-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Datetime</th>
							<th>Comments</th>
							<th>Account</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody> </tbody>
				</table>
			</div>
			<div class="box-footer"></div>
		</div>
		<div id="d-schedule" class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Schedule</h3>
			</div>
			<form id="f-schedule" action="<?=current_url().'/save';?>" method="post" role="form">
				<div id="error_b"></div>
				<div class="box-body table-responsive">
					
				</div>
				<div class="box-footer">
					<button type="button" class="btn btn-warning btn-back" >Back</button>
					<button type="submit" class="btn btn-primary" data-loading-text="Saving ...">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	function load_schedule_data(){
		$.ajax({ url: '<?=current_url();?>/get_history',
			data: $('#frm-history').serialize(),
			dataType: 'json', type: 'post',
			beforeSend: function() {
				$('table#history-tbl tbody').html('<tr><td colspan="5">Loading ... </td></tr>');
			}, 
			success: function(response){
				$('table#history-tbl tbody').html(response.content);
			},
			error: function(){ 
				$('table#history-tbl tbody').html('<tr><td colspan="5">Server Error Connection, Try Again.</td></tr>');
			}
		});

		return false;
	}

	$('#d-schedule').hide();

	$('#i-csv').change(function(){
		$('#f-csv').ajaxForm({
			dataType: 'json',
			success: function(response){
				if(response.type == 'success'){
					$('#uf-error').html('');
					$('#i-fn').val(response.param.fn);
				} else {
					$('#uf-error').html(response.param.error);
				}
			},
			beforeSubmit:function(){
				$('#uf-error').html(' ... ');
			}
		}).submit(); 

		return false;
	});
	$('#f-article').submit(function(){
		var btn = $('#b-upload');
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
					$('#d-upload, #d-history').hide();
					$('#d-schedule').show().find('.box-body').html(response.content);
					$('.dt-input').daterangepicker({
						singleDatePicker: true,
						timePicker: true, 
						timePickerIncrement: 3,
						format: 'MM/DD/YYYY h:mm A'
					});
				}
				btn.button('reset');
			},
			error: function(){ btn.button('reset');}
		});
		return false;
	});
	$('#f-schedule').submit(function(){
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
					$('#d-upload, #d-history').show();
					$('#d-schedule .box-body').html('');
					$('#d-schedule').hide();
					$(':input','#f-article').not(':button, :submit, :reset').val('');
				}
				btn.button('reset');
			},
			error: function(){ btn.button('reset');}
		});
		return false;
	});
	$('#b-upload').click(function(){
		$('#f-article').submit();
	});
	$('#f-schedule').on('click', 'button.btn-back', function(){
		$('#d-upload, #d-history').show();
		$('#d-schedule .box-body').html('');
		$('#d-schedule').hide();
	});
	$('.datepicker').daterangepicker({
		singleDatePicker: true,
		timePicker: false, 
		format: 'DD/MM/YYYY'
	});

	$('#frm-history').on('submit', function(){ load_schedule_data(); return false; });
})
</script>