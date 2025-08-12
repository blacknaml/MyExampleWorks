<div class="row">
	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header">
				<h3 class="box-title">Log Data</h3>
			</div>
			<div class="box-body">
				<form id="frm-lod" class="form-inline" role="form">
					<div class="form-group">
						<label class="sr-only" for="i-date-1">Date 1</label>
						<input type="text" class="form-control" id="i-date-1" name="i-date-1" placeholder="Date From" value="<?=$now;?>" >
					</div>	
					<div class="form-group">
						<label class="sr-only" for="i-date-2">Date 2</label>
						<input type="text" class="form-control" id="i-date-2" name="i-date-2" placeholder="Date To" value="<?=$now;?>" >
					</div>	
					<div class="form-group">
						<label class="sr-only" for="i-tags">Tag</label>
						<input type="text" class="form-control twitter-typeahead" id="i-tags" name="i-tags" placeholder="Tags" value="<?=$token_val;?>" >
					</div>	
					<button type="submit" class="btn btn-default" data-loading-text="Show ...">Show</button>
				</form>
				<hr/>
				<table id="log-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Date</th>
							<th>IP</th>
							<th>Information</th>
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
	function load_log_data(){
		$.ajax({ url: '<?=current_url();?>/get_data',
			data: $('#frm-lod').serialize(),
			dataType: 'json', type: 'post',
			beforeSend: function() {
				$('table#log-tbl tbody').html('<tr><td colspan="4">Loading ... </td></tr>');
			}, 
			success: function(response){
				$('table#log-tbl tbody').html(response.content);
			},
			error: function(){ 
				$('table#log-tbl tbody').html('<tr><td colspan="4">Server Error Connection, Try Again.</td></tr>');
			}
		});

		return false;
	}

	var engine = new Bloodhound({ local: <?=$token;?>,
		datumTokenizer: function(d) {
			return Bloodhound.tokenizers.whitespace(d.value);
		},
		queryTokenizer: Bloodhound.tokenizers.whitespace
	}); engine.initialize();

	$('#i-tags').tokenfield({ typeahead: [null, { source: engine.ttAdapter() }] });

	var datepicker_var = $('#i-date-1, #i-date-2').datepicker({
		format: 'dd/mm/yyyy'
	}).on('changeDate', function(response){
		datepicker_var.datepicker('hide');
	});

	$('#frm-lod').on('submit', function(){ load_log_data(); return false; });

	load_log_data();
	
});
</script>