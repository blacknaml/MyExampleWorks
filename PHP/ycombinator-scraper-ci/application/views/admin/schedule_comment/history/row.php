<tr>
	<td><?=$i;?></td>
	<td><?=$ycc_schedule;?></td>
	<td <?=$class;?> >
		<?=$ycc_comment;?>
		<?php if(empty($ycc_url) == FALSE){ ?>
			<small>[<a href="<?=$ycc_url;?>">link</a>]</small>
		<?php } ?>
		<?php if($ycc_sent == 2) { ?>
			<p class="text-danger">
				<small><?=$ycc_message;?></small>
			</p>
		<?php } ?>
	</td>
	<td><?=$ycu_username;?></td>
	<td class="text-center">
		<span <?=$color;?>><?=$status;?> </span>
	</td>
</tr>	