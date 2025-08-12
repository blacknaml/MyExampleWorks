<!DOCTYPE html>
<html class="bg-black">
<head>
	<meta charset="UTF-8">
	<title><?=$this->config->item('app_name');?></title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<!-- bootstrap 3.0.2 -->
	<link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- font Awesome -->
	<link href="<?=base_url();?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<!-- Theme style -->
	<link href="<?=base_url();?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
</head>

<body class="bg-black">
	<div class="form-box" id="login-box" style="margin-top: 90px;">
		<div class="header bg-light-blue">User Login</div>
		<form action="<?=current_url();?>/login" method="post">
			<div class="body bg-gray border-light-blue">
				<div class="form-group">
					<input type="text" name="i-username" class="form-control border-light-blue" placeholder="Username"/>
				</div>
				<div class="form-group">
					<input type="password" name="i-password" class="form-control border-light-blue" placeholder="Password"/>
				</div>
			</div>
			<div class="footer border-light-blue">
				<button type="submit" class="btn bg-light-blue btn-block">Login</button>  
				<div id="error_b"></div>
			</div>
		</form>
	</div>
	<!-- jQuery 2.0.2 -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="<?=base_url();?>assets/js/bootstrap.min.js" type="text/javascript"></script>        
	<!-- func -->
	<script src="<?=base_url();?>assets/js/AdminLTE/func.js" type="text/javascript"></script>  
	<script type="text/javascript">
		$(document).ready(function(){
			$('form').submit(function(){
				$.ajax({ url: $(this).attr('action'),
					data: $(this).serialize(),
					type: 'POST', dataType: 'json',
					success: function(response){
						json_handler(response);
						if(response.type == 'redirect'){ window.location.href = response.url;}
						if(response.type == 'failed'){}
					},
					fail: function(response){ alert('Connection Failed');}
				});
				return false;
			})
		});
	</script>
</body>
</html>