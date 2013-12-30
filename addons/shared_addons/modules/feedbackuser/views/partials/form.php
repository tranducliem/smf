<!-- Input Feedback manager user -->
<?php echo form_open('feedbackuser/process', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
    <input type="hidden" id="action" name="action" value="create"/>
    <label>{{ helper:lang line="feedbackuser:form_feedback_id" }}<span class="require">*</span></label>
    <?php echo form_dropdown('feedback_manager_id', $feedbackmanagers, '', 'id="feedback_manager_id" class="span6"')?>
    <label>{{ helper:lang line="feedbackuser:user_id" }}<span class="require">*</span></label>
    <?php echo form_dropdown('user_id', $users, '', 'id="user_id" class="span6"')?>
    <label>{{ helper:lang line="feedbackuser:status" }}<span class="require">*</span></label>
    <select name="status" data-placeholder="Choose status..." class="span6" tabindex="2">
        <option value="1">status 1</option>
        <option value="2">status 2</option>
        <option value="3">status 3</option>
    </select>
    <p class="span12"></p>
    <button type="submit" id="btnSubmit" class="btn-u pull-left">Create FeedbackUser</button>
<?php echo form_close()?>