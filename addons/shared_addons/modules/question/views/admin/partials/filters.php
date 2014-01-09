<fieldset id="filters">
	<legend><?php echo lang('global:filters') ?></legend>

	<?php echo form_open('', '', array('f_module' => $module_details['slug'])) ?>
		<ul>
			<li class="">
				<label for="f_category"><?php echo lang('question:form_title').' '.lang('global:keywords') ?></label>
				<?php echo form_input('f_keywords', '', 'style="width: 120%;"') ?>
			</li>
		</ul>
	<?php echo form_close() ?>
</fieldset>
