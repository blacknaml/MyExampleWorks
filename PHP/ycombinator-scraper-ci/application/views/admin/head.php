<head>
	<meta charset="UTF-8">
	<title><?=$this->config->item('app_name');?></title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<!-- bootstrap 3.0.2 -->
	<link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- font Awesome -->
	<link href="<?=base_url();?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<!-- Ionicons -->
	<link href="<?=base_url();?>assets/css/ionicons.min.css" rel="stylesheet" type="text/css" />
	<?=(isset($css)) ? $css : '';?>
	<!-- Theme style -->
	<link href="<?=base_url();?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<!-- jQuery 2.0.2 -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="<?=base_url();?>assets/js/bootstrap.min.js" type="text/javascript"></script>
	<?=(isset($js)) ? $js : '';?>
	<!-- AdminLTE App -->
	<script src="<?=base_url();?>assets/js/AdminLTE/app.js" type="text/javascript"></script>
	<!-- apps function -->
	<script src="<?=base_url();?>assets/js/AdminLTE/func.js" type="text/javascript"></script>
	<!-- onboard -->
	<script type="text/javascript">
	var site_url = '<?=base_url();?>';

	$('#loading_layer').ajaxStart(function() { 
		$(this).show(); 
	}).ajaxStop(function() { 
		$(this).hide();
	});

	</script>
</head>