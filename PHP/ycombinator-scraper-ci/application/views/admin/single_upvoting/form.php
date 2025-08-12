<div class="row">
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Upvote Single Article</h3>
			</div>
			<div id="error_b"></div>
			<form action="<?=current_url().'/submit';?>" method="post" role="form">
				<div class="box-body">
					<div class="form-group">
						<label for="i-account[]">Account</label>
						<?=form_multiselect('i-account[]', $account_cbo, '', 'id="i-account" class="form-control"');?>
					</div>
					<div class="form-group">
						<label for="i-article">Article Url</label>
						<input type="text" class="form-control" name="i-article" id="i-article" placeholder="Article Url">
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary" data-loading-text="Upvoting ...">Upvote</button>
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
})
</script>