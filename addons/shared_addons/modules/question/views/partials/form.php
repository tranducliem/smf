<!-- Input Team -->
<?php echo form_open('question/process', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
    <input type="hidden" id="action" name="action" value="create"/>
    <label>{{ helper:lang line="question:form_title" }}<span class="require">*</span></label>
    <?php echo form_input('title', '', 'id="title" class="span6 border-radius-none" placeholder="Title input"') ?>
    <label>{{ helper:lang line="question:form_description" }}<span class="require">*</span></label>
    <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => '', 'rows' => 6, 'class' => 'span12 border-radius-none margin-bottom-10')) ?>
    <button type="submit" id="btnSubmit" class="btn-u pull-left">Create question</button>
<?php echo form_close()?>