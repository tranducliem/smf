<!-- Input Team -->
<?php echo form_open('feedback_manager_question/process', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
    <input type="hidden" id="action" name="action" value="create"/>
    <label>{{ helper:lang line="feedback_manager_question:form_feedback_manager_id" }}<span class="require">*</span></label>
    <?php echo form_dropdown('feedback_manager_id', $feedback_managers, '', 'id="feedback_manager_id" class="span6"')?>
    <label>{{ helper:lang line="feedback_manager_question:form_question_id" }}<span class="require">*</span></label>
    <?php echo form_dropdown('question_id', $questions, '', 'id="question_id" class="span6"')?><br>
    <button type="submit" id="btnSubmit" class="btn-u pull-left">Create feedback_manager_question</button>
<?php echo form_close()?>