<fieldset id="filters">
	<legend><?php echo lang('global:filters') ?></legend>
    <?php echo form_open('', array('class' => 'filter_form'))?>
    <div class="control-group">
                <input type="text" name="f_keywords" placeholder="<?php echo lang('feedback_manager:title').' '.lang('global:keywords');?>">
                <input class="btn-u" type="submit" name="btnSubmit" value="Submit" />
    </div>
	<?php echo form_close() ?>
</fieldset>