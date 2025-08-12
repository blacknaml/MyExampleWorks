<div class="row">
	<div class="col-md-6">
		<div class="box box-success">
			<div class="box-header">
				<h3 class="box-title">Newest Navigation</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-success btn-sm btn-synch" data-loading-text="Synching ...">
						<i class="glyphicon glyphicon-download"></i>
					</button>
				</div>
			</div>			
			<div class="box-body">
				<form id="frm-yc-nav" class="form-inline" role="form">
					<div class="form-group">
						<label class="sr-only" for="i-date-1">Date 1</label>
						<input type="text" class="form-control datepicker" id="i-date-1" name="i-date-1" placeholder="Date From" value="<?=$last_week;?>" >
					</div>	
					<div class="form-group">
						<label class="sr-only" for="i-date-2">Date 2</label>
						<input type="text" class="form-control datepicker" id="i-date-2" name="i-date-2" placeholder="Date To" value="<?=$now;?>" >
					</div>
					<button type="submit" class="btn btn-default" data-loading-text="Show ...">Show</button>
				</form>
				<div id="error_b" style="margin-top: 10px;"></div>
				<hr/>
				<table id="yc-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Title</th>							
							<th style="text-align: center;">Action</th>
						</tr>
					</thead>
					<tbody>	</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Top News Navigation</h3>
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-danger btn-sm btn-nsynch" data-loading-text="Synching ...">
						<i class="glyphicon glyphicon-download"></i>
					</button>
				</div>
			</div>			
			<div class="box-body">
				<form id="frm-top-nav" class="form-inline" role="form">
					<div class="form-group">
						<label class="sr-only" for="i-date-1">Date 1</label>
						<input type="text" class="form-control datepicker" id="i-date-1" name="i-date-1" placeholder="Date From" value="<?=$last_week;?>" >
					</div>	
					<div class="form-group">
						<label class="sr-only" for="i-date-2">Date 2</label>
						<input type="text" class="form-control datepicker" id="i-date-2" name="i-date-2" placeholder="Date To" value="<?=$now;?>" >
					</div>	
					<button type="submit" class="btn btn-default" data-loading-text="Show ...">Show</button>
				</form>
				<div id="error_b" style="margin-top: 10px;"></div>
				<hr/>
				<table id="news-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Title</th>							
							<th style="text-align: center;">Action</th>
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
	var pos = 0;
	var npos = 0;

	function load_yc_data(){
		var data = $('#frm-yc-nav').serializeArray();
		data.push({name: 'pos', value: pos});
		$.ajax({ url: '<?=current_url();?>/get_data',
			data: $.param(data),
			dataType: 'json', type: 'post',
			beforeSend: function() {
				if(pos == 0){
					$('table#yc-tbl tbody').html('<tr><td colspan="3">Loading ... </td></tr>');
				} 
			}, 
			success: function(response){
				if(pos==0){
					$('table#yc-tbl tbody').html(response.content);
				} else {
					$('table#yc-tbl tbody tr#more').remove();
					$('table#yc-tbl tbody').append(response.content);
				}
			},
			error: function(){ 
				if(pos == 0){
					$('table#yc-tbl tbody').html('<tr><td colspan="3">Server Error Connection, Try Again.</td></tr>');
				} 
			}
		});

		return false;
	}

	function load_news_data(){
		var data = $('#frm-top-nav').serializeArray();
		data.push({name: 'pos', value: npos});
		$.ajax({ url: '<?=current_url();?>/get_top_data',
			data: $.param(data),
			dataType: 'json', type: 'post',
			beforeSend: function() {
				if(npos == 0){
					$('table#news-tbl tbody').html('<tr><td colspan="3">Loading ... </td></tr>');
				} 
			}, 
			success: function(response){
				if(npos==0){
					$('table#news-tbl tbody').html(response.content);
				} else {
					$('table#news-tbl tbody tr#more').remove();
					$('table#news-tbl tbody').append(response.content);
				}
			},
			error: function(){ 
				if(npos == 0){
					$('table#news-tbl tbody').html('<tr><td colspan="3">Server Error Connection, Try Again.</td></tr>');
				} 
			}
		});

		return false;
	}

	$('.datepicker').daterangepicker({
		singleDatePicker: true,
		timePicker: false, 
		format: 'DD/MM/YYYY'
	});

	$('#frm-yc-nav').on('submit', function(){ pos = 0; load_yc_data(); return false; });
	$('#frm-top-nav').on('submit', function(){ npos = 0; load_news_data(); return false; });

	$('table#yc-tbl>tbody').on('click', 'a.btn-load', function(){
		var btn = $(this);
		btn.button('loading');

		var p_next = btn.attr('data-id');
		pos = p_next;
		
		load_yc_data();

		return false;
	});
	$('table#news-tbl>tbody').on('click', 'a.btn-load', function(){
		var btn = $(this);
		btn.button('loading');

		var p_next = btn.attr('data-id');
		npos = p_next;
		
		load_news_data();

		return false;
	});
	$('.btn-synch').click(function(){
		var btn = $(this);		

		$.ajax({ url: '<?=current_url();?>/synch',
			data: {},
			dataType: 'json', type: 'post',
			beforeSend: function() { btn.button('loading');}, 
			success: function(response){
				json_handler(response);
				$('#frm-yc-nav').submit();
				btn.button('reset');
			},
			error: function(){ 
				json_handler({ type: 'failed', content: 'Server Error Connection, Try Again.' });
				btn.button('reset');
			}
		});
	});
	$('.btn-nsynch').click(function(){
		var btn = $(this);		

		$.ajax({ url: '<?=current_url();?>/top_synch',
			data: {},
			dataType: 'json', type: 'post',
			beforeSend: function() { btn.button('loading');}, 
			success: function(response){
				json_handler(response);
				$('#frm-top-nav').submit();
				btn.button('reset');
			},
			error: function(){ 
				json_handler({ type: 'failed', content: 'Server Error Connection, Try Again.' });
				btn.button('reset');
			}
		});
	});
	load_yc_data();
	load_news_data();
});
</script>