<tr>
	<td><?=$i;?></td>
	<td><?=$ycu_username;?></td>
	<td><?=$ycu_information;?></td>
	<td align="center">
		<?php if($ycu_active){ ?>
		<a href="#" alt="Disable Account" class="btn btn-danger btn-disable" data-loading-text="Processing ..." data-id="<?=$ycu_id;?>">
			<span class="glyphicon glyphicon-remove"></span>
		</a>
		<?php } else { ?>
		<a href="#" alt="Disable Account" class="btn btn-success btn-enable" data-loading-text="Processing ..." data-id="<?=$ycu_id;?>">
			<span class="glyphicon glyphicon-ok"></span>
		</a>
		<?php } ?>
	</td>
</tr>