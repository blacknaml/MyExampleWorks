<div class="row">
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Scraper Class / Category</h3>
			</div>
			<div id="error_b"></div>
			<form id="frm-scraper" action="<?=current_url().'/submit';?>" method="post" role="form">
				<div class="box-body">
					<div class="form-group col-xs-6">
						<label for="i-name">Scraper Name</label>
						<input type="text" class="form-control" name="i-name" id="i-name" placeholder="Scraper Name">
					</div>
					<div class="form-group col-xs-6">
						<label for="i-class">Scraper Class</label>
						<input type="text" class="form-control" name="i-class" id="i-class" placeholder="Scraper Class">
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
				<h3 class="box-title">List of Scraper Class</h3>
			</div>
			<div class="box-body">
				<table id="scraper-tbl" class="table table-striped">
					<thead>
						<tr>
							<th>Name</th>
							<th>Class</th>
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
	function get_scraper_data(){
		$.ajax({
			url: '<?=current_url();?>/get_data',
			data: {},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				$('table#scraper-tbl tbody').html(response.content);
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
					$(':input','#frm-scraper').not(':button, :submit, :reset, :hidden').val('');
					get_scraper_data();
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

	get_scraper_data();
})
</script>