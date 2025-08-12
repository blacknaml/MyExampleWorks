<tr>
	<td><?=$user_fullname;?></td>
	<td><?=$user_name;?></td>
	<td><?=$user_email;?></td>
	<td>
		<?php if($user_lock) { ?>
		<a href="#" class="btn btn-success btn-enable" data-id="<?=$user_id;?>"><span class="glyphicon glyphicon-ok"></span> Enable</a>		
		<?php } else { ?>
		<a href="#" class="btn btn-danger btn-disable" data-id="<?=$user_id;?>"><span class="glyphicon glyphicon-remove"></span> Disable</a>
		<?php } ?>
	</td>
</tr>