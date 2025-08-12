<td>---</td>
<td><?=$ycu_username;?></td>
<td><?=$error_message;?></td>
<td align="center">
	<?php if($error){ ?>
	<a href="#" alt="Re-register Account" class="btn btn-primary btn-play" data-loading-text="Processing ..." data-id="<?=$ycu_id;?>">
		<span class="glyphicon glyphicon-repeat"></span>
	</a>
	<?php } else { ?>
	<span class="text-green glyphicon glyphicon-ok"></span>
	<?php } ?>
</td>