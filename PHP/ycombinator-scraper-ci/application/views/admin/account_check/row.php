<tr>
	<td><?=$i;?></td>
	<td><?=$ycu_username;?></td>
	<td><?=$error_message;?></td>
	<td align="center">
		<?php if($error){ ?>
		<a href="#" alt="Re-register Account" class="btn btn-primary btn-play" data-id="<?=$ycu_id;?>" data-loading-text="Processing ...">
			<span class="glyphicon glyphicon-repeat"></span>
		</a>
		<?php } else { ?>
		<span class="text-green glyphicon glyphicon-ok"></span>
		<?php } ?>
	</td>
</tr>	