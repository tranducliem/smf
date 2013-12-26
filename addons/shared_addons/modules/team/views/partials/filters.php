<fieldset id="filters">
	<legend><?php echo lang('global:filters') ?></legend>
    <?php echo form_open('', array('class' => 'filter_form'))?>
		<ul>
			<li>
                <input type="text" name="f_keywords" placeholder="<?php echo lang('team:title').' '.lang('global:keywords');?>">
                <input type="submit" name="btnSubmit" value="Submit" />
			</li>
		</ul>
	<?php echo form_close() ?>
</fieldset>