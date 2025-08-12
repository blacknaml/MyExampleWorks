<tr>
	<td <?=$class;?> >
		<textarea name="i-comment[]" readonly="readonly" style="resize:vertical; max-height:500px; min-height:50px;" class="form-control" ><?=$comment;?></textarea>
	</td>
	<td>
		<input type="text" name="i-datetime[]" class='form-control dt-input'>
		<input type="hidden" name="i-flag[]" value="<?=$flag;?>">
	</td>
	<td>
		<?=form_dropdown('i-account[]', $account, '', 'class="form-control"');?>
	</td>
</tr>