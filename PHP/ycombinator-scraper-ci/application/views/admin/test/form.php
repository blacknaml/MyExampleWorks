<div class="row">
	<div class="col-md-12">
		<div class="box box-danger">
			<div class="box-header">
				<h3 class="box-title">Test Tor Proxy</h3>
			</div>
			<div id="error_b"></div>
			<form id="test-frm" action="<?=current_url().'/submit';?>" method="post" role="form">
				<div class="box-body">
					
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary" data-loading-text="Testing ...">Test</button>
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
					$('.box-body').html(response.param.table);
				}
				btn.button('reset');
			}
		});

		return false;
	});
})
</script>