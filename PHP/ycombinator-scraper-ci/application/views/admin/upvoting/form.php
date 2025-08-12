<div class="row">
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Random Upvoting</h3>
			</div>
			<div class="box-body">
				<form class="form-inline" action="<?=current_url().'/submit';?>" method="post" role="form">
					<div class="form-group col-xs-3">
						<label class="sr-only" for="i-category">Category</label>
						<?=form_dropdown('i-category', $scraper_cbo, '', 'id="i-category" class="form-control"');?>
					</div>
					<div class="form-group col-xs-2">
						<label class="sr-only" for="i-account">Account</label>
						<?=form_dropdown('i-account', $account_cbo, '', 'id="i-account" class="form-control"');?>
					</div>
					<button type="submit" class="btn btn-primary" data-loading-text="Upvoting ...">Upvote</button>
				</form>
			</div>
			<div id="error_b"></div>
			<div class="box-footer"></div>
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
				if(response.type == 'success'){
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
	$('#i-category').change(function(){
		$.ajax({
			url: '<?=current_url()."/category_change";?>',
			data: {'i-category': $(this).val()},
			dataType: 'json',
			type: 'post',
			beforeSend: function() {}, 
			success: function(response){
				json_handler(response);
				if(response.type == 'success'){ $('#i-account').html('').html(response.param.opt); }
			},
			error: function(){ alert('error connection to server'); }
		});

		return false;
	});
})
</script>