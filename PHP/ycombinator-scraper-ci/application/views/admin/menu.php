<!-- sidebar menu: : style can be found in sidebar.less -->
<ul class="sidebar-menu">
	<li>
		<a href="<?=base_url();?>home">
			<i class="fa fa-dashboard"></i> <span>Dashboard</span>
		</a>
	</li>
	<li class="treeview">
		<a href="#">
			<i class="fa fa-user-md"></i><span>Account</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li>
				<a href="<?=base_url();?>register">
					<i class="fa fa-user-md"></i> Registration
				</a>
			</li>
			<li>
				<a href="<?=base_url();?>account_check">
					<i class="fa fa-refresh"></i> Account Checking
				</a>
			</li>
		</ul>
	</li>
	<li class="treeview">
		<a href="#">
			<i class="fa fa-stack-exchange"></i><span>Voting</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li>
				<a href="<?=base_url();?>upvoting">
					<i class="fa fa-stack-exchange"></i> Random Upvoting
				</a>
			</li>
			<li>
				<a href="<?=base_url();?>single_upvoting">
					<i class="fa fa-stack-exchange"></i> Upvoting Single Article
				</a>
			</li>
		</ul>
		
	</li>
	<li class="treeview">
		<a href="#">
			<i class="fa fa-comment"></i><span>Comment</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li>
				<a href="<?=base_url();?>comment">
					<i class="fa fa-comment"></i> Article Comment
				</a>
			</li>
			<li>
				<a href="<?=base_url();?>schedule_comment">
					<i class="fa fa-comment"></i> Schedule Article Comments
				</a>
			</li>
		</ul>
	</li>
	<li>
		<a href="<?=base_url();?>article">
			<i class="fa fa-pencil"></i> <span>Post New Article</span>
		</a>
	</li>
	<li class="treeview">
		<a href="#">
			<i class="fa fa-wrench"></i>
			<span>Setting</span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
		<ul class="treeview-menu">
			<li>
				<a href="<?=base_url();?>user"><i class="fa fa-square-o"></i> User</a>
			</li>
			<li>
				<a href="<?=base_url();?>setting/scraper"><i class="fa fa-square-o"></i> Scraper</a>
			</li>
			<!-- <li>
				<a href="<?=base_url();?>setting/tor"><i class="fa fa-square-o"></i> Tor Proxy</a>
			</li> -->
		</ul>
	</li>
	<li>
		<a href="<?=base_url();?>log">
			<i class="fa fa-file-text"></i> <span>Logs</span>
		</a>
	</li>
</ul>
<script type="text/javascript">
$(document).ready(function(){
	var plg = $('ul.sidebar-menu').find('a[href="<?=current_url();?>"]').parent().parent('ul.treeview-menu');
	$('ul.sidebar-menu').find('a[href="<?=current_url();?>"]').parent().addClass('active');
	if(plg.length == 1){
		plg.css('display', 'block');
		plg.parent('li.treeview').addClass('active');
	}
});
</script>