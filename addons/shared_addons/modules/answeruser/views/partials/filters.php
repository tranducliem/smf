<!-- Search Bar -->
<div class="input-append filter_wrapper margin-bottom-10">
    <?php echo form_open('', array('class' => 'filter_form'))?>
        <a href="answeruser/create" class="btn-u btn_add_filter">Add Answer User</a>
        <button type="submit" class="btn-u">Search</button>
        <input type="text" name="f_keywords" class="span4" placeholder="<?php echo lang('answeruser:user_name').' '.lang('global:keywords');?>">
        <div class="clear"></div>
    <?php echo form_close() ?>
</div>