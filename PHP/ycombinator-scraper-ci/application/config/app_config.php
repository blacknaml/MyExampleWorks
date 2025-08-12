<?php
/* General */
$config['app_name'] = 'Web App';
$config['cookies_path'] = '/var/www/html/yc/tmp/';
$config['csv_folder'] = '/var/www/html/yc/tmp/';

/* app config */
$config['app_upvote_number'] = 3;
$config['app_nyc_scid'] = 1;
$config['log_rand_upvoting'] = 'random upvoting';
$config['log_bulk_upvoting'] = 'article upvoting';
$config['log_registration'] = 'account';
$config['log_reregistration'] = 're-regiter';
$config['log_checking_account'] = 'account checking';
$config['log_login'] = 'login';
$config['log_logout'] = 'logout';
$config['log_auth_fail'] = 'failed login';
$config['log_error'] = 'error';
$config['log_comments'] = 'article comment';
$config['log_sche_comments'] = 'article comments';
$config['log_submit'] = 'create article';
$config['log_profile'] = 'profile';
$config['log_user'] = 'user';
$config['log_create_scraper'] = 'create scraper';

/* 
 * Proxy Conf 
 */
/* Tor*/
$config['use_tor'] = FALSE;
$config['proxy_url'] = '127.0.0.1';
$config['proxy_port'] = '8118';

/* Tor Control */
$config['proxy_control_url'] = '127.0.0.1';
$config['proxy_control_port'] = '9051';
$config['proxy_control_auth'] = '"authname"';

/* HMA */
$config['proxy_list'] = array(
	'146.190.85.79:3128',
	'117.160.250.133:8899',
	'101.255.148.22:3127'
	);