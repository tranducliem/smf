<fieldset id="filters">
    <legend><?php echo lang('global:filters') ?></legend>
    <?php echo form_open('', array('class' => 'filter_form'))?>
    <div class="control-group">
        <input type="text" name="f_keywords" placeholder="<?php echo lang('feedback_manager_question:feedback_manager_id').' '.lang('global:keywords');?>">
        <input type="submit" name="btnSubmit" value="Submit" />
    </div>
    <?php echo form_close() ?>
</fieldset>