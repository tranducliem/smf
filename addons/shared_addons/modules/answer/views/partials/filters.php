<!-- Search Bar -->
<div class="input-append filter_wrapper margin-bottom-10">
    <?php echo form_open('', array('class' => 'filter_form'))?>
        <button type="submit" class="btn-u">Search</button>
        <input type="text" name="f_keywords" class="span4" placeholder="<?php echo lang('answer:title').' '.lang('global:keywords');?>">
        <div class="clear"></div>
    <?php echo form_close() ?>
</div>